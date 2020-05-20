<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TourAssignCsForm extends Model
{
	public $css = [];

	public function attributeLabels()
	{
		return [
			'css'=>'Customer Relations staff',
		];
	}

	public function rules()
	{
		return [
			[['css'], 'required', 'message'=>Yii::t('x', 'Required')],
		];
	}

}