<?php

namespace app\controllers;

use Yii;
use common\models\Destination;
use common\models\Country;
use yii\web\HttpException;

class DestinationController extends MyController
{
	public function actionIndex()
	{
		$models = Destination::find()->orderBy('country_code, name_en')->asArray()->all();
		$countries = Country::find()->orderBy('name_en')->asArray()->all();
		return $this->render('destinations', [
				'models'=>$models,
				'countries'=>$countries,
			]
		);
	}

	public function actionR($id = 0)
	{
		$model = Destination::findOne($id);
		if (!$model)
			throw new HttpException(404);

		return $this->render('destinations_r', [
			'model'=>$model,
		]);
	}


	public function actionC()
	{
		$theDest = new Destination;
		$theDest->scenario = 'create';

		$allCountries = Country::find()->select(['code', 'name_en'])->orderBy('name_en')->asArray()->all();


		if ($theDest->load($_POST) && $theDest->validate()) {
			$theDest->save();
			return Yii::$app->response->redirect('destinations');
		}
			
		return $this->render('destinations_u', [
			'theDest'=>$theDest,
			'allCountries'=>$allCountries,
			]);
	}

	public function actionU($id = 0)
	{
		$theDest = Destination::findOne($id);
		if (!$theDest)
			throw new HttpException(404);

		$theDest->scenario = 'update';

		$allCountries = Country::find()->select(['code', 'name_en'])->orderBy('name_en')->asArray()->all();

		if ($theDest->load($_POST) && $theDest->validate()) {
			$theDest->save();
			return Yii::$app->response->redirect('destinations');
		}

		return $this->render('destinations_u', [
			'theDest'=>$theDest,
			'allCountries'=>$allCountries,
			]);
	}

	public function actionD($id = 0)
	{
		if (Yii::$app->user->id != 1)
			throw new HttpException(403, 'Huan only');
		$theDest = Destination::findOne($id);
		if (!$theDest)
			throw new HttpException(404, 'Destination not found');
		$theDest->delete();
		return $this->redirect('destinations');
			
			
	}

}
