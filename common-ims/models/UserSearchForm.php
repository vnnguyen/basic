<?php
namespace common\models;

use yii\base\Model;

// This is used on /users page

class UserSearchForm extends Model
{
	public $fname;
	public $lname;
	public $gender;
	public $country_code;
	public $email;
	public $age;
	public $tag;

	public function rules()
	{
		return [
			[['fname', 'lname', 'gender', 'country_code'], 'filter', 'filter'=>'trim'],
		];
	}

	public function attributeLabels()
	{
		return [
			'fname'=>'First name',
			'lname'=>'Second name',
			'country_code'=>'Nationality',
		];
	}

}
