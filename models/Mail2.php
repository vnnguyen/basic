<?php
namespace common\models;

class Mail2 extends MyActiveRecord
{

	public static function tableName() {
		return '{{%2_mails}}';
	}

	public function attributeLabels() {
		return [

		];
	}

	public function rules()
	{
		return [
			[['status'], 'required'],
			[['body'], 'trim'],
			[['body'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'delete'=>['status'],
			'mails/u'=>['body'],
		];
	}

	public function getCase()
	{
		return $this->hasOne(Kase::className(), ['id'=>'case_id']);
	}
}
