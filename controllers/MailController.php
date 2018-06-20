<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use common\models\Mail;
use common\models\MailMapping;
use common\models\Person;
use common\models\Kase;
use common\models\CaseFromInquiryForm;
use common\models\UsersUuForm;
use common\models\Campaign;
use common\models\Company;
use common\models\Country;
use yii\helpers\Url;
use common\models\Meta;


class MailController extends MyController
{
    // Read a chain of mail from ID
    public function actionChain($id = 0, $msgid = '')
    {
        $sql = 'select id, message_id, in_reply_to, sent_dt_text, `from`, `to`, subject, body from at_mails where id=:id LIMIT 1';
        $sql2 = 'select id, message_id, in_reply_to, sent_dt_text, `from`, `to`, subject, body from at_mails where in_reply_to=:mid LIMIT 1';
        $theMail = Yii::$app->db->createCommand($sql, [':id'=>$id])->queryOne();
        while ($theMail) {
            echo '<h4><a href="/mails/r/'.$theMail['id'].'">'.$theMail['subject'].'</a></h4>';
            echo '<p>FROM: '.\yii\helpers\Html::encode($theMail['from']).' - TO: '.\yii\helpers\Html::encode($theMail['to']).'</p>';
            echo '<p>MSG ID: '.\yii\helpers\Html::encode($theMail['sent_dt_text']).'</p>';
            echo $theMail['body'], '<hr>';
            $theMail = Yii::$app->db->createCommand($sql2, [':mid'=>$theMail['message_id']])->queryOne();
        }
    }


    // 160107 Auto link orphan email after Mailgun error
    public function actionAutoLink()
    {
        $mailMessages = Mail::find()
            ->select(['id', 'subject', 'from', 'to', 'cc', 'bcc'])
            ->where(['case_id'=>0])
            ->orderBy('created_at DESC')
            ->limit(100)
            ->asArray()
            ->all();

        foreach ($mailMessages as $message) {
            echo '<hr>', '<a href="/mails/r/', $message['id'], '">', $message['id'].$message['subject'] , '</a> FROM:', $message['subject'], ' TO:', $message['to'];
            $from = $message['from'];
            $to = $message['to'];
            $cc = $message['cc'];
            $bcc = $message['bcc'];

            $addresses = $this->getEmails(implode(' ', [$from, $to, $cc, $bcc]));

            $hasPax = false; // Co dia chi ngoai
            $hasIms = false; // Co dia chi ims@

            $excludeList = [
                'hn.huan@gmail.com',
                'phuonganh.mkt@gmail.com',
                'huan@huanh.com',
                'notifications@amicatravel.com',
            ];

            foreach ($addresses as $address) {
                if (!in_array($address, $excludeList) && strpos($address, '@amicatravel.com') === false && strpos($address, '@amica-travel.com') === false && strpos($address, '@amicatravel.org') === false) {
                    $hasPax = true;
                }
            }

            if (in_array('ims@amicatravel.com', $addresses)) {
                $hasIms = true;
            }

            // Get email rule
            $email = $addresses[0];
            if (strpos($addresses[0], '@amicatravel.com') !== false && strpos($addresses[0], '@amica-travel.com') !== false && strpos($addresses[0], '@amicatravel.org') !== false) {
                $email = $addresses[1];
            }

            $sql = 'SELECT * FROM at_email_mapping WHERE email=:email LIMIT 1';
            $mapping = Yii::$app->db->createCommand($sql, [':email'=>$email])->queryOne();
            if ($mapping) {             
                if ($mapping['action'] == 'drop') {
                    // Drop email immediately
                } elseif ($mapping['action'] == 'ask') {
                    // Ask every time
                    // TODO
                } else {
                    echo '<a href="/cases/r/', $mapping['case_id'], '">Case ID</a>';
                }
            }

        }
    }

