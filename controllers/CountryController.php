<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Country;

class CountryController extends MyController
{
	public function actionIndex() {
		$models = Country::find()->orderBy('name_en')->asArray()->all();
		return $this->render('countries', [
				'models'=>$models,
			]
		);
	}
}
