<?php
namespace app\models;
use Yii;

class BookingContact extends MyActiveRecord
{
    public static function tableName()
    {
        return 'booking_contact';
    }

    public function getBooking() {
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }

    public function getContact() {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

}