    public function actionIndex($subject = '')
    {
        $query = Mail::find()
            ->where(['status'=>'on']);

        if (!in_array(USER_ID, [34718, 1, 15081, 4432])) {
            $query->andWhere(['or', ['from_email'=>Yii::$app->user->identity->email], ['to_email'=>Yii::$app->user->identity->email]]);
        }

        $getTags = Yii::$app->request->get('tags', '');
        $getAttachments = Yii::$app->request->get('attachments', 'all');
        if (!in_array($getAttachments, ['all', 'yes', 'no'])) {
            $getAttachments = 'all';
        }

        $getCaseId = Yii::$app->request->get('case_id', 'all');
        if (!in_array($getCaseId, ['all', 'yes', 'no'])) {
            $getCaseId = 'all';
        }

        $getFromEmail = Yii::$app->request->get('from_email', '');

        $getToEmail = Yii::$app->request->get('to_email', '');

        if (strlen($subject) > 2) {
            $query->andWhere(['like', 'subject', $subject]);
        }

        if ($getAttachments == 'yes') {
            $query->andWhere(['>', 'attachment_count', 0]);
        } elseif ($getAttachments == 'no') {
            $query->andWhere(['=', 'attachment_count', 0]);
        }

        if ($getCaseId == 'yes') {
            $query->andWhere(['!=', 'case_id', 0]);
        } elseif ($getCaseId == 'no') {
            $query->andWhere(['=', 'case_id', 0]);
        }

        if ($getFromEmail != '') {
            $query->andWhere(['from_email'=>$getFromEmail]);
        }

        if ($getToEmail != '') {
            $query->andWhere(['to_email'=>$getToEmail]);
        }

        if ($getTags != '') {
            $query->andWhere(['tags'=>$getTags]);
        }
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);


