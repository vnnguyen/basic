<?php
namespace common\models;

class Customer extends MyActiveRecord
{
	// Meta values
	public $metaEmail;

	public static function tableName()
	{
		return '{{%users}}';
	}

	public function attributeLabels()
	{
		return [
			'start_dt'=>'Start date',
			'end_dt'=>'End date',
			'info'=>'More information',
		];
	}

	public function rules()
	{
		return [
			[['fname', 'lname', 'name', 'info'], 'filter', 'filter'=>'trim'],
			[['fname', 'lname', 'name'], 'required'],
			[['email', 'metaEmail'], 'email'],
		];
	}

	public function scenarios()
	{
		return [
			'create'=>['fname', 'lname', 'name'],
			'update'=>['fname', 'lname', 'name', 'metaEmail', 'info'],
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

	public function getTours()
	{
		return $this->hasMany(Tour::className(), ['id'=>'tour_id'])
			->viaTable('at_pax', ['user_id'=>'id']);
	}

	public function getMetas()
	{
		return $this->hasMany(Meta::className(), ['rid' => 'id'])->where(['rtype'=>'user']);
	}


}
