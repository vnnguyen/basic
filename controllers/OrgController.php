<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use common\models\User;
use common\models\Country;
use common\models\Meta;

class OrgController extends MyController
{

	public function actionIndex()
	{
		return $this->redirect(['org/members']);
		return $this->render('org_index', [
		]);
	}

	public function actionCompanies()
	{
		$theCompanies = Company::find()
			->asArray()
			->limit(10)
			->all();
		return $this->render('org_companies', [
			'theCompanies'=>$theCompanies,
		]);
	}
	
	public function actionDepartments()
	{
		$metas = Meta::find()->where(['rtype'=>'user', 'rid'=>Yii::$app->user->id])->orderBy('k, v')->asArray()->all();
		return $this->render('org_companies', [
			'metas'=>$metas,
		]);
	}
	
	public function actionMembers()
	{
		$theUsers = User::find()
			->where(['is_member'=>'yes', 'status'=>'on'])
			->with([
				'metas',
				'profileMember',
				])
			->orderBy('lname, fname')
			->asArray()
			->all();
		return $this->render('org_members', [
			'theUsers'=>$theUsers,
		]);
	}
	
}
