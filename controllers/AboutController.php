<?php

namespace app\controllers;

use Yii;
use common\models\User;

class AboutController extends MyController
{
	public function actionMembers() {
		$models = User::find()
			->where(['status'=>'on', 'is_member'=>['or', 'yes', 'old']])
			->orderBy('lname, fname DESC')
			->asArray()
			->all();
		return $this->render('members', [
				'models'=>$models,
			]
		);
	}

	public function actionMembersr($id = 0)
	{
		$model = User::find()
			->where(['id'=>$id, 'is_member'=>'yes'])
			->with(['roles', 'metas', 'profileMember'])
			->one();
		return $this->render('members_r', [
			'model'=>$model,
		]);
	}
}

