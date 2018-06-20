<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Kase;
use common\models\Person;
use common\models\User;
use common\models\Inquiry;
use common\models\File;
use common\models\Tour;
use common\models\Task;
use common\models\Payment;
use yii\web\HttpException;

class ManagerController extends MyController
{
	public function z__construct($id, $module, $config = [])
	{
		parent::__construct($id, $module, $config);
		$denied = true;
		if (
			\app\helpers\User::inGroups('any:lanhdao,quanly')
			|| (\app\helpers\User::inGroups('banhang') && in_array(SEG2, ['cases', 'vespa2013', 'sales-results', 'sales-results-seller', 'sales-results-changes', 'sales-results-sources', 'sales-results-assignments']))
			|| (in_array(MY_ID, [23143]) && in_array(SEG2, ['cases', 'inquiries']))
			) {
			$denied = false;
		}

		if ($denied) {
			throw new HttpException(403, 'Access denied');
		}
	}

	public function actionIndex() {
		// Liên hệ trong tháng
		$monthInquiryCount = Inquiry::find()
			->where('SUBSTRING(created_at, 1, 7)="'.date('Y-m').'"')
			->count();
		$monthCaseCount = Kase::find()
			->where('SUBSTRING(created_at, 1, 7)="'.date('Y-m').'"')
			->count();
		$monthNewTourCount = Kase::find()
			->where('deal_status="won" AND SUBSTRING(deal_status_date, 1, 7)="'.date('Y-m').'"')
			->count();
		$monthTourCount = Tour::find()
			->where('SUBSTRING(code, 2, 4)="'.date('ym').'"')
			->andWhere('status!="deleted"')
			->count();
		$monthPayments = Payment::find()
			->select(['xrate', 'amount'])
			->where('SUBSTRING(payment_dt, 1, 7)="'.date('Y-m').'"')
			->andWhere('status!="deleted"')
			->asArray()
			->all();

		// Month new won
		$wonCasesBySeller = Yii::$app->db
			->createCommand('select count(*) as total, u.name from at_cases c, persons u where u.id=c.owner_id AND deal_status="won" and substring(deal_status_date,1,7)=:ym group by owner_id order by total desc', [':ym'=>date('Y-m')])
			->queryAll();

		// So HS ban them 12 thang qua
		$last12moWonCases = Yii::$app->db
			->createCommand('select count(*) as total, SUBSTRING(deal_status_date,1,7) AS ym from at_cases where deal_status="won" group by ym order by ym')
			->queryAll();

		// Tour khoi hanh 12 thang qua
		$last12moTours = Yii::$app->db
			->createCommand('select SUBSTRING(ct.day_from,1,7) AS ym, COUNT(*) AS total from at_ct ct, at_tours t where t.ct_id=ct.id AND t.status!="deleted" group by ym order by ym')
			->queryAll();

		return $this->render('manager', [
			'monthInquiryCount'=>$monthInquiryCount,
			'monthCaseCount'=>$monthCaseCount,
			'monthNewTourCount'=>$monthNewTourCount,
			'monthTourCount'=>$monthTourCount,
			'wonCasesBySeller'=>$wonCasesBySeller,
			'last12moWonCases'=>$last12moWonCases,
			'last12moTours'=>$last12moTours,
			'monthPayments'=>$monthPayments,
		]);
	}

