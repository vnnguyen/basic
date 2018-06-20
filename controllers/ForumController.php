<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Forum;

class ForumController extends MyController
{
	public function actionIndex() {
		return $this->render('forums');
	}
}
