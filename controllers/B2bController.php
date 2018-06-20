<?

namespace app\controllers;

use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\User;
use common\models\Search;
use common\models\Ct;
use common\models\Cpt;
use common\models\Day;
use common\models\Kase;
use common\models\Inquiry;
use common\models\Message;
use common\models\ProfileTA;
use common\models\Sysnote;
use common\models\Tour;
use common\models\Product;
use common\models\Booking;
use common\models\Task;
use common\models\SampleTourDay;
use common\models\SampleTourProgram;
use Mailgun\Mailgun;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\GridView;
use yii\data\Pagination;
use yii\web\HttpException;

class B2bController extends MyController
{

	public function actionIndex()
	{
		return $this->render('//b2b/index', [
		]);
	}

	public function actionLeads()
	{
		$theCases = Kase::find();


		return $this->render('//b2b/leads', [
			'theCases'=>$theCases,
		]);
	}

	public function actionSampleTourDays($language = 'fr', $name = '', $tags = '')
	{
		$query = SampleTourDay::find()
			->where(['program_id'=>0, 'owner'=>'si']);
		if ($language != '') {
			$query->andWhere(['language'=>$language]);
		}
		if ($name != '') {
			$query->andWhere(['like', 'title', $name]);
		}
		if ($tags != '') {
			$query->andWhere(['like', 'tags', $tags]);
		}

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);

		$theDays = $query
			->orderBy('updated_by DESC')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();

