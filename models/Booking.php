<?php
namespace app\models;
use Yii;

class Booking extends MyActiveRecord
{
    public $tourCode, $tourName, $clientRef;

    public static function tableName()
    {
        return '{{%bookings}}';
    }

    public function rules()
    {
        return [
            [['tourCode', 'tourName', 'clientRef'], 'trim'],
            [['tourCode', 'tourName'], 'required', 'message'=>Yii::t('app', 'Required')],
            [['prices', 'conditions', 'note', 'start_date'], 'trim'],
            [['case_id', 'price', 'currency', 'pax'], 'required'],
            [['case_id', 'pax'], 'integer', 'min'=>0],
            [['price'], 'number'],
            [['currency'], 'in', 'range'=>['USD', 'VND', 'EUR']],
        ];
    }

    public function scenarios()
    {
        return [
            'bookings_c'=>['case_id', 'price', 'currency', 'pax', 'note'],
            'bookings_u'=>['price', 'currency', 'pax', 'note'],
            'bookings_mp'=>[],
            'bookings_ml'=>[],
            'bookings_mw'=>['tourCode', 'tourName', 'clientRef', 'price', 'currency', 'pax', 'note'],
        ];
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getPeople() {
        return $this->hasMany(Contact::className(), ['id' => 'user_id'])
            ->viaTable('at_booking_user', ['booking_id'=>'id']);
    }

    public function getCase() {
        return $this->hasOne(Kase::className(), ['id' => 'case_id']);
    }

    public function getReport() {
        return $this->hasOne(BookingReport::className(), ['booking_id' => 'id']);
    }

    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getPax() {
        return $this->hasMany(Contact::className(), ['id' => 'user_id'])
            ->viaTable('at_booking_user', ['booking_id'=>'id']);
    }

    public function getInvoices() {
        return $this->hasMany(Invoice::className(), ['booking_id' => 'id']);
    }

    public function getPayments() {
        return $this->hasMany(Payment::className(), ['booking_id' => 'id']);
    }

}
