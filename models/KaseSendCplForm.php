<?php

namespace app\models;

use Yii;
use yii\base\Model;

class KaseSendCplForm extends Model
{
	public $booking_id;
	public $user_id;
	public $message;
	public $attachments = [];

	public function attributeLabels()
	{
		return [
			'booking_id'=>Yii::t('mn', 'Tour itinerary'),
			'user_id'=>Yii::t('mn', 'Recipient'),
			'message'=>Yii::t('mn', 'Message to customer'),
			'attachments'=>Yii::t('mn', 'Attachments'),
		];
	}

	public function rules()
	{
		return [
			[['message'], 'trim'],
			[['booking_id', 'user_id', 'message'], 'required', 'message'=>Yii::t('mn', 'Required')],
			[['attachments'], 'safe'],
		];
	}

}