<?php

namespace app\models;

class Meta extends MyActiveRecord
{

	public static function tableName() {
		return 'metas';
	}

	public function rules()
	{
		return [
			[['name', 'value'], 'required'],
			[['value'], 'trim'],
			[['value'], 'email', 'when'=>function($model) { return $model->name == 'email'; }, 'whenClient'=>"function (attribute, value) {return $('#meta-name').val() == 'email';}"],
		];
	}

}
