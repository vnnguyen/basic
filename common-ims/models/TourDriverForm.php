<?php

namespace common\models;

use yii\base\Model;

class TourDriverForm extends Model
{
	public $vehicleType;
	public $vehicleNumber;
	public $driverCompany;
	public $driverName;
	public $useFromDt;
	public $useUntilDt;
	public $useTimezone;
	public $points;
	public $note;
	public $bookingStatus;

	public static $bookingStatusList = [
		'draft'=>'Draft',
		'planned'=>'Planned',
		'request-sent'=>'Request sent',
		'request-rejected'=>'Request rejected',
		'wait-list'=>'Wait list',
		'canceled'=>'Canceled',
		'confirmed'=>'Confirmed',
	];

	public function attributeLabels()
	{
		return [
			'vehicleType'=>'Vehicle type',
			'vehicleNumber'=>'Vehicle number',
			'driverName'=>'Company',
			'driverName'=>'Name of driver',
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
			[['vehicleType', 'vehicleNumber', 'driverCompany', 'driverName', 'useFromDt', 'useUntilDt', 'useTimezone', 'points', 'note', 'bookingStatus'], 'trim'],
			[['vehicleType', 'driverName', 'useFromDt', 'useUntilDt', 'useTimezone', 'bookingStatus'], 'required', 'message'=>'Required'],
			[['points'], 'default', 'value'=>0],
			[['points'], 'integer'],
		];
	}

}