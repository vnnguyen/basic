<?php
namespace common\models;

class BAccount extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%baccounts}}';
	}

	public function rules()
	{
		return [
			[['stype', 'name', 'info', 'note'], 'trim'],
			[['stype', 'name'], 'required'],
			[['currency'], 'in', 'range'=>['USD', 'VND', 'EUR']],
		];
	}

	public function scenarios()
	{
		return [
			'baccount/c'=>['stype', 'name', 'currency', 'info', 'note'],
			'baccount/u'=>['stype', 'name', 'currency', 'info', 'note'],
		];
	}

	public function getCreatedBy() {
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy() {
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getInvoices() {
		return $this->hasMany(Invoice::className(), ['baccount_id'=>'id']);
	}

	public function getPayments() {
		return $this->hasMany(Payment::className(), ['baccount_id'=>'id']);
	}

}
