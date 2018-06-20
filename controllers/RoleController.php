<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Group;
use common\models\User;
use common\models\Permission;
use common\models\Role;

class RoleController extends MyController
{
	public function actionIndex()
	{
		$theRoles = Role::find()
			->orderBy('name')
			->limit(1000)
			->asArray()
			->all();

		return $this->render('role_index', [
			'theRoles'=>$theRoles,
		]);
	}

	public function actionC()
	{
		if (USER_ID != 1) {
			throw new HttpException(403, 'Access denied.');
		}

		$theRole = new Role;
		$theRole->scenario = 'role/c';

		if ($theRole->load(Yii::$app->request->post()) && $theRole->validate()) {
			$theRole->created_at = NOW;
			$theRole->created_by = USER_ID;
			$theRole->updated_at = NOW;
			$theRole->updated_by = USER_ID;
			$theRole->status = 'on';
			$theRole->save();
			return $this->redirect('/roles');
		}
		return $this->render('role_c', [
			'theRole'=>$theRole,
		]);
	}

	public function actionR($id = 0)
	{
		if (USER_ID > 4) {
			throw new HttpException(403, 'Access denied.');
		}

		$theRole = Role::findOne($id);

		if (!$theRole) {
			throw new HttpException(404, 'Role not found');
		}

		$theGroups = Group::find()
			->where(['stype'=>'user'])
			->orderBy('name')
			->asArray()
			->all();

		//$theRole->scenario = 'roles_c';

		if ($theRole->load(Yii::$app->request->post()) && $theRole->validate()) {
			$theRole->updated_at = NOW;
			$theRole->updated_by = USER_ID;
			$theRole->save();
			return $this->redirect('/roles');
		}

		return $this->render('role_r', [
			'theRole'=>$theRole,
			'theGroups'=>$theGroups,
		]);
	}

	public function actionU($id = 0)
	{
		if (USER_ID > 4) {
			throw new HttpException(403, 'Access denied.');
		}

		$theRole = Role::findOne($id);

		if (!$theRole) {
			throw new HttpException(404, 'Role not found');
		}

		$theRole->scenario = 'role/u';

		if ($theRole->load(Yii::$app->request->post()) && $theRole->validate()) {
			$theRole->updated_at = NOW;
			$theRole->updated_by = USER_ID;
			$theRole->save(false);
			return $this->redirect('/roles');
		}

		return $this->render('role_u', [
			'theRole'=>$theRole,
		]);
	}

	public function actionUx($id = 0)
	{
		$theRole = [];
		$theRoles = Role::find()
			->orderBy('name')
			->with(['users'=>function($q) {
					return $q->select(['id', 'name'])->where(['is_member'=>'yes'])->orderBy('fname, lname');
				}
			])
			->asArray()
			->all();

		return $this->render('role_u', [
			'theRole'=>$theRole,
			'theRoles'=>$theRoles,
			]);
	}


}
