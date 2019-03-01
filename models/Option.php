<?php
namespace common\models;

class Option extends MyActiveRecord
{
	public static function tableName() {
		return '{{%options}}';
	}

	public function rules()
	{
		return [
			[['key', 'value'], 'trim'],
			[['key', 'value'], 'required'],
		];
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function scenarios()
	{
		return [
			'default'=>['key', 'value'],
		];
	}

}
