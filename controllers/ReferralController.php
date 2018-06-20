<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;
use common\models\Referral;
use common\models\Kase;
use common\models\Person;

class ReferralController extends MyController
{
	public function actionIndex()
	{
		$getGift = Yii::$app->request->get('gift', 'all');
		$getCase = Yii::$app->request->get('case', '');
		$getCaseStatus = Yii::$app->request->get('casestatus', 'all');
		$getUser = Yii::$app->request->get('user', '');
		$getCreated = Yii::$app->request->get('created', '');
		$getThank = Yii::$app->request->get('thank', '');
		$getAsk = Yii::$app->request->get('ask', '');
		$getSelect = Yii::$app->request->get('select', '');
		$getOrder = Yii::$app->request->get('order', 'created');
		$getLimit = Yii::$app->request->get('limit', 25);

		$createdMonthList = Yii::$app->db
			->createCommand('SELECT SUBSTRING(created_at,1,7) AS ym FROM at_referrals GROUP BY ym ORDER BY ym DESC')
			->queryAll();

		$thankMonthList = Yii::$app->db
			->createCommand('SELECT SUBSTRING(ngay_cam_on,1,7) AS ym FROM at_referrals GROUP BY ym ORDER BY ym DESC')
			->queryAll();

		$askMonthList = Yii::$app->db
			->createCommand('SELECT SUBSTRING(ngay_hoi_qua,1,7) AS ym FROM at_referrals GROUP BY ym ORDER BY ym DESC')
			->queryAll();

		$selectMonthList = Yii::$app->db
			->createCommand('SELECT SUBSTRING(ngay_chon_qua,1,7) AS ym FROM at_referrals GROUP BY ym ORDER BY ym DESC')
			->queryAll();

		$query = Referral::find();

		if ($getGift == 'yes') {
			$query->andWhere('gift!="no"');
		} elseif ($getGift != 'all') {
			$query->andWhere(['gift'=>$getGift]);
		}

		if ($getCreated != '') {
			$query->andWhere('SUBSTRING(at_referrals.created_at,1,7)=:created', [':created'=>$getCreated]);
		}

		if ($getThank != '') {
			$query->andWhere('SUBSTRING(at_referrals.ngay_cam_on,1,7)=:ym', [':ym'=>$getThank]);
		}

		if ($getAsk != '') {
			$query->andWhere('SUBSTRING(at_referrals.ngay_hoi_qua,1,7)=:ym', [':ym'=>$getAsk]);
		}

		if ($getSelect != '') {
			$query->andWhere('SUBSTRING(at_referrals.ngay_chon_qua,1,7)=:ym', [':ym'=>$getSelect]);
		}

		if ((int)$getCase != 0) {
			$query->andWhere(['case_id'=>$getCase]);
			if (in_array($getCaseStatus, ['pending', 'won', 'lost'])) {
				$query->andWhere(['at_cases.deal_status'=>$getCaseStatus]);
			}
		} elseif (strlen($getCase) > 2 || in_array($getCaseStatus, ['pending', 'won', 'lost'])) {
			$query->innerJoinWith([
				'case'=>function($query) {
					$getCase = Yii::$app->request->get('case');
					$getCaseStatus = Yii::$app->request->get('casestatus', 'all');
					if (strlen($getCase) > 2) {
						$query->andWhere(['like', 'at_cases.name', $getCase]);
					}
					if (in_array($getCaseStatus, ['pending', 'won', 'lost'])) {
						$query->andWhere(['at_cases.deal_status'=>$getCaseStatus]);
					}
					return $query;
				},
			]);
		}

		if ((int)$getUser != 0) {
			$query->andWhere(['user_id'=>$getUser]);
		} elseif (strlen($getUser) > 2) {
			$query->innerJoinWith([
				'user'=>function($query) {
					$getUser = Yii::$app->request->get('user');
					$query->andWhere(['like', 'persons.name', $getUser]);
					return $query;
				},
			]);
		}

		if (!in_array($getLimit, [25, 50, 100])) {
			$getLimit = 25;
		}

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>$getLimit,
		]);

		$orderBy = 'updated_at';
		if ($getOrder != 'updated') {
			$orderBy = 'created_at';
		}

		$theReferrals = $query
			->with(['case',
				'user',
				'case.owner',
				'case.bookings.product.tour',
				'case.owner',
				'user.bookings.product.tour'
			])
			->orderBy($orderBy.' DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			->all();

		return $this->render('referrals', [
			'pages'=>$pages,
			'theReferrals'=>$theReferrals,
			'getGift'=>$getGift,
			'getCase'=>$getCase,
			'getCaseStatus'=>$getCaseStatus,
			'getUser'=>$getUser,
			'getCreated'=>$getCreated,
			'getOrder'=>$getOrder,
			'getThank'=>$getThank,
			'getAsk'=>$getAsk,
			'getSelect'=>$getSelect,
			'getLimit'=>$getLimit,
			'createdMonthList'=>$createdMonthList,
			'thankMonthList'=>$thankMonthList,
			'askMonthList'=>$askMonthList,
			'selectMonthList'=>$selectMonthList,
		]);
	}

	public function actionC()
	{
		throw new HttpException(404, 'This function is under development. Contact Mr Huan for more information.');
		
	}

	public function actionR($id = 0)
	{
		$theReferral = Referral::find()
			->where(['id'=>$id])
			->with(['case', 'user'])
			->one();

		if (!$theReferral) {
			throw new HttpException(404, 'Not found');
		}

		return $this->render('referrals_r', [
			'theReferral'=>$theReferral,
		]);
	}

	public function actionU($id = 0)
	{
		// Bao Tuan, 150824
		// Khang Ha, 170119
		// Phuong Anh TT, 170309
		if (!in_array(MY_ID, [1, 29296, 40217])) {
			throw new HttpException(403, 'Access denied');
		}
		$theReferral = Referral::findOne($id);
		if (!$theReferral) {
			throw new HttpException(404, 'Not found');
		}

		$theReferral->scenario = 'referrals_u';

		if ($theReferral->load(Yii::$app->request->post())) {
			if (!in_array(MY_ID, [1, 29296])) {
				throw new HttpException(403, 'Access denied.');
			}
			$theReferral->updated_at = NOW;
			$theReferral->updated_by = MY_ID;
			if ($theReferral->save()) {
				Yii::$app->session->setFlash('success', 'Referral case has been saved.');
				return $this->redirect('@web/referrals');
			}
		}

		return $this->render('referrals_u', [
			'theReferral'=>$theReferral,
		]);
	}

	public function actionD($id = 0)
	{
		throw new HttpException(404, 'This function is under development. Contact Mr Huan for more information.');
		
	}
}
