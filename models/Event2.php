<?php
namespace common\models;

class Event2 extends MyActiveRecord
{
	public $users;

	public static function tableName()
	{
		return '{{%2events}}';
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function rules()
	{
		return [
			[['name', 'summary', 'from_dt', 'until_dt', 'timezone', 'body', 'image'], 'trim'],
			[['name', 'summary', 'from_dt', 'until_dt', 'timezone', 'status'], 'required'],
			[['image'], 'url'],
		];
	}

	public function scenarios()
	{
		return [
			'event/c'=>['name', 'summary', 'from_dt', 'until_dt', 'timezone'],
			'event/u'=>['name', 'summary', 'from_dt', 'until_dt', 'timezone', 'body', 'image', 'is_sticky', 'status'],
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

	public function getPeople()
	{
		return $this->hasMany(User::className(), ['id'=>'user_id'])
			->viaTable('at_event_user', ['event_id'=>'id']);
	}

}
