<?
namespace app\controllers;

use common\models\Cpt;
use common\models\Company;
use common\models\Ltt;
use common\models\Mm;
use common\models\Mtt;
use common\models\Tour;
use common\models\User;
use common\models\Venue;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class MttController extends MyController
{
	public function actionIndex()
	{
		$query = Mtt::find()
			->with([
				'cpt',
				'cpt.tour'=>function($q) {
					return $q->select(['id', 'code']);
				},
				'cpt.venue'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'cpt.company'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'cpt.viaCompany'=>function($query) {
					return $query->select(['id', 'name']);
				},
			]);
		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$theMttx = $query
			->orderBy('payment_dt DESC')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();
		return $this->render('mtt_index', [
			'pagination'=>$pagination,
			'theMttx'=>$theMttx,
		]);
	}

	public function actionC($cpt = 0, $return = '')
	{
		$theCpt = Cpt::find()
			->where(['dvtour_id'=>$cpt])
			->with([
				'updatedBy'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'tour'=>function($query) {
					return $query->select(['id', 'code']);
				},
				'venue'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'company'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'mm',
				'mm.updatedBy'=>function($q) {
					return $q->select(['id', 'name']);
				},
				])
			->asArray()
			->one();
		if (!$theCpt) {
			throw new HttpException(404, 'CPT not found.');
		}

		if (substr($theCpt['c3'], 0, 2) == 'on' || substr($theCpt['c4'], 0, 2) == 'on') {
			throw new HttpException(403, 'Already paid and/or checked.');
		}

		$theMtt = new Mtt;
		$theMtt->amount = $theCpt['qty'] * $theCpt['price'];
		$theMtt->currency = $theCpt['unitc'];
		$theMtt->xrate = 1;

		if ($theMtt->load(Yii::$app->request->post()) && $theMtt->validate()) {
			$theMtt->created_dt = NOW;
			$theMtt->created_by = USER_ID;
			$theMtt->updated_dt = NOW;
			$theMtt->updated_by = USER_ID;
			$theMtt->status == 'on';
			$theMtt->cpt_id == $theCpt['dvtour_id'];
			$theMtt->save(false);

			// Update cpt neu can
			if ($theMtt['status'] == 'on') {
				if ($theMtt['paid_in_full'] == 'yes') {
					$sql = 'UPDATE cpt SET c3=:c3 WHERE dvtour_id=:id LIMIT 1';
					Yii::$app->db->createCommand($sql, [
						':c3'=>'on,'.USER_ID.','.NOW,
						':id'=>$theMtt['cpt_id'],
					])->execute();
				} else {
					$sql = 'UPDATE cpt SET c1=:c1 WHERE dvtour_id=:id LIMIT 1';
					Yii::$app->db->createCommand($sql, [
						':c1'=>'on,'.USER_ID.','.NOW,
						':id'=>$theMtt['cpt_id'],
					])->execute();
				}
			}

			$return = 'cpt';
			return $this->redirect('@web/'.$return);
		}
		return $this->render('mtt_u', [
			'theMtt'=>$theMtt,
			'theCpt'=>$theCpt,
		]);
	}

	public function actionR($id = 0, $search = '', $tour = '', $currency = '', $day = '', $limit = 25)
	{
		$theMtt = Mtt::find()
			->where(['id'=>$id])
			->with([
				'mtt',
				'mtt.cpt',
				'mtt.cpt.updatedBy'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'mtt.cpt.tour'=>function($query) {
					return $query->select(['id', 'code']);
				},
				'mtt.cpt.venue'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'mtt.cpt.company'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'mtt.cpt.mm',
				'mtt.cpt.mm.updatedBy'=>function($query) {
					return $query->select(['id', 'name']);
				},
				])
			->asArray()
			->one();
		if (!$theMtt) {
			throw new HttpException(404);
		}

		if (!in_array($limit, [25, 50, 100, 500])) {
			$limit = 25;
		}

		return $this->render('ltt_r', [
			'theMtt'=>$theMtt,
		]);
	}

	public function actionU($id = 0)
	{
		$theMtt = Mtt::find()
			->where(['id'=>$id, 'status'=>'draft'])
			->one();
		if (!$theMtt) {
			throw new HttpException(404);
		}
		$theCpt = Cpt::find()
			->where(['dvtour_id'=>$theMtt['cpt_id']])
			->with([
				'updatedBy'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'tour'=>function($query) {
					return $query->select(['id', 'code']);
				},
				'venue'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'company'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'comments',
				'comments.updatedBy'=>function($q) {
					return $q->select(['id', 'name'=>'nickname']);
				},
				])
			->asArray()
			->one();

		if ($theMtt->load(Yii::$app->request->post()) && $theMtt->validate()) {
			$theMtt->updated_dt = NOW;
			$theMtt->updated_by = USER_ID;
			$theMtt->save(false);
			return $this->redirect('@web/cpt/thanh-toan');
		}
		return $this->render('mtt_u', [
			'theMtt'=>$theMtt,
			'theCpt'=>$theCpt,
		]);
	}

	// Ajax call
	public function actionAjax()
	{
		if (!Yii::$app->request->isAjax) {
			throw new HttpException(403);
		}

		$action = Yii::$app->request->post('action');
		if ($action == 'add-cpt-to-ltt') {
			$cpt_id = Yii::$app->request->post('cpt_id', 0);
			$ltt_id = Yii::$app->request->post('ltt_id', 0);
			if ($cpt_id != 0 && $ltt_id != 0) {
				$theCpt = Cpt::find()
					->where(['dvtour_id'=>$cpt_id])
					->asArray()
					->one();
				if (!$theCpt) {
					throw new HttpException(404);
				}
				$theMtt = new Mtt;
				$theMtt->created_dt = NOW;
				$theMtt->created_by = USER_ID;
				$theMtt->updated_dt = NOW;
				$theMtt->updated_by = USER_ID;
				$theMtt->ltt_id = $ltt_id;
				$theMtt->cpt_id = $cpt_id;
				$theMtt->amount = $theCpt['qty'] * $theCpt['price'];
				$theMtt->save(false);
				exit;
			}
		} elseif ($action == 'search-cpt') {
			$tour = Yii::$app->request->post('tour', '');
			$search = Yii::$app->request->post('search', '');
			return $this->searchCpt($tour, $search);
			exit;
		}
		
		throw new HttpException(403);
	}

	private function searchCpt($tour = '', $search = '')
	{
		$query = Cpt::find();

		if (strlen($search) > 2) {
			// Tim venue
			$theVenues = Venue::find()
				->select(['id'])
				->where(['like', 'name', $search])
				->indexBy('id')
				->asArray()
				->all();
			$venueIdList = null;
			if (!empty($theVenues)) {
				$venueIdList = array_keys($theVenues);
			}
			$theCompanies = Company::find()
				->select(['id'])
				->where(['like', 'name', $search])
				->indexBy('id')
				->asArray()
				->all();
			$companyIdList = null;
			if (!empty($theCompanies)) {
				$companyIdList = array_keys($theCompanies);
			}
			$query->filterWhere(['or', ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
		}

		$theTours = [];
		$tourIdList = [];
		if (strlen($tour) > 2) {
			if (preg_match("/(\d{4})-(\d{2})/", $tour)) {
				$theTours = Tour::findBySql('SELECT t.id, day_from FROM at_tours t, at_ct p WHERE p.id=t.ct_id AND SUBSTRING(day_from,1,7)=:ym', [':ym'=>$tour])
					->indexBy('id')
					->asArray()
					->all();
			} else {
				$theTours = Tour::find()
					->select(['id'])
					->where(['or', ['like', 'code', $tour], ['id'=>$tour]])
					->indexBy('id')
					->asArray()
					->all();
			}
			if (!empty($theTours)) {
				$tourIdList = array_keys($theTours);
				$query->andWhere(['tour_id'=>$tourIdList]);
			}
		}

		$theCptx = $query
			->with([
				'updatedBy'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'tour'=>function($query) {
					return $query->select(['id', 'code']);
				},
				'venue'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'company'=>function($query) {
					return $query->select(['id', 'name']);
				},
				'mm',
				'mm.updatedBy',
			])
			->orderBy('dvtour_day DESC')
			->limit(100)
			->asArray()
			->all();

		$sql = $query->createCommand()->getRawSql();

		// Aprroved by
		$approvedByIdList = [];
		foreach ($theCptx as $cpt) {
			if ($cpt['approved_by'] != '') {
				$cpt['approved_by'] = trim($cpt['approved_by'], '[');
				$cpt['approved_by'] = trim($cpt['approved_by'], ']');

				$ids = explode(':][', $cpt['approved_by']);
				foreach ($ids as $id2) {
					$approvedByIdList[] = (int)$id2;
				}
			}
		}
		$approvedBy = User::find()
			->select(['id', 'name'])
			->where(['id'=>$approvedByIdList])
			->asArray()
			->all();
		return $this->renderPartial('_search_cpt', [
			'theCptx'=>$theCptx,
			'approvedBy'=>$approvedBy,
			'sql'=>$sql,
		]);
	}

	public function actionD($id = 0)
	{
		$theMtt = Mtt::find()
			->where(['id'=>$id, 'status'=>'draft'])
			->one();
		if (!$theMtt) {
			throw new HttpException(404);
		}
		$theMtt->delete();
		return $this->redirect('/cpt/thanh-toan');
	}

}
