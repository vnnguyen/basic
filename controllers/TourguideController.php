<?php

namespace app\controllers;

use Yii;
use yii\base\Model;
use yii\data\Pagination;
use yii\web\HttpException;

use common\models\Tourguide;
use common\models\ProfileTourguide;
use common\models\Person;
use common\models\Country;
use common\models\TourguidesCForm;

class TourguideController extends MyController
{
    // Them ma NCC
    public function actionNcc($action = 'list', $name = '', $region = '', $blank = '')
    {
        // Huan, Hien, Nga KT
        if (!in_array(MY_ID, [1,11,37159])) {
            throw new HttpException(403);
        }

        if ($action == 'ncc') {
            if (isset($_POST['value'], $_POST['pk'])) {
                $sql = 'UPDATE at_profiles_tourguide SET ma_ncc=:ma_ncc WHERE user_id=:user_id LIMIT 1';
                Yii::$app->db->createCommand($sql, [
                        ':ma_ncc'=>trim($_POST['value']),
                        ':user_id'=>$_POST['pk'],
                    ])->execute();
            } else {
                throw new HttpException(401, 'Error');
            }
            exit;
        }

        $query = Person::find()
            ->innerJoin('{{%profiles_tourguide}} tgp', 'tgp.user_id=persons.id');

        if ($name != '') {
            $query->andWhere(['or', ['like', 'ma_ncc', $name], ['like', 'fname', $name], ['like', 'lname', $name]]);
        }
        if ($region != '') {
            $query->andWhere(['like', 'regions', $region]);
        }
        if ($blank == 'yes') {
            $query->andWhere(['!=', 'ma_ncc', '']);
        } elseif ($blank == 'no') {
            $query->andWhere(['ma_ncc'=>'']);
        }
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $theTourguides = $query
            ->select('tgp.guide_since, tgp.ratings, tgp.tour_types, tgp.ma_ncc, tgp.regions, tgp.languages, persons.id, persons.status, fname, lname, gender, email, phone, bday, bmonth, byear, note')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('tourguide_ncc', [
            'theTourguides'=>$theTourguides,
            'pagination'=>$pagination,
            'name'=>$name,
            'region'=>$region,
            'blank'=>$blank,
            'action'=>$action,
        ]);
    }

