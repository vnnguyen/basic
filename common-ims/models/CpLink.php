<?php
namespace common\models;

// Client page links

class CpLink extends MyActiveRecord
{
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
            [['message'], 'required'],
            // [['attachments'], 'safe'],
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
