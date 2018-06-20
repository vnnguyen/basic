<?php
namespace common\models;

// Client page regs

class CpRegistration extends MyActiveRecord
{
    public $attachments = [];

    public static function tableName()
    {
        return 'cpregistrations';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name'], 'required'],
        ];
    }

    public function getCpLink()
    {
        return $this->hasOne(CpLink::className(), ['id'=>'cplink_id']);
    }
}
