<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Campaign;
use common\models\User;
use common\models\Message;

class CampaignController extends MyController
{
	public function actionIndex()
	{
		$query = Campaign::find();
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
		return $this->render('campaigns', [
			'pages'=>$pages,
			'models'=>$models,
			]
		);
	}

	public function actionC()
	{
		$model = new Campaign;

		$caseOwnerList = User::find()
			->select('id, name, email')
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orderBy('lname, fname')
			->asArray()
			->all();

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return Yii::$app->response->redirect('campaigns/r/'.$id);
		}
		return $this->render('campaigns_u', [
			'model'=>$model,
			]);
	}

	public function actionR($id = 0) {
		$model = Campaign::findOne($id);
		if (!$model)
			throw new HttpException(404, 'Campaign not found');

		return $this->render('campaigns_r', [
			'model'=>$model,
		]);
	}

	public function actionU($id = 0)
	{
		$model = Campaign::findOne($id);
		if (!$model)
			throw new HttpException(404);

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return Yii::$app->response->redirect('campaigns/r/'.$id);
		}
		return $this->render('campaigns_u', [
			'model'=>$model,
			]);
	}
}
