<?php
namespace common\models;

// Client page links

class Cplink extends MyActiveRecord
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
            [['booking_id', 'user_id', 'message'], 'trim'],
            [['booking_id', 'email', 'message'], 'required'],
            [['attachments'], 'safe'],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getCase()
    {
        return $this->hasOne(Kase::className(), ['id'=>'case_id']);
    }

    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['id'=>'booking_id']);
    }

}
