<?php
namespace common\models;

class Permission extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%permissions}}';
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function rules()
	{
		return [
			[['name', 'alias', 'info'], 'filter', 'filter'=>'trim'],
			[['name', 'alias'], 'required'],
		];
	}

	public function getUsers()
	{
		return $this->hasMany(User::className(), ['id' => 'user_id'])
			->viaTable('at_role_user', ['role_id'=>'id']);
	}

}
