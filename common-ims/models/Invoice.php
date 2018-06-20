<?php
namespace common\models;

class Invoice extends MyActiveRecord
{
	public static function tableName() {
		return 'invoices';
	}

	public function attributeLabels() {
		return [
			'ref'=>'Reference ID',
			'note_invoice'=>'Note on invoice',
			'due_dt'=>'Due date',
			'lang'=>'Invoice language',
			'currency'=>'Invoice currency',
			'method'=>'Payment method',
			'gw_name'=>'Payment gateway (Onepay, etc)',
			'gw_currency'=>'Gateway currency',
			'gw_xrate'=>'Gateway rate',
			'link'=>'Payment link',
		];
	}

	public function rules()
	{
		return [
			[['ref'], 'unique'],
			[['brand', 'stype', 'nho_thu', 'ref','gw_name', 'gw_xrate', 'body', 'body2', 'body3', 'bill_to_name', 'bill_to_address', 'note_invoice', 'note'], 'trim'],
			[['currency', 'gw_currency'], 'in', 'range'=>['EUR', 'USD', 'VND']],
			[['lang'], 'in', 'range'=>['en', 'fr', 'vi']],
			[['gw_xrate'], 'number'],
			[['link'], 'url'],
			[['stype'], 'default', 'value'=>'invoice'],
			[['stype', 'status', 'lang', 'due_dt', 'currency', 'gw_currency', 'method', 'bill_to_name', 'bill_to_address'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'invoices_c'=>['brand', 'stype', 'nho_thu', 'ref', 'status', 'lang', 'due_dt', 'currency', 'method', 'gw_name', 'gw_currency', 'gw_xrate', 'link', 'bill_to_name', 'bill_to_address', 'note', 'body', 'body2', 'body3', 'note_invoice', 'sig_client', 'sig_seller'],
			'invoices_u'=>['brand', 'stype', 'nho_thu', 'ref', 'status', 'lang', 'due_dt', 'currency', 'method', 'gw_name', 'gw_currency', 'gw_xrate', 'link', 'bill_to_name', 'bill_to_address', 'note', 'body', 'body2', 'body3', 'note_invoice', 'sig_client', 'sig_seller'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User2::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User2::className(), ['id'=>'updated_by']);
	}

	public function getBooking()
	{
		return $this->hasOne(Booking::className(), ['id'=>'booking_id']);
	}

	public function getPayments()
	{
		return $this->hasMany(Payment::className(), ['invoice_id'=>'id']);
	}

	public function getPayer()
	{
		return $this->hasOne(User2::className(), ['id'=>'payer_id']);
	}

}
