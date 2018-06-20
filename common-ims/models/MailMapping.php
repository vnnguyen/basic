<?php
namespace common\models;

class MailMapping extends MyActiveRecord
{

	public static function tableName()
	{
		return '{{%email_mapping}}';
	}

	public function attributeLabels()
	{
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
