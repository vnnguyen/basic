<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class MailTo extends MyActiveRecord
{

    public static function tableName()
    {
        return 'mail_to';
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id'=>'contact_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

    public function getMail()
    {
        return $this->hasOne(Mail::className(), ['id'=>'mail_id']);
    }
}
