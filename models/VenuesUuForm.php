<?php
namespace common\models;

use common\models\User;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Change hotel general info, logo, image
 */
class VenuesUuForm extends Model
{
	public $image;
	public $image2;

	public $location;
	public $style;
	public $service;
	public $facilities;
	public $publicRatings;
	public $amicaRatings;
	public $notes;

	public function attributeLabels()
	{
		return [
			'image'=>'Hotel feature image',
			'image2'=>'TripAdvisor ratings image',
		];
	}

	public function rules()
	{
		return [
			[['image', 'image2', 'location', 'style', 'service', 'facilities', 'publicRatings', 'amicaRatings', 'notes'], 'filter', 'filter'=>'trim'],
			[['image', 'image2'], 'url'],
		];
	}
}