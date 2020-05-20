<?php
namespace common\models;

class ProfileMember extends MyActiveRecord
{
	public $ext = '';

	public static function tableName()
	{
		return '{{%profiles_member}}';
	}

	public function attributeLabels()
	{
		return [
			'fname'=>'First name',
			'lname'=>'Second name',
			'name'=>'Display name',
			'email'=>'Email address',
			'bday'=>'Birth day',
			'bmonth'=>'Birth month',
			'byear'=>'Birth year',
			'country_code'=>'Nationality',
			'info'=>'Information',
			'since'=>'Start date',
		];
	}

	public function rules()
	{
		return [
			[['reports_to'], 'default', 'value'=>0],
			[['since', 'position', 'unit', 'location', ], 'required'],
			//[['since'], 'date', 'format'=>'Y-m-d'],
			[['position', 'unit', 'location', 'reports_to', 'bio', 'review', 'intro', 'note'], 'trim'],
		];
	}

	public function scenarios() {
		return [
			'member/c'=>['since', 'position', 'unit', 'location', 'intro', 'bio'],
			'member/u'=>['since', 'position', 'unit', 'location', 'intro', 'bio', 'ext'],
		];
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function getSupervisor()
	{
		return $this->hasOne(User::className(), ['id' => 'reports_to']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}
}
