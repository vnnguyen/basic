<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;

use common\models\Country;
use common\models\Meta;
use common\models\ProfileMember;
use common\models\User;

class MemberController extends MyController
{

	public function actionIndex()
	{
		//return $this->redirect('@web/kb/lists/members');
		//exit;
		$theUsers = User::find()
			->where(['is_member'=>'yes', 'status'=>'on'])
			->with([
				'metas',
				'profileMember',
			])
			->orderBy('lname, fname')
			->asArray()
			->all();

		$theOldMembers = User::find()
			->where(['is_member'=>'old'])
			->with(['metas'])
			->orderBy('lname, fname')
			->asArray()
			->all();

		return $this->render('members', [
			'theUsers'=>$theUsers,
			'theOldMembers'=>$theOldMembers,
		]);
	}

	public function actionR($id = 0)
	{
		$theUser = User::find()
			->where(['id'=>$id])
			->with([
				'profileDriver',
				'profileTourguide',
				])
			->asArray()
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'Person could not be found.');
		}

		$theProfile = profileMember::find()
			->where(['user_id'=>$id])
			->asArray()
			->one();

		if (!$theProfile) {
			throw new HttpException(404, 'Person is not a member!');
		}

		return $this->render('members_r', [
			'theUser'=>$theUser,
			'theProfile'=>$theProfile,
		]);
	}

	public function actionU($id = 0)
	{
		if (!in_array(MY_ID, [1,2,3,4,24229,22447])) {
			throw new HttpException(403);
		}

		$theUser = User::find()
			->where(['id'=>$id])
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'User not found');			
		}

		$theUser->scenario = 'member/u';

		$theProfile = ProfileMember::find()
			->where(['user_id'=>$theUser['id']])
			->one();

		if (!$theProfile) {
			$theProfile = new ProfileMember;
			$theProfile->scenario = 'member/c';
			$theProfile->since = NOW;

			$theUser->is_member = 'yes';
			$theUser->is_member = 'yes';
			$theUser->language = 'en';
			$theUser->timezone = 'Asia/Ho_Chi_Minh';
			$theUser->login = $theUser->email;
			$theUser->password = Yii::$app->security->generatePasswordHash('cp2016CP');
		} else {
			$theProfile->scenario = 'member/u';
		}

		$uploadPath = '/upload/users/'.substr($theUser['created_at'], 0, 7).'/'.$theUser['id'];
		\yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@web').$uploadPath);
		Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
		Yii::$app->session->set('ckfinder_resource_name', 'upload');

		if ($theUser->load(Yii::$app->request->post()) && $theProfile->load(Yii::$app->request->post())) {
			if ($theUser->validate() && $theProfile->validate()) {
				// Save user
				$theUser->save(false);
				// Update user meta
				if ($theUser->getOldAttribute('phone') != $theUser['phone']) {
					$sql = 'delete from at_meta where rtype="user" AND rid=:user_id and k IN ("mobile", "tel") AND v=:phone';
					Yii::$app->db->createCommand($sql, [':user_id'=>$theUser['id'], ':phone'=>$theUser['phone']])->execute();
					$sql = 'INSERT INTO at_meta SET (uo, ub, rtype, rid, k, v) VALUES (NOW(), :my_id, "user", :user_id, "mobile", :phone)';
					Yii::$app->db->createCommand($sql, [':my_id'=>MY_ID, ':user_id'=>$theUser['id'], ':phone'=>$theUser['phone']])->execute();
				}
				if ($theUser->getOldAttribute('email') != $theUser['email']) {
					$sql = 'delete from at_meta where rtype="user" AND rid=:user_id and k IN ("email") AND v=:email';
					Yii::$app->db->createCommand($sql, [':user_id'=>$theUser['id'], ':email'=>$theUser['email']])->execute();
					$sql = 'INSERT INTO at_meta SET (uo, ub, rtype, rid, k, v) VALUES (NOW(), :my_id, "user", :user_id, "email", :phone)';
					Yii::$app->db->createCommand($sql, [':my_id'=>MY_ID, ':user_id'=>$theUser['id'], ':email'=>$theUser['email']])->execute();
				}
				// Update user search
				$search = trim($theUser['fname'].$theUser['lname'].$theUser['name'].' '.$theUser['email'].' '.$theUser['phone']);
				$search = str_replace(['@'], ['--atm--ark--'], $search);
				$search = \fURL::makeFriendly($search, '_');
				$search = str_replace(['_', '--atm--ark--'], ['', '@'], $search);
				$search = strtolower($search);
				$found = trim($theUser['fname'].' '.$theUser['lname'].' '.$theUser['email'].' '.$theUser['phone']);
				Yii::$app->db->createCommand()->update('at_search',
					['search'=>$search, 'found'=>$found],
					['rtype'=>'user', 'rid'=>$theUser['id']])
					->execute();
				if ($theProfile->isNewRecord) {
					$theProfile->created_dt = NOW;
					$theProfile->created_by = MY_ID;
				}
				$theProfile->updated_dt = NOW;
				$theProfile->updated_by = MY_ID;

				$theProfile->user_id = $theUser['id'];
				$theProfile->save(false);
				return $this->redirect('@web/members/r/'.$theUser['id']);
			}
		}

		$allCountries = \common\models\Country::find()
			->select(['code', 'name_en'])
			->orderBy('name_en')
			->asArray()
			->all();

		return $this->render('members_u', [
			'theProfile'=>$theProfile,
			'theUser'=>$theUser,
			'allCountries'=>$allCountries,
		]);
	}
}
