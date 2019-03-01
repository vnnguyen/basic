<?php
namespace common\models;

class BookingReport extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%booking_reports}}';
	}

	public function rules()
	{
		return [
			[['note'], 'trim'],
			[['pax_count', 'day_count', 'price', 'price_unit', 'cost', 'cost_unit'], 'required'],
			[['price', 'cost'], 'integer', 'min'=>0],
			[['price_unit', 'cost_unit'], 'default', 'value'=>'USD'],
			[['price_unit', 'cost_unit'], 'in', 'range'=>['USD', 'VND', 'EUR']],
		];
	}

	public function getCreatedBy() {
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy() {
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}

	public function getBooking() {
		return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
	}
}
