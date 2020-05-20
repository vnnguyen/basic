<?php
namespace common\models;
use Yii;

class Booking extends MyActiveRecord
{
	public $tourCode, $tourName, $clientRef;
	public $is_prebooked = 'no';

	public static function tableName()
	{
		return 'bookings';
	}

	public function rules()
	{
		return [
			[['tourCode', 'tourName', 'clientRef', 'is_prebooked'], 'trim'],
			[['tourCode', 'tourName'], 'required', 'message'=>Yii::t('x', 'Required')],
			[['prices', 'conditions', 'note', 'start_date'], 'trim'],
			[['case_id', 'price', 'currency', 'pax'], 'required', 'message'=>Yii::t('x', 'Required')],
			[['case_id', 'pax'], 'integer', 'min'=>0],
			[['price'], 'number'],
			[['currency'], 'in', 'range'=>['USD', 'VND', 'EUR']],
		];
	}

	public function scenarios()
	{
		return [
			'bookings_c'=>['case_id', 'price', 'currency', 'pax', 'note'],
			'bookings_u'=>['price', 'currency', 'pax', 'note', 'is_prebooked'],
			'bookings_mp'=>[],
			'bookings_ml'=>[],
			'bookings_mw'=>['tourCode', 'tourName', 'clientRef', 'price', 'currency', 'pax', 'note', 'is_prebooked'],
		];
	}

	public function getCreatedBy() {
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy() {
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}

	public function getPeople() {
		return $this->hasMany(Contact::className(), ['id' => 'contact_id'])
			->viaTable('booking_contact', ['booking_id'=>'id']);
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
		return $this->hasMany(Contact::className(), ['id' => 'contact_id'])
			->viaTable('booking_contact', ['booking_id'=>'id']);
	}

	public function getInvoices() {
		return $this->hasMany(Invoice::className(), ['booking_id' => 'id']);
	}

	public function getPayments() {
		return $this->hasMany(Payment::className(), ['booking_id' => 'id']);
	}

}
