<?php
namespace app\models;

// Clien page link
use Yii;

class CpLink extends MyActiveRecord
{
    public $subject = '';
    public $attachments = [];

    public static function tableName()
    {
        return 'cplinks';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [[
                'subject', 'message',
                ], 'trim'],
            [[
                'subject', 'message',
                ], 'required', 'message'=>Yii::t('x', 'Required.')],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['id'=>'booking_id']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id'=>'customer_id']);
    }

    public function getCpRegistrations()
    {
        return $this->hasMany(CpRegistrations::className(), ['cplink_id'=>'id']);
    }

}
