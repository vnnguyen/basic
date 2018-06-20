<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Ct;
use common\models\Day;
use common\models\Tour;

class CtController extends MyController
{
	// CT
	public function actionIndex()
	{
		return $this->redirect('@web/products');
	}

	public function actionC()
	{
		return $this->redirect('@web/products/c');
		exit;

		$theCt = new Ct;
		
		$theCt->scenario = 'ct_c';
		$theCt->day_from = date('Y-m-d');
		$theCt->price_until = date('Y-m-d', strtotime('+1 year'));

		if ($theCt->load(Yii::$app->request->post()) && $theCt->validate()) {
			$theCt->uo = NOW;
			$theCt->ub = Yii::$app->user->id;
			$theCt->status = 'on';
			if ($theCt->save()) {
				Yii::$app->session->setFlash('success', 'Tour itinerary has been added: '.$theCt->title);
				return $this->redirect('@web/ct/r/'.$theCt['id']);
			}
		}

		return $this->render('ct_u', [
			'theCt'=>$theCt,
		]);
	}

	public function actionR($id = 0)
	{
		return $this->redirect('@web/products/r/'.$id);
		exit;

		$theCt = Ct::findOne($id);
		if (!$theCt) {
			throw new HttpException(404, 'Itinerary not found.');
		}

		$theDays = Day::find()->where(['rid'=>$id])->asArray()->all();

		// Check and fix day numbers
		$dayIdList = explode(',', $theCt['day_ids']);
		if ($theCt['day_count'] != count($dayIdList)) {
			$theCt->day_count = count($dayIdList);
			Yii::$app->db->createCommand()
				->update('at_ct', ['day_count'=>count($dayIdList)], ['id'=>$id])
				->execute();
		}

		$theCases = Yii::$app->db
			->createCommand('SELECT c.id, c.name FROM at_cases c, at_xproposals p WHERE p.case_id=c.id AND p.rid=:id LIMIT 100', [':id'=>$id])
			->queryAll();

		$theTour = Tour::find()
			->where(['ct_id'=>$id])
			->one();

		return $this->render('ct_r', [
			'theCt'=>$theCt,
			'theDays'=>$theDays,
			'theCases'=>$theCases,
			'theTour'=>$theTour,
		]);
	}

	public function actionUfinal($id = 0)
	{
		throw new HttpException(404, 'As from Apr 12, you can edit the final itinerary dicrectly. Go back to the Product view page to edit.');
	}
	
	public function actionU($id = 0)
	{
		return $this->redirect('@web/products/u/'.$id);
		exit;

		$theCt = Ct::findOne($id);

		if (!$theCt) {
			throw new HttpException(404, 'Tour itinerary not found.');
		}

		if (Yii::$app->user->id != 1 && Yii::$app->user->id != $theCt['ub']) {
			throw new HttpException(403, 'Access denied. You are not the owner.');
		}

		if ($theCt['offer_count'] > 0) {
			// Since 140412
			// throw new HttpException(403, 'Access denied. Cannot edit proposed itinerary.');
		}

		$theCt->scenario = 'ct_u';

		$days = Day::find()
			->select(['id', 'name', 'meals'])
			->where(['rid'=>$theCt['id']])
			->all();
		$theDays = [];
		$dayIds = explode(',', $theCt['day_ids']);
		if (!empty($dayIds)) {
			foreach ($dayIds as $id) {
				foreach ($days as $day) {
					if ($day['id'] == $id) {
						$theDays[] = $day;
					}
				}
			}
		}

		if ($theCt->load(Yii::$app->request->post()) && $theCt->validate()) {
			$theCt->uo = NOW;
			$theCt->ub = Yii::$app->user->id;
			if ($theCt->save()) {
				Yii::$app->session->setFlash('success', 'Tour itinerary has been updated: '.$theCt['title']);
				return $this->redirect('@web/ct/r/'.$theCt['id']);
			}
		}

		return $this->render('ct_u', [
			'theCt'=>$theCt,
			'theDays'=>$theDays,
		]);
	}
}
