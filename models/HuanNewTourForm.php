<?php

namespace app\models;

use Yii;
use yii\base\Model;

class HuanNewTourForm extends Model
{
	public $code, $name, $destinations, $start_date, $pax, $days, $dh, $bh, $cskh;

	public function rules()
	{
		return [
			[['code', 'name', 'destinations', 'start_date', 'pax', 'days', 'dh', 'bh', 'cskh'], 'trim'],
			[['code', 'name', 'destinations', 'start_date', 'pax', 'days'], 'required'],
		];
	}

}