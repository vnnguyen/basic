<?

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\base\Model;
use common\models\User2;
use common\models\Search;
use common\models\Country;
use common\models\Meta;
use common\models\Meta2;
use common\models\File;
use common\models\Note;
use common\models\ProfileMember;
use common\models\UsersUuForm;

class UserController extends MyController
{
    public function actionIndex()
    {
        if (!in_array(Yii::$app->user->id, [1,2,3,4,118,695,4432])) {
            return $this->redirect('@web/kb/lists/members');
        }

            return $this->redirect('@web/persons');
        $getFname = Yii::$app->request->get('fname', '');
        $getLname = Yii::$app->request->get('lname', '');
        $getCountry = Yii::$app->request->get('country', 'all');
        $getGender = Yii::$app->request->get('gender', 'all');
        $getEmail = Yii::$app->request->get('email', '');
        $getGroup = Yii::$app->request->get('group', 'all');

        $query = User2::find();

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
        $theUsers = $query
            ->with(['roles', 'cases', 'bookings', 'bookings.product'])
            ->orderBy('lname, fname')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('users', [
            'pages'=>$pages,
            'theUsers'=>$theUsers,
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

    public function actionC($email = '')
    {
        return $this->redirect('/persons/c?email='.$email);
        exit;

        $allCountries = Country::find()->select(['code', 'name_en'])->orderBy('name_en')->asArray()->all();
        $theUser = new User2;
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
        $theUser = User2::find()
            ->where(['id'=>$id])
            ->with([
                'metas'=>function($query) {
                    $query->andWhere(['rtype'=>'user'])->orderBy('k, id');
                },
                'country',
                'roles',
                'cases',
                'cases.owner'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings',
                'bookings.product',
                'bookings.product.tour',
                'refCases',
                'refCases.owner'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'profileMember',
                'profileTourguide',
                'profileDriver',
                'updatedBy',
            ])
            ->asArray()
            ->one();

        if (!$theUser) {
            return $this->redirect('/persons/r/'.$id);
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
            ->createCommand('SELECT u.name, u.id FROM persons u, hits h WHERE h.user_id=u.id AND h.uri=:uri GROUP BY u.id ORDER BY u.lname, u.fname', [':uri'=>'/users/r/'.$id])
            ->queryAll();

        $userMails = [];
        if ($theUser['email'] != '') {
            $sql = 'select id, subject, sent_dt, case_id from at_mails where locate(:email, `from`)!=0 or locate(:email, `to`)!=0 order by sent_dt desc limit 20';
            $userMails = Yii::$app->db->createCommand($sql, [':email'=>$theUser['email']])->queryAll();
        }
                
        return $this->render('users_r', [
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

        $theUser = User2::find()
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
        $theUser = User2::findOne($id);

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

    // Test
    public function actionU($id = 0, $booking_id=0)
    {
        // Huan, Fleur & CSKH only
        if (!in_array(USER_ID, [1, 695, 118, 4432, 30554, 34355, 1351, 18598, 26435, 29123,29296,30554, 33415, 34595, 39748, 8162, 34596, 35071, 35887])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theUser = User2::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theUser) {
            return $this->redirect('/persons/u/'.$id);
            throw new HttpException(404, 'User not found');
        }
        if ($theUser->is_member != 'no' && USER_ID != 1) {
            throw new HttpException(403, 'User is member. Access denied.');
        }

        $theUser->scenario = 'user/u';

        $theMetas = Meta::find()
            ->where(['rtype'=>'user', 'rid'=>$theUser['id']])
            ->orderBy('id')
            ->asArray()
            ->all();

        $theForm = new UsersUuForm;

        $theForm->setAttributes($theUser->getAttributes(), false);

        // User tags
        $sql = 'SELECT t.name FROM at_terms t, at_term_rel r WHERE t.taxonomy_id=2 AND r.term_id=t.id AND rtype="user" AND rid=:id';
        $tags = Yii::$app->db->createCommand($sql, [':id'=>$theUser['id']])->queryAll();
        $tagList = [];
        foreach ($tags as $tag) {
            $tagList[] = $tag['name'];
        }
        $theForm->tags = implode(', ', $tagList);


        foreach ($theMetas as $meta) {
            if ($meta['k'] == 'email') {
                if ($theForm->email1 == '') {
                    $theForm->email1 = $meta['v'];
                } elseif ($theForm->email2 == '') {
                    $theForm->email2 = $meta['v'];
                } elseif ($theForm->email3 == '') {
                    $theForm->email3 = $meta['v'];
                }
            }
            if ($meta['k'] == 'tel' || $meta['k'] == 'phone' || $meta['k'] == 'mobile') {
                if ($theForm->phone1 == '') {
                    $theForm->phone1 = $meta['v'];
                } elseif ($theForm->phone2 == '') {
                    $theForm->phone2 = $meta['v'];
                }
            }
            if ($meta['k'] == 'pob') {
                $theForm->pob = $meta['v'];
            }
            if ($meta['k'] == 'address') {
                $theForm->address = $meta['v'];
            }
            if ($meta['k'] == 'profession') {
                $theForm->profession = $meta['v'];
            }
            if ($meta['k'] == 'website') {
                $theForm->website = $meta['v'];
            }
        }

        $theForm->note = $theUser['info'];

        $uploadPath = '/upload/users/'.substr($theUser['created_at'], 0, 7).'/'.$theUser['id'];
        \yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

        Yii::$app->session->set('ckfinder_authorized', true);
        Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@web').$uploadPath);
        Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
        Yii::$app->session->set('ckfinder_role', 'user');
        Yii::$app->session->set('ckfinder_thumbs_dir', $uploadPath);
        Yii::$app->session->set('ckfinder_resource_name', 'upload');

        if ($theForm->load(Yii::$app->request->post()) && $theUser->load(Yii::$app->request->post()) && $theForm->validate()) {
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

            $theUser->info = $theForm->note;

            $theUser->save(false);

            $sql = 'DELETE FROM at_meta WHERE rtype="user" AND rid=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theUser['id']])->execute();

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
            //if (Model::loadMultiple($theMetas, Yii::$app->request->post()) && Model::validateMultiple($theMetas)) {
            //foreach ($settings as $setting) {
               // $setting->save(false);
            //}

            // Tags: 050921 Huan PA Thy P.Nhung
            if (in_array(USER_ID, [1,695,14671,18598])) {
                \app\helpers\OtaxonomyHelper::updateTerms(2, 'user', $theUser['id'], $theForm['tags']);
            }

            if ($booking_id != 0) {
                return $this->redirect('@web/bookings/r/'.$booking_id);
            }
            return $this->redirect('@web/users/r/'.$theUser['id']);
        }

        $allCountries = Country::find()
            ->select('code, name_en')
            ->orderBy('name_en')
            ->asArray()
            ->all();
        return $this->render('users_u', [
            'theUser'=>$theUser,
            'allCountries'=>$allCountries,
            'theMetas'=>$theMetas,
            'theForm'=>$theForm,
        ]);
    }

    // View all uploaded files, including: user-uploaded and manager-uploaded files
    public function actionUpload($id)
    {
        $theUser = User2::find()
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