		return $this->render('sample-tour-days', [
			'pagination'=>$pagination,
			'theDays'=>$theDays,
			'language'=>$language,
			'name'=>$name,
			'tags'=>$tags,
		]);
	}

	public function actionSampleTourDaysC()
	{
		$theDay = new SampleTourDay;
		$theDay->scenario = 'day/c';
		$theDay->language = 'fr';

		if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
			$theDay->created_dt = NOW;
			$theDay->created_by = USER_ID;
			$theDay->updated_dt = NOW;
			$theDay->updated_by = USER_ID;
			$theDay->program_id = 0;
			$theDay->owner = 'si';
			$theDay->save(false);
			Yii::$app->session->setFlash('success', 'Day has been saved: '.$theDay['title']);
			return $this->redirect('/b2b/sample-tour-days');
		}

		return $this->render('sample-tour-days-c', [
			'theDay'=>$theDay,
		]);
	}

	public function actionSampleTourDaysU($id = 0)
	{
		$theDay = SampleTourDay::find()
			->where(['id'=>$id, 'program_id'=>0, 'owner'=>'si'])
			->one();
		if (!$theDay) {
			throw new HttpException(404, 'Sample day not found.');
		}

		$theDay->scenario = 'day/u';

		if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
			$theDay->updated_dt = NOW;
			$theDay->updated_by = USER_ID;
			$theDay->save(false);
			Yii::$app->session->setFlash('success', 'Day has been updated: '.$theDay['title']);
			return $this->redirect('/b2b/sample-tour-days');
		}

		return $this->render('sample-tour-days-c', [
			'theDay'=>$theDay,
		]);
	}

	public function actionSampleTourDaysD($id = 0)
	{
		$theDay = SampleTourDay::find()
			->where(['id'=>$id, 'program_id'=>0, 'owner'=>'si'])
			->one();
		if (!$theDay) {
			throw new HttpException(404, 'Sample day not found.');
		}

		if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'delete') {
			$theDay->delete();
			Yii::$app->session->setFlash('success', 'Day has been deleted: '.$theDay['title']);
			return $this->redirect('/b2b/sample-tour-days');
		}

		return $this->render('sample-tour-days-d', [
			'theDay'=>$theDay,
		]);
	}

	public function actionSampleTourPrograms($language = 'fr', $name = '', $days = '', $tags = '')
	{
		$query = Product::find()
			->where(['owner'=>'at']);

		if ($language != '') {
			$query->andWhere(['language'=>$language]);
		}
		if ($name != '') {
			$query->andWhere(['like', 'title', $name]);
		}
		if ($days == '01-07') {
			$query->andWhere('day_count<=7');
		} elseif ($days == '08-14') {
			$query->andWhere('day_count>=8');
			$query->andWhere('day_count<=14');
		} elseif ($days == '15-up') {
			$query->andWhere('day_count>=15');
		}
		if ($tags != '') {
			$query->andWhere(['like', 'tags', $tags]);
		}

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);

		$thePrograms = $query
			->with([
				'days'=>function($q) {
					return $q->select(['id', 'name', 'rid']);
				},
				'updatedBy'=>function($q) {
					return $q->select(['id', 'name']);
				},
				])
			->orderBy('updated_at DESC')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();

		return $this->render('sample-tour-programs', [
			'pagination'=>$pagination,
			'thePrograms'=>$thePrograms,
			'language'=>$language,
			'name'=>$name,
			'days'=>$days,
			'tags'=>$tags,
		]);
	}

	public function actionSampleTourProgramsC()
	{
		$theProgram = new SampleTourProgram;
		$theProgram->scenario = 'program/c';
		$theProgram->language = 'fr';

		if ($theProgram->load(Yii::$app->request->post()) && $theProgram->validate()) {
			$theProgram->created_dt = NOW;
			$theProgram->created_by = USER_ID;
			$theProgram->updated_dt = NOW;
			$theProgram->updated_by = USER_ID;
			$theProgram->program_id = 0;
			$theProgram->owner = 'si';
			$theProgram->save(false);
			Yii::$app->session->setFlash('success', 'Sample program has been saved: '.$theProgram['title']);
			return $this->redirect('/b2b/sample-tour-programs-r/'.$theProgram['id']);
		}

		return $this->render('sample-tour-programs-c', [
			'theProgram'=>$theProgram,
		]);
	}

	public function actionSampleTourProgramsR($id = 0)
	{
		$theProgram = SampleTourProgram::find()
			->where(['id'=>$id, 'owner'=>'si'])
			->with(['days'])
			->one();
		if (!$theProgram) {
			throw new HttpException(404, 'Sample program not found.');
		}

		return $this->render('sample-tour-programs-r', [
			'theProgram'=>$theProgram,
		]);
	}

	public function actionSampleTourProgramsU($id = 0)
	{
		$theProgram = SampleTourProgram::find()
			->where(['id'=>$id, 'owner'=>'si'])
			->with(['days'])
			->one();
		if (!$theProgram) {
			throw new HttpException(404, 'Sample program not found.');
		}

		$theProgram->scenario = 'program/u';

		if ($theProgram->load(Yii::$app->request->post()) && $theProgram->validate()) {
			$theProgram->updated_dt = NOW;
			$theProgram->updated_by = USER_ID;
			$theProgram->save(false);
			Yii::$app->session->setFlash('success', 'Day has been updated: '.$theProgram['title']);
			return $this->redirect('/b2b/sample-tour-programs');
		}

		return $this->render('sample-tour-programs-c', [
			'theProgram'=>$theProgram,
		]);
	}

	public function actionSampleTourProgramsD($id = 0)
	{
		$theDay = SampleTourDay::find()
			->where(['id'=>$id, 'program_id'=>0, 'owner'=>'si'])
			->one();
		if (!$theDay) {
			throw new HttpException(404, 'Sample day not found.');
		}

		if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'delete') {
			$theDay->delete();
			Yii::$app->session->setFlash('success', 'Day has been deleted: '.$theDay['title']);
			return $this->redirect('/b2b/sample-tour-days');
		}

		return $this->render('sample-tour-days-d', [
			'theDay'=>$theDay,
		]);
	}

	public function actionCases($ym = 'm')
	{
		$getProspect = Yii::$app->request->get('prospect', 'all');
		$getSite = Yii::$app->request->get('site', 'all');
		$getCa = Yii::$app->request->get('ca', 'created');
		$getMonth = Yii::$app->request->get('month', 'all');
		$getStatus = Yii::$app->request->get('status', 'all');
		$getSaleStatus = Yii::$app->request->get('sale_status', 'all');
		$getOwnerId = Yii::$app->request->get('owner_id', 'all');
		$getCampaignId = Yii::$app->request->get('campaign_id', 'all');
		$getName = Yii::$app->request->get('name', '');
		$getHowFound = Yii::$app->request->get('found', 'all');
		$getHowContacted = Yii::$app->request->get('contacted', 'all');
		$getPriority = Yii::$app->request->get('is_priority', 'all');
		$getCompany = Yii::$app->request->get('company', 'all');
		$getLanguage = Yii::$app->request->get('language', 'all');

		$query = Kase::find()->where(['is_b2b'=>'yes']);
/*
		if (in_array($getProspect, [1,2,3,4,5]) || $getSite != 'all') {
			$cond = [];
			if ($getProspect != 'all') {
				$cond['prospect'] = $getProspect;
			}
			if ($getSite != 'all') {
				$cond['pa_from_site'] = $getSite;
			}
			$query->innerJoinwith('stats')->onCondition($cond);
		}
*/
		if ($getMonth != 'all' && $ym == 'm') {
			if ($getCa == 'created') {
				$query->andWhere('SUBSTRING(created_at, 1, 7)=:month', [':month'=>$getMonth]);
			} else {
				$query->andWhere('SUBSTRING(ao, 1, 7)=:month', [':month'=>$getMonth]);
			}
		}

		if ($getMonth != 'all' && $ym == 'y') {
			if ($getCa == 'created') {
				$query->andWhere('SUBSTRING(created_at, 1, 4)=:month', [':month'=>substr($getMonth, 0, 4)]);
			} else {
				$query->andWhere('SUBSTRING(ao, 1, 4)=:month', [':month'=>substr($getMonth, 0, 4)]);
			}
		}

		if ($getStatus != 'all') $query->andWhere(['status'=>$getStatus]);
		if ($getSaleStatus != 'all') $query->andWhere(['deal_status'=>$getSaleStatus]);
		if ($getCompany == 'no') {
			$query->andWhere(['company_id'=>0]);
		} elseif ($getCompany == 'yes') {
			$query->andWhere('company_id!=0');
		} elseif ((int)$getCompany != 0) {
			$query->andWhere(['company_id'=>(int)$getCompany]);
		}
		if ($getPriority != 'all') $query->andWhere(['is_priority'=>$getPriority]);
		if ($getLanguage != 'all') $query->andWhere(['language'=>$getLanguage]);
		if ($getOwnerId != 'all') {
			if (substr($getOwnerId, 0, 5) == 'cofr-') {
				$query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
			} else {
				$query->andWhere(['owner_id'=>(int)$getOwnerId]);
			}			
		}
		/*if ($getCampaignId == 'yes') {
			$query->andWhere('campaign_id!=0');
		} else {
			if ($getCampaignId != 'all') $query->andWhere(['campaign_id'=>$getCampaignId]);
		}
		if ($getHowFound != 'all') {
			$query->andWhere(['how_found'=>$getHowFound]);
		}
		if ($getHowContacted == 'unknown') {
			$query->andWhere(['how_contacted'=>'']);
		} else {
			if ($getHowContacted != 'all') {
				if ($getHowContacted == 'web-direct') {
					$query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
				} elseif ($getHowContacted == 'web-search') {
					$query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
				} elseif ($getHowContacted == 'web-search-amica') {
					$query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
				} elseif ($getHowContacted == 'web-adwords') {
					$query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
				} elseif ($getHowContacted == 'web-adwords-amica') {
					$query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
				} else {
					$query->andWhere(['how_contacted'=>$getHowContacted]);
				}
			}
		}
		*/
		if ($getName != '') $query->andWhere(['like', 'name', $getName]);

		/*if ($getProspect != 'all') {
			$query->innerJoinWith('stats')->onCondition();
				$getProspect = Yii::$app->request->get('prospect');
					if ((int)$getProspect != 0) {
						return $query->andWhere(['prospect'=>$getProspect]);
					}

		}*/

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$theCases = $query
			->select(['id', 'name', 'status', 'ref', 'is_priority', 'deal_status', 'opened', 'owner_id', 'created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed_note'])
			->orderBy('created_at DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			->with([
				'stats',
				'owner'=>function($query) {
					return $query->select(['id', 'name', 'image']);
				},
				'referrer'=>function($query) {
					return $query->select(['id', 'name', 'is_client']);
				},
				'company'=>function($query) {
					return $query->select(['id', 'name']);
				},
				])
			->asArray()
			->all();
			
		// List of months
		$monthList = Yii::$app->db->createCommand('SELECT SUBSTRING(created_at, 1, 7) AS ym FROM at_cases WHERE is_b2b="yes" GROUP BY ym ORDER BY ym DESC ')->queryAll();
		$ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email FROM at_cases c, persons u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
		$campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM at_campaigns c ORDER BY c.start_dt DESC')->queryAll();
		$companyList = Yii::$app->db->createCommand('SELECT c.id, c.name FROM at_cases k, at_companies c WHERE k.company_id=c.id GROUP BY k.company_id ORDER BY c.name')->queryAll();

		return $this->render('cases', [
			'pages'=>$pages,
			'theCases'=>$theCases,
			'getProspect'=>$getProspect,
			'getSite'=>$getSite,
			'getCa'=>$getCa,
			'getMonth'=>$getMonth,
			'monthList'=>$monthList,
			'getOwnerId'=>$getOwnerId,
			'ownerList'=>$ownerList,
			'getCampaignId'=>$getCampaignId,
			'campaignList'=>$campaignList,
			'getStatus'=>$getStatus,
			'getSaleStatus'=>$getSaleStatus,
			'getHowFound'=>$getHowFound,
			'getHowContacted'=>$getHowContacted,
			'getName'=>$getName,
			'getCompany'=>$getCompany,
			'getPriority'=>$getPriority,
			'getLanguage'=>$getLanguage,
			'companyList'=>$companyList,
			'ym'=>$ym,
		]);
	}

	// Login cho client SI
	public function actionClients()
	{
		$sql = 'SELECT company_id FROM at_cases GROUP BY company_id';
		$theList = Yii::$app->db->createCommand($sql)->queryAll();
		$idList = [];
		foreach ($theList as $item) {
			$idList[] = $item['company_id'];
		}
		$theAccounts = Company::find()
			->where(['id'=>$idList])
			->with([
				'metas',
				'profileTA',
				])
			->orderBy('name')
			->asArray()
			->all();

		return $this->render('//b2b/clients', [
			'theAccounts'=>$theAccounts,
		]);
	}

	// Login cho client SI
	public function actionClient($id = 0, $view = 'cases')
	{
		$theAccount = Company::find()
			->where(['id'=>$id])
			->with([
				'cases'=>function($q) {
					$q->orderBy('created_at DESC');
				},
				'cases.bookings'=>function($q) {
					$q->andWhere(['status'=>'won']);
				},
				'cases.bookings.product'=>function($q) {
					$q->select(['id', 'day_from', 'day_count', 'pax', 'op_name', 'op_code', 'op_finish', 'client_ref'])->andWhere(['op_status'=>'op']);
				},
				'cases.owner',
				'metas',
				'profileTA',
				])
			->asArray()
			->one();
		if (!$theAccount) {
			throw new HttpException(404, 'Account not found');
		}
		
		return $this->render('//b2b/client', [
			'theAccount'=>$theAccount,
			'view'=>$view,
		]);
	}


	// Login cho client SI
	public function actionClientLogin($id = 0)
	{
		$theAccount = Company::find()
			->where(['id'=>$id])
			->asArray()
			->one();
		if (!$theAccount) {
			throw new HttpException(404, 'Account not found');
		}

		$theProfile = profileTA::find()
			->where(['company_id'=>$theAccount['id']])
			->one();
		if (!$theProfile) {
			$theProfile = new ProfileTA;
			$theProfile->name = $theAccount['name'];
			$theProfile->login = \yii\helpers\Inflector::slug($theAccount['name']);
		}

		$theProfile->scenario = 'profile/u';

		if ($theProfile->load(Yii::$app->request->post()) && $theProfile->validate()) {
			if ($theProfile->isNewRecord) {
				$theProfile->created_dt = NOW;
				$theProfile->created_by = USER_ID;
				$theProfile->company_id = $theAccount['id'];
			}
			$theProfile->updated_dt = NOW;
			$theProfile->updated_by = USER_ID;

			if ($theProfile->newpassword != '') {
				$theProfile->password = Yii::$app->security->generatePasswordHash($theProfile->newpassword);
			}
			$theProfile->save(false);
			return $this->redirect('@web/b2b/clients');
		}

		return $this->render('//b2b/client-login', [
			'theAccount'=>$theAccount,
			'theProfile'=>$theProfile,
		]);
	}

}