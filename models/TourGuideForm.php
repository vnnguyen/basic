<?php

namespace common\models;

use yii\base\Model;

class TourGuideForm extends Model
{
	public $guideCompany;
	public $guideName;
	public $useFromDt;
	public $useUntilDt;
	public $useTimezone;
	public $points;
	public $note;
	public $bookingStatus;
	public $useStatus;

	public static $bookingStatusList = [
		'draft'=>'Draft',
		'planned'=>'Planned',
		'request-sent'=>'Request sent',
		'request-rejected'=>'Request rejected',
		'wait-list'=>'Wait list',
		'canceled'=>'Canceled',
		'confirmed'=>'Confirmed',
	];

	public static $useStatusList = [
		'pending'=>'Pending',
		'in-use'=>'In use',
		'not-used'=>'Not used',
		'used'=>'Used',
	];

	public function attributeLabels()
	{
		return [
			'guideName'=>'Company',
			'guideName'=>'Name of tour guide',
			'useFromDt'=>'Service time from',
			'useUntilDt'=>'Service time until',
			'useTimezone'=>'Timezone',
			'note'=>'Note',
			'bookingStatus'=>'Booking status',
		];
	}

	public function rules()
	{
		return [
			[['guideCompany', 'guideName', 'useFromDt', 'useUntilDt', 'useTimezone', 'points', 'note', 'bookingStatus'], 'trim'],
			[['guideName', 'useFromDt', 'useUntilDt', 'useTimezone', 'bookingStatus'], 'required', 'message'=>'Required'],
			[['points'], 'default', 'value'=>0],
			[['points'], 'integer'],
			[['guideCompany'], 'default', 'value'=>''],
		];
	}

}