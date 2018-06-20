<?

namespace app\controllers\acp;

use common\models\Country;
use common\models\Group;
use common\models\Message;
use common\models\Person;
use common\models\ProfileMember;
use common\models\User2;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class UserController extends \app\controllers\MyController
{
    public function actionIndex($status = 'on', $name = '', $email = '')
    {
        $query = User2::find()
            ->where(['status'=>$status]);
            // ->where(['account_id'=>ACCOUNT_ID, 'status'=>$status]);

        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }

        if ($email != '') {
            $query->andWhere(['like', 'email', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>15,
        ]);

        $theUsers = $query
            ->orderBy('lname, fname, status')
            ->with([
                'updatedBy'=>function($q) {
                    $q->select(['id', 'name']);
                },
            ])
            ->orderBy('fname, lname')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('user_index', [
            'theUsers'=>$theUsers,
            'pagination'=>$pagination,
            'status'=>$status,
            'name'=>$name,
            'email'=>$email,
        ]);
    }

    public function actionC()
    {
        $theUser = new User2;
        $theUser->scenario = 'user/c';
        $theUser->country_code = 'vn';
        $theUser->timezone = 'Asia/Ho_Chi_Minh';
        $theUser->image = 'https://my.amicatravel.com/assets/img/user_256x256.png';

        if ($theUser->load(Yii::$app->request->post()) && $theUser->validate()) {
            // $theUser->account_id = ACCOUNT_ID;
            $theUser->created_at = NOW;
            $theUser->created_by = USER_ID;
            $theUser->updated_at = NOW;
            $theUser->updated_by = USER_ID;
            $theUser->login = $theUser->email;
            $theUser->status = 'on';
            $theUser->is_member = 'yes';

            if ($theUser->raw_password != '') {
                $theUser->password = Yii::$app->security->generatePasswordHash($theUser->raw_password);
                $theUser->uid = Yii::$app->security->generateRandomString();

                // $args = [
                //     ['from', 'notifications@evalpro.vn', 'eValPro'],
                //     // ['to', $theUser->email, $theUser->fname, $theUser->lname],
                //     ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                // ];

                // $this->mgIt(
                //     'Login hệ thống eValPro cho '.$theUser->email,
                //     '//acp_user_welcome',
                //     [
                //         'theUser'=>$theUser,
                //     ],
                //     $args
                // );

            }

            $fields = ['created_at', 'created_by', 'updated_at', 'updated_by', 'fname', 'lname', 'gender', 'bday', 'bmonth', 'byear', 'name', 'nickname', 'country_code', 'status', 'language', 'timezone', 'email', 'phone', 'password', 'uid', 'is_member'];

            $thePerson = new Person;
            foreach ($fields as $field) {
                $thePerson->$field = $theUser->$field;
            }
            $thePerson->save(false);

            // $theUser->roles = is_array($theUser->roles) ? implode(',', $theUser->roles) : '';
            // $theUser->val_ptypes = is_array($theUser->val_ptypes) ? implode(',', $theUser->val_ptypes) : '';
            // $theUser->val_cities = is_array($theUser->val_cities) ? implode(',', $theUser->val_cities) : '';
            $theUser->id = $thePerson->id;
            $theUser->save(false);
            $theProfile = new ProfileMember;
            $theProfile->created_dt = NOW;
            $theProfile->created_by = USER_ID;
            $theProfile->user_id = $theUser->id;
            $theProfile->nickname = $theUser->nickname;
            $theProfile->save(false);
            Yii::$app->session->setFlash('success', 'User added: '.$theUser['name']);
            return $this->redirect('@web/acp/users');
        }

        $allCountries = Country::find()
            ->select(['name_en', 'code'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('user_u', [
            'theUser'=>$theUser,
            'allCountries'=>$allCountries,
        ]);
    }


    public function actionR($id = 0)
    {
        $theUser = User2::find()
            ->where(['id'=>$id, 'account_id'=>ACCOUNT_ID])
            ->with([
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404, 'Contact not found.');
        }

        $theMessage = new Message;
        if ($theMessage->load(Yii::$app->request->post()) && $theMessage->validate()) {
            $theMessage->created_dt = NOW;
            $theMessage->created_by = USER_ID;
            $theMessage->updated_dt = NOW;
            $theMessage->updated_by = USER_ID;
            $theMessage->account_id = ACCOUNT_ID;
            $theMessage->rtype = 'user';
            $theMessage->rid = $theUser['id'];
            $theMessage->save(false);
            return $this->redirect(DIR.URI);
        }

        $theMessages = Message::find()
            ->where(['rtype'=>'contact', 'rid'=>$theUser['id']])
            ->with([
                'updatedBy'=>function($q) {
                    $q->select(['id', 'name', 'image']);
                }
            ])
            ->orderBy('updated_dt')
            ->asArray()
            ->all();

        return $this->render('user_r', [
            'theUser'=>$theUser,
            'theMessage'=>$theMessage,
            'theMessages'=>$theMessages,
        ]);
    }

    public function actionU($id = 0)
    {
        $theUser = User2::find()
            ->where(['id'=>$id, 'account_id'=>ACCOUNT_ID])
            ->one();

        if (!$theUser) {
            throw new HttpException(404, 'User not found.');
        }
        $theUser->scenario = 'user/u';

        $uploadPath = '/upload/000001-3w623dsj/users/'.substr($theUser['created_dt'], 0, 7).'/'.$theUser['id'];
        \yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

        if (!Yii::$app->request->isPost) {
            $theUser->roles = explode(',', $theUser->roles);
            $theUser->val_ptypes = explode(',', $theUser->val_ptypes);
            $theUser->val_cities = explode(',', $theUser->val_cities);
        }

        Yii::$app->session->set('ckfinder_authorized', true);
        Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@www').$uploadPath);
        Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
        Yii::$app->session->set('ckfinder_role', 'user');
        Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
        Yii::$app->session->set('ckfinder_resource_name', 'upload');

        if ($theUser->load(Yii::$app->request->post()) && $theUser->validate()) {
            if (isset($_GET['ex'])) {
                \fCore::expose($_POST); exit;
            }
            $theUser->updated_dt = NOW;
            $theUser->updated_by = USER_ID;

            $theUser->login = $theUser->email;

            if ($theUser->raw_password != '') {
                $theUser->password = Yii::$app->security->generatePasswordHash($theUser->raw_password);
                $theUser->uid = Yii::$app->security->generateRandomString();

                $args = [
                    ['from', 'notifications@evalpro.vn', 'eValPro'],
                    ['to', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];

                $this->mgIt(
                    'Login hệ thống eValPro cho '.$theUser->email,
                    '//acp_user_welcome',
                    [
                        'theUser'=>$theUser,
                    ],
                    $args
                );
            }
            $theUser->roles = is_array($theUser->roles) ? implode(',', $theUser->roles) : '';
            $theUser->val_ptypes = is_array($theUser->val_ptypes) ? implode(',', $theUser->val_ptypes) : '';
            $theUser->val_cities = is_array($theUser->val_cities) ? implode(',', $theUser->val_cities) : '';
            $theUser->save(false);
            Yii::$app->session->setFlash('success', 'User updated: '.$theUser['name']);
            return $this->redirect('@web/acp/users');
        }

        $allCountries = Country::find()
            ->select(['name_en', 'code'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('user_u', [
            'theUser'=>$theUser,
            'allCountries'=>$allCountries,
        ]);
    }

    public function actionD($id = 0)
    {
        $theUser = User2::find()
            ->where(['id'=>$id, 'account_id'=>ACCOUNT_ID])
            ->one();

        if (!$theUser) {
            throw new HttpException(404, 'User not found.');
        }

        if (isset($_POST['answer']) && $_POST['answer'] == 'delete') {
            Yii::$app->db
                ->createCommand()
                ->update('users', ['status'=>'deleted'], ['id'=>$id])
                ->execute();
            Yii::$app->session->setFlash('success', 'User has been deleted: '.$theUser['name']);
            return $this->redirect('/acp/users');
        }
        return $this->render('user_d', [
            'theUser'=>$theUser,
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
            ->where(['id'=>$id, 'status'=>'on'])
            ->one();
        if (!$theUser) {
            throw new HttpException(403, 'User not found. User must be an active member.');
        }

        Yii::$app->user->switchIdentity($theUser);
        return $this->redirect('/');
        // $pwd = Yii::$app->request->post('pwd', '');
        // if ($pwd != '' && Yii::$app->security->validatePassword($pwd, Yii::$app->user->identity->password)) {
        //     Yii::$app->user->switchIdentity($theUser);
        //     Yii::$app->db
        //         ->createCommand()
        //         ->update('at_logins', ['user_id' => $theUser['id']], [
        //             'uid'=>Yii::$app->session->get('uid'),
        //             'ua_string'=>Yii::$app->request->getUserAgent()])
        //         ->execute();
        //     return $this->redirect('@web/tours');
        // }

        return $this->render('users_loginas', [
            'theUser'=>$theUser,
        ]);
    }
}
