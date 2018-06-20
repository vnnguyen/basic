<?php
namespace common\models;

class Dvv extends MyActiveRecord
{

	public static function tableName() {
		return '{{%dvv}}';
	}

	public function attributeLabels() 
	{
		return [
			'code'=>'Country code',
			'dial_code'=>'Dial code',
			'name_en'=>'Name in English',
			'name_en'=>'Name in French',
			'name_vi'=>'Name in Vietnamese',
			'stype'=>'Type of service',
		];
	}

	public function rules()
	{
		return [
			['name', 'string', 'min'=>3, 'max'=>64],
			['name, stype, company_id, status', 'required'],
			//['status', 'range', 'range'=>['on', 'off', 'draft', 'deleted']],
			['name', 'filter', 'filter'=>'trim'],
			['company_id', 'number', 'integerOnly'=>true, 'message'=>'Company name is not valid'],
		];
	}

	public function getCompany()
	{
		return $this->hasOne('Company', array('id' => 'company_id'));
	}

}
