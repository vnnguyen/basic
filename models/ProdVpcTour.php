<?php
namespace common\models;

class ProdVpcTour extends MyActiveRecord
{

	public static function tableName() {
		return '{{%prod_vpctour}}';
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
