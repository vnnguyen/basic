<?

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Inflector;
use yii\data\Pagination;
use common\models\Inquiry;
use common\models\CaseFromInquiryForm;
use common\models\Country;
use common\models\User;
use common\models\Meta;
use common\models\Message;
use common\models\Kase;
use common\models\Search;
use common\models\Person;
use common\models\UsersUuForm;
use common\models\Campaign;
use common\models\Company;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


class InquiryController extends MyController
{
    public function actionIndex()
    {
        $getMonth = Yii::$app->request->get('month', 'all');
        $getForm = Yii::$app->request->get('form', 'all');
        $getCountry = Yii::$app->request->get('country', 'all');
        $getCaseId = Yii::$app->request->get('case_id', 'all');
        $getName = Yii::$app->request->get('name', '');

        $query = Inquiry::find();

        if ($getMonth != 'all') {
            $query->andWhere('SUBSTRING(created_at, 1, 7)=:month', [':month'=>$getMonth]);
        }
        if ($getForm != 'all') {
            $query->andWhere(['form_name'=>$getForm]);
        }
        if ($getCaseId == 'yes') {
            $query->andWhere(['!=', 'case_id', 0]);
        } elseif ($getCaseId == 'no') {
            $query->andWhere(['=', 'case_id', 0]);
        }
        if ($getName != '') {
            $query->andWhere(['like', 'name', $getName]);
        }

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $models = $query
            ->select(['id', 'name', 'email', 'ip', 'created_at', 'case_id', 'site_id', 'data', 'form_name', 'ref'])
            ->orderBy('created_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->with([
                'kase'=>function($q){
                    return $q->select(['id', 'name', 'owner_id', 'status', 'deal_status']);
                },
                'kase.owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'site'
                ])
            ->asArray()
            ->all();

        // List of months
        $monthList = Yii::$app->db->createCommand('SELECT SUBSTRING(created_at, 1, 7) AS ym FROM at_inquiries GROUP BY ym ORDER BY ym DESC ')->queryAll();
        $countryList = Yii::$app->db->createCommand('SELECT code, name_en FROM at_countries ORDER BY name_en')->queryAll();
        $formList = Yii::$app->db->createCommand('SELECT SUBSTRING_INDEX(form_name, "_", 1) AS site, form_name FROM at_inquiries GROUP BY form_name ORDER BY form_name')->queryAll();

