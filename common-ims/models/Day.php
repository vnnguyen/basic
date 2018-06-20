<?php
namespace common\models;

class Day extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%days}}';
	}

	public function attributeLabels()
	{
		return [
			'note'=>'Note',
		];
	}

	public function rules()
	{
		return [
			[['day', 'name', 'body', 'image', 'meals', 'guides', 'transport', 'note'], 'filter', 'filter'=>'trim'],
			[['name', 'body'], 'required'],
			[['day'], 'default', 'value'=>'0000-00-00'],
		];
	}

	public function scenarios()
	{
		return [
			'day/c'=>['day', 'name', 'body', 'image', 'meals', 'guides', 'transport', 'note'],
			'day/u'=>['day', 'name', 'body', 'image', 'meals', 'guides', 'transport', 'note'],
			'products_copy'=>[],
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

	public function getProduct()
	{
		return $this->hasOne(Product::className(), ['id'=>'rid']);
	}

}
