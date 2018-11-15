<?php
namespace app\jobqueues;

use Yii;
use yii\base\Model;
use yii\base\BaseObject;
class SendNotif extends BaseObject implements \yii\queue\JobInterface
{
    public $datas;

    public function execute($queue)
    {
        $this->datas->send();
    }
}
?>