        return $this->render('inquiries', [
            'pages'=>$pages,
            'models'=>$models,
            'getMonth'=>$getMonth,
            'monthList'=>$monthList,
            'getForm'=>$getForm,
            'formList'=>$formList,
            'getCountry'=>$getCountry,
            'countryList'=>$countryList,
            'getCaseId'=>$getCaseId,
            'getName'=>$getName,
            ]
        );
    }

    // Load latest inquiries data from remote server (LON)
    public function actionC($id = 0) {
        $maxId = $id == 0 ? Inquiry::find()->max('id') : $id;

        $handle = fopen('https://admin.amica-travel.com/inquiries/x/'.$maxId, 'r');
        $str = stream_get_contents($handle);

        $result = '';
        $arr = explode('-', trim($str, '-'));
        foreach ($arr as $chr) {
            $result .= chr($chr);
        }

        $theInquiries = @unserialize($result);

        //\fCore::expose($theInquiries); exit;

        foreach ($theInquiries as $inquiry) {
            if ($inquiry['ip'] != '117.6.3.222') {
                $theInquiry = new Inquiry;
                $theInquiry->scenario = 'inquiries/c';
                $theInquiry->setAttributes($inquiry, false);
                $theInquiry->uid = '1234567890';
                $theInquiry->save(false);
            }
            if ($id != 0) {
                // Only one in this case
                break;
            }
        }

        return $this->render('inquiries_c',
            ['inquiries'=>$theInquiries]
        );
    }


    public function actionR($id = 0)
    {
        $theInquiry = Inquiry::find()
            ->where(['id'=>$id])
            ->with([
                'site',
                'case'=>function($q) {
                    return $q->select(['id', 'name', 'owner_id', 'created_at', 'status', 'deal_status']);
                },
                'kase.owner'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ]) 
            ->one();
        $theUserForm = new UsersUuForm();
        $theForm = new CaseFromInquiryForm;
        $theCase = new Kase;
        $theCase->scenario = 'kase/c';
        $theCase->is_b2b = 'no';
        $theCase->created_at = NOW;
        $theCase->created_by = USER_ID;
        $theCase->updated_at = NOW;
        $theCase->updated_by = USER_ID;
        $theCase->status = 'open';
        $theCase->ao = NOW;
        $theCase->name = ucwords(strtolower($theInquiry['name']));

        $theCase->is_priority = 'no';
        $theCase->company_id = 0;
        $theCase->owner_id = USER_ID;
        $theCase->language = 'fr';
        $theCase->how_found = 'new/nref/web';
        $theCase->how_contacted = 'web';


        if (!$theInquiry) {
            throw new HttpException(404, 'Inquiry not found');
        }
        // $theInquiry['email'] = 'abc@gmail.com';
        // Error in this ; 11087
        if ($id == 9507) {
            $inquiryData = [
                'fullName'=>'Gustin Élisabeth',
                'fname'=>'Gustin',
                'lname'=>'Élisabeth',
                'country'=>'fr',
                'email'=>'elisabeth.gustin123@orange.fr',
            ];
        } else {
            $inquiryData = @unserialize($theInquiry['data']);
        }

        // Find user cases if not empty
        $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.gender, u.country_code FROM persons u, metas m WHERE m.rtype="user" AND m.rid=u.id AND m.name="email" AND m.value=:email GROUP BY u.id ORDER BY u.fname, u.lname';
        $inquiryUsers = Yii::$app->db->createCommand($sql, [':email'=>$theInquiry['email']])->queryAll();

        $inquiryUserCases = [];
        $theCases = [];
        if (!empty($inquiryUsers)) {
            $userIdList = [];
            foreach ($inquiryUsers as $iu) {
                $userIdList[] = $iu['id'];
            }
            $inquiryUserCases = Kase::findBySql('SELECT k.id, k.name, k.status, k.created_at, k.owner_id FROM at_cases k, at_case_user ku WHERE k.id=ku.case_id AND ku.user_id IN ('.implode(',', $userIdList).') ORDER BY created_at DESC LIMIT 10')
                ->with([
                    'owner'=>function($q) {
                        return $q->select(['id', 'name']);
                    }
                ])
                ->asArray()
                ->all();
            $sql2 = 'SELECT k.id, k.name, k.status, k.deal_status, k.created_at, k.owner_id FROM at_cases k, at_bookings b, at_booking_user bu WHERE b.id=bu.booking_id AND k.id=b.case_id AND bu.user_id IN ('.implode(',', $userIdList).') ORDER BY status, k.id DESC';
            $theCases2 = Kase::findBySql($sql2)
                ->with([
                    'owner'=>function($q) {
                        return $q->select(['id', 'name']);
                    }
                ])
                ->asArray()
                ->all();
            $caseIdList = [];
            foreach ($inquiryUserCases as $case) {
                $caseIdList[] = $case['id'];
                $theCases[] = $case;
            }
            foreach ($theCases2 as $case) {
                if (!in_array($case['id'], $caseIdList)) {
                    $theCases[] = $case;
                }
            }
            $inquiryUserCases = $theCases;
            if ($inquiryUserCases != null) {
                $theForm->case_id = $inquiryUserCases[0]['id'];
            } else {
                $theForm->case_id = 0;
            }
        }
        $allCountries = Country::find()->select([
            'code',
            'name'=>'CONCAT(name_en, " (", name_vi, ")")',
            'name_en',
            'dial_code'
        ])->asArray()->all();
        if (isset($inquiryData['email'])) {
            $theUserForm->email = $inquiryData['email'] != '' ? $inquiryData['email'] : $theInquiry['email'];
        }
        if (isset($inquiryData['country'])) {
            $theUserForm->country = ($inquiryData['country'] != '') ? $inquiryData['country']: 'fr';
        }

        if (isset($inquiryData['phone'])) {
            $code = '';
            foreach ($allCountries as $k => $country) {
                if ($country['code'] == $theUserForm->country) {
                    $code = '+'.$country['dial_code'];
                }
            }
            $theUserForm->tel = $code.' '. $inquiryData['phone'];
        }
        if (isset($inquiryData['fname'])) {
            $theUserForm->fname = $inquiryData['fname'];
        }
        if (isset($inquiryData['lname'])) {
            $theUserForm->lname = $inquiryData['lname'];
        }
        if (isset($theInquiry['name'])) {
            $theUserForm->name = $theInquiry['name'];
        }
        //submit ajax 
        if (Yii::$app->request->isAjax) {
            if ($theForm->load(Yii::$app->request->post()) && $theForm->user_id > 0) {
                $theUsers = Person::findAll(explode(',',$theForm->user_id));
                if ($theUsers && isset($_POST['current_email']) && $_POST['current_email'] != '') {
                    $sql = 'SELECT u.id FROM persons u, metas m WHERE m.rtype="user" AND m.rid=u.id AND m.name="email" AND m.value=:email GROUP BY u.id ORDER BY u.fname, u.lname';
                    $listUsers = Yii::$app->db->createCommand($sql, [':email'=>$_POST['current_email']])->queryAll();
                    if ($listUsers) {
                        $user_ids = [];
                        foreach ($listUsers as $key => $u) {
                            $user_ids[] = $u['id'];
                        }
                        foreach ($theUsers as $user) {
                            if (!in_array($user->id, $user_ids)) {
                                Yii::$app->db->createCommand ()->insert ( 'metas', [//[ 'user', $theUser ['id'], '', 'email', $values]
                                    'rtype' => 'user',
                                    'rid' => $user->id,
                                    'format' => '',
                                    'name' => 'email',
                                    'value' => $_POST['current_email']
                                ])->execute ();
                                Yii::$app->db->createCommand ()->update ( 'at_search', [
                                    'search' => str_replace ( '-', '', \fURL::makeFriendly ( $user->name . ' ' . $_POST['current_email'] . ' ' . $user->phone, '-' ) ),
                                    'found' => trim ( $user->name . ' ' . $_POST['current_email'] . ' ' . $user->phone )
                                ], 'rid = ' . $user->id)->execute ();
                            }
                        }
                    } else {
                        foreach ($theUsers as $user) {
                            Yii::$app->db->createCommand ()->insert ( 'metas', [//[ 'user', $theUser ['id'], '', 'email', $values]
                                'rtype' => 'user',
                                'rid' => $user->id,
                                'format' => '',
                                'name' => 'email',
                                'value' => $_POST['current_email']
                            ])->execute ();
                            Yii::$app->db->createCommand ()->update ( 'at_search', [
                                    'search' => str_replace ( '-', '', \fURL::makeFriendly ( $user->name . ' ' . $_POST['current_email'] . ' ' . $user->phone, '-' ) ),
                                    'found' => trim ( $user->name . ' ' . $_POST['current_email'] . ' ' . $user->phone )
                                ], 'rid = ' . $user->id)->execute ();
                        }
                    }
                    if (count($theUsers) == 1) {
                        return json_encode(['theUser' => ArrayHelper::toArray($theUsers[0])]);
                    }
                    return json_encode(['theUsers' => ArrayHelper::toArray($theUsers)]);
                } else {
                    return json_encode(['error' => "Users not found"]);
                }
            }
            if ($theUserForm->load(Yii::$app->request->post()) && $theUserForm->validate()) {
                $theUser = new Person;
                $theUser->scenario = 'create';
                $theUser->created_at = NOW;
                $theUser->created_by = USER_ID;
                $theUser->updated_at = NOW;
                $theUser->updated_by = USER_ID;
                $theUser->status = 'on';
                $theUser->country_code = ($theUserForm->country != '')? $theUserForm->country : isset($inquiryData['country']) ? $inquiryData['country'] : 'fr';
                $theUser->fname = $theUserForm->fname;
                $theUser->lname = $theUserForm->lname;
                $theUser->name = (isset($theUserForm->name) && $theUserForm->name != '') ? $theUserForm->name : $theUserForm->fname.' '.$theUserForm->lname;
                $theUser->gender = ($theUserForm->gender != '') ? $theUserForm->gender : isset($inquiryData['prefix']) && in_array($inquiryData['prefix'], ['', 'M.', 'M', 'Mr.', 'Mr']) ? 'male' : 'female';
                $theUser->bday = $theUserForm->bday;
                $theUser->bmonth = $theUserForm->bmonth;
                $theUser->byear = $theUserForm->byear;

                if (substr($theInquiry['form_name'], 0, 2) == 'en') {
                    $theUser->language = 'en';
                    $theUser->timezone = 'UTC';
                } else {
                    $theUser->language = 'fr';
                    $theUser->timezone = 'Europe/Paris';
                }
                if (!$theUser->save( false )) {
                    return json_encode(['error' => $theUser->errors]);
                } else {
                    Yii::$app->db->createCommand ()->insert ( 'at_search', [ 
                            'rtype' => 'user',
                            'rid' => $theUser->id,
                            'search' => str_replace ( '-', '', \fURL::makeFriendly ( $theUser->name . ' ' . $theUser->email . ' ' . $theUser->phone, '-' ) ),
                            'found' => trim ( $theUser->name . ' ' . $theUser->email . ' ' . $theUser->phone ) 
                    ] )->execute ();
                    $newMetas = [];

                    if ($theUserForm->note != '') {
                        $newMetas [] = [ 'user',$theUser ['id'],'','Note',$theUserForm->note ];
                    }

                    if ($theUserForm->tags != '') {
                        $newMetas [] = [ 'user',$theUser ['id'],'','Tags',$theUserForm->tags ];
                    }

                    if ($theUserForm->profession != '') {
                        $newMetas [] = [ 'user',$theUser ['id'],'','profession',$theUserForm->profession ];
                    }

                    if ($theUserForm->pob != '') {
                        $newMetas [] = [ 'user',$theUser ['id'],'','pob',$theUserForm->pob ];
                    }
                    if ($theUserForm->marital_status != '') {
                        $newMetas [] = [ 'user', $theUser ['id'], '', 'marital_status', $theUserForm->marital_status ];
                    }
                    if ($_POST ['phone_format'] != null) {
                        foreach ( $_POST ['phone_format'] as $key => $value ) {
                            if ($_POST ['phone'] [$key] != null) {

                                $newMetas [] = [ 'user', $theUser ['id'], $value, 'tel', $_POST['dial_code_input'][$key] ."&nbsp;". $_POST ['phone'][$key] ];
                            }
                        }
                    }

                    foreach ( $_POST ['relationship_family'] as $key_rela => $value_rela ) {
                        if ($value_rela != null && $_POST ['person_family'] [$key_rela] != null) {
                            $u_Id = trim ( $_POST ['person_family'] [$key_rela], "https://my.amicatravel.com/users/r/" );
                            $newMetas [] = [ 'user', $theUser ['id'], $value_rela, 'uid_rela', $u_Id ];
                        }
                    }

                    foreach ( $_POST ['id_website'] as $key_w => $value_w ) {
                        if ($_POST ['website'] [$key_w] != null) {
                            $newMetas [] = [ 'user', $theUser ['id'], $value_w, 'website', $_POST ['website'] [$key_w] ];
                        }
                    }

                    if ($_POST ['address'] != null && $_POST ['city'] != null && $_POST ['nation'] != null) {
                        foreach ( $_POST ['address'] as $key => $val ) {
                            if ($val != null && $_POST ['city'] [$key] != null && $_POST ['nation'] [$key] != null)
                                $newMetas [] = [ 'user', $theUser ['id'],'', 'address', $val . ' /n ' . $_POST ['city'] [$key] . ' /n ' . $_POST ['nation'] [$key]];
                        }
                    }

                    if ($_POST ['email'] != null) {
                        foreach ( $_POST ['email'] as $keys => $values ) {
                            if ($values != null) {
                                $newMetas [] = [ 'user', $theUser ['id'], '', 'email', $values];
                            }
                        }
                    }

                    if (! empty ( $newMetas )) {
                        $list = Yii::$app->db->createCommand ()->batchInsert ( 'metas', ['rtype', 'rid', 'format', 'name', 'value'], $newMetas )->execute ();
                    }
                    return json_encode(['theUser' => ArrayHelper::toArray($theUser)]);
                }
            } else {
                return json_encode(['error' => $theUserForm->errors]);
            }
        }
        if ($theInquiry['case_id'] == 0) {

            if (!isset($inquiryData['lname'])) {
                $inquiryData['fname'] = isset($inquiryData['name']) ? $inquiryData['name'] : $inquiryData['fullName'];
                $inquiryData['lname'] = '';
            }

            if (isset($inquiryData['country']) && in_array($inquiryData['country'], ['vn', 'la', 'kh', 'cn'])) {
                $userName = ucwords(strtolower($inquiryData['fname'].' '.$inquiryData['lname']));
            } else {
                $userName = ucwords(strtolower($inquiryData['lname'].' '.$inquiryData['fname']));
            }
            // var_dump($inquiryData);die;
            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                if ($theForm->case_id != 0) {
                    $theCase = Kase::find()
                        ->Where(['id' => $theForm->case_id])
                        ->with([
                            'owner'=>function($q) {
                                return $q->select(['id', 'name']);
                            },
                            'cperson'=>function($q) {
                                return $q->select(['id', 'name']);
                            }
                        ])
                        ->one();
                    if (!$theCase) {
                        throw new HttpException(403, "Case not found");
                    }
                }
                if ($theForm->case_id == 0 && $theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
                    $theCase->is_priority = ($theCase->is_priority != '') ? $theCase->is_priority : 'no';
                    $theCase->company_id = ($theCase->company_id != '')? $theCase->company_id : 0;
                    $theCase->owner_id = ($theCase->owner_id != '')? $theCase->owner_id : USER_ID;
                    $theCase->language = ($theCase->language != '')? $theCase->language : 'fr';
                    $theCase->how_found = ($theCase->how_found != '') ? $theCase->how_found : 'new/nref/web';
                    $theCase->how_contacted = ($theCase->how_contacted != '') ? $theCase->how_contacted : 'web';

                    if (!$theCase->save(false)) {
                        throw new HttpException(403, "Case not saved");
                    }
                }
                if (!count($theCase->errors) > 0) {
                    //update theInquiry
                    $theInquiry->scenario = 'inquiries/u';
                    $theInquiry->case_id = $theCase->id;
                    if (!$theInquiry->save(false)) {
                        throw new HttpException(403, "Inquiry not saved");
                    }
                    if ($theCase->id) {
                        $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                        Yii::$app->db->createCommand($sql, [':email'=>$theInquiry['email'], ':action'=>'add', ':case_id'=> $theCase->id])->execute();
                    }
                    // if (isset($_POST['user_id']) && $_POST['user_id'] != 0) {
                    //     $theUsers = Person::findAll(explode(',', $_POST['user_id']));
                    //     if ($theUsers != null) {
                    //         foreach ($theUsers as $user) {
                    //             $exist_user = false;
                    //             foreach ($theCase->cperson as $person) {
                    //                 if ($person->id == $user->id) {
                    //                     $exist_user = true;
                    //                 }
                    //             }
                    //             if (!$exist_user) {
                    //                 Yii::$app->db->createCommand()->insert('at_case_user', [
                    //                         'case_id' => $theCase['id'],
                    //                         'user_id' => $user['id'],
                    //                         'role'=> 'contact',
                    //                     ])->execute();
                    //             }
                    //         }
                    //     }
                    // }
                    return $this->redirect(Url::to(['cases/r', 'id' => $theCase['id']]));
                }
            }
        } else {
            $theForm = new CaseFromInquiryForm;
            $theForm->case_id = 'remove';
            $theForm->user_id = 0;
            if ($theForm->load(Yii::$app->request->post())) {
                if ($theForm->case_id == 'remove') {
                    $theForm->case_id = 0;
                }
                if ($theForm->validate()) {
                    if ($theForm->case_id == 0 && $theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
                        $theCase->is_priority = ($theCase->is_priority != '') ? $theCase->is_priority : 'no';
                        $theCase->company_id = ($theCase->company_id != '')? $theCase->company_id : 0;
                        $theCase->owner_id = ($theCase->owner_id != '')? $theCase->owner_id : USER_ID;
                        $theCase->language = ($theCase->language != '')? $theCase->language : 'fr';
                        $theCase->how_found = ($theCase->how_found != '') ? $theCase->how_found : 'new/nref/web';
                        $theCase->how_contacted = ($theCase->how_contacted != '') ? $theCase->how_contacted : 'web';

                        if (!$theCase->save(false)) {
                            throw new HttpException(403, "Case not saved");
                        }
                    }
                    if (!count($theCase->errors) > 0) {
                        //update theInquiry
                        $theInquiry->scenario = 'inquiries/u';
                        $theInquiry->case_id = $theCase->id;
                        if (!$theInquiry->save(false)) {
                            throw new HttpException(403, "Inquiry not saved");
                        }
                        if ($theCase->id > 0) {
                            $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                            Yii::$app->db->createCommand($sql, [':email'=>$theInquiry['email'], ':action'=>'add', ':case_id'=> $theCase->id])->execute();
                            return $this->redirect(Url::to(['cases/r', 'id' => $theCase['id']]));
                        }
                        return $this->redirect(Url::current());
                    }
                }
            }
        }
        $cofrList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes', 'id'=>[13, 5246, 767]])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $ownerList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $campaignList = Campaign::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();
        $companyList = Company::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('inquiries_r', [
            'theForm'=>isset($theForm) ? $theForm : null,
            'inquiryData'=>$inquiryData,
            'inquiryUsers'=>$inquiryUsers,
            'inquiryUserCases'=>$inquiryUserCases,
            'theInquiry'=>isset($theInquiry) ? $theInquiry : null,
            'theUserForm' => $theUserForm,
            'ownerList'=>$ownerList,
            'cofrList'=>$cofrList,
            'companyList'=>$companyList,
            'allCountries'=>$allCountries,
            'campaignList'=>$campaignList,
            'theCase' => $theCase,
        ]);
    }
    public function actionSearch_case_name($q,$page)
    {
            $data_user = $query = Kase::find()->select(['id', 'trim(name) as name'])
                        ->join('INNER JOIN', 'at_case_user cu', 'at_cases.id = cu.case_id')

                        ->andWhere(['LIKE', 'name', $q]);
            $resultCount = 100;
            $offset = ($page - 1) * $resultCount;
            $data_user = $data_user->offset($offset)->limit($resultCount)->asArray()->all();
            $count = count($query->all());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;
            $results = [
              "items" => $data_user,
              'total_count' => $count
            ];
            echo json_encode($results);
    }
    public function actionSearch_user_name($q,$page)
    {
            $data_user = $query = Person::find()->select(['persons.id', 'trim(name) as name', 'm.v'])
                        ->join('INNER JOIN', 'at_case_user cu', 'persons.id = cu.user_id')
                        ->join('INNER JOIN', 'at_meta m', 'm.id=cu.user_id')
                        ->andWhere('m.rtype = "user" AND m.k="email"')
                        ->andWhere(['LIKE', 'name', $q]);
            $resultCount = 100;
            $offset = ($page - 1) * $resultCount;
            $data_user = $data_user->offset($offset)->limit($resultCount)->orderBy('name')->asArray()->all();
            $count = count($query->all());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;
            $results = [
              "items" => $data_user,
              'total_count' => $count
            ];
            echo json_encode($results);
    }
    // Move inquiry to another case
    public function actionU($id = 0)
    {
        $theInquiry = Inquiry::find()
            ->where(['id'=>$id])
            ->one();

        if (!in_array(Yii::$app->user->id, [1, 4432])) {
            throw new HttpException(403, 'Web inquiries cannot be edited.');
        }

        if (!$theInquiry) {
            throw new HttpException(404, 'Web inquiry not found.');
        }

        $theInquiry->scenario = 'inquiries/u';
        if ($theInquiry->load(Yii::$app->request->post()) && $theInquiry->validate()) {
            $theInquiry->save(false);
            return $this->redirect('@web/inquiries/r/'.$theInquiry['id']);
        }

        return $this->render('inquiries_u', [
            'theInquiry'=>$theInquiry,
        ]);
    }

    public function actionD($id = 0)
    {
        $theInquiry = Inquiry::find()
            ->where(['id'=>$id])
            ->with(['site', 'kase'])
            ->one();

        if (!$theInquiry) {
            throw new HttpException(404, 'Inquiry not found');
        }

        if ($theInquiry['kase']['id'] != 0) {
            throw new HttpException(403, 'This has been linked to a case. You need to unlink it first.');
        }

        if (Yii::$app->request->isPost && in_array(Yii::$app->user->id, [1, 4432])) {
            $theInquiry->delete();
            return $this->redirect('@web/manager/inquiries');
        }
        
        return $this->render('inquiries_d', [
            'theInquiry'=>$theInquiry,
        ]);
    }
}
