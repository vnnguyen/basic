<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Option;

class OptionController extends MyController
{
	public function actionIndex() {
		$theOptions = Option::find()
			->asArray()
			->all();
		return $this->render('options', [
			'theOptions'=>$theOptions,
		]);
	}

}
