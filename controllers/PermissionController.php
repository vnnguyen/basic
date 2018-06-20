<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Group;
use common\models\User;
use common\models\Permission;
use common\models\Role;

class PermissionController extends MyController
{
	public function actionIndex()
	{
		$thePermissions = Permission::find()
			->orderBy('name')
			->limit(1000)
			->asArray()
			->all();

		return $this->render('permission_index', [
			'thePermissions'=>$thePermissions,
		]);
	}

	public function actionC()
	{
		if (USER_ID != 1) {
			throw new HttpException(403, 'Access denied.');
		}

		$thePermission = new Permission;
		//$thePermission->scenario = 'permissions_c';

		if ($thePermission->load(Yii::$app->request->post()) && $thePermission->validate()) {
			$thePermission->created_at = NOW;
			$thePermission->created_by = USER_ID;
			$thePermission->updated_at = NOW;
			$thePermission->updated_by = USER_ID;
			$thePermission->status = 'on';
			$thePermission->save();
			return $this->redirect('/permissions');
		}
		return $this->render('permission_c', [
			'thePermission'=>$thePermission,
		]);
	}

	public function actionR($id = 0)
	{
		if (USER_ID > 4) {
			throw new HttpException(403, 'Access denied.');
		}

		$thePermission = Permission::findOne($id);

		if (!$thePermission) {
			throw new HttpException(404, 'Permission not found');
		}

		$theGroups = Group::find()
			->where(['stype'=>'user'])
			->orderBy('name')
			->asArray()
			->all();

		//$thePermission->scenario = 'permissions_c';

		if ($thePermission->load(Yii::$app->request->post()) && $thePermission->validate()) {
			$thePermission->updated_at = NOW;
			$thePermission->updated_by = USER_ID;
			$thePermission->save();
			return $this->redirect('/permissions');
		}

		return $this->render('permission_r', [
			'thePermission'=>$thePermission,
			'theGroups'=>$theGroups,
		]);
	}

	public function actionU($id = 0)
	{
		if (USER_ID > 4) {
			throw new HttpException(403, 'Access denied.');
		}

		$thePermission = Permission::findOne($id);

		if (!$thePermission) {
			throw new HttpException(404, 'Permission not found');
		}

		//$thePermission->scenario = 'permissions_c';

		if ($thePermission->load(Yii::$app->request->post()) && $thePermission->validate()) {
			$thePermission->updated_at = NOW;
			$thePermission->updated_by = USER_ID;
			$thePermission->save();
			return $this->redirect('/permission');
		}

		return $this->render('permission_u', [
			'thePermission'=>$thePermission,
		]);
	}

	public function actionUx($id = 0)
	{
		$thePermission = [];
		$theRoles = Role::find()
			->orderBy('name')
			->with(['users'=>function($q) {
					return $q->select(['id', 'name'])->where(['is_member'=>'yes'])->orderBy('fname, lname');
				}
			])
			->asArray()
			->all();

		return $this->render('permission_u', [
			'thePermission'=>$thePermission,
			'theRoles'=>$theRoles,
			]);
	}


}
