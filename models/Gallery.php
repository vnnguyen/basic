<?php
namespace common\models;

class Gallery extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%galleries}}';
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function rules()
	{
		return [
		];
	}

	public function getUsers()
	{
		return $this->hasMany(User::className(), ['id' => 'user_id'])
			->viaTable('at_role_user', ['role_id'=>'id']);
	}

}
