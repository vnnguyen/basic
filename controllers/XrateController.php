<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use common\models\Xrate;
use yii\data\Pagination;
use yii\web\HttpException;

class XrateController extends MyController
{
	public function actionIndex() {
		$query = Xrate::find()->where(array('status'=>'on'))->orderBy('rate_dt DESC');
		$countQuery = clone $query;
		$pages = new Pagination(array('totalCount' => $countQuery->count()));
		$models = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
/*
		Yii::$app->mail->compose()
			->setTo('hn.huan@gmail.com')
			->setFrom(['huan@my.amicatravel.com'=>'Amica Travel IMS'])
			->setSubject('Testing Swiftmailer from Yii')
			->setTextBody('Hello world. Đây là tiếng Việt. Bạn '.Yii::$app->user->identity->name.' vừa xem xrates.')
			->send();
*/
		return $this->render('xrates', [
				'pages'=>$pages,
				'models'=>$models,
			]
		);
	}

	public function actionC() {
		$model = new Xrate;
		//$model->setScenario('create');
		$model->rate_dt = NOW;
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->save(false)) {
				Yii::$app->response->redirect('@web/xrates');
			}
		}
		return $this->render('xrates_c', [
			'model'=>$model,
		]);
	}

	public function actionU($id = 0) {
		$model = Xrate::findOne($id);
		if (!$model) {
			throw new HttpException(404, 'Exchange rate not found');
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->save(false)) {
				Yii::$app->response->redirect('@web/xrates');
			}
		}
			
		return $this->render('xrates_c', [
			'model'=>$model,
		]);
	}

	public function actionD($id = 0)
	{
		$model = Xrate::findOne($id);
		if (!$model)
			throw new HttpException(404, 'Exchange rate not found');

		if ($model->delete($id))
			Yii::$app->response->redirect('@web/xrates');
	}

	public function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->isNewRecord()) {
				$this->created_at = NOW;
			} else {
				$this->updated_at = NOW;
			}
		}
	}
}
