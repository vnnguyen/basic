<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TourTestForm extends Model
{
	public $service_time;
	public $note;

	public function attributeLabels()
	{
		return [
			'service_time'=>Yii::t('tour', 'Service time'),
			'note'=>Yii::t('tour', 'Note'),
		];
	}

	public function rules()
	{
		return [
			[['service_time', 'note'], 'trim'],
			[['service_time'], 'required', 'message'=>Yii::t('mn', 'Required')],
		];
	}

}