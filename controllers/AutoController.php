<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use common\models\Mail;
use common\models\Message;
use common\models\Note;
use common\models\Mail2;
use common\models\User;
use Mailgun\Mailgun;

class AutoController extends MyController
{
    public function behaviors() {
        return [
            'AccessControl' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions'=>['index', 'mailgun', 'mailgun2', 'mg-event', 'mg-reply'],
                        'allow'=>true,
                        //'roles'=>['?'],
                    ], [
                        'actions'=>['mailgun-test'],
                        'allow'=>true,
                        'roles'=>['@'],
                    ],
                ]
            ]
        ];
    }

    // Test incoming email
    public function actionMailgunTest($id = 1) {
        $mail = Mail::findOne($id);
        if (!$mail) {
            die('Not found');
        }
        /*
        $from = Yii::$app->request->post('From');
        $to = Yii::$app->request->post('To');
        $cc = Yii::$app->request->post('Cc');
        $bcc = Yii::$app->request->post('Bcc');
*/
        $from = $mail['from'];
        $to = $mail['to'];
        $cc = $mail['cc'];
        $bcc = $mail['bcc'];

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
            if (!in_array($address, $excludeList) && strpos($address, '@amicatravel.com') === false && strpos($address, '@amica-travel.com') === false) {
                $hasPax = true;
            }
        }

        if (in_array('ims@amicatravel.com', $addresses)) {
            $hasIms = true;
        }

        return $this->render('mailgun_test', [
            'mail'=>$mail,
            'addresses'=>$addresses,
            'hasPax'=>$hasPax,
            'hasIms'=>$hasIms,
        ]);
    }

    // Mailgun post: reply to message
    // 1 - Save to at_messages
    // 2 - Email senders
    public function actionMgReply() {
        // Must be posted from Mailgun
        if (!Yii::$app->request->isPost) {
            // die(0);
        }

        $from = Yii::$app->request->post('Sender', '');
        $to = Yii::$app->request->post('To', '');
        $cc = Yii::$app->request->post('Cc', '');
        $bcc = Yii::$app->request->post('Bcc', '');

        //$from = 'ngoc.pk@amicatravel.com';
        //$to = 'msg-379504-28319@amicatravel.com';

        // Find sender
        $fromEmail = str_replace('amica-travel.com', 'amicatravel.com', $from);
        $sql = 'SELECT rid FROM at_meta WHERE rtype="user" AND k="email" AND v=:email LIMIT 1';
        $senderUserId = Yii::$app->db->createCommand($sql, [':email'=>$fromEmail])->queryScalar();

        if (!$senderUserId) {
            die('SENDER USER ID NOT FOUND');
        }

        $theSender = User::find()
            ->where(['is_member'=>'yes', 'id'=>$senderUserId])
            ->asArray()
            ->one();
        if (!$theSender) {
            die('NO SENDER');
        }

        $toParts = explode('-', $to);

        $nId = $toParts[1] ?? 0;
        $uId = $toParts[2] ?? 0;

        // Orig message
        $theMessage = Message::find()
            ->select(['id', 'rtype', 'rid', 'n_id', 'from_id', 'co', 'cb', 'uo', 'ub', 'title'])
            ->where(['id'=>$nId])
            ->with([
                'from'=>function($q) {
                    return $q->select(['id', 'email', 'fname', 'lname']);
                },
                'to'=>function($q) {
                    return $q->select(['id', 'email', 'fname', 'lname']);
                },
                'replies'=>function($q) {
                    return $q->select(['id', 'rtype', 'rid', 'n_id', 'from_id', 'co', 'cb', 'uo', 'ub']);
                },
                'replies.updatedBy'=>function($q) {
                    return $q->select(['id', 'email', 'fname', 'lname']);
                },
                'replies.to'=>function($q) {
                    return $q->select(['id', 'email', 'fname', 'lname']);
                },
            ])
            ->asArray()
            ->one();
        if (!$theMessage) {
            die('NO MESSAGE');
        }

        $theOrigSender = User::find()
            ->where(['is_member'=>'yes', 'id'=>$uId])
            ->asArray()
            ->one();
        if (!$theOrigSender) {
            die('NO ORIG SENDER');
        }

        // People who will be notified when reply is posted
        $emailList = [$theOrigSender['email']];
        $nameList[$theOrigSender['email']] = $theOrigSender['lname'];
        foreach ($theMessage['replies'] as $reply) {
            $emailList[] = $reply['updatedBy']['email'];
            $nameList[$reply['updatedBy']['email']] = $reply['updatedBy']['lname'];
        }
        foreach ($theMessage['to'] as $to2) {
            $emailList[] = $to2['email'];
            $nameList[$to2['email']] = $to2['lname'];
        }
        $emailList = array_unique($emailList);

        $rType = $theMessage['rtype'];
        $rId = $theMessage['rid'];

        $msg = new Message;
        $msg->co = NOW;
        $msg->uo = NOW;
        $msg->cb = $theSender['id']; // Should be sender ID
        $msg->ub = $theSender['id'];
        $msg->status = 'on';
        $msg->via = 'email';
        $msg->from_id = $theSender['id']; // Should be sender ID
        $msg->title = '';
        $msg->body = $_POST['stripped-html'] ?? $_POST['stripped-text'] ?? '';
        $msg->rtype = $rType;
        $msg->rid = $rId;
        $msg->n_id = $nId;

        $msg->save(false);

        // TODO email
        if (!empty($emailList)) {
            $subject = 'RE: '.$theMessage['title'];

            $args = [
                ['from', 'notifications@amicatravel.com', $theSender['nickname'], ' on IMS'],
                ['reply-to', $to],
                ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            ];
            foreach ($emailList as $email) {
                if ($email != $theSender['email']) {
                    $args[] = ['to', $email, $nameList[$email] ?? ''];
                }
            }
            if ($theMessage['rtype'] == 'company') {
                $rType = 'companies/r';
            } else {
                $rType = $theMessage['rtype'].'s/r';
            }
            $this->mgIt(
                $subject,
                '//mg/note_added',
                [
                    'toList'=>[],
                    'theNote'=>$msg,
                    'relUrl'=>'https://my.amicatravel.com/'.$rType.'/'.$msg['rid'],
                    'body'=>$msg['body'],
                ],
                $args
            );
        }
    }

    // Mailgun post
    public function actionMailgun() {
        // Must be posted from Mailgun
        if (!Yii::$app->request->isPost) {
            die(0);
        }

        $from = Yii::$app->request->post('From', '');
        $to = Yii::$app->request->post('To', '');
        $cc = Yii::$app->request->post('Cc', '');
        $bcc = Yii::$app->request->post('Bcc', '');

        $addresses = $this->getEmails(implode(' ', [$from, $to, $cc, $bcc]));

        // Exclude cases
        if (
            strpos($from, 'linkedin') !== false ||
            strpos($from, 'facebook') !== false ||
            strpos($from, 'notifications@amicatravel.com') !== false ||
            strpos($from, 'noreply') !== false ||
            strpos($from, 'no-reply') !== false ||
            strpos($from, 'hn.huan@gmail.com') !== false ||
            strpos($from, 'phuonganh.mkt@gmail.com') !== false ||
            substr($from, -5) == '.top>' ||
            false
        ) {
            die(0);
        }

        $hasPax = false; // Co dia chi ngoai
        $hasIms = false; // Co dia chi ims@

        foreach ($addresses as $address) {
            if (strpos($address, '@amicatravel.com') === false && strpos($address, '@amicatravel.org') === false && strpos($address, '@amica-travel.com') === false) {
                $hasPax = true;
                break;
            }
        }

        if (in_array('ims@amicatravel.com', $addresses)) {
            $hasIms = true;
        }

        // 160109 CSKH
        if (in_array('in-com@amicatravel.com', $addresses)) {
            $hasIms = true;
        }

        // 160109 CSKH
        //if (in_array('n.thiminh@amica-travel.com', $addresses) || in_array('pham.ha@amica-travel.com', $addresses) || in_array('tran.duong@amica-travel.com', $addresses)) {
            //$hasIms = true;
        //}

        if (!$hasPax && !$hasIms) {
            die(0);
        }

        // Check for dup message
        if (isset($_POST['Message-Id'])) {
            $sql = 'SELECT id FROM at_mails where message_id=:id LIMIT 1';
            $dupMail = Yii::$app->db->createCommand($sql, [':id'=>$_POST['Message-Id']])->queryOne();
            if ($dupMail) {
                die(0);
            }
        }

        $attachmentCount = isset($_POST['attachment-count']) ? (int)$_POST['attachment-count'] : 0;

        $datetime = \DateTime::createFromFormat('D, d M Y H:i:s O', trim(substr($_POST['Date'], 0, 31)));
        if ($datetime instanceof \DateTime) {
            $timestamp = $datetime->getTimestamp();
        } else {
            $timestamp = $_POST['timestamp'];
        }
        $sentDt = date('Y-m-d H:i:s', $timestamp);

        $mail = new Mail;
        $mail->created_at = NOW;
        $mail->updated_at = NOW;
        $mail->sent_dt = $sentDt;
        $mail->sent_dt_text = $_POST['Date'];
        $mail->message_id = isset($_POST['Message-Id']) ? $_POST['Message-Id'] : '';
        $mail->in_reply_to = isset($_POST['In-Reply-To']) ? $_POST['In-Reply-To'] : '';
        $mail->from = $from;
        $mail->reply_to = $_POST['sender'];
        $mail->to = $to;
        $mail->cc = $cc;
        $mail->bcc = $bcc;
        $mail->from_user_id = 0;
        $mail->to_user_id = 0;
        $mail->subject = $_POST['subject'];


        // Get email rule
        $email = $addresses[0];
        if (strpos($addresses[0], '@amicatravel.com') !== false || strpos($addresses[0], '@amica-travel.com') !== false || strpos($addresses[0], '@amicatravel.org') !== false) {
            $email = $addresses[1];
        }

        // Assign case id
        if (strpos($email, '@amicatravel.com') === false && strpos($email, '@amica-travel.com') === false && strpos($email, '@amicatravel.org') === false) {
            $sql = 'SELECT * FROM at_email_mapping WHERE email=:email LIMIT 1';
            $mapping = Yii::$app->db->createCommand($sql, [':email'=>$email])->queryOne();
            if ($mapping) {             
                if ($mapping['action'] == 'drop') {
                    // Drop email immediately
                    exit;
                } elseif ($mapping['action'] == 'ask') {
                    // Ask every time
                    // TODO
                    $mail->case_id = $mapping['case_id'];
                } else {
                    $mail->case_id = $mapping['case_id'];
                }
            }
        }

        $sh = isset($_POST['stripped-html']) ? $_POST['stripped-html'] : '';
        $sh = strip_tags($sh, '<a><b><br><hr><img><p><div><table><tbody><thead><tr><td><em><span><strong><ul><ol><li>');
        $sh = str_replace([' style='], [' x='], $sh);
        if ($sh == '') {
            $mail->body = isset($_POST['stripped-text']) ? nl2br(Html::encode($_POST['stripped-text'])) : '';
        } else {
            $mail->body = $sh;
        }
        
        $mail->body = HtmlPurifier::process($mail->body);

        $mail->body_full = isset($_POST['body-html']) ? HtmlPurifier::process($_POST['body-html']) : $mail->body;
        $mail->data = serialize($_POST);
        $mail->data = serialize([]);

        $mail->from_email = $addresses[0];
        $mail->to_email = $addresses[1];

        if ($mail->save(false)) {
            if ($attachmentCount > 0) {
                $attachmentFiles = [];
                for ($i = 1; $i <= $attachmentCount; $i ++) {
                    // Only save real big files, no inline images
                    if (!isset($_POST['content-id-map']) || strpos($_POST['content-id-map'], 'attachment-'.$i) === false) {
                        // If Amica to Pax: only save file with -ims. part in name
                        if ((strpos($addresses[0], '@amicatravel.com') === false && strpos($addresses[0], '@amica-travel.com') === false && strpos($addresses[0], '@amicatravel.org') === false) || strpos($_FILES['attachment-'.$i]['name'], '-ims.') !== false) {
                            $uploadDir = Yii::getAlias('@webroot').'/upload/mail-attachments/'.substr(NOW, 0, 7).'/'.$mail['id'].'/';
                            FileHelper::createDirectory($uploadDir);
                            $uploadFile = $uploadDir.basename($mail['id'].'-'.sha1($_FILES['attachment-'.$i]['name']));
                            if (move_uploaded_file($_FILES['attachment-'.$i]['tmp_name'], $uploadFile)) {
                                $attachmentFiles[] = [
                                    'name'=>$_FILES['attachment-'.$i]['name'],
                                    'type'=>$_FILES['attachment-'.$i]['type'],
                                    'size'=>$_FILES['attachment-'.$i]['size'],
                                ];
                            }
                        }
                    }
                }
                $mail->files = serialize($attachmentFiles);
                $mail->attachment_count = count($attachmentFiles);
                $mail->save(false);
            }
        }
    }

    // Extract email addresses from text
    private function getEmails($text)
    {
        $pattern = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i";
        preg_match_all($pattern, $text, $matches);
        // Get unique values
        return array_keys(array_flip($matches[0]));
    }

    // Webhook endpoint for Mailgun events
    public function actionMgEvent($event = '')
    {
        $message = '';
        if ($event == 'bounce') {
            $message = Yii::$app->request->post('code', 'CODE') . ' / '. Yii::$app->request->post('reason', 'REASON') . ' / ' . Yii::$app->request->post('error', 'ERROR');
        }
        if ($event == 'drop') {
            $message = Yii::$app->request->post('code', 'CODE') . ' / '. Yii::$app->request->post('reason', 'REASON') . ' / ' . Yii::$app->request->post('description', 'NOTIFICATION');
        }
        if ($event == 'spam') {
            $message = Yii::$app->request->post('campaign-id', 'CAMPAIGN-ID') . ' / '. Yii::$app->request->post('CAMPAIGN-NAME', '') . ' / ' . Yii::$app->request->post('tag', 'TAG');
        }
        $sql = 'INSERT INTO at_mg_events (created_dt, event, recipient, domain, headers, message, data) VALUES (:dt, :ev, :re, :do, :mh, :me, :da)';
        Yii::$app->db->createCommand($sql, [
            ':dt'=>NOW,
            ':ev'=>Yii::$app->request->post('event', ''),
            ':re'=>Yii::$app->request->post('recipient', ''),
            ':do'=>Yii::$app->request->post('domain', ''),
            ':mh'=>Yii::$app->request->post('message-headers', ''),
            ':me'=>$message,
            ':da'=>serialize($_POST),
        ])->execute();
        echo 'OK: '.$event;
    }

}
