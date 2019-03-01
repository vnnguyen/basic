<?php
namespace common\models;

class Mm extends MyActiveRecord
{

	public static function tableName()
	{
		return '{{%mm}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [
			[['mm'], 'trim'],
			[['mm'], 'required', 'message'=>'Required'],
		];
	}

	public function getTour()
	{
		return $this->hasOne(Tour::className(), ['id'=>'pid']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'ub']);
	}

	public function getDvt()
	{
		return $this->hasOne(Dvt::className(), ['id'=>'rel_id']);
	}

}