    public function actionIndex()
    {
        // Thuy Linh, Bich Ngoc, Ngo Hang, Kim Ngoc, Khang Ha, Tuyen, Thu Hien
        if (!in_array(MY_ID, [37675, 1,2,3,4,8,11,118,4432,25457,27726,29296,33415])) {
            throw new HttpException(403);
        }

        $getOrderby = Yii::$app->request->get('orderby', 'name');
        $getName = Yii::$app->request->get('name', '');
        $getPhone = Yii::$app->request->get('phone', '');
        $getLanguage = Yii::$app->request->get('language', '');
        $getRegion = Yii::$app->request->get('region', '');
        $getTourtype = Yii::$app->request->get('tourtype', '');
        $getGender = Yii::$app->request->get('gender', 'all');

        $query = Person::find()
            ->innerJoin('{{%profiles_tourguide}} tgp', 'tgp.user_id=persons.id');

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
            'pageSize'=>36,
        ]);

        if ($getOrderby == 'pts') {
            $query->orderBy('ratings DESC, lname, fname');
        } elseif ($getOrderby == 'age') {
            $query->orderBy('byear, lname, fname');
        } elseif ($getOrderby == 'since') {
            $query->orderBy('guide_since, lname, fname');
        } else {
            $query->orderBy('lname, fname');
        }

        $theTourguides = $query
            ->select('tgp.guide_since, tgp.ratings, tgp.tour_types, tgp.regions, tgp.languages, persons.id, persons.status, fname, lname, gender, email, phone, image, byear, note')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();


        return $this->render('tourguide_index', [
            'pages'=>$pages,
            'theTourguides'=>$theTourguides,
            'getOrderby'=>$getOrderby,
            'getName'=>$getName,
            'getLanguage'=>$getLanguage,
            'getPhone'=>$getPhone,
            'getGender'=>$getGender,
            'getRegion'=>$getRegion,
            'getTourtype'=>$getTourtype,
        ]);
    }

    // Add new tour guide
    public function actionC()
    {
        if (!in_array(MY_ID, [37675, 1,2,3,4,118,4432,25457,27726])) {
            throw new HttpException(403);
        }

        $theUser = new Person;

        $theUser->scenario = 'tourguide/c';

        $theUser->country_code = 'vn';
        $theUser->gender = 'male';
        if ($theUser->load(Yii::$app->request->post()) && $theUser->validate()) {
            // Save user
            $theUser->created_at = NOW;
            $theUser->created_by = MY_ID;
            $theUser->updated_at = NOW;
            $theUser->updated_by = MY_ID;
            $theUser->save(false);
            return $this->redirect('@web/tourguides/u/'.$theUser['id']);
        }

        $allCountries = \common\models\Country::find()->select(['code', 'name_en'])->orderBy('name_en')->asArray()->all();

        return $this->render('tourguides_c', [
            'theUser'=>$theUser,
            'allCountries'=>$allCountries,
        ]);
    }

    public function actionR($id = 0)
    {
        $theGuide = Person::find()
            ->where(['id'=>$id])
            ->with([
                'profileDriver',
                'profileMember',
                'profileTourguide',
                ])
            ->asArray()
            ->one();

        if (!$theGuide) {
            throw new HttpException(404, 'User not found');
        }

        if (!$theGuide['profileTourguide']) {
            throw new HttpException(404, 'User not a tour guide!');
        }

        //$theTours = Yii::$app->db
            //->createCommand('SELECT t.id, t.code, t.name, t.status, tg.day, tg.pax_ratings FROM at_tours t, at_tour_guide tg WHERE tg.tour_id=t.id AND tg.user_id=:id GROUP BY t.id ORDER BY tg.day DESC LIMIT 1000', [':id'=>$theGuide['id']])
            //->queryAll();

        $sql = 'SELECT t.id, t.op_code, t.op_name, t.op_finish, t.day_from, tg.use_from_dt, tg.use_until_dt, tg.note, tg.points FROM at_ct t, at_tour_guides tg WHERE t.op_status="op" AND tg.tour_id=t.id AND tg.guide_user_id=:user_id ORDER BY tg.use_from_dt DESC LIMIT 1000';
        $theTours = Yii::$app->db->createCommand($sql, ['user_id'=>$theGuide['id']])->queryAll();

        return $this->render('tourguides_r', [
            'theGuide'=>$theGuide,
            'theTours'=>$theTours,
        ]);
    }

    public function actionU($id = 0)
    {
        // Bich Ngoc, Ngo Hang, Kim Ngoc, Khang Ha
        if (!in_array(MY_ID, [37675, 1,2,3,4,118,4432,25457,27726,33415])) {
            throw new HttpException(403);
        }

        $theUser = Person::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theUser) {
            throw new HttpException(404, 'User not found');         
        }

        $theUser->scenario = 'tourguide/u';

        $theProfile = ProfileTourguide::find()
            ->where(['user_id'=>$theUser['id']])
            ->one();

        if (!$theProfile) {
            $theProfile = new ProfileTourguide;
            $theProfile->scenario = 'tourguide/c';
        } else {
            $theProfile->scenario = 'tourguide/u';
        }

        $uploadDir = 'users/'.substr($theUser['created_at'], 0, 7).'/'.$theUser['id'];
        \yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/'.$uploadDir);

        $ckfSessionName = 'user'.$theUser['id'];
        $ckfSessionValue = [
            'ckfResourceName'=>'upload',
            'ckfResourceDirectory'=>$uploadDir,
        ];
        Yii::$app->session->set('ckfAuthorized', true);
        Yii::$app->session->set('ckfRole', 'user');
        Yii::$app->session->set($ckfSessionName, $ckfSessionValue);

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

                $theProfile->user_id = $theUser['id'];
                $theProfile->save(false);
                return $this->redirect('@web/tourguides/r/'.$theUser['id']);
            }
        }

        $allCountries = \common\models\Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('tourguides_u', [
            'theProfile'=>$theProfile,
            'theUser'=>$theUser,
            'allCountries'=>$allCountries,
        ]);
    }

    public function actionBirthdays($month = null)
    {
        if (!in_array(USER_ID, [37675, 1,2,3,4, 118, 4432,29296,27726])) {
            throw new HttpException(403, 'Access denied');
        }

        $getMonth = date('n');
        if (isset($month) && (int)$month <= 12) {
            $getMonth = $month;
        }

        $query = Person::find()
            ->innerJoin('{{%profiles_tourguide}} tgp', 'tgp.user_id=persons.id')
            ->where(['bmonth'=>$getMonth]);

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
        ]);

        $theUsers = $query
            ->select(['persons.id', 'fname', 'lname', 'gender', 'country_code', 'bday', 'bmonth', 'byear'])
            ->with(['metas'])
            ->orderBy('bday, byear')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('tourguides_birthdays', [
            'pages'=>$pages,
            'theUsers'=>$theUsers,
            'getMonth'=>$getMonth,
        ]);
    }

}
