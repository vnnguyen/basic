<?php
namespace common\models;

class Search extends MyActiveRecord
{

	public static function tableName()
	{
		return '{{%search}}';
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function rules()
	{
		return [
			[['rtype', 'rid', 'search', 'found'], 'required'],
		];
	}

}
