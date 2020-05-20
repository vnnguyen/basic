<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TourInCtForm extends Model
{
	public $language;
	public $days;
	public $sections;
	public $logo;
	public $note;

	public function attributeLabels()
	{
		return [
			'days'=>'In các ngày (vd 1-3,4,5-7)',
			'sections'=>'In các phần',
			'logo'=>'Logo',
			'note'=>'Ghi chú in kèm',
		];
	}

	public function rules()
	{
		return [
			[['days', 'sections', 'logo', 'note'], 'trim'],
			[['days', 'logo'], 'required', 'message'=>'Required'],
		];
	}

}