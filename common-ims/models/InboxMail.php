<?php
namespace common\models;

class InboxMail extends MyActiveRecord
{

	public static function tableName() {
		return '{{%inbox}}';
	}

	public function attributeLabels() {
		return [

		];
	}

	public function rules()
	{
		return [
			[['status'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'delete'=>['status'],
		];
	}

}
