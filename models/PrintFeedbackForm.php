<?php

namespace app\models;

use yii\base\Model;

class PrintFeedbackForm extends Model
{
	public $version = 'new';
	public $logoName;
	public $language;
	public $printDays;
	public $paxName;
	public $regionName;
	public $guideNames;
	public $driverNames;

	public function attributeLabels()
	{
		return [
			'printDays'=>'Days to print, eg 1-4',
			'paxName'=>'Name of pax',
			'regionName'=>'Name of region (North, South, etc)',
		];
	}

	public function rules()
	{
		return [
			[['version', 'logoName', 'language', 'printDays', 'paxName', 'regionName', 'guideNames', 'driverNames'], 'trim'],
			[['version', 'paxName'], 'required', 'message'=>'Required'],
		];
	}

}