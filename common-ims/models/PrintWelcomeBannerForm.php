<?php
namespace common\models;

use yii\base\Model;

class PrintWelcomeBannerForm extends Model
{
	public $template;
	public $language;
	public $logo;
	public $names;
	public $extra;
	public $pax;
	public $location;
	public $time;
	public $output = 'pdf-download';

	public function attributeLabels()
	{
		return [
			'template'=>'Banner template',
			'names'=>'Pax names (max 3 lines)',
			'extra'=>'Extra information',
			'pax'=>'Number of pax',
			'location'=>'Flight/Train number or Pick-up location',
			'time'=>'Pick-up time',
		];
	}

	public function rules()
	{
		return [
			[['template', 'language', 'logo', 'names', 'extra', 'pax', 'location', 'time'], 'trim'],
			[['template', 'language', 'logo', 'names', 'output'], 'required', 'message'=>'Required'],
		];
	}

}