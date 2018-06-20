<?php
namespace common\models;

class ProdTcgTour extends MyActiveRecord
{

	public static function tableName() {
		return '{{%prod_tcgtour}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [];
	}

	public function getCt()
	{
		return $this->hasOne(Ct::className(), ['id'=>'ct_id']);
	}

	public function beforeSave($insert)
	{
	}

}
