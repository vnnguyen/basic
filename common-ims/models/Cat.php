<?php
namespace common\models;

class Cat extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%nestedsets}}';
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function rules()
	{
		return [
			['name', 'filter', 'filter'=>'trim'],
			['name, body', 'required'],
		];
	}

	public function behaviors()
	{
		return [
			'tree'=>[
				'class' => '@common\models\NestedSet',
			],
		];
	}

	public function getUpdatedBy()
	{
		return $this->hasOne('User', ['id'=>'ub']);
	}

}
