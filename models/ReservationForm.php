<?php
namespace common\models;

use Yii;
use yii\base\Model;

class ReservationForm extends Model
{
	public $ppt_country;
	public $ppt_number;
	public $ppt_fname;
	public $ppt_lname;
	public $ppt_bday;
	public $ppt_bmonth;
	public $ppt_byear;
	public $ppt_gender;

	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'filter', 'filter' => 'strtolower'],
			['email', 'required'],
			['email', 'email'],
			['email', 'exist',
				'targetClass' => '\common\models\User',
				'filter' => ['status'=>'on', 'is_member'=>'yes'],
				'message' => 'There is no user with such email.'
			],
		];
	}

}