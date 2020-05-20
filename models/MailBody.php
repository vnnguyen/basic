<?php
namespace app\models;

class MailBody extends MyActiveRecord
{
    public static function tableName()
    {
        return 'mail_body';
    }

    public function rules()
    {
        return [
            ['body', 'trim']
        ];
    }

    public function getMail()
    {
        return $this->hasOne(Mail::className(), ['id'=>'mail_id']);
    }
}
