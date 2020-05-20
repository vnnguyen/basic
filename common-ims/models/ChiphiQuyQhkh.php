<?php
namespace common\models;

class ChiphiQuyQhkh extends MyActiveRecord
{
	public static function tableName()
	{
		return 'chiphi_quy_qhkh';
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
			'chiphi/c'=>['stype', 'name', 'currency', 'info', 'note'],
			'chiphi/u'=>['stype', 'name', 'currency', 'info', 'note'],
		];
	}

	public function getCreatedBy() {
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy() {
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getTour() {
		return $this->hasOne(Product::className(), ['id'=>'tour_id']);
	}

	public function getPayments() {
		return $this->hasMany(Payment::className(), ['baccount_id'=>'id']);
	}

}
