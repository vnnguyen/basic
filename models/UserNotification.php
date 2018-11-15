<?php
namespace app\models;

use Yii;
use yii\base\Model;

use yii\db\Query;

use common\models\Contact;



class UserNotification extends \yii\db\ActiveRecord
{
    public $sendEmail = true;
    public static function tableName()
    {
        return 'notification';
    }
   public function fetchNumberByRecipientUid($recipientUid)
   {
       $q = \Yii::$app->db->createCommand("SELECT * FROM notification WHERE recipientUid =:d AND isNew = 1"
           , [':d' => $recipientUid])->execute();
       return $q;
   }

   public function fetchDataToSend($uid)
   {
       $q = \Yii::$app->db->createCommand("SELECT * FROM notification WHERE recipientUid =:d AND isNew = 1"
           , [':d' => $uid])->queryAll();
       return $q;
   }
   /**
    *
    * @param int $recipientUid
    * @param int $eventId
    */
   public function add($recipientUid, $eventId, $action, $title = '', $source_url = '', $stype = 'all')
   {
        $result = [];
        if(!is_array($recipientUid)) {
            $recipientUid = explode(',', $recipientUid);
        }
        foreach ($recipientUid as $uid) {
            $q = \Yii::$app->db->createCommand()->insert('notification',
                [
                    'recipientUid' => $uid,
                    'eventId' => $eventId,
                    'action' => $action,
                    'title' => $title,
                    'source_url' => $source_url,
                    'stype' => $stype,
                    'status' => 'open',
                    'isNew' => 1,
                ])
                ->execute();
            if ($q) $last_id = \Yii::$app->db->getLastInsertID();
            if($this->sendEmail) {
                if($stype == 'mailqueue') {
                    $status_push = self::addMailQueue($uid, $last_id);
                    // var_dump($status_push);die;
                    if(!$status_push) die('Error on push Mail Queue !!!!');
                    echo 'pushing doned: ' .$status_push. '<br>';
                    // $mailHandel = new \app\components\MyMailHandler();
                    // $mailHandel->conditions = [
                    //     'where' => [
                    //         'id' => $status_push
                    //     ]
                    // ];
                    // \Yii::$app->mailqueue->delivery($mailHandel);
                } else {
                    $pri = rand(100, 1024);
                    $id_job = \Yii::$app->queue->priority($pri)->delay(rand(1,3) * 60)->push(new Job([
                        'recipientUids' => $uid,
                        'rid' => $last_id,
                        'prit' => $pri
                    ]));

                    $result[] = $id_job;
                }
            }
        }



        // if($this->sendEmail) {
        //     $id = Yii::$app->queue->delay(5 * 60)->push(new Job([
        //         'recipientUids' => $result,
        //         'rid' => $eventId
        //     ]));
        // }
        return 'okkk !!!';
   }
   public function addMailQueue($RevPerson_id, $rid)
   {
        $theRevPerson = Contact::findOne($RevPerson_id);
        $theNty = self::findOne($rid);
        $theMailer = Contact::findOne(USER_ID);
        $r = rand(10,100);
        $modelMail = new MyMailQueueModel;
        $modelMail->to = 'nguyen.nv@amica-travel.com';
        $modelMail->from = 'nguyen.nv@amica-travel.com';
        $modelMail->subject = 'email_test: ' . $r;
        $modelMail->body = 'content demo';
        $modelMail->status = 0;
        $modelMail->mailer = 'mailer';
        $modelMail->priority = $r;
        $modelMail->attaches = [];
        $modelMail->createDate = NOW;
        $status = true;
        if(!\Yii::$app->mailqueue->push($modelMail)){
            $status = false;
        }
        if ($status) {
            $status = \Yii::$app->db->getLastInsertID();
        }
        return $status;
   }
   public function sendAllMailQueue()
   {

        try {
            Yii::$app->db->close();
            Yii::$app->db->open();
            echo 'Sendding.. <br>';
             \Yii::$app->mailqueue->delivery(new \app\components\MyMailHandler());
             echo '<br>Sended! <br>';
        } catch (Exception $e) {
            Yii::error(LoggerMessage::log($e), __METHOD__);
        }
        // while (true) {
        //     \Yii::$app->mailqueue->delivery(new \app\components\MyMailHandler());

        //     sleep($this->delay);
        // }
   }

   public function detele($recipientUid)
   {
    $result = \Yii::$app->db->createCommand()->update('notification', [
            'isNew' => 0,
            'status' => 'deleted',
        ], [
            'recipientUid' => $recipientUid])
        ->execute();
    return $result;
   }


   public function removeAll($recipientUid)
   {
    $result = \Yii::$app->db->createCommand('DELETE FROM notification WHERE recipientUid=:recipientUid', [
            ':recipientUid' => $recipientUid
        ])
        ->execute();
   }
}