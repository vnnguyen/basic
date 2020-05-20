<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TourSendFbLinkForm extends Model
{
    public $recipients;
    public $subject;
    public $message;

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [[
                'recipients', 'subject', 'message',
                ], 'trim'],
            [[
                'subject', 'message',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}