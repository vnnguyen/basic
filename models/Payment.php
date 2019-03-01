<?php
namespace common\models;

class Payment extends MyActiveRecord
{
	public static function tableName() {
		return '{{%payments}}';
	}

	public function attributeLabels() {
		return [
			'invoice_id'=>'Invoice #',
			'payment_dt'=>'Date of payment',
			'method'=>'Method of payment',
			'ref'=>'Reference ID',
			'payer'=>'Payment by',
			'payee'=>'Payment to',
			'xrate'=>'Exchange rate to VND',
		];
	}

	public function rules()
	{
		return [
			[['invoice_id', 'method', 'note', 'ref', 'payer', 'payee'], 'trim'],
			[['invoice_id', 'amount', 'xrate'], 'number', 'min'=>0],
			[['currency'], 'in', 'range'=>['EUR', 'USD', 'VND']],
			[['payment_dt', 'method', 'amount', 'currency', 'note', 'payer', 'payee', 'xrate'], 'required'],
			[['invoice_id'], 'default', 'value'=>0],
		];
	}

	public function scenarios()
	{
		return [
			'payments_c'=>['invoice_id', 'payment_dt', 'ref', 'method', 'amount', 'currency', 'note', 'payer', 'payee', 'xrate'],
			'payments_u'=>['invoice_id', 'payment_dt', 'ref', 'method', 'amount', 'currency', 'note', 'payer', 'payee', 'xrate'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getBooking()
	{
		return $this->hasOne(Booking::className(), ['id'=>'booking_id']);
	}

	public function getInvoice()
	{
		return $this->hasOne(Invoice::className(), ['id'=>'invoice_id']);
	}

	public function getPayer()
	{
		return $this->hasOne(User::className(), ['id'=>'payer_id']);
	}

}
