<?php
namespace common\models\dvcp;

class Dv extends ActiveRecord
{

	public static function tableName()
	{
		return '{{dv}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [
			[['stype', 'destination_id', 'name', 'provider', 'venue', 'specs', 'use', 'search', 'note'], 'trim'],
			[['stype', 'destination_id', 'name', 'note'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'dv/c'=>['stype', 'destination_id', 'name', 'provider', 'venue', 'specs', 'use', 'search', 'note'],
			'dv/u'=>['stype', 'destination_id', 'name', 'provider', 'venue', 'specs', 'use', 'search', 'note'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(\common\models\User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(\common\models\User::className(), ['id'=>'updated_by']);
	}

	public function getCp()
	{
		return $this->hasMany(Cp::className(), ['dv_id'=>'id']);
	}
}
