<?php
namespace common\models;

class TAProfile extends MyActiveRecord
{

	public static function tableName()
	{
		return '{{%profiles_ta}}';
	}

	public function attributeLabels()
	{
		return [
			'note'=>'Ghi chú khác',
		];
	}

	public function rules()
	{
		return [
			[['name', 'login'], 'trim'],
			[['name', 'login', 'password'], 'required', 'message'=>'Required'],
		];
	}

	public function scenarios() {
		return [
			'profile/c'=>['name', 'login', 'password'],
			'profile/c'=>['name', 'login', 'password'],
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
