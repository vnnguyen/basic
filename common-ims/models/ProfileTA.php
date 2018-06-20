<?php
namespace common\models;

class ProfileTA extends MyActiveRecord
{
	public $newpassword;

	public static function tableName()
	{
		return '{{%profiles_ta}}';
	}

	public function attributeLabels()
	{
		return [
			'name'=>'Display name',
			'login'=>'Login name',
			'newpassword'=>'Password',
		];
	}

	public function rules()
	{
		return [
			[['name', 'login', 'password'], 'trim'],
			[['name', 'login'], 'required', 'message'=>'Required'],
			[['name', 'login'], 'unique', 'message'=>'Duplicate name'],
		];
	}

	public function scenarios() {
		return [
			'profile/c'=>['name', 'login', 'newpassword'],
			'profile/u'=>['name', 'login', 'newpassword'],
		];
	}

	public function getCompany()
	{
		return $this->hasOne(User::className(), ['id' => 'company_id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}
}
