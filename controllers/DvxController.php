<?

namespace app\controllers;

use common\models\Dvx;
use common\models\Cpx;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class DvxController extends MyController
{
	public function actionIndex() {
		$query = Dvx::find();

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>50,
		]);

		$theDvx = $query
			//->orderBy('')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();

		return $this->render('dvx', [
			'pagination'=>$pagination,
			'theDvx'=>$theDvx,
		]);
	}

	public function actionC()
	{
		if (!in_array(USER_ID, [1])) {
			throw new HttpException(403, 'Access denied.');
		}

		$theDv = new Dvx;
		$theDv->scenario = 'dv/c';

		if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
			$theDv->created_dt = NOW;
			$theDv->created_by = USER_ID;
			$theDv->updated_dt = NOW;
			$theDv->updated_by = USER_ID;
			$theDv->save(false);
			return $this->redirect('@web/dvx');
		}

		return $this->render('dvx_u', [
			'theDv'=>$theDv,
		]);
	}

	public function actionR($id = 0)
	{
		$theDv = Cp::find()
			->where(['id'=>$id])
			->with(['company', 'venue', 'cpg'])
			->one();

		if (!$theDv) {
			throw new HttpException(404, 'Cp not found');			
		}

		$relatedCpx = Cp::find()
			->select(['id', 'stype', 'grouping', 'name', 'total', 'unit', 'info', 'abbr'])
			->andWhere(['company_id'=>$theDv['company_id'], 'venue_id'=>$theDv['venue_id']])
			->orderBy('stype, grouping, name')
			->all();

		return $this->render('cp_r', [
			'theDv'=>$theDv,
			'relatedCpx'=>$relatedCpx,
		]);
	}

	public function actionU($id = 0)
	{
		if (!in_array(USER_ID, [1])) {
			throw new HttpException(403, 'Access denied.');
		}

		$theDv = Dvx::findOne($id);
		if (!$theDv) {
			throw new HttpException(404, 'DV not found.');
		}

		$theDv->scenario = 'dv/u';

		if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
			$theDv->updated_dt = NOW;
			$theDv->updated_by = USER_ID;
			$theDv->save(false);
			return $this->redirect('@web/dvx');
		}

		return $this->render('dvx_u', [
			'theDv'=>$theDv,
		]);
	}

	public function actionD($id = 0)
	{
		$theDv = Cp::find()
			->where(['id'=>$id])
			->with(['company', 'venue', 'cpg', 'cpt'])
			->one();

		if (!$theDv) {
			throw new HttpException(404, 'Cp not found');
		}

		if (!in_array(Yii::$app->user->id, [1, 9, 7766, 9198])) {
			throw new HttpException(403, 'Access denied');
		}

		if ($theDv['cpt']) {
			throw new HttpException(403, 'Related bookings found. You need to delete them first.');
		}

		if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
			// Delete related cpg
			Yii::$app->db->createCommand()
				->delete('at_dvg', ['dv_id'=>$id])
				->execute();
			// Delete cp
			$theDv->delete();
			if ($theDv['venue']) {
				return $this->redirect('@web/venues/r/'.$theDv['venue']['id']);
			} else {
				return $this->redirect('@web/cp');
			}
		}

		return $this->render('cp_d', [
			'theDv'=>$theDv
		]);
	}
}
