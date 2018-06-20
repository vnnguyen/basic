<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use common\models\User;
use common\models\Driver;
use common\models\ProfileDriver;
use common\models\Vehicle;
use common\models\Tour;
use common\models\TourDriver;
use common\models\Product;
use common\models\Booking;

class DriverController extends MyController
{
	// 160613
	public function actionReport($company = '', $driver = '', $month = '')
	{
		$query = TourDriver::find()
			->with([
				'driver'=>function($q) {
					return $q->select(['id', 'name']);
				},
				'tour'=>function($q) {
					return $q->select(['id', 'op_code', 'op_name']);
				}
			]);

		if (strlen($company) >= 2) {
			if (substr($company, 0, 1) == '"' && substr($company, -1) == '"') {
				$query->andWhere(['driver_company'=>str_replace('"', '', $company)]);
			} else {
				$query->andWhere(['like', 'driver_company', $company]);
			}
		}
		if (strlen($driver) >= 2) {
			if (substr($driver, 0, 1) == '"' && substr($driver, -1) == '"') {
				$query->andWhere(['driver_name'=>str_replace('"', '', $driver)]);
			} else {
				$query->andWhere(['like', 'driver_name', $driver]);
			}
		}
		if (strlen($month) == 4) {
			$query->andWhere('YEAR(use_from_dt)=:y', [':y'=>$month]);
		} elseif (strlen($month) == 7) {
			$query->andWhere('SUBSTRING(use_from_dt, 1, 7)=:y', [':y'=>$month]);
		}

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>50,
		]);

		$theTourDrivers = $query
			->offset($pagination->offset)
			->limit($pagination->limit)
			->orderBy('use_from_dt DESC')
			->asArray()
			->all();

		return $this->render('driver_report', [
			'pagination'=>$pagination,
			'theTourDrivers'=>$theTourDrivers,
			'company'=>$company,
			'driver'=>$driver,
			'month'=>$month,
		]);
	}

	public function actionIndex()
	{
		if (!in_array(MY_ID, [1,2,3,4,118,4432,27729])) {
			throw new HttpException(403);
		}

		$getOrderby = Yii::$app->request->get('orderby', 'name');
		$getName = Yii::$app->request->get('name', '');
		$getPhone = Yii::$app->request->get('phone', '');
		$getLanguage = Yii::$app->request->get('language', '');
		$getRegion = Yii::$app->request->get('region', '');
		$getTourtype = Yii::$app->request->get('tourtype', '');
		$getGender = Yii::$app->request->get('gender', 'all');

		$query = User::find()
			->innerJoin('{{%profiles_driver}} tgp', 'tgp.user_id=persons.id');

		if (strlen($getName) >= 2) {
			$query->andWhere(['like', 'persons.name', $getName]);
		}

		if (strlen($getLanguage) >= 2) {
			$query->andWhere(['like', 'languages', $getLanguage]);
		}

		if (strlen($getRegion) >= 2) {
			$query->andWhere(['like', 'regions', $getRegion]);
		}

		if (strlen($getTourtype) >= 2) {
			$query->andWhere(['like', 'tour_types', $getTourtype]);
		}

		if (in_array($getGender, ['male', 'female'])) {
			$query->andWhere(['gender'=>$getGender]);
		}

		if (strlen($getPhone) > 2) {
			$query->andWhere(['like', 'phone', $getPhone]);
		}

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);

		if ($getOrderby == 'pts') {
			$query->orderBy('points DESC, lname, fname');
		} elseif ($getOrderby == 'age') {
			$query->orderBy('byear, lname, fname');
		} elseif ($getOrderby == 'since') {
			$query->orderBy('guide_since, lname, fname');
		} else {
			$query->orderBy('lname, fname');
		}

		$theDrivers = $query
			->select('tgp.since, tgp.points, tgp.tour_types, tgp.vehicle_types, tgp.regions, tgp.languages, persons.id, persons.status, fname, lname, gender, email, phone, image, byear, persons.info')
			->offset($pages->offset)
			->limit($pages->limit)
			->asArray()
			->all();

		return $this->render('drivers', [
			'pages'=>$pages,
			'theDrivers'=>$theDrivers,
			'getOrderby'=>$getOrderby,
			'getName'=>$getName,
			'getLanguage'=>$getLanguage,
			'getPhone'=>$getPhone,
			'getGender'=>$getGender,
			'getRegion'=>$getRegion,
			'getTourtype'=>$getTourtype,
		]);
	}

	public function actionC($id = 0)
	{
		if (!in_array(MY_ID, [1,2,3,4,118,4432])) {
			throw new HttpException(403);
		}

		$theUser = new User;

		$theUser->scenario = 'driver/c';

		$theUser->country_code = 'vn';
		$theUser->gender = 'male';
		if ($theUser->load(Yii::$app->request->post()) && $theUser->validate()) {
			// Save user
			$theUser->created_at = NOW;
			$theUser->created_by = MY_ID;
			$theUser->updated_at = NOW;
			$theUser->updated_by = MY_ID;
			$theUser->save(false);
			return $this->redirect('@web/drivers/u/'.$theUser['id']);
		}

		$allCountries = \common\models\Country::find()->select(['code', 'name_en'])->orderBy('name_en')->asArray()->all();

		return $this->render('drivers_c', [
			'theUser'=>$theUser,
			'allCountries'=>$allCountries,
		]);
	}

	public function actionR($id = 0)
	{
		$theUser = User::find()
			->where(['id'=>$id])
			->with([
				'profileMember',
				'profileTourguide',
				])
			->asArray()
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'Person could not be found.');
		}

		$theProfile = ProfileDriver::find()
			->where(['user_id'=>$id])
			->asArray()
			->one();

		if (!$theProfile) {
			throw new HttpException(404, 'Person is not a driver!');
		}

		$sql = 'select td.*, t.op_code, t.op_name, (select name from persons u where u.id=td.updated_by limit 1) as updated_by_name from at_tour_drivers td, at_ct t where t.id=td.tour_id and driver_user_id=:id order by use_from_dt desc limit 1000';
		$theDiemlx = Yii::$app->db->createCommand($sql, [':id'=>$theUser['id']])->queryAll();

		return $this->render('drivers_r', [
			'theUser'=>$theUser,
			'theProfile'=>$theProfile,
			'theDiemlx'=>$theDiemlx,
		]);
	}

	public function actionU($id = 0)
	{
		if (!in_array(MY_ID, [1,2,3,4,118,4432])) {
			throw new HttpException(403);
		}

		$theUser = User::find()
			->where(['id'=>$id])
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'User not found');			
		}

		$theUser->scenario = 'driver/u';

		$theProfile = ProfileDriver::find()
			->where(['user_id'=>$theUser['id']])
			->one();

		if (!$theProfile) {
			$theProfile = new ProfileDriver;
			$theProfile->scenario = 'drivers/c';
			$theProfile->since = NOW;
			$theProfile->us_since = NOW;
		} else {
			$theProfile->scenario = 'drivers/u';
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
					$theProfile->created_at = NOW;
					$theProfile->created_by = MY_ID;
				}
				$theProfile->updated_at = NOW;
				$theProfile->updated_by = MY_ID;

				$theProfile->user_id = $theUser['id'];
				$theProfile->save(false);
				return $this->redirect('@web/drivers/r/'.$theUser['id']);
			}
		}

		$allCountries = \common\models\Country::find()
			->select(['code', 'name_en'])
			->orderBy('name_en')
			->asArray()
			->all();

		return $this->render('drivers_u', [
			'theProfile'=>$theProfile,
			'theUser'=>$theUser,
			'allCountries'=>$allCountries,
		]);
	}

	public function actionD($id = 0)
	{
		if (!in_array(MY_ID, [1,2,3,4,118,4432])) {
			throw new HttpException(403);
		}

		$theUser = User::find()
			->where(['id'=>$id])
			->asArray()
			->one();

		if (!$theUser) {
			throw new HttpException(404, 'Person could not be found.');
		}

		$theProfile = ProfileDriver::find()
			->where(['user_id'=>$id])
			->one();

		if (!$theProfile) {
			throw new HttpException(404, 'Person is not a driver!');
		}

		if (Yii::$app->request->isPost && MY_ID == $theProfile['created_by']) {
			$theProfile->delete();
			return $this->redirect('@web/users/r/'.$theUser['id']);
		}

		return $this->render('drivers_d', [
			'theUser'=>$theUser,
			'theProfile'=>$theProfile,
		]);
	}

	// Phan lai xe cho tour
	public function actionTour($id = 0)
	{
		$theTour = Tour::find()
			->where(['id'=>$id])
			->asArray()
			->one();
		if (!$theTour) {
			throw new HttpException(404, 'Tour not found.');
		}

		$theProduct = Product::find()
			->where(['id'=>$theTour['ct_id']])
			->with(['days'])
			->asArray()
			->one();
		if (!$theProduct) {
			throw new HttpException(404, 'Tour itinerary not found.');
		}

		$theDrivers = Yii::$app->db
			->createCommand('SELECT u.id, u.fname, u.lname, u.phone FROM persons u, at_profiles_driver d WHERE d.user_id=u.id ORDER By u.lname, u.fname LIMIT 1000')
			->queryAll();

		// if (Yii::$app->request->isPost && isset($_POST['action']) && $_POST['action'] == 'add') {
		if (Yii::$app->request->isPost) {
			foreach ($_POST['days'] as $day) {
				echo '<br>', $day;
			}
		}

		return $this->render('drivers_tour', [
			'theTour'=>$theTour,
			'theProduct'=>$theProduct,
			'theDrivers'=>$theDrivers,
		]);
	}

	// All drivers and tours
	public function actionTours()
	{
		$sql = 'select td.*, t.op_code, t.op_name, (select name from persons u where u.id=td.updated_by limit 1) as updated_by_name from at_tour_drivers td, at_ct t where t.id=td.tour_id order by use_from_dt desc limit 1000';
		$theTours = Yii::$app->db->createCommand($sql)->queryAll();
		return $this->render('drivers_tours', [
			'theTours'=>$theTours,
			//'pagination'=>$pagination,
		]);
	}
}