	public function actionCases() {
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

		$query = Kase::find();

		if ($getMonth != 'all') {
			if ($getCa == 'created') {
				$query->andWhere('SUBSTRING(created_at, 1, 7)=:month', [':month'=>$getMonth]);
			} else {
				$query->andWhere('SUBSTRING(ao, 1, 7)=:month', [':month'=>$getMonth]);
			}
		}
		if ($getStatus != 'all') $query->andWhere(['status'=>$getStatus]);
		if ($getSaleStatus != 'all') $query->andWhere(['deal_status'=>$getSaleStatus]);
		if ($getCompany == 'no') {
			$query->andWhere(['company_id'=>0])->andWhere('how_contacted!="agent"');
		} elseif ($getCompany == 'yes') {
			$query->andWhere(['or', 'company_id!=0', 'how_contacted="agent"']);
		} else {
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
		if ($getCampaignId == 'yes') {
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
		if ($getName != '') $query->andWhere(['like', 'name', $getName]);

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
		$monthList = Yii::$app->db->createCommand('SELECT SUBSTRING(created_at, 1, 7) AS ym FROM at_cases GROUP BY ym ORDER BY ym DESC ')->queryAll();
		$ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email FROM at_cases c, persons u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
		$campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM at_campaigns c ORDER BY c.start_dt DESC')->queryAll();
		$companyList = Yii::$app->db->createCommand('SELECT c.id, c.name FROM at_cases k, at_companies c WHERE k.company_id=c.id GROUP BY k.company_id ORDER BY c.name')->queryAll();

		return $this->render('manager_cases', [
			'pages'=>$pages,
			'theCases'=>$theCases,
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
			]
		);
	}

	// Cac nhiem vu trong HSBH
	public function actionSellersTasks($user = 0)
	{
		$theTasks = Task::find()
			->where(['rtype'=>'case', 'status'=>'on'])
			->with('assignees')
			->orderBy('due_dt DESC')
			->all();

		$getSeller = \fRequest::get('seller_id', 'integer', 0, true);

		$allSellers = Yii::$app->db->createCommand('SELECT u.id, u.email, u.name, u.lname FROM persons u, at_user_role r WHERE r.user_id=u.id AND r.role_id=4 ORDER BY lname')->queryAll();

		$theCases = Yii::$app->db->createCommand('SELECT ao, id, name, deal_status, opened, closed, closed_note
			FROM at_cases WHERE owner_id=:owner_id AND status="open"
			ORDER BY ao DESC, updated_at DESC LIMIT 1000', [':owner_id'=>$getSeller])
			->queryAll();

		$theTasks = [];
		if (!empty($theCases)) {
			$caseIds = array();
			foreach ($theCases as $c) $caseIds[] = $c['id'];
			$theTasks = Yii::$app->db->createCommand('SELECT t.* FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="on" AND tu.user_id=:user_id AND rtype="case" ORDER BY due_dt LIMIT 1000', [':user_id'=>$getSeller])->queryAll();
		}

		return $this->render('manager_sellers-tasks', [
			'theTasks'=>$theTasks,
			'theCases'=>$theCases,
			'getSeller'=>$getSeller,
			'allSellers'=>$allSellers,
		]);
	}

	// Phan cong HSBH trong thang
	public function actionSellersCases()
	{
		$getMonth = \fRequest::get('month', 'string', date('Y-m'), true);

		// Danh sach cac thang co ho so
		$ymx = Yii::$app->db
			->createCommand('SELECT SUBSTRING(ao, 1, 7) AS ym, COUNT(*) AS total FROM at_cases GROUP BY ym ORDER BY ym DESC')
			->queryAll();

		// Danh sách các nv có hồ sơ BH trong tháng này
		$sellers = Yii::$app->db
			->createCommand('SELECT owner_id,
		  (SELECT name FROM persons u WHERE u.id=owner_id LIMIT 1) AS owner_name,
		  COUNT(*) AS total FROM at_cases WHERE SUBSTRING(ao, 1, 7)=:mo GROUP BY owner_id ORDER BY total DESC', [':mo'=>$getMonth])
			->queryAll();

		// Danh sách các ngày có hồ sơ
		$ex = Yii::$app->db
			->createCommand('SELECT owner_id, ao, is_priority FROM at_cases WHERE SUBSTRING(ao, 1, 7)=:mo ORDER BY ao LIMIT 1000', [':mo'=>$getMonth])
			->queryAll();

		return $this->render('manager_sellers-cases', [
			'getMonth'=>$getMonth,
			'ymx'=>$ymx,
			'ex'=>$ex,
			'sellers'=>$sellers,
		]);
	}

	public function actionToursSold()
	{
		return $this->render('//undercon');

		$getYear = \fRequest::getValid('year', array(2016, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016));

		// Years
		$allYears = Yii::$app->db
			->createCommand('SELECT YEAR(day_from) AS y, COUNT(*) AS total FROM at_ct ct, at_tours t WHERE t.ct_id=ct.id GROUP BY y ORDER BY y DESC')
			->queryAll();

		// Tours
		$allTours = Yii::$app->db
			->createCommand('SELECT YEAR(day_from) AS y, t.se, t.op, t.status FROM at_ct ct, at_tours t WHERE t.ct_id=ct.id HAVING y=:y ORDER BY day_from', [':y'=>$getYear])
			->queryAll();

		// Danh sach tour: thuc ra la danh sach HSBH thanh cong
		$tourList = Yii::$app->db
			->createCommand('SELECT t.*, ct.id, ct.ub, MONTH(day_from) AS mo FROM at_ct ct, at_tours t WHERE t.ct_id=ct.id AND YEAR(day_from)=:y ORDER BY day_from', [':y'=>$getYear])
			->queryAll();

		// Bán hàng các tour
		$sellerList = Yii::$app->db
			->createCommand('SELECT se, (SELECT name FROM persons WHERE persons.id=at_tours.se LIMIT 1) AS ub_name, COUNT(*) AS total FROM at_ct, at_tours WHERE at_ct.id=at_tours.ct_id AND YEAR(day_from)=:y GROUP BY se ORDER BY total DESC', [':y'=>$getYear])
			->queryAll();

		return $this->render('manager_tours-sold', [
			'getYear'=>$getYear,
			'allYears'=>$allYears,
			'allTours'=>$allTours,
			'tourList'=>$tourList,
			'sellerList'=>$sellerList,
		]);

	}

	public function actionToursDepartures()
	{
		$getYear = Yii::$app->request->get('year', date('Y'));

		// Years
		$yearList = Yii::$app->db
			->createCommand('SELECT YEAR(day_from) AS y, COUNT(*) AS total FROM at_ct ct, at_tours t WHERE t.ct_id=ct.id GROUP BY y ORDER BY y DESC')
			->queryAll();

		// Tours
		$allTours = Yii::$app->db
			->createCommand('SELECT YEAR(day_from) AS y, t.se, t.op, t.status FROM at_ct ct, at_tours t WHERE t.ct_id=ct.id HAVING y=:y ORDER BY day_from', [':y'=>$getYear])
			->queryAll();

		// Danh sach tour: thuc ra la danh sach HSBH thanh cong
		$tourList = Yii::$app->db
			->createCommand('SELECT t.*, ct.id, ct.created_by AS ub, MONTH(day_from) AS mo FROM at_ct ct, at_tours t WHERE t.ct_id=ct.id AND YEAR(day_from)=:y ORDER BY day_from', [':y'=>$getYear])
			->queryAll();

		// Bán hàng các tour
		$sellerList = Yii::$app->db
			->createCommand('SELECT se, (SELECT name FROM persons WHERE persons.id=at_tours.se LIMIT 1) AS ub_name, COUNT(*) AS total FROM at_ct, at_tours WHERE at_ct.id=at_tours.ct_id AND YEAR(day_from)=:y GROUP BY se ORDER BY total DESC', [':y'=>$getYear])
			->queryAll();

		return $this->render('manager_tours-departures', [
			'getYear'=>$getYear,
			'yearList'=>$yearList,
			'allTours'=>$allTours,
			'tourList'=>$tourList,
			'sellerList'=>$sellerList,
		]);
	}

	public function actionSalesResults()
	{
		$yearList = [2018, 2017, 2016, 2015, 2014, 2013, 2012, 2011, 2010, 2009, 2008, 2007];
		$getAssign = Yii::$app->request->get('assign', 'all');
		$getYear = Yii::$app->request->get('year', date('Y'));
		if (!in_array($getYear, $yearList)) {
			$getYear = date('Y');
		}

		// So HS duoc giao trong nam
		$query = Kase::find()
			->select(['id', 'ao', 'created_at', 'name', 'status', 'owner_id', 'deal_status'])
			->where('SUBSTRING(ao,1,4)=:year', [':year'=>$getYear]);

		$getSource = Yii::$app->request->get('source', 'all');

		if ($getSource == 'adwords') {
			$query->andWhere(['web_referral'=>'ad/adwords']);
		} elseif ($getSource == 'adwords-amica') {
			$query->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
		} elseif ($getSource == 'returning') {
			$query->andWhere(['how_found'=>'returning']);
		} elseif ($getSource == 'direct') {
			$query->andWhere(['web_referral'=>'direct']);
		} elseif ($getSource == 'search') {
			$query->andWhere('SUBSTRING(web_referral,1,6)="search"');
		} elseif ($getSource == 'search-amica') {
			$query->andWhere('SUBSTRING(web_referral,1,6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
		} elseif ($getSource == 'referred') {
			$query->andWhere(['how_found'=>'word']);
		} elseif ($getSource == 'b2b') {
			$query->andWhere(['is_b2b'=>'yes']);
		}

		if ($getAssign == 'assigned') {
			$query->andWhere('ao!=0');
		} elseif ($getAssign == 'unassigned') {
			$query->andWhere('ao=0');
		}

		$assignedCases = $query
			->orderBy('ao DESC, id')
			->asArray()
			->all();

		$ownerIdList = [];
		foreach ($assignedCases as $li) {
			if (!in_array($li['owner_id'], $ownerIdList)) {
				$ownerIdList[] = $li['owner_id'];
			}
		}

		$sellerList = User::find()
			->select(['id', 'CONCAT(fname, " ", lname) AS rname, image'])
			->where(['id'=>$ownerIdList])
			->orderBy('lname, fname')
			->asArray()
			->all();

		$results = [];

		// Total number
		for ($mo = 0; $mo <=12; $mo ++) {
			$results['all'][$mo]['all'] = 0;
			$results['all'][$mo]['won'] = 0;
			$results['all'][$mo]['lost'] = 0;
		}

		// Total unassigned
		for ($mo = 0; $mo <=12; $mo ++) {
			$results[0][$mo]['all'] = 0;
			$results[0][$mo]['won'] = 0;
			$results[0][$mo]['lost'] = 0;
		}

		// Total assigned
		foreach ($sellerList as $li) {
			for ($mo = 0; $mo <=12; $mo ++) {
				$results[$li['id']][$mo]['all'] = 0;
				$results[$li['id']][$mo]['won'] = 0;
				$results[$li['id']][$mo]['lost'] = 0;
			}
		}

		foreach ($assignedCases as $li) {
			$mo = date('n', strtotime($li['ao']));

			$results[$li['owner_id']][$mo]['all'] ++;
			$results[$li['owner_id']][0]['all'] ++;
			$results[0][$mo]['all'] ++;
			$results[0][0]['all'] ++;

			if ($li['deal_status'] == 'won') {
				$results[$li['owner_id']][$mo]['won'] ++;
				$results[$li['owner_id']][0]['won'] ++;
				$results[0][$mo]['won'] ++;
				$results[0][0]['won'] ++;
			}

			if ($li['deal_status'] != 'won' && $li['status'] == 'closed') {
				$results[$li['owner_id']][$mo]['lost'] ++;
				$results[$li['owner_id']][0]['lost'] ++;
				$results[0][$mo]['lost'] ++;
				$results[0][0]['lost'] ++;
			}
		}

		return $this->render('manager_sales-results', [
			'assignedCases'=>$assignedCases,
			'getYear'=>$getYear,
			'yearList'=>$yearList,
			'getSource'=>$getSource,
			'sellerList'=>$sellerList,
			'getAssign'=>$getAssign,
			'results'=>$results,
		]);
	}

	public function actionSalesResultsSources()
	{
		$sourceList = [
			'direct'=>'Direct web access',
			'search'=>'Web search',
			'adwords'=>'Google Adwords',
			'adwords-amica'=>'- Google Adwords Amica',
			'returning'=>'Returning customers',
			'referred'=>'Referred customers',
			'b2b'=>'B2B customers',
			'others'=>'Others',
			'all'=>'All sources',
		];
		$yearList = [2016, 2015, 2014, 2013, 2012, 2011, 2010, 2009, 2008, 2007];
		$getSource = Yii::$app->request->get('source', 'all');
		$getYear = Yii::$app->request->get('year', date('Y'));
		if (!in_array($getYear, $yearList)) {
			$getYear = date('Y');
		}

		// So HS duoc giao trong nam
		$assignedCases = Kase::find()
			->select(['id', 'ao', 'created_at', 'name', 'status', 'owner_id', 'deal_status', 'web_referral', 'is_b2b', 'how_found', 'web_keyword'])
			->where('SUBSTRING(ao,1,4)=:year', [':year'=>$getYear])
			->orderBy('owner_id, ao')
			->asArray()
			->all();

		$results = [];

		foreach ($assignedCases as $case) {
			// $case[seller][month][status][source]
			$month = (int)substr($case['ao'], 5, 2);
			$seller = $case['owner_id'];
			$status = $case['deal_status'];

			foreach ($sourceList as $k=>$v) {
				if (!isset($results[$seller][$month]['all'][$k])) {
					$results[$seller][$month]['all'][$k] = 0;
				}
				if (!isset($results[$seller][$month]['won'][$k])) {
					$results[$seller][$month]['won'][$k] = 0;
				}
			}

			$source = 'others';
			if ($case['web_referral'] == 'direct') {
				$source = 'direct';
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			if (substr($case['web_referral'],0,6) == 'search') {
				$source = 'search';
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			if ($case['web_referral'] == 'ad/adwords') {
				$source = 'adwords';
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			if ($case['web_referral'] == 'ad/adwords' && strpos($case['web_keyword'], 'amica') !== false) {
				$source = 'adwords-amica';
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			if ($case['how_found'] == 'returning') {
				$source = 'returning';
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			if ($case['how_found'] == 'word') {
				$source = 'referred';
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			if ($case['is_b2b'] == 'yes') {
				$source = 'b2b';
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			if ($source == 'others') {
				$results[$seller][$month]['all'][$source] ++;
				if ($case['deal_status'] == 'won') {
					$results[$seller][$month]['won'][$source] ++;
				}
			}
			$results[$seller][$month]['all']['all'] ++;
			if ($case['deal_status'] == 'won') {
				$results[$seller][$month]['won']['all'] ++;
			}
		}

		$ownerIdList = [];
		foreach ($assignedCases as $case) {
			if (!in_array($case['owner_id'], $ownerIdList)) {
				$ownerIdList[] = $case['owner_id'];
			}
		}

		$sellerList = Person::find()
			->select(['id', 'CONCAT(fname, " ", lname) AS rname, image'])
			->where(['id'=>$ownerIdList])
			->orderBy('lname, fname')
			->asArray()
			->all();

		// \fCore::expose($sellerList); exit;

		return $this->render('manager_sales-results-sources', [
			'assignedCases'=>$assignedCases,
			'getYear'=>$getYear,
			'yearList'=>$yearList,
			'sourceList'=>$sourceList,
			'getSource'=>$getSource,
			'sellerList'=>$sellerList,
			'results'=>$results,
		]);
	}

	public function actionSalesResultsChanges($year = null)
	{
		$getYear = Yii::$app->request->get('year', date('Y'));

		//$yearList = Yii::$app->db->createCommand('SELECT SUBSTRING(deal_status_date,1,4) AS yr FROM at_cases GROUP BY yr ORDER BY yr DESC')->queryAll();
		$yearList = [2016, 2015, 2014, 2013, 2012, 2011, 2010, 2009, 2008, 2007];

		// Won cases:
		$wonCases = Kase::find()
			->select(['id', 'name', 'status', 'owner_id', 'deal_status', 'deal_status_date'])
			->where(['deal_status'=>'won'])
			->andWhere('SUBSTRING(deal_status_date,1,4)=:yr', [':yr'=>$getYear])
			->asArray()
			->all();
		// Lost cases:
		$lostCases = Kase::find()
			->select(['id', 'name', 'status', 'owner_id', 'deal_status', 'closed'])
			->where(['status'=>'closed'])
			->andWhere('deal_status!=:won', [':won'=>'won'])
			->andWhere('SUBSTRING(closed,1,4)=:yr', [':yr'=>$getYear])
			->asArray()
			->all();

		// Last30DWon
		$last30dWonCases = Kase::find()
			->select(['id', 'name', 'status', 'owner_id', 'deal_status', 'deal_status_date'])
			->where(['deal_status'=>'won'])
			->andWhere('deal_status_date>:last30d', [':last30d'=>date('Y-m-d', strtotime('-30 day'))])
			->asArray()
			->all();
		// Last30DLost
		$last30dLostCases = Kase::find()
			->select(['id', 'name', 'status', 'owner_id', 'deal_status', 'closed'])
			->where(['status'=>'closed'])
			->andWhere('deal_status!=:won', [':won'=>'won'])
			->andWhere('closed>:last30d', [':last30d'=>date('Y-m-d', strtotime('-30 day'))])
			->asArray()
			->all();

		// Sellers
		$sellerIdList = [];
		foreach ($wonCases as $li) {
			if (!in_array($li['owner_id'], $sellerIdList)) {
				$sellerIdList[] = $li['owner_id'];
			}
		}
		foreach ($lostCases as $li) {
			if (!in_array($li['owner_id'], $sellerIdList)) {
				$sellerIdList[] = $li['owner_id'];
			}
		}

		$sellerList = Person::find()
			->select(['id', 'CONCAT(fname, " ", lname) AS rname'])
			->where(['id'=>$sellerIdList])
			->orderBy('lname, fname')
			->asArray()
			->all();

		// Results
		$results = [];
		for ($mo = 0; $mo <=12; $mo ++) {
			$results[0][$mo]['won'] = 0;
			$results[0][$mo]['lost'] = 0;
		}
		$results[0][30]['won'] = 0;
		$results[0][30]['lost'] = 0;

		foreach ($sellerList as $li) {
			for ($mo = 0; $mo <=12; $mo ++) {
				$results[$li['id']][$mo]['won'] = 0;
				$results[$li['id']][$mo]['lost'] = 0;
			}
			$results[$li['id']][30]['won'] = 0;
			$results[$li['id']][30]['lost'] = 0;
		}

		foreach ($wonCases as $li) {
			$mo = date('n', strtotime($li['deal_status_date']));
			$results[$li['owner_id']][$mo]['won'] ++;
			$results[$li['owner_id']][0]['won'] ++;
			$results[0][$mo]['won'] ++;
			$results[0][0]['won'] ++;
		}

		foreach ($lostCases as $li) {
			$mo = date('n', strtotime($li['closed']));
			$results[$li['owner_id']][$mo]['lost'] ++;
			$results[$li['owner_id']][0]['lost'] ++;
			$results[0][$mo]['lost'] ++;
			$results[0][0]['lost'] ++;
		}

		foreach ($last30dWonCases as $li) {
			if (isset($results[$li['owner_id']][30]['won'])) {
				$results[$li['owner_id']][30]['won'] ++;
				$results[0][30]['won'] ++;
			}
		}

		foreach ($last30dLostCases as $li) {
			if (isset($results[$li['owner_id']][30]['lost'])) {
				$results[$li['owner_id']][30]['lost'] ++;
				$results[0][30]['lost'] ++;
			}
		}

		$last10Assigned = Yii::$app->db
			->createCommand('SELECT c.id, c.name, u.name AS owner_name, c.ao AS at FROM at_cases c, persons u WHERE c.owner_id=u.id ORDER BY at DESC LIMIT 10')
			->queryAll();

		$last10Won = Yii::$app->db
			->createCommand('SELECT c.id, c.name, u.name AS owner_name, c.deal_status_date AS at FROM at_cases c, persons u WHERE c.owner_id=u.id AND c.deal_status="won" ORDER BY at DESC LIMIT 10')
			->queryAll();

		$last10Lost = Yii::$app->db
			->createCommand('SELECT c.id, c.name, u.name AS owner_name, c.closed AS at FROM at_cases c, persons u WHERE c.owner_id=u.id AND c.deal_status!="won" AND c.status="closed" ORDER BY at DESC LIMIT 10')
			->queryAll();

		return $this->render('manager_sales-results-changes', [
			'wonCases'=>$wonCases,
			'lostCases'=>$lostCases,
			'yearList'=>$yearList,
			'getYear'=>$getYear,
			'sellerList'=>$sellerList,
			'results'=>$results,
			'last10Won'=>$last10Won,
			'last10Lost'=>$last10Lost,
			'last10Assigned'=>$last10Assigned,
			'last30dWonCases'=>$last30dWonCases,
			'last30dLostCases'=>$last30dLostCases,
		]);
	}

	public function actionSalesResultsSeller() {

		$yearList = [2016, 2015, 2014, 2013, 2012, 2011, 2010, 2009, 2008, 2007];

		$getYear = Yii::$app->request->get('year', date('Y'));
		if (!in_array($getYear, $yearList))
			$getYear = date('Y');

		$getSource = Yii::$app->request->get('source', 'all');

		$getSeller = Yii::$app->request->get('seller', 0);

		$sellerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.fname FROM persons u, at_cases c WHERE c.owner_id=u.id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();

		$theSeller = Person::find()
			->where(['id'=>$getSeller])
			->andWhere(['is_member'=>['yes', 'old']])
			->one();

		if (!$theSeller)
			throw new HttpException(404, 'Seller not found');

		// Assigned cases
		$query = Kase::find()
			->select(['id', 'name', 'status', 'owner_id', 'ao', 'deal_status', 'deal_status_date'])
			->where(['owner_id'=>$getSeller])
			->andWhere('SUBSTRING(ao,1,4)=:yr', [':yr'=>$getYear]);

		if ($getSource == 'adwords') {
			$query->andWhere(['web_referral'=>'ad/adwords']);
		} elseif ($getSource == 'adwords-amica') {
			$query->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
		} elseif ($getSource == 'returning') {
			$query->andWhere(['how_found'=>'returning']);
		} elseif ($getSource == 'direct') {
			$query->andWhere(['web_referral'=>'direct']);
		} elseif ($getSource == 'search') {
			$query->andWhere('SUBSTRING(web_referral,1,6)="search"');
		} elseif ($getSource == 'referred') {
			$query->andWhere(['how_found'=>'word']);
		} elseif ($getSource == 'b2b') {
			$query->andWhere(['is_b2b'=>'yes']);
		}

		$assignedCases = $query
			->orderBy('deal_status, status')
			->asArray()
			->all();

		// Won cases:
		$query = Kase::find()
			->select(['id', 'name', 'status', 'owner_id', 'deal_status', 'deal_status_date'])
			->where(['deal_status'=>'won', 'owner_id'=>$getSeller])
			->andWhere('SUBSTRING(deal_status_date,1,4)=:yr', [':yr'=>$getYear]);
		if ($getSource == 'adwords') {
			$query->andWhere(['web_referral'=>'ad/adwords']);
		} elseif ($getSource == 'adwords-amica') {
			$query->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
		} elseif ($getSource == 'returning') {
			$query->andWhere(['how_found'=>'returning']);
		} elseif ($getSource == 'direct') {
			$query->andWhere(['web_referral'=>'direct']);
		} elseif ($getSource == 'search') {
			$query->andWhere('SUBSTRING(web_referral,1,6)="search"');
		} elseif ($getSource == 'referred') {
			$query->andWhere(['how_found'=>'word']);
		} elseif ($getSource == 'b2b') {
			$query->andWhere(['is_b2b'=>'yes']);
		}
		$wonCases = $query
			->asArray()
			->all();

		// Lost cases:
		$query = Kase::find()
			->select(['id', 'name', 'status', 'owner_id', 'deal_status', 'closed'])
			->where(['status'=>'closed', 'owner_id'=>$getSeller])
			->andWhere('deal_status!=:won', [':won'=>'won'])
			->andWhere('SUBSTRING(closed,1,4)=:yr', [':yr'=>$getYear]);
		if ($getSource == 'adwords') {
			$query->andWhere(['web_referral'=>'ad/adwords']);
		} elseif ($getSource == 'adwords-amica') {
			$query->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
		} elseif ($getSource == 'returning') {
			$query->andWhere(['how_found'=>'returning']);
		} elseif ($getSource == 'direct') {
			$query->andWhere(['web_referral'=>'direct']);
		} elseif ($getSource == 'search') {
			$query->andWhere('SUBSTRING(web_referral,1,6)="search"');
		} elseif ($getSource == 'referred') {
			$query->andWhere(['how_found'=>'word']);
		} elseif ($getSource == 'b2b') {
			$query->andWhere(['is_b2b'=>'yes']);
		}
		$lostCases = $query
			->asArray()
			->all();

		return $this->render('manager_sales-results-seller', [
			'getSeller'=>$getSeller,
			'theSeller'=>$theSeller,
			'sellerList'=>$sellerList,
			'getYear'=>$getYear,
			'yearList'=>$yearList,
			'getSource'=>$getSource,
			'assignedCases'=>$assignedCases,
			'wonCases'=>$wonCases,
			'lostCases'=>$lostCases,
		]);
	}


	public function actionUsers($id = 0) {
		$query = File::find();
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$theFiles = $query
			->orderBy('co DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			//->with('owner')
			->asArray()
			->all();

		return $this->render('users', [
			'pages'=>$pages,
			'theFiles'=>$theFiles,
			]
		);
	}

	public function actionFiles($id = 0) {
		$query = File::find();

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$theFiles = $query
			->orderBy('co DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			//->with('owner')
			->asArray()
			->all();

		return $this->render('files', [
			'pages'=>$pages,
			'theFiles'=>$theFiles,
			]
		);
	}

	public function actionRoles($id = 0) {
	}

	public function actionBookings($id = 0) {
		return $this->render('manager_bookings');
	}

	public function actionProposals($id = 0) {
	}

	public function actionCustomers($id = 0) {
		$getName = Yii::$app->request->get('name', '');
		
		$query = Person::find()
			->where(['is_client'=>'yes']);

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$models = $query
			->orderBy('lname, fname DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			->asArray()
			->all();

		return $this->render('manager_customers', [
			'getName'=>$getName,
			'pages'=>$pages,
			'models'=>$models,
		]);
	}

	public function actionInquiries()
	{
		return $this->redirect('/inquiries');
		$getMonth = Yii::$app->request->get('month', 'all');
		$getForm = Yii::$app->request->get('form', 'all');
		$getCountry = Yii::$app->request->get('country', 'all');
		$getCaseId = Yii::$app->request->get('case_id', 'all');
		$getName = Yii::$app->request->get('name', '');

		$query = Inquiry::find();

		if ($getMonth != 'all') {
			$query->andWhere('SUBSTRING(created_at, 1, 7)=:month', [':month'=>$getMonth]);
		}
		if ($getForm != 'all') {
			$query->andWhere(['form_name'=>$getForm]);
		}
		if ($getCaseId == 'yes') {
			$query->andWhere(['!=', 'case_id', 0]);
		} elseif ($getCaseId == 'no') {
			$query->andWhere(['=', 'case_id', 0]);
		}
		if ($getName != '') {
			$query->andWhere(['like', 'name', $getName]);
		}

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$models = $query
			->select(['id', 'name', 'email', 'ip', 'created_at', 'case_id', 'site_id', 'data', 'form_name', 'ref'])
			->orderBy('created_at DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			->with(['kase', 'kase.owner', 'site'])
			->asArray()
			->all();

		// List of months
		$monthList = Yii::$app->db->createCommand('SELECT SUBSTRING(created_at, 1, 7) AS ym FROM at_inquiries GROUP BY ym ORDER BY ym DESC ')->queryAll();
		$countryList = Yii::$app->db->createCommand('SELECT code, name_en FROM at_countries ORDER BY name_en')->queryAll();
		$formList = Yii::$app->db->createCommand('SELECT SUBSTRING_INDEX(form_name, "_", 1) AS site, form_name FROM at_inquiries GROUP BY form_name ORDER BY form_name')->queryAll();

		return $this->render('manager_inquiries', [
			'pages'=>$pages,
			'models'=>$models,
			'getMonth'=>$getMonth,
			'monthList'=>$monthList,
			'getForm'=>$getForm,
			'formList'=>$formList,
			'getCountry'=>$getCountry,
			'countryList'=>$countryList,
			'getCaseId'=>$getCaseId,
			'getName'=>$getName,
			]
		);
	}

	public function actionPhpinfo()
	{
		if (Yii::$app->user->id > 4)
			throw new HttpException(403, 'Access denied.');
		return $this->render('manager_phpinfo');
	}

	public function actionTongCpTour()
	{
		$getMonth = \fRequest::get('month', 'string', date('Y-m'), true); if ($getMonth == '0') $getMonth = date('Y-m');
		$getSe = \fRequest::get('se', 'integer', 0, true);
		$getOp = \fRequest::get('op', 'integer', 0, true);
		$getCs = \fRequest::get('cs', 'integer', 0, true);
		$getStatus = \fRequest::get('status', 'string', 'any', true);
		$getOrderby = \fRequest::getValid('orderby', ['code', 'startdate']);

		if (!\app\helpers\User::inGroups('any:dieuhanh,quanly,cskh,it')) {
			throw new HttpException(403, 'Access denied.');			
		}

		// CACHED??
		$mima = Yii::$app->db->createCommand('select min(year(day_from)) as miny, max(year(day_from)) as maxy from at_ct ct, at_tours WHERE ct.id=at_tours.ct_id')->queryAll();
		$miny = $mima['miny'];
		$maxy = $mima['maxy'];

		$allMonthTourCount = Yii::$app->db->createCommand('SELECT SUBSTRING(day_from, 1, 7) AS ym, YEAR(day_from) AS y, MONTH(day_from) AS m, COUNT(*) AS total FROM at_ct, at_tours WHERE at_ct.id=at_tours.ct_id GROUP BY ym ORDER BY y DESC, m DESC')->queryAll();
	
		$allMonthCanceledTours = Yii::$app->db->createCommand('SELECT SUBSTRING(day_from, 1, 7) AS ym, COUNT(*) AS total FROM at_ct, at_tours WHERE at_tours.status="deleted" AND at_ct.id=at_tours.ct_id GROUP BY ym')->queryAll();
	
		// Danh sách tour
		$monthTours = Yii::$app->db->createCommand('SELECT ct.pax, ct.day_count, ct.day_from, t.code, t.name, t.status, t.ct_id, t.id, t.se
		  FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id AND SUBSTRING(day_from, 1, 7)=:mo ORDER BY '.($getOrderby == 'code' ? 'SUBSTRING(code,-3)' : 'day_from, SUBSTRING(code,-3)').' LIMIT 1000', [':mo'=>$getMonth])->queryAll();

		// Danh sách khách
		$monthPax = Yii::$app->db->createCommand('SELECT p.*, fname, lname, u.name, country_code, gender, byear, bmonth, bday
		  FROM at_pax p, at_tours t, at_ct ct, persons u WHERE u.id=p.user_id AND ct_id=ct.id AND p.tour_id=t.id AND SUBSTRING(day_from,1,7)=:mo ORDER BY tour_id LIMIT 1000', [':mo'=>$getMonth])->queryAll();

		// Cac tour Id
		$tourIds = array(); foreach ($monthTours as $t) $tourIds[] = $t['id']; if (empty($tourIds)) $tourIds[] = 0;


		// Danh sach Ban hang, all
		$q = $db->query('SELECT u.id, u.name FROM persons u, at_tours t WHERE t.se=u.id GROUP BY u.id ORDER BY u.lname');
		$tourSes = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

		// Danh sach Seller trong thang
		$tourSellerIds = array(0);
		foreach ($monthTours as $mt) if (!in_array($mt['se'], $tourSellerIds)) $tourSellerIds[] = $mt['se'];
		$q = $db->query('SELECT id, name FROM persons WHERE id IN ('.implode(',', $tourSellerIds).') ORDER BY lname');
		$tourSellers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

		// Danh sach Dieu hanh, all
		$q = $db->query('SELECT u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id GROUP BY u.id ORDER BY u.lname');
		$tourOps = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

		// Danh sach Dieu hanh, thang nay
		$q = $db->query('SELECT tu.tour_id, u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id AND tu.tour_id IN ('.implode(',', $tourIds).') ORDER BY u.lname');
		$monthTourOps = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($monthTourOps as $to) $monthTourOpList[$to['tour_id']][] = $to['id'];

		// Danh sach CSKH, all
		$q = $db->query('SELECT u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role="cservice" AND tu.user_id=u.id GROUP BY u.id ORDER BY u.lname');
		$tourCss = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

		// Danh sach Dieu hanh, thang nay
		$q = $db->query('SELECT tu.tour_id, u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role="cservice" AND tu.user_id=u.id AND tu.tour_id IN ('.implode(',', $tourIds).') ORDER BY u.lname');
		$monthTourCss = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($monthTourCss as $tc) $monthTourCsList[$tc['tour_id']][] = $tc['id'];

		// Max number of tours
		$max_total = 0;
		foreach ($allMonthTourCount as $getMonthi) {
		  if ($getMonthi['total'] > $max_total) $max_total = $getMonthi['total'];
		}

		// Cac chi phi cua tour
		$q = $db->query('SELECT * FROM cpt WHERE tour_id IN ('.implode(',', $tourIds).') AND (latest=0 OR latest=dvtour_id) ORDER BY tour_id');
		$theDvtx = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

		// List cac cpt
		foreach ($tourIds as $tid) $cpTour[$tid] = 0;

		// USD-VND rates
		$usdRates = [];
		foreach ($monthTours as $t) {
			$q = $db->query('SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="'.$t['day_from'].'" ORDER BY rate_dt DESC LIMIT 1');
			$usdRates[$t['id']] = $q->countReturnedRows() > 0 ? $q->fetchScalar() : 21000;
		}

		foreach ($theDvtx as $s) {
			if ($s['latest']==0) {
				if ($s['unitc'] == 'USD') {
					$sub = $s['qty']*$s['price']*$usdRates[$s['tour_id']]*(1+$s['vat']/100);
				} else {
					$sub = $s['qty']*$s['price']*$xRates[$s['unitc']]*(1+$s['vat']/100);
				}

				if ($s['plusminus'] == 'minus') {
					$sub = -$sub;
				}
				$cpTour[$s['tour_id']] += $sub;
			}	
		}
		return $this->render('//tour/tours_tongchiphi', [
			'getMonth'=>$getMonth,
			'getSe'=>$getSe,
			'getOp'=>$getOp,
			'getCs'=>$getCs,
			'getStatus'=>$getStatus,
			'getOrderby'=>$getOrderby,
			'allMonthTourCount'=>$allMonthTourCount,
			'allMonthCanceledTours'=>$allMonthCanceledTours,
		]);
	}

	public function actionSalesResultsAssignments()
	{
		$yearList = [2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018];
		$getSeller = Yii::$app->request->get('seller', 4432);

		foreach ($yearList as $yr) {
			for ($mo = 0; $mo <= 12; $mo ++) {
				$results['all'][$yr][$mo] = 0;
				$results['won'][$yr][$mo] = 0;
			}
		}

		if ($getSeller != 0) {
			$sql = 'SELECT k.id, k.ao, k.deal_status FROM at_cases k WHERE owner_id=:id';
			$theCases = Yii::$app->db->createCommand($sql, [':id'=>$getSeller])->queryAll();
			foreach ($theCases as $case) {
				$yr = (int)substr($case['ao'], 0, 4);
				$mo = (int)substr($case['ao'], 5, 2);
				if (isset($results['all'][$yr][$mo])) {
					$results['all'][$yr][$mo] ++;
				}
				// Inc year total
				if (isset($results['all'][$yr][0])) {
					$results['all'][$yr][0] ++;
				}
				if (isset($results['won'][$yr][$mo]) && $case['deal_status'] == 'won') {
					$results['won'][$yr][$mo] ++;
				}
				if (isset($results['won'][$yr][0]) && $case['deal_status'] == 'won') {
					$results['won'][$yr][0] ++;
				}
			}
		}

		$sql = 'SELECT u.id, u.fname, u.lname, u.email, u.status FROM persons u, at_cases k WHERE k.owner_id=u.id GROUP BY u.id ORDER BY u.status, u.lname, u.fname';
		$sellerList = Yii::$app->db->createCommand($sql)->queryAll();
		return $this->render('manager_sales-results-assignments', [
			'sellerList'=>$sellerList,
			'yearList'=>$yearList,
			'getSeller'=>$getSeller,
			'results'=>$results,
		]);
	}
}
