<?php

namespace app\controllers;

use Yii;
use common\models\User2;

class KblmemberController extends MyController
{
	public function actionIndex()
	{
		$models = User2::find()
			->select(['id', 'email', 'phone', 'bday', 'bmonth', 'image',
				new \yii\db\Expression('IF(country_code IN ("fr", "be", "us"), CONCAT(lname, " ", fname), CONCAT(fname, " ", lname)) AS name')])
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orderBy('lname, fname')
			->with(['metas', 'profileMember'])
			->asArray()
			->all();
		return $this->render('kblmembers', [
			'models'=>$models,
		]);
	}
}