        $theMails = $query
            ->with([
                'case',
                'case.owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->orderBy('created_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('mail_index', [
            'pages'=>$pages,
            'theMails'=>$theMails,
            'getAttachments'=>$getAttachments,
            'getFromEmail'=>$getFromEmail,
            'getToEmail'=>$getToEmail,
            'getCaseId'=>$getCaseId,
            'getTags'=>$getTags,
            'subject'=>$subject,
            ]
        );
    }

    // Read email_data
    public function actionX($id = 0)
    {
        $sql = 'SELECT * FROM at_email_data WHERE id=:id LIMIT 1';
        $theMail = Yii::$app->db->createCommand($sql, [':id'=>$id])->queryOne();
        if (!$theMail) {
            throw new HttpException(404, 'Not found');          
        }
        return $this->render('mails_x', ['theMail'=>$theMail]);
    }
    public function actionR($id = 0)
    {
        $theMail = Mail::find()
            ->where(['id'=>$id])
            ->with([
                'case',
                'case.owner', 
            ])
            ->one();
        if (!$theMail) {
            throw new HttpException(404, 'Mail not found');
        }
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

        $theCase->is_priority = 'no';
        $theCase->company_id = 0;
        $theCase->owner_id = USER_ID;
        $theCase->language = 'fr';
        $theCase->how_contacted = 'email';
        $theCase->how_found = '';
        $theCase->name = $theMail['from'];

        $emailFields = $theMail['from'].' '.$theMail['to'].' '.$theMail['cc'].' '.$theMail['bcc'];
        $filterAddresses = $this->getEmails($emailFields);
        $emails = [];
        foreach ($filterAddresses as $email) {
            if (strpos($email, 'amicatravel') === false && strpos($email, 'amica-travel') === false) {
                $emails[] = $email;
            }
        }

        $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.gender, u.country_code, LCASE(m.value) AS email FROM persons u, metas m WHERE m.rtype="user" AND m.rid=u.id AND m.name="email" AND LCASE(m.value) IN ('.'"'.implode('", "', $emails).'"'.')';
        $cmd = Yii::$app->db->createCommand($sql);
        $theUsers = $cmd->queryAll();

        if (Yii::$app->request->isAjax) {
            if (isset($_POST['user_id']) && $_POST['user_id'] != '') {
                $userList = [];
                if (!empty($theUsers)) {
                    foreach ($theUsers as $k => $user) {
                        if (strpos($_POST['user_id'], $user['id']) !== false) {
                            $userList[] = $user;
                        }
                    }
                    if (count($userList) == 0) {
                        return json_encode(['theUser' => null]);
                    } else {
                        if (count($userList) == 1) {
                            return json_encode(['theUser' => $userList[0]]);
                        } else {
                            return json_encode(['theUsers' => $userList]);
                        }
                    }
                } else {
                    return json_encode(['theUser' => null]);
                }
            }
        }


        $userIds = [];
        $theCases = [];
        if (!empty($theUsers)) {
            foreach ($theUsers as $user) {
                $userIds[] = $user['id'];
            }
            $sql = 'SELECT k.id, k.name, k.status, k.deal_status, k.created_at, k.owner_id FROM at_cases k, at_case_user cu WHERE cu.case_id=k.id AND cu.user_id IN ('.implode(',', $userIds).') ORDER BY status, k.id DESC';
            $theCases = Kase::findBySql($sql)
                ->with([
                    'owner'=>function($q) {
                        return $q->select(['id', 'name']);
                    }
                ])
                ->asArray()
                ->all();
            $sql2 = 'SELECT k.id, k.name, k.status, k.deal_status, k.created_at, k.owner_id FROM at_cases k, at_bookings b, at_booking_user bu WHERE b.id=bu.booking_id AND k.id=b.case_id AND bu.user_id IN ('.implode(',', $userIds).') ORDER BY status, k.id DESC';
            $theCases2 = Kase::findBySql($sql2)
                ->with([
                    'owner'=>function($q) {
                        return $q->select(['id', 'name']);
                    }
                ])
                ->asArray()
                ->all();
            $caseIdList = [];
            foreach ($theCases as $case) {
                $caseIdList[] = $case['id'];
            }
            foreach ($theCases2 as $case) {
                if (!in_array($case['id'], $caseIdList)) {
                    $theCases[] = $case;
                }
            }
        }
        $allCountries = Country::find()->select([
            'code',
            'name'=>'CONCAT(name_en, " (", name_vi, ")")',
            'name_en',
            'dial_code'
        ])->asArray()->all();
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
            if (isset($_POST['email'])) {
                $theUserForm->email = $_POST['email'][0];
            }
            if ($theUserForm->load(Yii::$app->request->post()) && $theUserForm->validate()) {
                $theUser = new Person;
                $theUser->scenario = 'create';
                $theUser->created_at = NOW;
                $theUser->created_by = USER_ID;
                $theUser->updated_at = NOW;
                $theUser->updated_by = USER_ID;
                $theUser->status = 'on';
                $theUser->country_code = ($theUserForm->country != '')? $theUserForm->country : 'fr';
                $theUser->fname = $theUserForm->fname;
                $theUser->lname = $theUserForm->lname;
                $theUser->name = (isset($theUserForm->name) && $theUserForm->name != '') ? $theUserForm->name : $theUserForm->fname.' '.$theUserForm->lname;
                $theUser->gender = $theUserForm->gender;
                $theUser->bday = $theUserForm->bday;
                $theUser->bmonth = $theUserForm->bmonth;
                $theUser->byear = $theUserForm->byear;
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
        if ($theMail['case_id'] == 0) {
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
                    //update Mail
                    $theMail->case_id = $theCase->id;
                    if (!$theMail->save(false)) {
                        throw new HttpException(403, "Mail not saved");
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
            $theForm->user_id = 0;
            $theForm->case_id = 'remove';
            if ($theForm->load(Yii::$app->request->post())) {
                if (!$theForm->case_id == 'remove') {
                    $theForm->case_id = 0;
                }
                if ($theForm->validate()) {
                    if ($theForm->case_id == 0 && $theCase->load(Yii::$app->request->post()) ) {
                        $theCase->is_priority = ($theCase->is_priority != '') ? $theCase->is_priority : 'no';
                        $theCase->company_id = ($theCase->company_id != '')? $theCase->company_id : 0;
                        $theCase->owner_id = ($theCase->owner_id != '')? $theCase->owner_id : USER_ID;
                        $theCase->language = ($theCase->language != '')? $theCase->language : 'fr';
                        $theCase->how_found = ($theCase->how_found != '') ? $theCase->how_found : '-';
                        $theCase->how_contacted = ($theCase->how_contacted != '') ? $theCase->how_contacted : 'email';

                        if ($theCase->validate() && !$theCase->save(false)) {
                            throw new HttpException(403, "Case not saved");
                        }
                    }
                    if (!count($theCase->errors) > 0) {
                        $theMail->scenario = 'inquiries/u';
                        $theMail->case_id = $theCase->id;
                        if (!$theMail->save(false)) {
                            throw new HttpException(403, "Inquiry not saved");
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
        return $this->render('mails_r', [
            'theMail'=>$theMail,
            'theForm' => $theForm,
            'theCases'=>$theCases,
            'theUsers'=>$theUsers,
            'emails'=>$emails,
            'theUserForm' => $theUserForm,
            'ownerList'=>$ownerList,
            'cofrList'=>$cofrList,
            'companyList'=>$companyList,
            'allCountries'=>$allCountries,
            'campaignList'=>$campaignList,
            'theCase' => $theCase,
        ]);
    }
    public function actionR1($id = 0)
    {
        $theMail = Mail::find()
            ->where(['id'=>$id])
            ->with([
                'case',
                'case.owner', 
            ])
            ->one();
        if (!$theMail) {
            throw new HttpException(404, 'Mail not found');
        }
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

        $theCase->is_priority = 'no';
        $theCase->company_id = 0;
        $theCase->owner_id = USER_ID;
        $theCase->language = 'fr';
        $theCase->how_contacted = 'email';
        $theCase->how_found = '';
        $theCase->name = $theMail['from'];

        $emailFields = $theMail['from'].' '.$theMail['to'].' '.$theMail['cc'].' '.$theMail['bcc'];
        $filterAddresses = $this->getEmails($emailFields);
        $emails = [];
        foreach ($filterAddresses as $email) {
            if (strpos($email, 'amicatravel') === false && strpos($email, 'amica-travel') === false) {
                $emails[] = $email;
            }
        }

        $sql = 'SELECT u.id, u.name, u.gender, u.country_code, LCASE(m.v) AS email FROM persons u, at_meta m WHERE m.rtype="user" AND m.rid=u.id AND m.k="email" AND LCASE(m.v) IN ('.'"'.implode('", "', $emails).'"'.')';
        $cmd = Yii::$app->db->createCommand($sql);
        $theUsers = $cmd->queryAll();
        //echo $cmd->getSql();
        //exit;

        $userIds = [];
        $theCases = [];
        if (!empty($theUsers)) {
            foreach ($theUsers as $user) {
                $userIds[] = $user['id'];
            }
            $sql = 'SELECT k.id, k.name, k.status, k.deal_status, k.created_at, k.owner_id FROM at_cases k, at_case_user cu WHERE cu.case_id=k.id AND cu.user_id IN ('.implode(',', $userIds).') ORDER BY status, k.id DESC';
            $theCases = Kase::findBySql($sql)
                ->with([
                    'owner'=>function($q) {
                        return $q->select(['id', 'name']);
                    }
                ])
                ->asArray()
                ->all();
            $sql2 = 'SELECT k.id, k.name, k.status, k.deal_status, k.created_at, k.owner_id FROM at_cases k, at_bookings b, at_booking_user bu WHERE b.id=bu.booking_id AND k.id=b.case_id AND bu.user_id IN ('.implode(',', $userIds).') ORDER BY status, k.id DESC';
            $theCases2 = Kase::findBySql($sql2)
                ->with([
                    'owner'=>function($q) {
                        return $q->select(['id', 'name']);
                    }
                ])
                ->asArray()
                ->all();
            $caseIdList = [];
            foreach ($theCases as $case) {
                $caseIdList[] = $case['id'];
            }
            foreach ($theCases2 as $case) {
                if (!in_array($case['id'], $caseIdList)) {
                    $theCases[] = $case;
                }
            }
        }
        if ($theMail['case_id'] == 0) {
            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                switch ($theForm->case_id) {
                    case '0':
                        if ($theForm->user_id == '0') {
                            $ar_n = explode(":", $theForm->new_user_name);
                            if (count($ar_n) == 2) {
                                $e = strtolower(trim($ar_n[0]));
                                $name = trim($ar_n[1]);
                                if (in_array($e, $emails)) {
                                    Yii::$app->session->setFlash('email_new_case', $e);
                                    Yii::$app->session->setFlash('name_email_new_case', $name);
                                    return $this->redirect(Url::to(['cases/c', 'from' => 'mail', 'f_id' => $theMail['id']]));
                                }
                            } else {
                                if (count($ar_n) > 2) {
                                    echo "oooop ! unknown mail";
                                    var_dump($ar_n);die();
                                }
                                if (count($ar_n) == 1 && count($emails) == 1) {
                                    $e = strtolower(trim($emails[0]));
                                    $name = trim($theForm->new_user_name);
                                    Yii::$app->session->setFlash('email_new_case', $e);
                                    Yii::$app->session->setFlash('name_email_new_case', $name);
                                    return $this->redirect(Url::to(['cases/c', 'from' => 'mail', 'f_id' => $theMail['id']]));
                                }
                                foreach ($emails as $k => $email) {
                                    foreach ($theUsers as $user) {
                                        if (strtolower($user['email']) == strtolower($email)) {
                                            unset($emails[$k]);
                                        }
                                    }
                                }
                                if (count($emails) != 1) {
                                    echo "oooop ! unknown mail";
                                    var_dump($emails);die();
                                }
                                $e = strtolower(trim($emails[0]));
                                $name = trim($theForm->new_user_name);
                                Yii::$app->session->setFlash('email_new_case', $e);
                                Yii::$app->session->setFlash('name_email_new_case', $name);
                                return $this->redirect(Url::to(['cases/c', 'from' => 'mail', 'f_id' => $theMail['id']]));
                            }
                        } else {
                            $sql = 'SELECT u.id FROM persons u, at_case_user cu WHERE u.id = cu.user_id AND u.id =:uid';
                            $per = Yii::$app->db->createCommand($sql, [':uid'=>intval($theForm->case_id)]);
                            if ($per == null) {
                                throw new HttpException(403, "Person not found");
                            }
                            return $this->redirect(Url::to(['cases/c', 'from' => 'mail', 'f_id' => $theMail['id'], 'uid' => $theForm->user_id]));
                        }
                        break;
                    default:
                        $theForm->case_id = intval($theForm->case_id);
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
                            throw new HttpException(404, 'Case not found');
                        }
                            //update Mail
                        $theMail->case_id = $theCase->id;
                        $theMail->save(false);

                        if ($theForm->user_id == 0) {
                            $arr_names = explode(';', $theForm->new_user_name);
                            foreach ($arr_names as $n) {
                                if ($n == '') {
                                    continue;
                                }
                                $ar_n = explode(":", $n);
                                if (count($ar_n) == 2) {
                                    $e = strtolower(trim($ar_n[0]));
                                    $name = strtolower(trim($ar_n[1]));
                                    if (in_array($e, $emails)) {
                                        $theUser = new Person();
                                        $theUser->scenario = 'create';
                                        $theUser->created_at = NOW;
                                        $theUser->created_by = MY_ID;
                                        $theUser->updated_at = NOW;
                                        $theUser->updated_by = MY_ID;
                                        $theUser->status = 'on';
                                        $theUser->name = $name;
                                        $theUser->email = $e;
                                        if ($theUser->save(false)) {
                                            $sql = 'INSERT INTO at_email_mapping (email, action, case_id)
                                                    VALUES (:email, :action, :case_id)
                                                    ON DUPLICATE KEY
                                                    UPDATE case_id=:case_id';
                                            Yii::$app->db->createCommand($sql,
                                                [':email'=>$theUser['email'], ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                                            // Meta email
                                            $meta = new Meta;
                                            $meta->rtype = 'user';
                                            $meta->rid = $theUser->id;
                                            $meta->k = 'email';
                                            $meta->v = $theUser['email'];
                                            if ($meta->save()) {
                                                Yii::$app->db->createCommand()->insert('at_search', [
                                                    'rtype'=>'user',
                                                    'rid'=>$theUser->id,
                                                    'search'=>\fURL::makeFriendly(trim($theUser->name.' '.$theUser->email), ' '),
                                                    'found'=>trim($theUser->name.' '.$theUser->email),
                                                    ])->execute();
                                                    // Insert case_user
                                                Yii::$app->db->createCommand()->insert('at_case_user', [
                                                    'case_id' => $theCase['id'],
                                                    'user_id' => $theUser['id'],
                                                    'role'=>'contact',
                                                    ])->execute();
                                            } else {
                                                var_dump($Meta->errors);die();
                                            }
                                        } else {var_dump($theUser->errors);die();}
                                    }
                                }
                                else {
                                    if (count($emails) == 1) {
                                        // Create new user
                                        $theUser = new Person;
                                        $theUser->scenario = 'create';
                                        $theUser->created_at = NOW;
                                        $theUser->created_by = MY_ID;
                                        $theUser->updated_at = NOW;
                                        $theUser->updated_by = MY_ID;
                                        $theUser->status = 'on';
                                        $theUser->name = $theForm->new_user_name;
                                        $theUser->email = isset($emails[0])? $emails[0]: '';
                                        if ($theUser->save(false)) {
                                            $sql = 'INSERT INTO at_email_mapping (email, action, case_id)
                                            VALUES (:email, :action, :case_id)
                                            ON DUPLICATE KEY
                                            UPDATE case_id=:case_id';
                                            Yii::$app->db->createCommand($sql, [':email'=>$theUser['email'], ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                                                // Meta email
                                            $meta = new Meta;
                                            $meta->rtype = 'user';
                                            $meta->rid = $theUser->id;
                                            $meta->k = 'email';
                                            $meta->v = $theUser['email'];
                                            if ($meta->save()) {
                                                Yii::$app->db->createCommand()->insert('at_search', [
                                                    'rtype'=>'user',
                                                    'rid'=>$theUser->id,
                                                    'search'=>\fURL::makeFriendly(trim($theUser->name.' '.$theUser->email.' '.$theUser->phone), ' '),
                                                    'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                                                                // 'search'=>\fURL::makeFriendly(trim($theUser->name.' '.$theUser->email.' '.$theUser->phone), ' '),
                                                                // 'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                                                    ])->execute();
                                                            // Insert case_user
                                                Yii::$app->db->createCommand()->insert('at_case_user', [
                                                    'case_id' => $theCase['id'],
                                                    'user_id' => $theUser['id'],
                                                    'role'=>'contact',
                                                    ])->execute();
                                            } else {var_dump($Meta->errors);die();}
                                        }
                                    } else {
                                        $theMail->case_id = 0;
                                        $theMail->save(false);
                                        echo "oooop ! unknown mail for name: ". $theForm->new_user_name;
                                        var_dump($emails);die();
                                    }
                                }
                            }
                            return $this->redirect(Url::current());
                        } else {
                            $theUser = Person::find()->where(['id'=>$theForm->user_id])->one();
                            if (!$theUser) {
                                throw new HttpException(404, 'User not found');
                            }
                            $exist_user = false;
                            foreach ($theCase->cperson as $person) {
                                if ($person->id == $theUser->id) {
                                    $exist_user = true;
                                }
                            }
                            if (!$exist_user) {
                                Yii::$app->db->createCommand()->insert('at_case_user', [
                                    'case_id' => $theCase['id'],
                                    'user_id' => $theUser['id'],
                                    'role'=> 'contact',
                                    ])->execute();
                            }

                        }
                        break;
                }

                $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                Yii::$app->db->createCommand($sql, [':email'=>$theUser['email'], ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                return $this->redirect(Url::current());
            }
        } else {
            $theForm->user_id = 0;
            if ($theForm->load(Yii::$app->request->post())) {
                if (!$theForm->case_id) {
                    $theForm->case_id = 0;
                }

                if ($theForm->validate()) {
                    $theMail->case_id = $theForm->case_id;
                    $theMail->save(false);
                    return $this->redirect(Url::current());
                }
            }
        }

        return $this->render('mails_r', [
            'theMail'=>$theMail,
            'theForm' => $theForm,
            'theCases'=>$theCases,
            'theUsers'=>$theUsers,
            'emails'=>$emails,
        ]);
    }

    // Download file
    public function actionF($id = 0, $name = '')
    {
        $theMail = Mail::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theMail) {
            throw new HttpException(404, 'Mail not found');
        }

        $fileName = urldecode($name);
        $filePath = Yii::getAlias('@webroot').'/upload/mail-attachments/'.substr($theMail['created_at'], 0, 7).'/'.$theMail['id'].'/'.$theMail['id'].'-'.sha1($fileName);
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath, $fileName, [
                'inline'=>true,
                'mimeType'=>\yii\helpers\FileHelper::getMimeTypeByExtension($fileName),
            ]);
        }
        throw new HttpException(404, 'Attachment not found');
    }

    public function actionBh($id = 0)
    {
        $theMail = Mail::find()
            ->select(['body_full'])
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theMail) {
            die('Mail not found');
        }
        $theMail['body_full'] = strip_tags($theMail['body_full'], '<a><b><br><hr><img><p><div><table><tbody><thead><tr><td><em><span><strong><ul><ol><li>');
        $theMail['body_full'] = str_replace([' style='], [' x='], $theMail['body_full']);
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"></head><body>'.$theMail['body_full'].'</body></html>';
    }

    // Move inquiry to another case
    public function actionU($id = 0)
    {
        $theMail = Mail::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theMail) {
            throw new HttpException(403, 'Mail message not found.');
        }
        $theMail->scenario = 'mails/u';
        $theMail->body = $theMail->body_full;
        if ($theMail->load(Yii::$app->request->post()) && $theMail->validate()) {
            $theMail->updated_at = NOW;
            $theMail->updated_by = Yii::$app->user->id;
            $theMail->save(false);
            return $this->redirect('@web/mails/r/'.$theMail['id']);
        }
        return $this->render('mails_u', [
            'theMail'=>$theMail,
        ]);
    }

    public function actionD($id = 0)
    {
        // Huan: delete similarly titled spam mail
        if ($id == 0 && USER_ID == 1) {
            $subject = Yii::$app->request->get('subject', '');
            if ($subject != '') {
                $theMails = Mail::find()
                    ->select(['id', 'status', 'subject', 'created_at'])
                    ->where(['case_id'=>0])
                    ->where(['like', 'subject', $subject])
                    ->limit(100)
                    ->asArray()
                    ->all();
                $idList = [];
                foreach ($theMails as $mail) {
                    $idList[] = $mail['id'];
                    FileHelper::removeDirectory(Yii::getAlias('@webroot').'/upload/mail-attachments/'.substr($mail['created_at'], 0, 7).'/'.$mail['id']);
                    echo '<br>', $mail['subject'];
                }
                Yii::$app->db->createCommand()->delete('at_mails', ['id'=>$idList])->execute();
            }
            die('<hr>Done!');
        }
        $theMail = Mail::find()
            ->select(['id', 'status', 'subject', 'created_at'])
            ->where(['id'=>$id])
            ->one();
        if (!$theMail) {
            throw new HttpException(404, 'Mail not found');
        }
        //Khue 
        if (!in_array(MY_ID, [1, 15081]) && $theMail['case_id'] == 0) {
            throw new HttpException(403, 'Access denied.');
        }

        $path = Yii::getAlias('@webroot').'/upload/mail-attachments/'.substr($theMail['created_at'], 0, 7).'/'.$theMail['id'];

        if ($theMail->delete()) {
            FileHelper::removeDirectory(Yii::getAlias('@webroot').'/upload/mail-attachments/'.substr($theMail['created_at'], 0, 7).'/'.$theMail['id']);
            Yii::$app->session->setFlash('success', 'Mail has been DELETED: '.$theMail->subject);
            //echo 'DELETED';
            //return;
        }

        /*

        if ($theMail->status != 'deleted') {
            $theMail->status = 'deleted';
            if ($theMail->save(false)) {
                Yii::$app->session->setFlash('success', 'Mail has been marked DELETED: '.$theMail->subject);
            }
        } else {
            if ($theMail->delete()) {
                FileHelper::removeDirectory(Yii::getAlias('@webroot').'/upload/mail-attachments/'.substr($theMail['created_at'], 0, 7).'/'.$theMail['id']);
                Yii::$app->session->setFlash('success', 'Mail has been DELETED: '.$theMail->subject);
                echo 'DELETED';
                return;
            }
        }
        */
        if (Yii::$app->request->isAjax) {
            return true;
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    // Display email mappings
    public function actionMappings()
    {
        //echo '?email=email@domain.com';
        $query = MailMapping::find();
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $theMappings = $query
            ->orderBy('email')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('mails_mappings', [
            'theMappings'=>$theMappings,
            'pages'=>$pages,
        ]);
    }

    // Extract email addresses from text
    private function getEmails($text)
    {
        $pattern = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i";
        preg_match_all($pattern, $text, $matches);
        // Get unique values
        return array_keys(array_flip($matches[0]));
    }

    // Share with op
    public function actionUOp($id)
    {
        $theMail = Mail::find()
            ->select(['id', 'case_id', 'from_email', 'to_email', 'tags'])
            ->where(['id'=>$id])
            ->one();
        if (!$theMail) {
            throw new HttpException(404, 'Email not found.');
        }
        if ($theMail['case_id'] != 0) {
            $theCase = Kase::find()
                ->select(['id', 'owner_id'])
                ->where(['id'=>$theMail['case_id']])
                ->one();
        }
        if (
            ($theCase && in_array(Yii::$app->user->id, [1, $theCase['owner_id']])) ||
            ($theMail['from_email'] == Yii::$app->user->identity->email || $theMail['to_email'] == Yii::$app->user->identity->email)
        ) {
            if ($theMail->tags == 'op') {
                $theMail->tags = '';
            } else {
                $theMail->tags = 'op';
            }
            $theMail->save(false);
        } else {
            throw new HttpException(403, 'Access denied');
        }
        if ($theCase) {
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        } else {
            return $this->redirect('@web/mails/r/'.$theMail['id']);
        }
    }

    public function actionTrash()
    {
        $query = Mail::find()
            ->where(['status'=>'deleted']);
        if (!in_array(Yii::$app->user->id, [1, 4432])) {
            $query->andWhere(['or', ['from_email'=>Yii::$app->user->identity->email], ['to_email'=>Yii::$app->user->identity->email]]);
        }

        $getAttachments = Yii::$app->request->get('attachments', 'all');
        if (!in_array($getAttachments, ['all', 'yes', 'no'])) {
            $getAttachments = 'all';
        }

        $getCaseId = Yii::$app->request->get('case_id', 'all');
        if (!in_array($getCaseId, ['all', 'yes', 'no'])) {
            $getCaseId = 'all';
        }

        $getFromEmail = Yii::$app->request->get('from_email', '');

        $getToEmail = Yii::$app->request->get('to_email', '');

        if ($getAttachments == 'yes') {
            $query->andWhere(['>', 'attachment_count', 0]);
        } elseif ($getAttachments == 'no') {
            $query->andWhere(['=', 'attachment_count', 0]);
        }

        if ($getCaseId == 'yes') {
            $query->andWhere(['!=', 'case_id', 0]);
        } elseif ($getCaseId == 'no') {
            $query->andWhere(['=', 'case_id', 0]);
        }

        if ($getFromEmail != '') {
            $query->andWhere(['from_email'=>$getFromEmail]);
        }

        if ($getToEmail != '') {
            $query->andWhere(['to_email'=>$getToEmail]);
        }

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);


        $theMails = $query
            ->with([
                'case',
                'case.owner',
            ])
            ->orderBy('created_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('mails_trash', [
            'pages'=>$pages,
            'theMails'=>$theMails,
            'getAttachments'=>$getAttachments,
            'getFromEmail'=>$getFromEmail,
            'getToEmail'=>$getToEmail,
            'getCaseId'=>$getCaseId,
            ]
        );
    }

    // Unlink email msg from case
    public function actionUnlink($id)
    {
        $theMail = Mail::find()
            ->where(['id'=>$id])
            ->with(['case'])
            ->one();
        if ($theMail['case_id'] == 0) {
            throw new HttpException(404, 'Email not linked to any case.');
        } else {
            if (!in_array(MY_ID, [1, 4432, 26435, $theMail['case']['owner_id']])) {
                throw new HttpException(403, 'Access denied.');
                
            }
            $theMail->case_id = 0;
            $theMail->save(false);
            return $this->redirect('@web/cases/r/'.$theMail['case']['id']);
        }
    }

    // Search for email
    public function actionSearch($key = '', $value = '')
    {
        if ($key == '' || $value == '') {
            throw new HttpException(404, 'Email not found');
        }
        $query = Mail::find();
        if ($key == 'message_id') {
            $theMail = $query->select(['id'])->where(['message_id'=>$value])->asArray()->one();
        }
        if (!$theMail) {
            throw new HttpException(404, 'Email not found');
        }
        return $this->redirect('@web/mails/r/'.$theMail['id']);
    }
}
