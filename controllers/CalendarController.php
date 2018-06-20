<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use common\models\Event;

class CalendarController extends MyController
{
	public function actionIndex() {
		return $this->render('calendar');
	}
}
