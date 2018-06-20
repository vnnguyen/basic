<?php
namespace common\models;

class Tournote extends MyActiveRecord
{
	public static function tableName() {
		return '{{%tour_notes}}';
	}

	public function rules()
	{
		return [
			[['body'], 'trim'],
		];
	}

	public function getTour()
	{
		return $this->hasOne(Product::className(), ['id'=>'product_id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

}
