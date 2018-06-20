<?php

namespace app\controllers;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\BaseFileHelper;
use common\models\User;
use common\models\Country;
use common\models\Meta;
use yii\web\HttpException;

class MeController extends MyController
{

	public function actionIndex()
	{
		return $this->actionProfile();
	}

	public function actionProfile()
	{
		$metas = Meta::find()->where(['rtype'=>'user', 'rid'=>Yii::$app->user->id])->orderBy('k, v')->asArray()->all();
		return $this->render('me_profile', [
			'metas'=>$metas,
		]);
	}
	
	public function actionAccount()
	{
		$model = User::find()->where(['id'=>Yii::$app->user->id])->one();
		if (!$model) {
			throw new HttpException(403);
		}

		$model->scenario = 'changePassword';

		if ($model->load($_POST) && $model->validate()) {
			$model->password = Yii::$app->security->generatePasswordHash($model->rawPassword);
			$model->save();
			return $this->redirect('@web/me');
		}

		return $this->render('me_account', [
			'model'=>$model,
		]);
	}

	public function actionPreferences()
	{
		$model = User::find()->where(['id'=>Yii::$app->user->id])->one();

		$model->scenario = 'meprefs';
		// var_dump($model);die();
		$countries = Country::find()->select(['code', 'name_en'])->orderBy('name_en')->all();
		if ($model->load($_POST) && $model->validate()) {
			//CHECK AVATAR IMG FILE UPLOAD
			if (isset($_POST['img-avatar']) && $_POST['img-avatar'] != '') {
				$rel_path = "/uploads/".date('Y').'/'.date('m').'/'.USER_ID.'/'.Yii::$app->getSecurity()->generateRandomString(10).'_'.time();
				$path = Yii::getAlias('@webroot').$rel_path;
				$file_name = isset($_POST['img-avatar']) ? basename(stripslashes($_POST['img-avatar'])) : null;
				$arr_tmp_path = explode('/', str_replace('//', '', $_POST['img-avatar']));
				$tmp = implode('/', array_diff($arr_tmp_path, [$arr_tmp_path[0]]));
				$tmp_path = Yii::getAlias('@webroot').'/'.$tmp;
				$tmp_dir = dirname($tmp_path);
				if (file_exists($tmp_path)) {
					if( !is_dir( $path ) ) {
			            FileHelper::createDirectory( $path );
			            // chmod( $path, 0777 );
			        }

					$file_ext = substr(strrchr($file_name, '.'), 1);
					$newFileName = Yii::$app->getSecurity()->generateRandomString(10).'.'.$file_ext;
					$final_dir = $path.'/'.$newFileName;
					if (copy($tmp_path, $final_dir)) {
						BaseFileHelper::removeDirectory($tmp_dir);
						$model->image = Yii::getAlias('@www').$rel_path.'/'.$newFileName;
					}
				}
			}
			if ($model->save()) {
				Yii::$app->session->setFlash('success', 'Your preferences have been saved.');
				return $this->redirect('@web/me');
			}
		}

		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', 'https://my.amicatravel.com/upload/user-files/'.Yii::$app->user->id);
		Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').'/upload/user-files/'.Yii::$app->user->id);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'user-files/'.Yii::$app->user->id);
		Yii::$app->session->set('ckfinder_resource_name', 'user-files');

		return $this->render('me_preferences', [
			'model'=>$model,
			'countries'=>$countries,
		]);
	}
	protected function remoteFileExists($url) {
		$curl = curl_init($url);

		//don't fetch the actual page, you only want to check the connection is ok
		curl_setopt($curl, CURLOPT_NOBODY, true);

		//do request
		$result = curl_exec($curl);

		$ret = false;

		//if request did not fail
		if ($result !== false) {
		    //if request was ok, check response code
		    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

		    if ($statusCode == 200) {
		        $ret = true;   
		    }
		}

		curl_close($curl);

		return $ret;
	}

	public function actionReports()
	{
		return $this->render('me_reports');
	}

	public function actionHome()
	{		
		return $this->render('//home');
	}

	public function actionCalendar()
	{		
		return $this->render('//calendar/calendar');
	}
}
