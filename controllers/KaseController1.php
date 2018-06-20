<?php

namespace app\controllers;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\validators\EmailValidator;

use common\models\Kase;
use common\models\KaseStats;
use common\models\CpLink;
use common\models\Country;
use common\models\Person;
use common\models\Inquiry;
use common\models\Message;
use common\models\Note;
use common\models\Sysnote;
use common\models\Mail;
use common\models\Company;
use common\models\Campaign;
use common\models\Meta;

class KaseController extends MyController
{
    // 160723 Acheteur / non-acheteur report
    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionAna($id = 0)
    {
        $theCase = Kase::find()
            ->with([
                'owner'=>function($q) {
                    return $q->select(['id', 'email']);
                }
                ])
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        $theSysnote = Sysnote::find()
            ->where(['rtype'=>'case', 'rid'=>$theCase['id'], 'action'=>'kase/ana'])
            ->asArray()
            ->one();

        if (!Yii::$app->request->isPost) {
            if ($theSysnote) {
                $pos = strpos($theSysnote['info'], '[#QA]');
                $ana = substr($theSysnote['info'], 0, $pos);
                $str = substr($theSysnote['info'], $pos);
                $email = $theCase['owner']['email'];
            } else {
                $ana = $theCase['deal_status'] == 'won' ? 'a_160730' : 'na_160730';
                $str = '';
                $email = $theCase['owner']['email'];
            }
        } else {
            $ana = Yii::$app->request->post('ana');
            $str = Html::encode(Yii::$app->request->post('str'));
            $email = Yii::$app->request->post('email');
        }

        if (Yii::$app->request->isPost && isset($_POST['str']) && $_POST['str'] != '' && isset($_POST['ok']) && $_POST['ok'] == 'ok') {
            if ($theSysnote) {
                Yii::$app->db->createCommand()
                    ->update('at_sysnotes', [
                        'created_at'=>NOW,
                        'ip'=>USER_IP,
                        'info'=>$ana.'[#QA]'.$str,
                        ],[
                        'id'=>$theSysnote['id'],
                        ]
                    )
                    ->execute();
            } else {
                Yii::$app->db->createCommand()
                    ->insert('at_sysnotes', [
                        'created_at'=>NOW,
                        'user_id'=>USER_ID,
                        'action'=>'kase/ana',
                        'rtype'=>'case',
                        'rid'=>$theCase['id'],
                        'uri'=>URI,
                        'ip'=>USER_IP,
                        'info'=>$ana.'[#QA]'.$str,
                    ])
                    ->execute();
            }
            // Email people
            // TODO check email list
            $emailList = explode(',', str_replace(' ', '', $email));
            if (!empty($emailList)) {
                $args = [
                    ['from', Yii::$app->user->identity->email, Yii::$app->user->identity->nickname.' on IMS'],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];

                foreach ($emailList as $emailAddress) {
                    $args[] = ['to', $emailAddress];
                }

                $this->mgIt(
                    'Exit survey / Kết quả thăm dò ý kiến sau bán hàng - '.$theCase['name'],
                    '//mg/kase_ana',
                    [
                        'theCase'=>$theCase,
                        'anaString'=>$ana.'[#QA]'.$str,
                    ],
                    $args
                );
            }
            return $this->redirect('/cases/r/'.$theCase['id']);
        }

        return $this->render('kase_ana', [
            'theCase'=>$theCase,
            'ana'=>$ana,
            'str'=>$str,
            'email'=>$email,
        ]);
    }

    // Update 
    public function actionThy()
    {
        // 150806
        $sql = 'select substring(i.form_name, 1, 3) as site, k.id, k.name from at_cases k, at_inquiries i WHERE i.case_id=k.id AND i.id >7533';
        $inquiries = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($inquiries as $inquiry) {
            echo '<br>', $inquiry['site'], ' - ', $inquiry['name'];
            $site = trim($inquiry['site'], '_');
            if (in_array($site, ['fr', 'val', 'vac', 'en', 'ami', 'vpc'])) {
                Yii::$app->db->createCommand()->update('at_case_stats', ['pa_from_site'=>$site], ['case_id'=>$inquiry['id']])->execute();
                echo ' / OK';
            }
        }
    }

