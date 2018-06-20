<?

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\base\Model;
use common\models\Person;
use common\models\Search;
use common\models\Country;
use common\models\Meta;
use common\models\Meta2;
use common\models\File;
use common\models\Note;
use common\models\ProfileMember;
use common\models\UsersUuForm;
use app\models\PersonForm;

class PersonController extends MyController
{
    public function actionIndex()
    {
        if (!in_array(Yii::$app->user->id, [1,2,3,4,118,695,4432])) {
            return $this->redirect('@web/kb/lists/members');
        }

        $getFname = Yii::$app->request->get('fname', '');
        $getLname = Yii::$app->request->get('lname', '');
        $getCountry = Yii::$app->request->get('country', 'all');
        $getGender = Yii::$app->request->get('gender', 'all');
        $getEmail = Yii::$app->request->get('email', '');
        $getGroup = Yii::$app->request->get('group', 'all');

        $query = Person::find();

        if ($getFname != '') {
            $query->andWhere(['like', 'fname', $getFname]);
        }
        if ($getLname != '') {
            $query->andWhere(['like', 'lname', $getLname]);
        }
        if ($getEmail != '') {
            $query->andWhere(['like', 'email', $getEmail]);
        }
        if (in_array($getGender, ['male', 'female'])) {
            $query->andWhere(['gender'=>$getGender]);
        }
        if (strlen($getCountry) == 2) {
            $query->andWhere(['country_code'=>$getCountry]);
        }

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);
        $thePersons = $query
            ->with(['cases', 'bookings', 'bookings.product'])
            ->orderBy('lname, fname')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('person_index', [
            'pages'=>$pages,
            'thePersons'=>$thePersons,
            'getFname'=>$getFname,
            'getLname'=>$getLname,
            'getGender'=>$getGender,
            'getEmail'=>$getEmail,
            'getCountry'=>$getCountry,
            'countryList'=>$countryList,
        ]);
    }

    // List old tags
    public function actionTags($tag = 0)
    {
        $sql = 'SELECT id, name FROM at_terms WHERE taxonomy_id=2 ORDER BY name';
        $theTags = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'SELECT u.id, u.fname, u.lname, u.email, u.country_code, u.gender, u.byear FROM persons u, at_terms t, at_term_rel r WHERE t.taxonomy_id=2 AND r.term_id=t.id AND rtype="user" AND u.id=r.rid AND t.id=:id ORDER BY lname, fname LIMIT 5000';
        $theUsers = Yii::$app->db->createCommand($sql, [':id'=>$tag])->queryAll();

        $userIdList = [];
        $theTours = [];
        if (!empty($theUsers)) {
            foreach ($theUsers as $user) {
                $userIdList[] = $user['id'];
            }
            $sql = 'SELECT p.id, p.op_name, p.op_code, bu.user_id FROM at_ct p, at_bookings b, at_booking_user bu WHERE bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN ('.implode(', ', $userIdList).') AND op_status="op" ORDER BY p.day_from';
            $theTours = Yii::$app->db->createCommand($sql)->queryAll();
        }


        return $this->render('users_tags', [
            'theTags'=>$theTags,
            'theUsers'=>$theUsers,
            'theTours'=>$theTours,
            'tagId'=>$tag,
        ]);
    }
    public function actionCreate_info($email = '')
    {
        $theUser = new Person;
        $theUser->scenario = 'create';

        $theForm = new PersonForm;

        $theForm->email1 = $email;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $theUser->created_at = NOW;
            $theUser->created_by = USER_ID;
            $theUser->updated_at = NOW;
            $theUser->updated_by = USER_ID;

            $theUser->fname = $theForm->fname;
            $theUser->lname = $theForm->lname;
            $theUser->name = $theForm->name;
            $theUser->gender = $theForm->gender;
            $theUser->bday = $theForm->bday;
            $theUser->bmonth = $theForm->bmonth;
            $theUser->byear = $theForm->byear;
            $theUser->country_code = $theForm->country_code;

            $theUser->email = $theForm->email1;
            $theUser->phone = $theForm->phone1;

            $theUser->save(false);

            Yii::$app->db->createCommand()
                ->insert('at_search', [
                    'rtype'=>'user',
                    'rid'=>$theUser->id,
                    'search'=>str_replace('-', '', \fURL::makeFriendly($theUser->name.' '.$theUser->email.' '.$theUser->phone, '-')),
                    'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                    ])
                ->execute();

            $newMetas = [];

            if ($theForm->email1 != '') {
                $newMetas[] = ['user', $theUser['id'], 'email', $theForm->email1];
            }
            if ($theForm->email2 != '') {
                $newMetas[] = ['user', $theUser['id'], 'email', $theForm->email2];
            }

            if (!empty($newMetas)) {
                Yii::$app->db->createCommand()->batchInsert('at_meta', ['rtype', 'rid', 'k', 'v'], $newMetas)->execute();
            }

            return $this->redirect('@web/users/r/'.$theUser['id']);
        }
                
        return $this->render('users_u', [
            'theUser'=>$theUser,
            'theForm'=>$theForm,
            'allCountries'=>$allCountries,
        ]);
    }


    public function actionC($email = '')
    {
        $allCountries = Country::find()->select(['code', 'name_en'])->orderBy('name_en')->asArray()->all();
        $theUser = new Person;
        $theUser->scenario = 'create';

        $theForm = new UsersUuForm;

        $theForm->email1 = $email;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $theUser->created_at = NOW;
            $theUser->created_by = USER_ID;
            $theUser->updated_at = NOW;
            $theUser->updated_by = USER_ID;

            $theUser->fname = $theForm->fname;
            $theUser->lname = $theForm->lname;
            $theUser->name = $theForm->name;
            $theUser->gender = $theForm->gender;
            $theUser->bday = $theForm->bday;
            $theUser->bmonth = $theForm->bmonth;
            $theUser->byear = $theForm->byear;
            $theUser->country_code = $theForm->country_code;

            $theUser->email = $theForm->email1;
            $theUser->phone = $theForm->phone1;

            $theUser->save(false);

            Yii::$app->db->createCommand()
                ->insert('at_search', [
                    'rtype'=>'user',
                    'rid'=>$theUser->id,
                    'search'=>str_replace('-', '', \fURL::makeFriendly($theUser->name.' '.$theUser->email.' '.$theUser->phone, '-')),
                    'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                    ])
                ->execute();

            $newMetas = [];

            if ($theForm->email1 != '') {
                $newMetas[] = ['user', $theUser['id'], 'email', $theForm->email1];
            }
            if ($theForm->email2 != '') {
                $newMetas[] = ['user', $theUser['id'], 'email', $theForm->email2];
            }
            if ($theForm->email3 != '') {
                $newMetas[] = ['user', $theUser['id'], 'email', $theForm->email3];
            }
            if ($theForm->phone1 != '') {
                $newMetas[] = ['user', $theUser['id'], 'tel', $theForm->phone1];
            }
            if ($theForm->phone2 != '') {
                $newMetas[] = ['user', $theUser['id'], 'tel', $theForm->phone2];
            }
            if ($theForm->address != '') {
                $newMetas[] = ['user', $theUser['id'], 'address', $theForm->address];
            }

            if ($theForm->profession != '') {
                $newMetas[] = ['user', $theUser['id'], 'profession', $theForm->profession];
            }

            if ($theForm->pob != '') {
                $newMetas[] = ['user', $theUser['id'], 'pob', $theForm->pob];
            }
            if ($theForm->website != '') {
                $newMetas[] = ['user', $theUser['id'], 'website', $theForm->website];
            }

            if (!empty($newMetas)) {
                Yii::$app->db->createCommand()->batchInsert('at_meta', ['rtype', 'rid', 'k', 'v'], $newMetas)->execute();
            }

            return $this->redirect('@web/users/r/'.$theUser['id']);
        }
        return $this->render('users_u', [
            'theUser'=>$theUser,
            'theForm'=>$theForm,
            'allCountries'=>$allCountries,
        ]);
    }

    public function actionR($id = 0)
    {
        $theUser = Person::find()
            ->where(['id'=>$id])
            ->with([
                'metas'=>function($query) {
                    $query->andWhere(['rtype'=>'user'])->orderBy('name, id');
                },
                'country',
                'roles',
                'cases.stats',
                'cases.owner'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'bookings',
                'bookings.product',
                'bookings.product.tour',
                'ref',
                'ref.case',
                'ref.case.owner'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'profileMember',
                'profileTourguide',
                'profileDriver',
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404, 'User not found');
        }
        $theProducts = [];
        if ($theUser['profileTourguide']) {
            $theProducts = Yii::$app->db->createCommand('SELECT tg.pax_ratings, t.id, t.code AS op_code, t.name AS op_name FROM at_tours t, at_tour_guide tg, persons u WHERE tg.user_id=:id AND tg.pax_ratings!="" AND u.id=tg.user_id AND tg.tour_id=t.id GROUP BY tg.tour_id ORDER BY SUBSTRING(t.code, 2, 6) DESC', [':id'=>$theUser['id']])->queryAll();
        }

        $userMemberProfile = ProfileMember::find()
            ->where(['user_id'=>$id])
            ->asArray()
            ->one();

        $userFiles = File::find()
            ->where(['rtype'=>'user', 'rid'=>$id])
            ->asArray()
            ->all();

        $userNotes = Note::find()
            ->where(['or', ['rtype'=>'user', 'rid'=>$id], ['from_id'=>$id]])
            ->with('updatedBy')
            ->orderBy('co DESC')
            ->limit(10)
            ->asArray()
            ->all();

        // Users who viewed this
        $viewedBy = Yii::$app->db
            ->createCommand('SELECT u.nickname AS name, u.id FROM persons u, hits h WHERE h.user_id=u.id AND h.uri=:uri GROUP BY u.id ORDER BY u.lname, u.fname', [':uri'=>'/users/r/'.$id])
            ->queryAll();

        $userMails = [];
        if ($theUser['email'] != '') {
            $sql = 'select id, subject, sent_dt, case_id from at_mails where locate(:email, `from`)!=0 or locate(:email, `to`)!=0 order by sent_dt desc limit 5';
            $userMails = Yii::$app->db->createCommand($sql, [':email'=>$theUser['email']])->queryAll();
        }
        // var_dump($userMails);die;
        return $this->render('person_r', [
            'theUser'=>$theUser,
            'userFiles'=>$userFiles,
            'userNotes'=>$userNotes,
            'userMails'=>$userMails,
            'userMemberProfile'=>$userMemberProfile,
            'viewedBy'=>$viewedBy,
            'theProducts'=>$theProducts,
        ]);
    }

    // Log in as another user
    public function actionLoginas($id = 0)
    {
        if (!in_array(USER_ID, [1,2,3,4])) {
            throw new HttpException(403, 'Access denied');
        }
        if (USER_ID == $id) {
            throw new HttpException(403, 'You are already logged in.');
        }

        $theUser = Person::find()
            ->where(['id'=>$id, 'status'=>'on', 'is_member'=>['yes', 'old']])
            ->one();
        if (!$theUser) {
            throw new HttpException(403, 'User not found. User must be an active member.');
        }

        $pwd = Yii::$app->request->post('pwd', '');
        if ($pwd != '' && Yii::$app->security->validatePassword($pwd, Yii::$app->user->identity->password)) {
            Yii::$app->user->switchIdentity($theUser);
            Yii::$app->db
                ->createCommand()
                ->update('at_logins', ['user_id' => $theUser['id']], [
                    'uid'=>Yii::$app->session->get('uid'),
                    'ua_string'=>Yii::$app->request->getUserAgent()])
                ->execute();
            return $this->redirect('@web/tours');
        }

        return $this->render('users_loginas', [
            'theUser'=>$theUser,
        ]);
    }

    public function actionD($id = 0)
    {
        $theUser = Person::findOne($id);

        if (!$theUser) {
            throw new HttpException(404, 'User not found.');
        }

        if (Yii::$app->user->id != 1) {
            throw new HttpException(403, 'Access denied.');
        }

        $getAction = Yii::$app->request->get('action', 'none');
        if ($getAction == 'name') {
            $names = explode(' ', $theUser['fname']);
            $getOption = Yii::$app->request->get('option', 12);
            if ($getOption == 12) {
                $fname = trim($names[0]);
                $lname = trim($names[1]);
            } else {
                $fname = trim($names[1]);
                $lname = trim($names[0]);
            }

            Yii::$app->db->createCommand()
                ->update('persons', [
                    'fname'=>$fname,
                    'lname'=>$lname,
                    ], [
                    'id'=>$id
                    ])
                ->execute();
            die('OK USER NAME');
        }

        if (Yii::$app->request->get('action') == 'delete') {
            Yii::$app->db->createCommand()
                ->update('persons', [
                    'fname'=>'A-BLANK-NAME',
                    'lname'=>'',
                    'name'=>'',
                    'about'=>'',
                    'email'=>'',
                    'phone'=>'',
                    'bday'=>0,
                    'bmonth'=>0,
                    'byear'=>0,
                    'is_client'=>'no',
                    ], [
                    'id'=>$id
                    ])
                ->execute();

            Yii::$app->db->createCommand()
                ->delete('at_meta', ['rtype'=>'user', 'rid'=>$id])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('at_passports', ['user_id'=>$id])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('at_search', ['rtype'=>'user', 'rid'=>$id])
                ->execute();

            die('OK USER DEL');
            return $this->redirect('@web/users/r/'.$id);
        }
    }
    public function actionUpdate_user_info($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $thePerson = Person::find()
                ->where(['id'=>$id])
                // ->asArray()
                ->one();
            if (!$thePerson) {
                throw new HttpException(404, 'Person not found.');
            }
        }
    }

    // Test
    public function actionU($id = 0, $booking_id = 0)
    {
        $thePerson = Person::find()
            ->where(['id'=>$id])
            // ->asArray()
            ->one();
        if (!$thePerson) {
            throw new HttpException(404, 'Person not found.');
        }

        // Booking
        $theTour = false;
        $theTourOld = false;
        if ($booking_id != 0) {
            $tour_id = Yii::$app->db->createCommand('SELECT product_id FROM at_bookings WHERE id=:bid LIMIT 1', [':bid'=>$booking_id])->queryScalar();
            $theTour = Product::find()
                ->with([
                    'pax'=>function($q){
                        return $q->orderBy('booking_id, name');
                    },
                    'bookings',
                    'bookings.report',
                    'bookings.case',
                    'bookings.case.owner'=>function($q){
                        return $q->select(['id', 'name'=>'nickname']);
                    },
                    'bookings.case.people'=>function($q){
                        return $q->select(['id', 'name', 'email', 'gender', 'country_code', 'byear']);
                    },
                    'bookings.people'=>function($q){
                        return $q->select(['id', 'name', 'fname', 'lname', 'gender', 'country_code', 'bday', 'bmonth', 'byear', 'email', 'phone']);
                    },
                    'bookings.people.country'=>function($q){
                        return $q->select(['code', 'name'=>'name_'.Yii::$app->language]);
                    },
                ])
                ->where(['id'=>$tour_id])
                ->asArray()
                ->one();

            if (!$theTour) {
                throw new HttpException(404, 'Tour not found.');
            }

            $theTourOld = Tour::find()
                ->where(['ct_id'=>$theTour['id'], 'status'=>'on'])
                ->asArray()
                ->one();

            if (!$theTourOld) {
                throw new HttpException(404, 'Tour not found.');
            }
        }

        // Tim xem khach o HS nao -> phu trach HS
        $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        $kaseOwnerIdList = Yii::$app->db->createCommand($sql, [':user_id'=>$id])->queryColumn();
        // TODO Tim xem khach o tour nao -> phu trach tour
        // $sql = 'SELECT k.owner_id FROM at_case_user ku, at_cases k WHERE k.id=ku.case_id AND user_id=:user_id';
        // $tourOwnerIdList = Yii::$app->db->createCommand($sql, [':user_id'=>$id])->queryColumn();
        // \fCore::expose($tourOwnerIdList);
        // exit;
        // Huan, Fleur & CSKH only
        $allowList = array_merge($kaseOwnerIdList, [1, 695, 118, 4432, 30554, 34355, 1351, 18598, 26435, 29123,29296,30554, 33415, 34595, 39748, 8162, 34596, 35071, 35887, 40218]);
        // if (!in_array(USER_ID, $allowList)) {
        //     throw new HttpException(403, 'Access denied.');
        // }

        $theMetas = \common\models\Meta::find()
            ->select(['name', 'value'])
            ->where(['rtype'=>'user', 'rid'=>$thePerson->id])
            // ->indexBy('name')
            ->orderBy('name')
            ->asArray()
            ->all();
        // \fCore::expose($theMetas); exit;

        $theForm = new \app\models\PersonEditForm;

        $attribList = [
            'name', 'nickname', 'fname', 'lname', 'gender', 'bday', 'bmonth', 'byear', 'country_code',
            'language',
            'info',
        ];
        $metaList = [
            'marital', 'pob', 'pob_country',
            'profession', 'job_title', 'employer',

            /*'tel', 'tel2', 'email', 'email2', 'email3', 'email4', */ 'website', 'website2',
            'addr_street', 'addr_city', 'addr_state', 'addr_country', 'addr_postal',

            'traveler_profile', 'traveler_profile_assoc_names',
            'travel_preferences', 'diet', 'allergies', 'diet_note', 'health_condition', 'health_note',
            'transportation', 'transportation_note', 'future_travel_wishlist',
            'likes', 'dislikes',

            'rel_with_amica', 'customer_ranking', 'ambassaddor_potentiality',
            'newsletter_optin', 'active_social_networks',
        ];

        foreach ($attribList as $name) {
            $theForm->$name = $thePerson->$name ?? '';
            foreach (['bday', 'bmonth', 'byear'] as $name) {
                if ($theForm->$name == 0) {
                    $theForm->$name = '';
                }
            }
        }

        if (USER_ID == 1){
            // \fCore::expose($theMetas); exit;
        }

        // Tinh rieng email, tel
        foreach ($theMetas as $meta) {
            if ($meta['name'] == 'email') {
                if ($theForm->email == '') {
                    $theForm->email = $meta['value'];
                } elseif ($theForm->email2 == '') {
                    $theForm->email2 = $meta['value'];
                } elseif ($theForm->email3 == '') {
                    $theForm->email3 = $meta['value'];
                } elseif ($theForm->email4 == '') {
                    $theForm->email4 = $meta['value'];
                }
            }
            if ($meta['name'] == 'tel' || $meta['name'] == 'phone' || $meta['name'] == 'mobile') {
                if ($theForm->tel == '') {
                    $theForm->tel = $meta['value'];
                } elseif ($theForm->tel2 == '') {
                    $theForm->tel2 = $meta['value'];
                }
            }
        }

        foreach ($metaList as $name) {
            foreach ($theMetas as $meta) {
                if ($meta['name'] == $name) {
                    if (strpos($meta['value'], '|') !== false) {
                        $theForm->$name = explode('|', $meta['value']);
                    } else {
                        $theForm->$name = $meta['value'];
                    }
                }
            }
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            foreach ($attribList as $name) {
                if (isset($thePerson->$name)) {
                    $thePerson->$name = $theForm->$name ?? '';
                }
                foreach (['bday', 'bmonth', 'byear'] as $name) {
                    if ($theForm->$name == '') {
                        $thePerson->$name = 0;
                    }
                }
                $thePerson->phone = $theForm->tel;
                $thePerson->email = $theForm->email;
            }

            $thePerson->save(false);

            $sql = 'DELETE FROM metas WHERE rtype="user" AND rid=:pid';
            Yii::$app->db->createCommand($sql, [':pid'=>$thePerson->id])->execute();
            foreach ($metaList as $name) {
                $theValue = $theForm->$name ?? '';
                if (is_array($theValue)) {
                    $theValue = implode('|', $theValue);
                }

                $sql = 'INSERT INTO metas (created_dt, created_by, updated_dt, updated_by, rtype, rid, name, value) VALUES (:now,:me,:now,:me,:rtype,:rid,:n,:v)';
                Yii::$app->db->createCommand($sql, [
                    ':now'=>NOW,
                    ':me'=>USER_ID,                    
                    ':rtype'=>'user',
                    ':rid'=>$thePerson->id,
                    ':n'=>$name,
                    ':v'=>$theValue
                    ])->execute();
            }

            $newMetas = [];
            if ($theForm->email != '') {
                $newMetas[] = [NOW, USER_ID, NOW, USER_ID, 'user', $thePerson['id'], 'email', $theForm->email];
            }
            if ($theForm->email2 != '') {
                $newMetas[] = [NOW, USER_ID, NOW, USER_ID, 'user', $thePerson['id'], 'email', $theForm->email2];
            }
            if ($theForm->email3 != '') {
                $newMetas[] = [NOW, USER_ID, NOW, USER_ID, 'user', $thePerson['id'], 'email', $theForm->email3];
            }
            if ($theForm->email4 != '') {
                $newMetas[] = [NOW, USER_ID, NOW, USER_ID, 'user', $thePerson['id'], 'email', $theForm->email3];
            }
            if ($theForm->tel != '') {
                $newMetas[] = [NOW, USER_ID, NOW, USER_ID, 'user', $thePerson['id'], 'tel', $theForm->tel];
            }
            if ($theForm->tel2 != '') {
                $newMetas[] = [NOW, USER_ID, NOW, USER_ID, 'user', $thePerson['id'], 'tel', $theForm->tel2];
            }

            if (!empty($newMetas)) {
                Yii::$app->db->createCommand()
                    ->batchInsert('metas', ['created_dt', 'created_by', 'updated_dt', 'updated_by', 'rtype', 'rid', 'name', 'value'], $newMetas)
                    ->execute();
            }

            if ($booking_id != 0) {
                return $this->redirect('@web/bookings/r/'.$booking_id);
            }

            return $this->redirect('/persons/r/'.$thePerson['id']);
        }

        return $this->render('person_u', [
            'thePerson'=>$thePerson,
            'theForm'=>$theForm,
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
        ]);
    }

    // View all uploaded files, including: user-uploaded and manager-uploaded files
    public function actionUpload($id)
    {
        $theUser = Person::find()
            ->where(['id'=>$id])
            ->with([
                'profileDriver',
                'profileMember',
                'profileTourguide',
                ])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404, 'User not found');         
        }

        $theFiles = [];
        $folder1 = Yii::getAlias('@webroot').'/upload/users/'.substr($theUser['created_at'], 0, 7).'/'.$theUser['id'];
        $folder2 = Yii::getAlias('@webroot').'/upload/user-files/'.$theUser['id'];
        if (file_exists($folder1)) {
            $theFiles = \yii\helpers\FileHelper::findFiles($folder1);
        }
        if (file_exists($folder2)) {
            $theFiles = array_merge($theFiles, \yii\helpers\FileHelper::findFiles($folder2));
        }

        return $this->render('users_upload', [
            'theUser'=>$theUser,
            'theFiles'=>$theFiles,
        ]);
    }
}
