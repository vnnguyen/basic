<?php
namespace app\models;

class Mail extends \yii\db\ActiveRecord
{

    public static function tableName() {
        return '{{%mails}}';
    }

    public function rules()
    {
        return [
            [['status'], 'required'],
            [['body'], 'trim'],
            [['body'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'delete'=>['status'],
            'mails/u'=>['body'],
        ];
    }

    public function getCase()
    {
        return $this->hasOne(Kase::className(), ['id'=>'case_id']);
    }

    public function getBody()
    {
        return $this->hasOne(MailBody::className(), ['mail_id'=>'id']);
    }
}