    public function actionIndex($source = '')
    {
        $getProspect = Yii::$app->request->get('prospect', 'all');
        $getDevice = Yii::$app->request->get('device', 'all');
        $getSite = Yii::$app->request->get('site', 'all');
        $getCa = Yii::$app->request->get('ca', 'created');
        $getMonth = Yii::$app->request->get('month', 'all');
        $getStatus = Yii::$app->request->get('status', 'all');
        $getSaleStatus = Yii::$app->request->get('sale_status', 'all');
        $getOwnerId = Yii::$app->request->get('owner_id', 'all');
        $getCampaignId = Yii::$app->request->get('campaign_id', 'all');
        $getName = Yii::$app->request->get('name', '');
        $getHowFound = Yii::$app->request->get('found', 'all');
        $getHowContacted = Yii::$app->request->get('contacted', 'all');
        $getPriority = Yii::$app->request->get('is_priority', 'all');
        $getCompany = Yii::$app->request->get('company', 'all');
        $getLanguage = Yii::$app->request->get('language', 'all');

        $query = Kase::find()->where(['is_b2b'=>'no']);

        if (in_array($getProspect, [1,2,3,4,5]) || $getSite != 'all' || $getDevice != 'all') {
            $cond = [];
            if ($getProspect != 'all') {
                $cond['prospect'] = $getProspect;
            }
            if ($getSite != 'all') {
                $cond['pa_from_site'] = $getSite;
            }
            if ($getDevice != 'all') {
                $cond['request_device'] = $getDevice;
            }
            $query->innerJoinwith('stats')->onCondition($cond);
        }

        if ($getMonth != 'all') {
            if ($getCa == 'created') {
                $query->andWhere('SUBSTRING(created_at, 1, 7)=:month', [':month'=>$getMonth]);
            } elseif ($getCa == 'assigned') {
                $query->andWhere('SUBSTRING(ao, 1, 7)=:month', [':month'=>$getMonth]);
            } else {
                $query->andWhere('SUBSTRING(closed, 1, 7)=:month', [':month'=>$getMonth]);
            }
        }
        if ($getStatus != 'all') $query->andWhere(['status'=>$getStatus]);
        if ($getSaleStatus != 'all') $query->andWhere(['deal_status'=>$getSaleStatus]);
        if ($getPriority != 'all') $query->andWhere(['is_priority'=>$getPriority]);
        if ($getLanguage != 'all') $query->andWhere(['language'=>$getLanguage]);
        if ($getOwnerId != 'all') {
            if (substr($getOwnerId, 0, 5) == 'cofr-') {
                $query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
            } else {
                $query->andWhere(['owner_id'=>(int)$getOwnerId]);
            }           
        }
        if ($getCampaignId == 'yes') {
            $query->andWhere('campaign_id!=0');
        } else {
            if ($getCampaignId != 'all') $query->andWhere(['campaign_id'=>$getCampaignId]);
        }
        if ($getHowFound != 'all') {
            $query->andWhere(['how_found'=>$getHowFound]);
        }
        if ($getHowContacted == 'unknown') {
            $query->andWhere(['how_contacted'=>'']);
        } else {
            if ($getHowContacted != 'all') {
                if ($getHowContacted == 'web-direct') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
                } elseif ($getHowContacted == 'link') {
                    $query->andWhere(['web_referral'=>'link']);
                } elseif ($getHowContacted == 'social') {
                    $query->andWhere(['web_referral'=>'social']);
                } elseif ($getHowContacted == 'web-search') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
                } elseif ($getHowContacted == 'web-search-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($getHowContacted == 'web-adsense') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
                } elseif ($getHowContacted == 'web-bingad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
                } elseif ($getHowContacted == 'web-otherad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
                } elseif ($getHowContacted == 'web-adwords') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
                } elseif ($getHowContacted == 'web-adwords-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($getHowContacted == 'web-trip-connexion') {
                    $query->andWhere(['web_referral'=>'ad/trip-connexion']);
                } else {
                    $query->andWhere(['how_contacted'=>$getHowContacted]);
                }
            }
        }
        if ($getName != '') $query->andWhere(['like', 'name', $getName]);

        /*if ($getProspect != 'all') {
            $query->innerJoinWith('stats')->onCondition();
                $getProspect = Yii::$app->request->get('prospect');
                    if ((int)$getProspect != 0) {
                        return $query->andWhere(['prospect'=>$getProspect]);
                    }

        }*/

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $theCases = $query
            ->select(['id', 'name', 'status', 'ref', 'is_priority', 'deal_status', 'opened', 'owner_id', 'created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed_note'])
            ->orderBy('created_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->with([
                'stats',
                'owner'=>function($query) {
                    return $query->select(['id', 'nickname', 'image']);
                },
                'referrer'=>function($query) {
                    return $query->select(['id', 'name', 'is_client']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->asArray()
            ->all();

        // List of months
        $monthList = Yii::$app->db->createCommand('SELECT SUBSTRING(created_at, 1, 7) AS ym FROM at_cases GROUP BY ym ORDER BY ym DESC ')->queryAll();
        $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email FROM at_cases c, persons u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
        $campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM at_campaigns c ORDER BY c.start_dt DESC')->queryAll();
        $companyList = Yii::$app->db->createCommand('SELECT c.id, c.name FROM at_cases k, at_companies c WHERE k.company_id=c.id GROUP BY k.company_id ORDER BY c.name')->queryAll();

        //check emails unknown
        $unknowns = Mail::find()
            ->where(['status'=>'on', 'case_id' => 0])->all();
        //hoa.nhung@amicatravel.com
        // ngo.hang@amicatravel.com
        $email_ids = [];
        foreach ($unknowns as $theMail) {
            $emailFields = $theMail['from'].' '.$theMail['to'].' '.$theMail['cc'].' '.$theMail['bcc'];
            $emailAddresses = $this->getEmails($emailFields);
            $emails = [];
            foreach ($emailAddresses as $email) {

                if (strtolower($email) == 'ngo.hang@amicatravel.com' || strtolower($email) == Yii::$app->user->identity->email) {
                    $email_ids[] = $theMail['id'];
                    break;
                }
            }
        }
        $cnt_emails_unknown = Mail::find()
            ->where(['id' => $email_ids, 'status' => 'on'])->count();

        return $this->render('kase_index', [
            'pages'=>$pages,
            'theCases'=>$theCases,
            'getProspect'=>$getProspect,
            'getDevice'=>$getDevice,
            'getSite'=>$getSite,
            'getCa'=>$getCa,
            'getMonth'=>$getMonth,
            'monthList'=>$monthList,
            'getOwnerId'=>$getOwnerId,
            'ownerList'=>$ownerList,
            'getCampaignId'=>$getCampaignId,
            'campaignList'=>$campaignList,
            'getStatus'=>$getStatus,
            'getSaleStatus'=>$getSaleStatus,
            'getHowFound'=>$getHowFound,
            'getHowContacted'=>$getHowContacted,
            'getName'=>$getName,
            'getCompany'=>$getCompany,
            'getPriority'=>$getPriority,
            'getLanguage'=>$getLanguage,
            'companyList'=>$companyList,
            'source'=>$source,
            'cnt' => $cnt_emails_unknown,
        ]);
    }

    // Testing: send CPL
    public function actionSendCpl($id = 0, $action = 'send', $cplink_id = 0)
    {
        // actions = [send, resend, confirm, reset] reset = change uid
        $theCase = Kase::find()
            ->with([
                'owner',
                'people',
                'bookings',
                'bookings.product'
            ])
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');            
        }
        if (empty($theCase['bookings'])) {
            throw new HttpException(404, 'No tour itineraries found.');         
        }
        if (!in_array(USER_ID, [1, 4432, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');         
        }

        if (!in_array($theCase['language'], ['en', 'fr', 'vi'])) {
            throw new HttpException(403, 'Language not supported.');            
        }

        // Already sent links
        $sentLinks = CpLink::find()
            ->where(['case_id'=>$theCase['id']])
            ->asArray()
            ->all();

        $subject = 'Vui lòng cung cấp thông tin cho chuyến du lịch sắp tới';
        $message = <<<'TXT'
            <p>Cảm ơn quý khách đã tin tưởng vào dịch vụ của Amica Travel.</p>
            <p>Bước tiếp theo, xin quý khách dành chút thời gian để cung cấp một số thông tin cần thiết cho chuyến du lịch.</p>
            <p>Xin sử dụng đường link dưới đây để đến Trang Khách hàng dành riêng cho quý khách, làm theo hướng dẫn trên trang và điền các thông tin.</p>
            <p>Chú ý: Quý khách vui lòng điền thông tin cho <strong>mọi thành viên</strong> trong nhóm đi tour của mình. Nếu trong đoàn tour có những người mà quý khách không biết thông tin, xin cho tôi biết, tôi sẽ gửi link riêng cho những người ấy.</p>
            <p>{{ $link }}</p>
            <p>Quý khách không nên chia sẻ đường link này với người khác vì mỗi người đều có đường link riêng của mình.</p>
            <p>Nếu có ý kiến hay câu hỏi, xin vui lòng liên hệ với tôi theo địa chỉ email: {{ $email }}.</p>
            <p>Trân trọng,</p>
            <p>{{ $name }}<br>
            Nhân viên tư vấn tour<br>
            AMICA TRAVEL</p>
TXT;

        if ($theCase['language'] == 'en') {
            $subject = 'Your registration for the next trip with Amica Travel';
            $message = <<<'TXT'
            <p>Dear Sir/Madam,</p>
            <p>Nous sommes ravies que notre proposition vous convienne et nous vous remercions de votre confiance.</p>
            <p>Afin de lancer la procédure de réservation officielle, nous vous remercions de bien vouloir nous fournir les renseignements nécessaires pour votre prochain voyage.</p>
            <p>Nous vous invitons à cliquer sur le lien ci-dessous afin d’accéder à votre propre page client. Suivez ensuite les instructions et entrez les informations demandées.</p>
            <p>This is your link: {{ $link }}</p>

            <p>PLEASE NOTE: Veuillez compléter les informations pour tous les participants de votre groupe sur notre formulaire en ligne. Si vous ne connaissez pas les informations d’une ou de plusieurs personnes, merci de nous en informer, nous enverrons un lien différent directement à la personne concernée.</p>
            <p>Si vous partagez le lien avec les autres participants inscrits sur la liste, merci de leur dire de faire attention à ne pas modifier les informations concernant les autres membres du groupe.</p>

            <p>Nous vous rappelons à ce stade, et tel que mentionné dans notre offre, qu’en cas d’indisponibilité d’une ou de plusieurs prestations (hébergement, vol, train…) prévues dans le programme, nous vous en informerons tout en vous proposant des solutions de remplacement équivalentes.</p>
            <p>Should you have any questions or comments, do not hesitate to contact me {{ $email }}.</p>

            <p>Yours sincerely,</p>

            <p>{{ $name }}<br>
            Your travel consultant<br>
            AMICA TRAVEL
TXT;
        } elseif ($theCase['language'] == 'fr') {
            $subject = '[TEST] Fiche de réservation - renseignements nécessaires à fournir pour votre prochain voyage';
            $message = <<<'TXT'
            <p>Bonjour Madame/Monsieur,</p>
            <p>Nous sommes ravies que notre proposition vous convienne et nous vous remercions de votre confiance.</p>
            <p>Afin de lancer la procédure de réservation officielle, nous vous remercions de bien vouloir nous fournir les renseignements nécessaires pour votre prochain voyage.</p>
            <p>Nous vous invitons à cliquer sur le lien ci-dessous afin d’accéder à votre propre page client. Suivez ensuite les instructions et entrez les informations demandées.</p>
            <p>Voici votre lien : {{ $link }}</p>

            <p>NOTE : Veuillez compléter les informations pour tous les participants de votre groupe sur notre formulaire en ligne. Si vous ne connaissez pas les informations d’une ou de plusieurs personnes, merci de nous en informer, nous enverrons un lien différent directement à la personne concernée.</p>
            <p>Si vous partagez le lien avec les autres participants inscrits sur la liste, merci de leur dire de faire attention à ne pas modifier les informations concernant les autres membres du groupe.</p>

            <p>Nous vous rappelons à ce stade, et tel que mentionné dans notre offre, qu’en cas d’indisponibilité d’une ou de plusieurs prestations (hébergement, vol, train…) prévues dans le programme, nous vous en informerons tout en vous proposant des solutions de remplacement équivalentes.</p>
            <p>Si vous avez des questions, n’hésitez pas à nous contacter.</p>

            <p>Amicalement,</p>

            <p>{{ $name }}<br>
            Votre conseillère en voyage<br>
            AMICA TRAVEL
TXT;
        }

        $attachmentList = [
            'en'=>[],
            'fr'=>[
                1=>'Amica Travel - 2016 - Modalités de paiement d\'Amica Travel - 5.9.2016.pdf',
                2=>'Amica Travel - 2016 - Conditions générales de vente.pdf',
                3=>'Amica Travel - 2016 - Démarches à suivre.pdf',
            ],
            'vi'=>[],
        ];

        if ($action == 'resend' && $cplink_id != 0) {
            $theForm = CpLink::findOne($cplink_id);
            $theForm['attachments'] = array_keys($attachmentList[$theForm['language']]);
            $uid = $theForm['uid'];
        } elseif ($action == 'send') {
            $theForm = new CpLink;
            $theForm['message'] = $message;
            $theForm['attachments'] = array_keys($attachmentList[$theCase['language']]);
            $uid = Yii::$app->security->generateRandomString(10);
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            foreach ($theCase['people'] as $user) {
                if ($user['email'] == $theForm['customer_email']) {
                    $thePax = [
                        'id'=>$user['id'],
                        'email'=>$user['email'],
                        'name'=>$user['name'],
                    ];
                }
            }

            $theForm->created_dt = NOW;
            $theForm->created_by = USER_ID;
            $theForm->status = 'sent';
            $theForm->case_id = $theCase['id'];
            $theForm->customer_id = $thePax['id']; // TODO remove
            $theForm->customer_name = $thePax['name']; // TODO remove
            $theForm->customer_email = $thePax['email']; // TODO remove
            $theForm->uid = $uid;
            $theForm->language = $theCase['language'];

            $theForm->save(false);

            $theLink = 'https://client.amica-travel.com/s/'.$theForm['id'].'.'.$uid;

            $message = str_replace(['{{ $email }}', '{{ $name }}', '{{ $link }}'], [$theCase['owner']['email'], $theCase['owner']['fname'].' '.$theCase['owner']['lname'], Html::a($theLink, $theLink)], $theForm['message']);
            $args = [
                ['from', $theCase['owner']['email'], $theCase['owner']['fname'].' '.$theCase['owner']['lname'], 'Amica Travel'],
                // ['to', $thePax['customer_email'], $thePax['customer_name']],
                // ['to', 'ngo.hang@amica-travel.com', 'Hằng Ngô Amica Travel'],
                ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            ];

            if (!empty($theForm->attachments)) {
                foreach ($theForm->attachments as $attachment) {
                    $args[] = ['attachment', 'www/special/'.$attachmentList[$theCase['language']][$attachment]];
                }
            }

            // \fCore::expose($theForm);
            // \fCore::expose($args);
            // exit;

            $this->mgIt(
                $subject,
                '//mg/cases_send-cpl',
                [
                    'theCase'=>$theCase,
                    'thePax'=>$thePax,
                    'message'=>$message,
                ],
                $args
            );

            // Sys note
            // Yii::$app->db->createCommand()->insert('at_sysnotes', [
            //     'created_at'=>NOW,
            //     'user_id'=>USER_ID,
            //     'action'=>'kase/send-cpl',
            //     'rtype'=>'case',
            //     'rid'=>$theCase['id'],
            //     'uri'=>'',
            //     'ip'=>'',
            //     'info'=>$theLink,
            //     ])->execute();

            Yii::$app->session->setFlash('info', 'The link is '.Html::a($theLink, $theLink, ['class'=>'alert-link', 'rel'=>'external']));
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        return $this->render('cases_send-cpl', [
            'theCase'=>$theCase,
            'theForm'=>$theForm,
            'attachmentList'=>$attachmentList,
            'sentLinks'=>$sentLinks,
        ]);
    }

    // Extra report info, B2C only
    public function actionStats($month = 0)
    {
        // Huan, PA, Fleur, Doan Ha
        if (!in_array(USER_ID, [34718,1, 2, 695, 4432, 26435, 35887])) {
            throw new HttpException(403, 'Access denied.');
        }

        // Ajax update
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['pk'], $_POST['name'], $_POST['value'])) {
                // Prospect
                if ($_POST['name'] == 'prospect' && in_array(USER_ID, [4432, 26435, 35887])) {
                    $sql = 'INSERT INTO at_case_stats (case_id, prospect, prospect_updated_dt, prospect_updated_by) VALUES (:case_id, :prospect, :updated_dt, :updated_by) ON DUPLICATE KEY UPDATE prospect=:prospect, prospect_updated_dt=:updated_dt, prospect_updated_by=:updated_by';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':prospect'=>$_POST['value'],
                        ':updated_dt'=>NOW,
                        ':updated_by'=>USER_ID,
                    ])->execute();
                    return true;
                }
                // Diem den
                if ($_POST['name'] == 'destinations') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_destinations) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_destinations=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // So pax
                if ($_POST['name'] == 'pax') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_pax) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_pax=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Do tuoi
                if ($_POST['name'] == 'pax_ages') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_pax_ages) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_pax_ages=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // So ngay
                if ($_POST['name'] == 'days') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_days) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_days=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Khoi hanh
                if ($_POST['name'] == 'start_date') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_start_date) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_start_date=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Kieu tour
                if ($_POST['name'] == 'tour_type') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_tour_type) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_tour_type=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Kieu di
                if ($_POST['name'] == 'group_type') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_group_type) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_group_type=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Tags
                if ($_POST['name'] == 'tags') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_tags) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_tags=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
            }
            throw new HttpException(403, 'Access denied');
        }

        $sql = 'SELECT SUBSTRING(created_at,1,7) AS ym FROM at_cases GROUP BY ym ORDER BY ym DESC';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();

        if (strlen($month) != 7) {
            $month = date('Y-m');
        }
        $query = Kase::find()
            ->where('is_b2b="no"')
            ->andWhere('SUBSTRING(created_at,1,7)=:month', [':month'=>$month]);

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theCases = $query
            ->select(['id', 'name', 'owner_id', 'created_at', 'how_found', 'how_contacted', 'web_referral', 'ref', 'status', 'deal_status', 'is_priority'])
            ->with([
                'owner'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'stats',
            ])
            ->orderBy('created_at DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('cases_stats', [
            'pagination'=>$pagination,
            'theCases'=>$theCases,
            'month'=>$month,
            'monthList'=>$monthList,
        ]);
    }

    // My open cases
    public function actionOpen()
    {
        $theCases = Kase::find()
            ->where(['and', ['status'=>['open']], ['or', 'owner_id='.USER_ID, 'cofr='.USER_ID]])
            //->where(['and', ['status'=>['open', 'onhold']], ['or', 'owner_id=1677', 'cofr=1677']])
            ->orderBy('ao DESC, name')
            ->with(['tasks'=>function($q) {
                return $q->andWhere(['status'=>'on']);
                }
            ])
            ->asArray()
            ->limit(1000)
            ->all();

        return $this->render('cases_open', [
            'theCases'=>$theCases,
        ]);
    }

    // Edit customer request
    public function actionRequest($id = 0)
    {
        // die('ok');
        if (USER_ID != 1) {
            // throw new HttpException(403, 'Page is being updated. Please come back later.');
        }

        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with(['stats'])
            ->asArray()
            ->one();
        if (!in_array(USER_ID, [34718, 1, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }
        // var_dump($theCase);die();
        if ($theCase['status'] != 'open') {
            // throw new HttpException(403, 'Case is not open.');
        }

        if (!$theCase['stats']) {
            Yii::$app->db->createCommand()
                ->insert('at_case_stats', [
                    'case_id'=>$theCase['id'],
                    'updated_at'=>NOW,
                    'updated_by'=>USER_ID,
                ])->execute();
            return $this->redirect('@web/cases/request/'.$theCase['id']);
        }

        $caseStats = KaseStats::find()
            ->where(['case_id'=>$theCase['id']])
            ->one();
        $caseStats->scenario = 'cases/request';

        // $caseStats->pa_destinations = explode(',', $caseStats->pa_destinations);

        if ($caseStats->load(Yii::$app->request->post()) && $caseStats->validate()) {
            // $caseStats->pa_destinations = implode(',', $caseStats->pa_destinations);
            $caseStats->save(false);
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('case_request', [
            'theCase'=>$theCase,
            'caseStats'=>$caseStats,
            'countryList'=>$countryList,
        ]);

    }

    public function actionUnk_email(){
        $unknowns = Mail::find()
            ->where(['status'=>'on', 'case_id' => 0])->all();
        // var_dump(Yii::$app->user->identity->email);die();
        //hoa.nhung@amicatravel.com
        $email_ids = [];
        foreach ($unknowns as $theMail) {
            $emailFields = $theMail['from'].' '.$theMail['to'].' '.$theMail['cc'].' '.$theMail['bcc'];
            $emailAddresses = $this->getEmails($emailFields);
            $emails = [];
            foreach ($emailAddresses as $email) {

                if (strtolower($email) == 'ngo.hang@amicatravel.com' || strtolower($email) == Yii::$app->user->identity->email) {
                    $email_ids[] = $theMail['id'];
                    break;
                }
            }
        }
        $query = Mail::find()
            ->where(['id' => $email_ids, 'status' => 'on']);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);


        $theMails = $query
            ->orderBy('created_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('kase_unk_email', [
            'pages'=>$pages,
            'theMails'=>$theMails
            ]
        );
    }
    // Extract email addresses from text
    private function getEmails($text)
    {
        $pattern = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i";
        preg_match_all($pattern, $text, $matches);
        // Get unique values
        return array_keys(array_flip($matches[0]));
    }

    // My frozen cases
    public function actionOnhold()
    {
        $theCases = Kase::find()
            ->where(['and', ['status'=>['onhold']], ['or', 'owner_id='.USER_ID, 'cofr='.USER_ID]])
            //->where(['and', ['status'=>['open', 'onhold']], ['or', 'owner_id=1677', 'cofr=1677']])
            ->orderBy('ao DESC, name')
            ->with(['tasks'=>function($q) {
                return $q->andWhere(['status'=>'on']);
                }
            ])
            ->asArray()
            ->limit(1000)
            ->all();

        return $this->render('cases_onhold', [
            'theCases'=>$theCases,
        ]);
    }

    public function actionC($from = '', $f_id = 0, $uid = 0)
    {
        if ($f_id > 0) {
            $id = $f_id;
        } else { $id = 0;}

        $theInquiry = false;
        $theMail = false;
        $dataUser = false;
        $theUser = new Person;
        $theUser->scenario = 'create';
        $theCaseStats = new KaseStats;
        $theCaseStats->scenario = 'cases/request';
        if ($from == 'inquiry') {
            $theInquiry = Inquiry::find()->with(['site'])->where(['id'=>$id])->asArray()->one();
            if (!$theInquiry) {
                throw new HttpException(404, 'Inquiry not found.');
            }

            if ($theInquiry['case_id'] != 0) {
                throw new HttpException(403, 'Inquiry has been linked to a case. Unlink it first.');
            }
            if ($uid > 0) {
                $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.gender, u.country_code, u.email FROM persons u WHERE u.id =:uid GROUP BY u.id ORDER BY u.fname, u.lname';
                $theUser = Person::findBySql($sql, [':uid' => $uid])->one();
                if (!$theUser) {
                    throw new HttpException(404, 'User not found.');
                }
                $theUser->scenario = 'update';
            }
        } elseif ($from == 'mail') {
            $theMail = Mail::find()->where(['id'=>$id])->asArray()->one();
            if (!$theMail) {
                throw new HttpException(404, 'Email not found.');
            }
            if ($theMail['case_id'] != 0) {
                throw new HttpException(403, 'Mail has been linked to a case. Unlink it first.');
            }
            if ($uid > 0) {
                $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.gender, u.country_code, u.email, u.phone FROM persons u WHERE u.id =:uid GROUP BY u.id ORDER BY u.fname, u.lname';
                $theUser = Person::findBySql($sql, [':uid' => $uid])->one();
                if (!$theUser) {
                    throw new HttpException(404, 'User not found.');
                }
                $theUser->scenario = 'update';
            }
        }
        $theCase = new Kase;
        $theCase->scenario = 'kase/c';

        $theCase->language = 'fr';
        $theCase->is_b2b = 'no';
        $theCase->is_priority = 'no';
        $theCase->company_id = 0;
        $theCase->owner_id = USER_ID;

        $theCase->created_at = NOW;
        $theCase->created_by = USER_ID;
        $theCase->updated_at = NOW;
        $theCase->updated_by = USER_ID;
        $theCase->status = 'open';
        $theCase->ao = NOW;
        $theCase->how_found = 'new/nref/web';



        if ($theInquiry) {
            $inquiryData = unserialize($theInquiry['data']);
            $theCase->how_contacted = 'web';
            if (isset($inquiryData['fname'], $inquiryData['lname'])) {
                if (isset($inquiryData['country']) && in_array($inquiryData['country'], ['vn', 'la', 'kh', 'cn'])) {
                    $theCase->name = ucwords(strtolower($inquiryData['fname'].' '.$inquiryData['lname']));
                } else {
                    $theCase->name = ucwords(strtolower($inquiryData['lname'].' '.$inquiryData['fname']));
                }
            } else {
                $theCase->name = ucwords(strtolower($theInquiry['name']));
            }
            if ($inquiryData['phone'] != '') {
                $theUser['phone'] = $inquiryData['phone'];
            }
        } elseif ($theMail) {
            $theCase->how_contacted = 'email';
            $theCase->how_found = '';
            $theCase->name = $theMail['from'];
        }
        if (!$theUser->id) {
            $theUser->id = 0;
        }
        if ($theCase->load(Yii::$app->request->post())
            && $theUser->load(Yii::$app->request->post())
            && $theCaseStats->load(Yii::$app->request->post())
            && $theCase->validate()
            // && $theUser->validate()
            && $theCaseStats->validate()
            )
        {


            if (substr($theCase['how_found'], 0, 7) != 'new/ref') {
                $theCase['ref'] = 0;
            }
            if ($theCase['how_contacted'] != 'agent') {
                //$theCase['company_id'] = 0;
            }
            $theCase->save();
            // $theCaseLink = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];


            if ($theUser->id == 0) {
                $theUser->scenario = 'create';
                $theUser->id == null;
                $theUser->created_at = NOW;
                $theUser->created_by = MY_ID;
                $theUser->updated_at = NOW;
                $theUser->updated_by = MY_ID;
                $theUser->status = 'on';
                $theUser->fname = $theUser->fname != '' ? $theUser->fname : '-';
                $theUser->lname = $theUser->lname != '' ? $theUser->lname : '-';
                $theUser->name = $theUser->fname.' '.$theUser->lname;
                $theUser->phone = $theUser->phone != '' ? $theUser->phone : '-';
                $theUser->language = 'fr';
                $theUser->timezone = 'UTC';
                if ($theUser->save()) {
                    // Search
                    /*
                    $search = new Search;
                    $search->rtype = 'user';
                    $search->id = $theUser->id;
                    $search->search = strtolower(trim($theUser->fname.$theUser->lname.' '.$theUser->email.' '.$theUser->phone));
                    $search->found = trim($theUser->name.' '.$theUser->email.' '.$theUser->phone);
                    $search->save();
                    */
                    // Meta email
                    $meta = new Meta;
                    $meta->rtype = 'user';
                    $meta->rid = $theUser->id;
                    $meta->k = 'email';
                    $meta->v = $theInquiry['email'];
                    $meta->save();
                    // Meta phone
                    if ($theUser->phone != '') {
                        $meta = new Meta;
                        $meta->rtype = 'user';
                        $meta->rid = $theUser->id;
                        $meta->k = 'tel';
                        $meta->v = $theUser->phone;
                        $meta->save();
                    }
                    // Search
                    Yii::$app->db->createCommand()->insert('at_search', [
                        'rtype'=>'user',
                        'rid'=>$theUser->id,
                        'search'=>\fURL::makeFriendly(trim($theUser->name.' '.$theUser->email.' '.$theUser->phone), ' '),
                        'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                    ])->execute();
                }
            } else {
                $theUser->scenario = 'update';
                $theUser->updated_at = NOW;
                $theUser->updated_by = MY_ID;
                $theUser->fname = $theUser->fname != '' ? $theUser->fname : '-';
                $theUser->lname = $theUser->lname != '' ? $theUser->lname : '-';
                $theUser->name = $theUser->fname.' '.$theUser->lname;
                $theUser->phone = $theUser->phone != '' ? $theUser->phone : '-';
                if ($theUser->save()) {
                    if ($theUser['email'] != '') {
                        // Meta email
                        $meta = new Meta;
                        $meta->rtype = 'user';
                        $meta->rid = $theUser->id;
                        $meta->k = 'email';
                        $meta->v = $theUser['email'];
                        $meta->save();
                    }
                    // Meta phone
                    if ($theUser->phone != '') {
                        $meta = new Meta;
                        $meta->rtype = 'user';
                        $meta->rid = $theUser->id;
                        $meta->k = 'tel';
                        $meta->v = $theUser->phone;
                        $meta->save();
                    }
                    // Search
                    Yii::$app->db->createCommand()->insert('at_search', [
                        'rtype'=>'user',
                        'rid'=>$theUser->id,
                        'search'=>\fURL::makeFriendly(trim($theUser->name.' '.$theUser->email.' '.$theUser->phone), ' '),
                        'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                    ])->execute();
                }
            }
            // Case stats
            $theCaseStats->updated_at = NOW;
            $theCaseStats->updated_by = USER_ID;
            $theCaseStats->case_id = $theCase['id'];
            $theCaseStats->save(false);

            // Insert case_user
            Yii::$app->db->createCommand()->insert('at_case_user', [
                'case_id' => $theCase['id'],
                'user_id' => $theUser['id'],
                'role'=>'contact',
            ])->execute();
            // Case search
            Yii::$app->db->createCommand()
                ->insert('at_search', [
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                    'search'=>$theCase['name'].' '.Inflector::slug($theCase['name'], ''),
                    'found'=>$theCase['name'],
                ])->execute();

            // Case ref
            if (substr($theCase['how_found'], 0, 7) == 'new/ref' && $theCase['ref'] != 0) {
                Yii::$app->db->createCommand()
                    ->insert('at_referrals', [
                        'created_at'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'draft',
                        'user_id'=>$theCase['ref'],
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            // Update from
            if ($from == 'inquiry') {
                // Save email
                $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                Yii::$app->db->createCommand($sql, [':email'=>$theUser['email'], ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                // Link inquiry to case
                Yii::$app->db->createCommand()->update('at_inquiries', ['case_id'=>$theCase['id']], ['id'=>$id])->execute();
            } elseif ($from == 'mail') {
                    $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                    Yii::$app->db->createCommand($sql, [':email'=>$theUser['email'], ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                    // Link mails with same address to case
                    Yii::$app->db->createCommand()->update('at_mails', ['case_id'=>$theCase['id']])->execute();
            }

            // // Email people
            // if ($theCase['owner_id'] != 0) {
            //     $theOwner = Person::find()
            //         ->where(['id'=>$theCase['owner_id']])
            //         ->asArray()
            //         ->one();
            //     // User may not exist
            //     if (!$theOwner) {
            //         throw new HttpException(404, 'Case owner not found.');                  
            //     }

                // sys note
                Yii::$app->db->createCommand()
                    ->insert('at_sysnotes', [
                        'created_at'=>NOW,
                        'user_id'=>USER_ID,
                        'action'=>'kase/assign',
                        'rtype'=>'case',
                        'rid'=>$theCase['owner_id'],
                        'uri'=>URI,
                        'ip'=>USER_IP,
                        'info'=>$theOwner['id'],
                    ])
                    ->execute();

            //     // Email owner
            //     if (USER_ID != $theOwner['id']) {
            //         $this->mgIt(
            //             'Case "'.$theCase['name'].'" has been assigned to you',
            //             '//mg/cases_assign',
            //             [
            //                 'theCase'=>$theCase,
            //             ],
            //             [
            //                 ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //                 ['to', $theOwner['email'], $theOwner['lname'], $theOwner['fname']],
            //                 // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //             ]
            //         );
            //     }
            // } // if case owner id
            // // Email people
            // if ($theCase['cofr'] == 13) {
            //     $this->mgIt(
            //         'ims | Case "'.$theCase['name'].'" has been assigned to you',
            //         '//mg/cases_assign',
            //         [
            //             'theCase'=>$theCase,
            //         ],
            //         [
            //             ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //             ['to', 'bearez.hoa@amicatravel.com', 'Hoa', 'Bearez'],
            //             // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //         ]
            //     );
            // }
            // // Email people
            // if ($theCase['cofr'] == 5246) {
            //     $this->mgIt(
            //         'ims | Case "'.$theCase['name'].'" has been assigned to you',
            //         '//mg/cases_assign',
            //         [
            //             'theCase'=>$theCase,
            //         ],
            //         [
            //             ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //             ['to', 'arnaud.l@amicatravel.com', 'Arnaud', 'Levallet'],
            //             // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //         ]
            //     );
            // }
            // // Email people
            // if ($theCase['cofr'] == 767) {
            //     $this->mgIt(
            //         'ims | Case "'.$theCase['name'].'" has been assigned to you',
            //         '//mg/cases_assign',
            //         [
            //             'theCase'=>$theCase,
            //         ],
            //         [
            //             ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //             ['to', 'vuong.xuan@amicatravel.com', 'Xuan', 'Vuong'],
            //             // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //         ]
            //     );
            // }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $ownerList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $cofrList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes', 'id'=>[13, 5246, 767]])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $campaignList = Campaign::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        // $caseStats = KaseStats::find()
        //     ->where(['case_id'=>$theCase['id']])
        //     ->one();
        // var_dump($caseStats);die();
        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        $countrys = Country::find()
            ->select(['code', 'name_en'])
            // ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        return $this->render('kase_u', [
            'theCase'=>$theCase,
            'theUser' => $theUser,
            'theCaseStats' => $theCaseStats,
            'ownerList'=>$ownerList,
            'cofrList'=>$cofrList,
            'companyList'=>$companyList,
            'campaignList'=>$campaignList,
            'theInquiry'=>$theInquiry,
            'theMail'=>$theMail,
            'countryList' => $countryList,
            'countrys' => $countrys,

        ]);
    }

    public function actionR($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with([
                'owner',
                'people',
                'people.cases'=>function($q){
                    return $q->select(['id', 'created_at', 'status', 'name', 'deal_status', 'owner_id'])
                        ->orderBy('created_at DESC')
                        ->limit(5);
                },
                'people.cases.owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'people.bookings',
                'people.bookings.product',
                //'people.tours'=>function($q) {
                    //return $q->select(['id', 'code']);
                //},
                'company',
                'createdBy',
                'updatedBy',
                'referrer',
                'stats',
                'tasks'=>function($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tasks.assignees'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tasks.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'files',
                'bookings'=>function($q) {
                    return $q->orderBy('updated_at DESC');
                },
                'bookings.payments'=>function($q) {
                    return $q->orderBy('payment_dt');
                },
                'bookings.createdBy'=>function($q) {
                    return $q->select('id, image');
                },
                'bookings.product'=>function($q){
                    return $q->select(['id', 'title', 'day_ids', 'day_from', 'day_count']);
                },
                'bookings.product.tour'=>function($q){
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'bookings.product.days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
            ])
            ->asArray()
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        // Remove email mapping
        if (isset($_GET['action']) && $_GET['action'] == 'remove-email' && isset($_GET['email']) && in_array(USER_ID, [1, 4432, 26435, $theCase['owner_id']])) {
            $sql = 'DELETE FROM at_email_mapping WHERE case_id=:k AND email=:e';
            Yii::$app->db->createCommand($sql, [':k'=>$theCase['id'], ':e'=>$_GET['email']])->execute();
            return $this->redirect(DIR.URI);
        }

        // Add email mapping
        if (isset($_POST['email'])) {
            // Not owner
            if (!in_array(USER_ID, [1, 4432, 26435, $theCase['owner_id']])) {
                throw new HttpException(403, 'Access denied.');
            }
            // Not open
            if ($theCase['status'] == 'closed') {
                //throw new HttpException(403, 'Case is not open.');
            }
            // Good to go
            $email = trim(strtolower($_POST['email']));
            $validator = new EmailValidator();
            if ($validator->validate($email, $error)) {
                // Save email
                // HS dong thi them email nhung khong them email rule
                if ($theCase['status'] != 'closed') {
                    $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                    Yii::$app->db->createCommand($sql, [':email'=>$email, ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                }
                // Add current
                $sql = 'UPDATE at_mails SET case_id=:case_id WHERE case_id=0 AND (from_email=:email OR to_email=:email)';
                Yii::$app->db->createCommand($sql, [':email'=>$email, ':case_id'=>$theCase['id']])->execute();
                // Return
                return $this->redirect('@web/cases/r/'.$theCase['id']);
            } else {
                throw new HttpException(403, $error);
            }
        }

        $thePeople = Person::find()
            ->select(['id', 'name', 'fname', 'lname', 'email', 'nickname'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        if (isset($_POST['body'])) {
            $utag = false;
            $itag = false;
            $title = isset($_POST['title']) ? trim($_POST['title']): '';
            $body = $_POST['body'];

            if (strpos($title, '#urgent') !== false) {
                $utag = true;
                $title = str_replace('#urgent', '', $title);
            }
            if (strpos($title, '#important') !== false) {
                $itag = true;
                $title = str_replace('#important', '', $title);
            }

            $title = trim($title);
            $title = trim($title, '#');


            // Name mentions
            $toList = [];
            $toEmailList = [];
            $toIdList = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@['.$person['nickname'].']';
                    if (strpos($_POST['to'], $mention) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[] = $person['email'];
                        $toIdList[] = $person['id'];
                    }
                }
                foreach ($theCase['people'] as $person) {
                    $fromEmail = 'from:'.$person['email'];
                    $toEmail = 'to:'.$person['email'];
                    if (strpos($_POST['to'], $fromEmail) !== false) {
                        $noteFromId = $person['id'];
                        $noteToId = USER_ID;
                        $noteViaEmail = true;
                    } elseif (strpos($_POST['to'], $toEmail) !== false) {
                        $noteFromId = USER_ID;
                        $noteToId = $person['id'];
                        $noteViaEmail = true;
                    }
                }
            }


            /* OLD
            foreach ($thePeople as $person) {
                $mention = '@['.$person['name'].']';
                if (strpos($body, $mention) !== false) {
                    $body = str_replace($mention, '@'.Html::a($person['name'], 'https://my.amicatravel.com/users/r/'.$person['id']), $body);
                    $_POST['body'] = str_replace($mention, '@[user-'.$person['id'].']', $_POST['body']);
                    $toEmailList[] = $person['email'];
                    $toIdList[] = $person['id'];
                }
            }
            */
            $toEmailList = array_unique($toEmailList);

          // \fCore::expose($title);
          //   \fCore::expose($body);
          //   \fCore::expose($toEmailList);
          //   exit;

            // Save note

            define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));

            $theNote = new Note;
            $theNote->scenario = 'notes_c';

            $theNote->co = NOW;
            $theNote->cb = USER_ID;
            $theNote->uo = NOW;
            $theNote->ub = USER_ID;
            $theNote->status = 'on';
            $theNote->via = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
            $theNote->priority = 'A1';
            if ($itag) {
                $theNote->priority = 'C1';
            }
            if ($utag) {
                $theNote->priority = 'A3';
            }
            $theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : USER_ID;
            $theNote->m_to = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
            $theNote->title = $title;
            $theNote->body = $_POST['body'];
            $theNote->rtype = 'case';
            $theNote->rid = $theCase['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }


            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
            }

            $relUrl = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];
            $relName = $theCase['name'];

            // Upload files
            $fileList = '';
            if (isset($_POST['fileid']) && isset($_POST['filename']) && is_array($_POST['fileid']) && is_array($_POST['filename']) &&  count($_POST['fileid']) == count($_POST['filename'])) {
                foreach ($_POST['fileid'] as $i => $fileId) {
                    $newFileName = $_POST['filename'][$i];
                    $rawFileExt = strrchr($newFileName, '.');
                    $rawFileName = $fileId.$rawFileExt;
                    $rawFilePath = Yii::getAlias('@webroot').'/assets/plupload_2.1.9/'.$rawFileName;
                    if (file_exists($rawFilePath)) {
                        $fileUid = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($rawFilePath);
                        $imgSize = @getimagesize($rawFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0].'×'.$imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }
                        Yii::$app->db->createCommand()
                            ->insert('at_files', [
                                'co'=>ICT,
                                'cb'=>USER_ID,
                                'uo'=>ICT,
                                'ub'=>USER_ID,
                                'name'=>$newFileName,
                                'ext'=>$rawFileExt,
                                'size'=>$fileSize,
                                'img_size'=>$fileImgSize,
                                'uid'=>$fileUid,
                                'filegroup_id'=>1,
                                'rtype'=>'case',
                                'rid'=>$theCase['id'],
                                'n_id'=>$theNote['id'],
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $newDir = Yii::getAlias('@webroot').'/upload/user-files/'.substr(ICT, 0, 7).'/';
                        @mkdir($newDir);

                        // New name
                        $newName = 'file-'.USER_ID.'-'.$newFileId.'-'.$fileUid;

                        // Move upload file to new (official) location
                        if (copy($rawFilePath, $newDir.$newName)) {
                            unlink($rawFilePath);
                            $fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/'.$newFileId.'">'.$newFileName.'</a>';
                            //echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
                        } else {
                        Yii::$app->db->createCommand()
                            ->delete('at_files', [
                                'id'=>$newFileId,
                            ])
                            ->execute();
                        }
                    }
                }
            }

            if ($fileList != '') {
                $body = $fileList.'<br>'.$body;
            }

            // Send email

            if (!empty($toEmailList)) {
                $subject = $title;
                if ($itag) {
                    $subject = '#important '.$subject;
                }
                if ($utag) {
                    $subject = '#urgent '.$subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Case: '.$relName;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    //['reply-to', Yii::$app->user->identity->email],
                    ['reply-to', 'msg-'.$theNote->id.'-'.USER_ID.'@amicatravel.com'],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($toList as $id=>$user) {
                    $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
                }
                $this->mgIt(
                    $subject,
                    '//mg/note_added',
                    [
                        'toList'=>$toList,
                        'theNote'=>$theNote,
                        'relUrl'=>$relUrl,
                        'body'=>$body,
                    ],
                    $args
                );
            }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id'=>$theCase['id']])
            ->asArray()
            ->all();

        $theCaseOwner = Person::find()->where(['id'=>$theCase['owner_id']])->one();

        if (Yii::$app->request->get('allnotes') == 'yes') {
            $tourIdList = [];
            foreach ($theCase['bookings'] as $booking) {
                if (isset($booking['product']['tour'])) {
                    $tourIdList[] = $booking['product']['tour']['id'];
                }
            }
            $theNotes = Note::find()
                ->where(['rtype'=>'case', 'rid'=>$id])
                ->orWhere(['rtype'=>'tour', 'rid'=>$tourIdList])
                ->with(['from', 'to'])
                ->orderBy('co DESC')
                ->all();
        } else {
            $theNotes = Note::find()
                ->where(['rtype'=>'case', 'rid'=>$id])
                ->with(['from', 'to'])
                ->orderBy('co DESC')
                ->all();
        }

        $caseInquiries = Inquiry::find()
            ->where(['case_id'=>$id])
            ->with(['site'])
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            ->with([
                'user'=>function($q) {
                    return $q->select(['id', 'nickname', 'name', 'image']);
                }
            ])
            ->asArray()
            ->all();

        // Email mapping: emails for auto-import
        $sql = 'SELECT email FROM at_email_mapping WHERE case_id=:id ORDER BY email';
        $theEmails = Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->queryColumn();

        $allCountries = Country::find()->select([
            'code',
            'dial_code',
            'name'=>'CONCAT(name_en, " (", name_vi, ")")',
            'name_vi',
            ])->asArray()->all();
        
        return $this->render('kase_r', [
            'theCase'=>$theCase,
            'theCaseOwner'=>$theCaseOwner,
            'caseInquiries'=>$caseInquiries,
            'theSysnotes'=>$theSysnotes,
            'theNotes'=>$theNotes,
            'inboxMails'=>$inboxMails,
            'thePeople'=>$thePeople,
            'theEmails'=>$theEmails,
            'allCountries'=>$allCountries,
        ]);
    }

    public function actionR2($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with([
                'owner',
                'people',
                'people.cases'=>function($q){
                    return $q->select(['id', 'created_at', 'status', 'name', 'deal_status', 'owner_id'])
                        ->orderBy('created_at DESC')
                        ->limit(5);
                },
                'people.cases.owner'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'people.bookings',
                'people.bookings.product',
                //'people.tours'=>function($q) {
                    //return $q->select(['id', 'code']);
                //},
                'company',
                'createdBy',
                'updatedBy',
                'referrer',
                'stats',
                'tasks'=>function($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tasks.assignees',
                'files',
                'bookings'=>function($q) {
                    return $q->orderBy('updated_at DESC');
                },
                'bookings.payments'=>function($q) {
                    return $q->orderBy('payment_dt');
                },
                'bookings.createdBy'=>function($q) {
                    return $q->select('id, image');
                },
                'bookings.product'=>function($q){
                    return $q->select(['id', 'title', 'day_ids', 'day_from', 'day_count']);
                },
                'bookings.product.tour'=>function($q){
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'bookings.product.days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
            ])
            ->asArray()
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        // Add email mapping
        if (isset($_POST['email'])) {
            // Not owner
            if (!in_array(USER_ID, [1, 4432, $theCase['owner_id']])) {
                throw new HttpException(403, 'Access denied.');
            }
            // Not open
            if ($theCase['status'] == 'closed') {
                throw new HttpException(403, 'Case is not open.');
            }
            // Good to go
            $email = trim(strtolower($_POST['email']));
            $validator = new EmailValidator();
            if ($validator->validate($email, $error)) {
                // Save email
                $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                Yii::$app->db->createCommand($sql, [':email'=>$email, ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                // Add current
                $sql = 'UPDATE at_mails SET case_id=:case_id WHERE case_id=0 AND (from_email=:email OR to_email=:email)';
                Yii::$app->db->createCommand($sql, [':email'=>$email, ':case_id'=>$theCase['id']])->execute();
                // Return
                return $this->redirect('@web/cases/r/'.$theCase['id']);
            } else {
                throw new HttpException(403, $error);
            }
        }

        $thePeople = Person::find()
            ->select(['id', 'name', 'fname', 'lname', 'email'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        if (isset($_POST['body'])) {
            $utag = false;
            $itag = false;
            $title = isset($_POST['title']) ? trim($_POST['title']): '';
            $body = $_POST['body'];

            if (strpos($title, '#urgent') !== false) {
                $utag = true;
                $title = str_replace('#urgent', '', $title);
            }
            if (strpos($title, '#important') !== false) {
                $itag = true;
                $title = str_replace('#important', '', $title);
            }

            $title = trim($title);
            $title = trim($title, '#');


            // Name mentions
            $toList = [];
            $toEmailList = [];
            $toIdList = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@['.$person['name'].']';
                    if (strpos($_POST['to'], $mention) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[] = $person['email'];
                        $toIdList[] = $person['id'];
                    }
                }
                foreach ($theCase['people'] as $person) {
                    $fromEmail = 'from:'.$person['email'];
                    $toEmail = 'to:'.$person['email'];
                    if (strpos($_POST['to'], $fromEmail) !== false) {
                        $noteFromId = $person['id'];
                        $noteToId = USER_ID;
                        $noteViaEmail = true;
                    } elseif (strpos($_POST['to'], $toEmail) !== false) {
                        $noteFromId = USER_ID;
                        $noteToId = $person['id'];
                        $noteViaEmail = true;
                    }
                }
            }
            /* OLD
            foreach ($thePeople as $person) {
                $mention = '@['.$person['name'].']';
                if (strpos($body, $mention) !== false) {
                    $body = str_replace($mention, '@'.Html::a($person['name'], 'https://my.amicatravel.com/users/r/'.$person['id']), $body);
                    $_POST['body'] = str_replace($mention, '@[user-'.$person['id'].']', $_POST['body']);
                    $toEmailList[] = $person['email'];
                    $toIdList[] = $person['id'];
                }
            }
            */
            $toEmailList = array_unique($toEmailList);

            /*          \fCore::expose($title);
                        \fCore::expose($body);
                        \fCore::expose($toEmailList);
                        exit;
            */
            // Save note

            define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));

            $theNote = new Note;
            $theNote->scenario = 'notes_c';

            $theNote->co = NOW;
            $theNote->cb = USER_ID;
            $theNote->uo = NOW;
            $theNote->ub = USER_ID;
            $theNote->status = 'on';
            $theNote->via = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
            $theNote->priority = 'A1';
            if ($itag) {
                $theNote->priority = 'C1';
            }
            if ($utag) {
                $theNote->priority = 'A3';
            }
            $theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : USER_ID;
            $theNote->m_to = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
            $theNote->title = $title;
            $theNote->body = $_POST['body'];
            $theNote->rtype = 'case';
            $theNote->rid = $theCase['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }


            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_messages_to', ['n_id', 'user_id'], $nTo)->execute();
            }

            $relUrl = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];
            $relName = $theCase['name'];

            // Upload files
            $fileList = '';
            if (isset($_POST['fileid']) && isset($_POST['filename']) && is_array($_POST['fileid']) && is_array($_POST['filename']) &&  count($_POST['fileid']) == count($_POST['filename'])) {
                foreach ($_POST['fileid'] as $i => $fileId) {
                    $newFileName = $_POST['filename'][$i];
                    $rawFileExt = strrchr($newFileName, '.');
                    $rawFileName = $fileId.$rawFileExt;
                    $rawFilePath = Yii::getAlias('@webroot').'/assets/plupload_2.1.9/'.$rawFileName;
                    if (file_exists($rawFilePath)) {
                        $fileUid = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($rawFilePath);
                        $imgSize = @getimagesize($rawFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0].'×'.$imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }
                        Yii::$app->db->createCommand()
                            ->insert('at_files', [
                                'co'=>ICT,
                                'cb'=>USER_ID,
                                'uo'=>ICT,
                                'ub'=>USER_ID,
                                'name'=>$newFileName,
                                'ext'=>$rawFileExt,
                                'size'=>$fileSize,
                                'img_size'=>$fileImgSize,
                                'uid'=>$fileUid,
                                'filegroup_id'=>1,
                                'rtype'=>'case',
                                'rid'=>$theCase['id'],
                                'n_id'=>$theNote['id'],
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $newDir = Yii::getAlias('@webroot').'/upload/user-files/'.substr(ICT, 0, 7).'/';
                        @mkdir($newDir);

                        // New name
                        $newName = 'file-'.USER_ID.'-'.$newFileId.'-'.$fileUid;

                        // Move upload file to new (official) location
                        if (copy($rawFilePath, $newDir.$newName)) {
                            unlink($rawFilePath);
                            $fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/'.$newFileId.'">'.$newFileName.'</a>';
                            //echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
                        } else {
                        Yii::$app->db->createCommand()
                            ->delete('at_files', [
                                'id'=>$newFileId,
                            ])
                            ->execute();
                        }
                    }
                }
            }

            if ($fileList != '') {
                $body = $fileList.'<br>'.$body;
            }

            // Send email

            if (!empty($toEmailList)) {
                $subject = $title;
                if ($itag) {
                    $subject = '#important '.$subject;
                }
                if ($utag) {
                    $subject = '#urgent '.$subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Case: '.$relName;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->name, 'Amica Travel'],
                    ['reply-to', Yii::$app->user->identity->email],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($toList as $id=>$user) {
                    $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
                }
                $this->mgIt(
                    'ims | '.$subject,
                    '//mg/note_added',
                    [
                        'toList'=>$toList,
                        'theNote'=>$theNote,
                        'relUrl'=>$relUrl,
                        'body'=>$body,
                    ],
                    $args
                );
            }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id'=>$theCase['id']])
            ->asArray()
            ->all();

        $theCaseOwner = Person::find()->where(['id'=>$theCase['owner_id']])->one();
        $theNotes = Note::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            ->with(['from', 'to'])
            ->orderBy('co DESC')
            ->all();

        $caseInquiries = Inquiry::find()
            ->where(['case_id'=>$id])
            ->with(['site'])
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            ->with([
                'user'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name']);
                }
            ])
            ->asArray()
            ->all();

        // Email mapping: emails for auto-import
        $sql = 'SELECT email FROM at_email_mapping WHERE case_id=:id ORDER BY email';
        $theEmails = Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->queryColumn();

        $allCountries = Country::find()->select('code', 'name_vi')->asArray()->all();
        
        if (USER_ID == 111) {
        return $this->render('cases_r_huan', [
            'theCase'=>$theCase,
            'theCaseOwner'=>$theCaseOwner,
            'caseInquiries'=>$caseInquiries,
            'theSysnotes'=>$theSysnotes,
            'theNotes'=>$theNotes,
            'inboxMails'=>$inboxMails,
            'thePeople'=>$thePeople,
            'theEmails'=>$theEmails,
            'allCountries'=>$allCountries,
        ]);
        }
        return $this->render('cases_r2', [
            'theCase'=>$theCase,
            'theCaseOwner'=>$theCaseOwner,
            'caseInquiries'=>$caseInquiries,
            'theSysnotes'=>$theSysnotes,
            'theNotes'=>$theNotes,
            'inboxMails'=>$inboxMails,
            'thePeople'=>$thePeople,
            'theEmails'=>$theEmails,
            'allCountries'=>$allCountries,
        ]);
    }

    public function actionU($id = 0)
    {
        $theCase = Kase::find()
            ->with([
                'cperson'=> function($q) {
                    return $q->select('id, fname, lname, name, gender, country_code');
                }
            ])
            ->where(['id' => $id])->one();
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }
        $theCaseStats = KaseStats::findOne($theCase->id);
        if (!$theCaseStats) {
            throw new HttpException(404, 'Case request not found');
        }
        if ($theCase['is_b2b'] == 'yes') {
            return $this->redirect('/b2b/cases/u/'.$theCase['id']);
            exit;
        }

        // 140723: PhAnh và ThyNN edit nguon
        // 160616: Added Megan JB,
        // 160816: Added Hoa NTT,
        if (in_array(USER_ID, [695, 14671, 27510])) {
            return $this->redirect('@web/cases/upa/'.$id);
        }

        if (!in_array(USER_ID, [34718, 1,2,3,4, 4432, 11724, 36654, 26435, 35887, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');         
        }

        $oldOwnerId = $theCase['owner_id'];
        $oldRef = $theCase['ref'];
        $oldCofr = $theCase['cofr'];

        $theCase->scenario = 'kase/u';
        // $theCaseStats->scenario = 'cases/request';

        if ($theCase->load(Yii::$app->request->post())
            && $theCase->validate()
            // && $theCaseStats->load(Yii::$app->request->post())
            // && $theCaseStats->validate()
        ) {
            if (substr($theCase['how_found'], 0, 7) != 'new/ref') {
                $theCase['ref'] = 0;
            }
            if ($theCase['how_contacted'] != 'agent') {
                // $theCase['company_id'] = 0;
            }
            $theCase->updated_at = NOW;
            $theCase->updated_by = USER_ID;
            $theCase->save(false);
            // $theCaseStats->save(false);
            // Update search
            Yii::$app->db->createCommand()
                ->update('at_search', ['search'=>$theCase['name'].' '.Inflector::slug($theCase['name'], ''), 'found'=>$theCase['name']], ['rtype'=>'case', 'rid'=>$theCase['id']])
                ->execute();

            $theCaseLink = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];
    
            // Case search
            Yii::$app->db->createCommand()
                ->update('at_search', [
                    'search'=>$theCase['name'].' '.Inflector::slug($theCase['name'], ''),
                    'found'=>$theCase['name'],
                ], [
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                ]
                )->execute();

            // Case ref
            if ($theCase['ref'] != $oldRef  && $oldRef != 0) {
                Yii::$app->db->createCommand()
                    ->delete('at_referrals', [
                        'user_id'=>$oldRef,
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            if (substr($theCase['how_found'], 0, 7) == 'new/ref' && $theCase['ref'] != 0  && $theCase['ref'] != $oldRef) {
                Yii::$app->db->createCommand()
                    ->insert('at_referrals', [
                        'created_at'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'draft',
                        'user_id'=>$theCase['ref'],
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            // Email people
            if ($theCase['owner_id'] != 0 && $theCase['owner_id'] != $oldOwnerId) {
                // Owner may not exist
                $theOwner = Person::find()
                    ->where(['id'=>$theCase['owner_id']])
                    ->one();
                if (!$theOwner) {
                    throw new HttpException(404, 'Case owner not found.');
                }
                // New save
                $theCase->ao = NOW;
                $theCase->save(false);

                Yii::$app->db->createCommand()
                    ->insert('at_sysnotes', [
                        'created_at'=>NOW,
                        'user_id'=>USER_ID,
                        'action'=>'kase/assign',
                        'rtype'=>'case',
                        'rid'=>$theCase['id'],
                        'uri'=>URI,
                        'ip'=>USER_IP,
                        'info'=>$theOwner['id'],
                    ])
                    ->execute();

                // Email owner
                if (USER_ID != $theOwner['id']) {
                    $this->mgIt(
                        'ims | Case "'.$theCase['name'].'" has been assigned to you',
                        '//mg/cases_assign',
                        [
                            'theCase'=>$theCase,
                        ],
                        [
                            ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                            ['to', $theOwner['email'], $theOwner['lname'], $theOwner['fname']],
                            // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                        ]
                    );
                }
            }
            // Email people
            if ($theCase['cofr'] == 13 && 13 != $oldCofr) {
                $this->mgIt(
                    'ims | Case "'.$theCase['name'].'" has been assigned to you',
                    '//mg/cases_assign',
                    [
                        'theCase'=>$theCase,
                    ],
                    [
                        ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                        ['to', 'bearez.hoa@amicatravel.com', 'Hoa', 'Bearez'],
                        // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                    ]
                );
            }
            // Email people, Arnaud L
            if ($theCase['cofr'] == 5246 && 5246 != $oldCofr) {
                $this->mgIt(
                    'ims | Case "'.$theCase['name'].'" has been assigned to you',
                    '//mg/cases_assign',
                    [
                        'theCase'=>$theCase,
                    ],
                    [
                        ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                        ['to', 'arnaud.l@amicatravel.com', 'Arnaud', 'Levallet'],
                        // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                    ]
                );
            }
            // Email people, Xuan V
            if ($theCase['cofr'] == 767 && 767 != $oldCofr) {
                $this->mgIt(
                    'ims | Case "'.$theCase['name'].'" has been assigned to you',
                    '//mg/cases_assign',
                    [
                        'theCase'=>$theCase,
                    ],
                    [
                        ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                        ['to', 'vuong.xuan@amicatravel.com', 'Xuan', 'Vuong'],
                        // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                    ]
                );
            }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }


        $ownerList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orWhere(['id'=>$theCase['id']])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $cofrList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes', 'id'=>[13, 5246, 767, $theCase['cofr']]])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $campaignList = Campaign::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();
        // var_dump($caseStats);die();
        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('kase_u', [
            'theCase'=>$theCase,
            'ownerList'=>$ownerList,
            'cofrList'=>$cofrList,
            'companyList'=>$companyList,
            'campaignList'=>$campaignList,
            // 'theCaseStats' => $theCaseStats,
            // 'countryList' => $countryList,
        ]);
    }

    // 140723: PhAnh và ThyNN edit nguon HS 
    public function actionUpa($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        // 150921 Added Sophie N
        // 160614 Added Megan JB
        if (!in_array(USER_ID, [1,695,14671,33776,27510])) {
            throw new HttpException(403, 'Access denied.');         
        }

        $oldOwnerId = $theCase['owner_id'];
        $oldRef = $theCase['ref'];
        $oldCofr = $theCase['cofr'];

        $theCase->scenario = 'kase/upa';

        if ($theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
            if (substr($theCase['how_found'], 0,7) != 'new/ref') {
                $theCase['ref'] = 0;
            }
            if ($theCase['how_contacted'] != 'agent') {
                // $theCase['company_id'] = 0;
            }
            $theCase->updated_at = NOW;
            $theCase->updated_by = USER_ID;
            $theCase->save(false);

            // Case ref
            if ($theCase['ref'] != $oldRef  && $oldRef != 0) {
                Yii::$app->db->createCommand()
                    ->delete('at_referrals', [
                        'user_id'=>$oldRef,
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            if (substr($theCase['how_found'], 0, 7) == 'new/ref' && $theCase['ref'] != 0  && $theCase['ref'] != $oldRef) {
                Yii::$app->db->createCommand()
                    ->insert('at_referrals', [
                        'created_at'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'draft',
                        'user_id'=>$theCase['ref'],
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            return $this->redirect('@web/cases');
        }

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $campaignList = Campaign::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('kase_upa', [
            'theCase'=>$theCase,
            'companyList'=>$companyList,
            'campaignList'=>$campaignList,
        ]);
    }

    // Put a case on hold
    public function actionHold($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            //->asArray()
            ->one();
        if (!in_array(USER_ID, [1, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] != 'open') {
            throw new HttpException(403, 'Case is not open.');
        }

        $theCase->status = 'onhold';
        $theCase->save(false);

        return $this->redirect('@web/cases/r/'.$theCase['id']);
    }

    // Put a case on hold
    public function actionUnhold($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            //->asArray()
            ->one();
        if (!in_array(USER_ID, [1, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] != 'onhold') {
            throw new HttpException(403, 'Case is not on hold.');
        }

        $theCase->status = 'open';
        $theCase->save(false);

        return $this->redirect('@web/cases/r/'.$theCase['id']);
    }

    // Close a case
    public function actionClose($id = 0)
    {
        $theCase = Kase::findOne($id);
        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');
        }

        if (!in_array(USER_ID, [1,2,3,4, 4432, 26435, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] == 'closed') {
            throw new HttpException(403, 'Case is already CLOSED.');
        }

        $theCase->scenario = 'kase/close';

        if ($theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
            $theCase->updated_at = NOW;
            $theCase->updated_by = USER_ID;
            $theCase->status = 'closed';
            if ($theCase->deal_status == 'pending') {
                $theCase->deal_status = 'lost';             
            }
            $theCase->status_date = NOW;
            $theCase->closed = NOW;
            $theCase->save(false);
            $this->mgIt(
                'ims | Case "'.$theCase['name'].'" was closed by '.Yii::$app->user->identity->name.' for reason: '.$theCase['why_closed'],
                '//mg/kase_close',
                [
                    'theCase'=>$theCase,
                ],
                [
                    ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                    ['to', 'ngo.hang@amica-travel.com', 'Hằng', 'Ngô'],
                ]
            );
            Yii::$app->db->createCommand()
                ->insert('at_sysnotes', [
                    'created_at'=>NOW,
                    'user_id'=>USER_ID,
                    'action'=>'kase/close',
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                    'uri'=>URI,
                    'ip'=>isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Yii::$app->request->getUserIP(),
                    'info'=>$theCase['closed_note'].' : '.$theCase['why_closed'],
                ])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('at_email_mapping', ['case_id'=>$theCase['id']])
                ->execute();
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        return $this->render('kase_close', [
            'theCase'=>$theCase,
        ]);
    }

    // Reopen a case
    public function actionReopen($id = 0)
    {
        $theCase = Kase::findOne($id);
        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');
        }

        if (!in_array(USER_ID, [1,2,3,4, 4432, 26435, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] != 'closed' && $theCase['status'] != 'onhold') {
            throw new HttpException(403, 'Case is already OPEN');
        }

        if (Yii::$app->request->isPost && isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
            Yii::$app->db
                ->createCommand()
                ->update('at_cases', [
                    'updated_at'=>NOW,
                    'updated_by'=>USER_ID,
                    'status'=>'open',
                    'status_date'=>NOW,
                    // TODO: Neu HS da dong hon 6 thang thi ao= NOW,
                    'closed'=>'0000-00-00',
                    'deal_status'=>$theCase['deal_status'] == 'won' ? 'won' : 'pending',
                    ],[
                    'id'=>$id,
                    ])
                ->execute();
            Yii::$app->db->createCommand()
                ->insert('at_sysnotes', [
                    'created_at'=>NOW,
                    'user_id'=>USER_ID,
                    'action'=>'kase/reopen',
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                    'uri'=>URI,
                    'ip'=>isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Yii::$app->request->getUserIP(),
                    'info'=>'',
                ])
                ->execute();
            return Yii::$app->response->redirect('@web/cases/open');
        }
        return $this->render('cases_reopen', [
            'theCase'=>$theCase,
        ]);
    }

    // Delete case
    public function actionD($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        // Must be case owner
        if (!in_array(USER_ID, [34718, 1, 4432, 26435])) {
            throw new HttpException(403, 'Access denied');
        }

        // Do not delete case with existing booking
        $sql = 'SELECT COUNT(*) FROM  at_bookings WHERE case_id=:id';
        $cnt = Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->queryScalar();
        if ($cnt > 0) {
            throw new HttpException(403, 'Access denied: you must remove all bookings / proposals first.');
        }

        if (Yii::$app->request->post('confirm') == 'delete') {
            // Delete case user
            $sql = 'DELETE FROM at_case_user WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete tasks
            $sql = 'DELETE FROM at_tasks WHERE rtype="case" AND rid=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete inquiries
            $sql = 'DELETE FROM at_inquiries WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete mails
            $sql = 'DELETE FROM at_mails WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete notes
            $sql = 'DELETE FROM at_messages WHERE rtype="case" AND rid=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete files
            $sql = 'DELETE FROM at_files WHERE rtype="case" AND rid=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete bookings
            /*
            $sql = 'DELETE FROM at_bookings WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
             Delete booking user
            $sql = 'DELETE FROM at_booking_user WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
        */
            $theCase->delete();
            return $this->redirect('@web/cases');
        }

        return $this->render('cases_d', [
            'theCase'=>$theCase,
        ]);
    }


    // Giới thiệu Mme Xuân
    public function actionMmeXuan($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->one();

        if (!in_array(USER_ID, [1, 1097, $theCase['owner_id']])) {
            throw new HttpException(403, 'You are not the owner.');
        }

        if ($theCase['status'] == 'closed') {
            throw new HttpException(403, 'Case is CLOSED. You have to reopen this case first.');
        }

        if (!in_array($theCase['at_who'], [0, 767])) {
            throw new HttpException(403, 'Case has been assigned to a different person other than Mme Xuan.');
        }

        if ($theCase['at_who'] == 0) {
            $theCase->at_who = 767;
            $theCase->at_opened = NOW;
            $theCase->save(false);
        } else {
            $theCase->at_who = 0;
            $theCase->at_opened = 0;
            $theCase->at_closed = 0;
            $theCase->save(false);
        }

        return $this->redirect('@web/cases/r/'.$theCase['id']);
    }

    // Add or remove people
    public function actionPeople($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with(['people'])
            ->asArray()
            ->one();
        if ($theCase['status'] == 'closed') {
            //throw new HttpException(403, 'Case is closed');
        }
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }
        if (!in_array(USER_ID, [1, 4432, $theCase['owner_id']])) {
            // throw new HttpException(403, 'Access denied');
        }

        // Add a person
        if (Yii::$app->request->isPost && isset($_POST['action'], $_POST['user']) && $_POST['action'] == 'add') {
            $alreadyAdded = false;
            foreach ($theCase['people'] as $user) {
                if ($user['id'] == $_POST['user']) {
                    $alreadyAdded = true;
                    break;
                }
            }
            if (!$alreadyAdded) {
                $sql = 'INSERT INTO at_case_user (case_id, user_id, role) VALUES (:case_id, :user_id, "contact")';
                Yii::$app->db->createCommand($sql, [':case_id'=>$theCase['id'], ':user_id'=>$_POST['user']])->execute();
                return $this->redirect('@web/cases/r/'.$theCase['id']);
            }
        }

        // Remove a person
        if (isset($_GET['action'], $_GET['user']) && $_GET['action'] == 'remove') {
            $sql = 'DELETE FROM at_case_user WHERE case_id=:case_id AND user_id=:user_id';
            Yii::$app->db->createCommand($sql, [':case_id'=>$theCase['id'], ':user_id'=>$_GET['user']])->execute();
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        return $this->render('cases_people', [
            'theCase'=>$theCase,
        ]);
    }

    // Case request device
    public function actionUpdateCasesRequestDevice($year = 0)
    {
        if ($year == 0 || strlen($year) != 4) {
            $year = date('Y');
        }
        $theCases = Kase::find()
            ->select(['id', 'name'])
            ->where('SUBSTRING(created_at,1,4)=:y', [':y'=>$year])
            ->with([
                'stats',
                'inquiries'=>function($q) {
                    return $q->select(['ip', 'ua', 'case_id']);
                }
                ])
            ->asArray()
            ->all();
        foreach ($theCases as $case) {
            echo '<br>', $case['name'], ' : ';
            $device = 'none';
            foreach ($case['inquiries'] as $inquiry) {
                echo ' - UA: ', $inquiry['ua'];
                $detect = new \Mobile_Detect;
                $detect->setUserAgent($inquiry['ua']);
                $device = 'desktop';
                if ($detect->isMobile()) {
                    $device = 'mobile';
                }
                if ($detect->isTablet()) {
                    $device = 'tablet';
                }
            }
            echo ' --- <strong style="color:red">', $device, '</strong>';
            $sql = 'UPDATE at_case_stats SET request_device=:d WHERE case_id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [
                ':d'=>$device,
                ':id'=>$case['id']
                ])->execute();
        }
    }
}
