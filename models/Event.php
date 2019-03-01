<?php
namespace common\models;

class Event extends MyActiveRecord
{
	public $users;

	public static function tableName()
	{
		return '{{%events}}';
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function rules()
	{
		return [
			[['name', 'info', 'venue'], 'filter', 'filter'=>'trim'],
			[['name', 'info', 'from_dt', 'until_dt'], 'required'],
			[['mins'], 'integer'],
		];
	}

	public function scenarios()
	{
		return [
			'events/c'=>['name', 'info', 'stype', 'status', 'from_dt', 'until_dt', 'venue', 'mins'],
			'events/u'=>['name', 'info', 'stype', 'status', 'from_dt', 'until_dt', 'venue', 'mins'],
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
