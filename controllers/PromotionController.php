<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Promotion;

class PromotionController extends MyController
{
	public function actionIndex()
	{
		$query = Promotion::find();
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$models = $query
			->orderBy('start_dt DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			->asArray()
			->all();
		return $this->render('promotions', [
			'pages'=>$pages,
			'models'=>$models,
			]
		);
	}

	public function actionC()
	{
		$model = new Promotion;

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return $this->redirect('promotions/r/'.$model->id);
		}
		return $this->render('promotions_u', [
			'model'=>$model,
		]);
	}

	public function actionR($id = 0) {
		$model = Promotion::find()->where(['id'=>$id])->asArray()->one();
		if (!$model)
			throw new HttpException(404, 'Promotion not found');

		return $this->render('promotions_r', [
			'model'=>$model,
		]);
	}

	public function actionU($id = 0)
	{
		$model = Promotion::findOne($id);
		if (!$model)
			throw new HttpException(404);

		$caseOwnerList = User::find()
			->select('id, name, email')
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orderBy('lname, fname')
			->asArray()
			->all();

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return Yii::$app->response->redirect('cases/r/'.$id);
		}
		return $this->render('promotions_u', [
			'model'=>$model,
			'caseOwnerList'=>$caseOwnerList,
			]);
	}


	// Close a case
	public function actionClose($id = 0)
	{
		$model = Promotion::findOne($id);
		if (!$model)
			throw new HttpException(404);

		if ($model->load($_POST) && $model->validate()) {
			//$model->save();
			return Yii::$app->response->redirect('v2cases/r/'.$id);
		}
		return $this->render('promotions_u', [
			'model'=>$model,
			]);
	}

	// Reopen a case
	public function actionReopen($id = 0)
	{
		$model = Promotion::findOne($id);
		if (!$model)
			throw new HttpException(404);

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return Yii::$app->response->redirect('cases/r/'.$id);
		}
		return $this->render('promotions_u', [
			'model'=>$model,
			]);
	}


}
