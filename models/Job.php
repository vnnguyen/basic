<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\BaseObject;


use common\models\Contact;

class Job extends BaseObject implements \yii\queue\JobInterface
{
    public $recipientUids = 0;
    public $rid = 0;
    public $prit = 0;

    public function execute($queue)
    {
        //send email here
        $revPersons = Contact::find()
                    ->where(['id' => $this->recipientUids])
                    ->asArray()->all();
        $theNty = Yii::$app->ntyModel->find()
                ->where(['id' => $this->rid])->asArray()->one();
        $mailer = Contact::findOne(USER_ID);

        foreach ($revPersons as $person) {
            Yii::$app->mailer->compose('test_email', [
                'person' => 'a',
                ])
                ->setFrom('nguyen.nv@amica-travel.com')
                ->setTo('nguyen.nv@amica-travel.com')
                ->setSubject($this->prit . '. test mail '. rand(1,100))
                ->send();
        }


        echo 'ok job';
    }
    // public function getTtr()
    // {
    //     return 15 * 60;
    // }

    // public function canRetry($attempt, $error)
    // {
    //     return ($attempt < 5) && ($error instanceof TemporaryException);
    // }
}
?>