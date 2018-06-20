<?php
namespace common\models;

class Destination extends MyActiveRecord
{

	public static function tableName() {
		return '{{%destinations}}';
	}

	public function attributeLabels() {
		return [
			'name_en'=>'Name in English',
			'name_fr'=>'Name in French',
			'name_vi'=>'Name in Vietnamese',
			'name_local'=>'Name in local language',
			'parent_destination_id'=>'Parent destination',
			'latlng'=>'Lat/Long',
			'country_code'=>'Country',
		];
	}

	public function rules()
	{
		return [
			[['latlng'], 'filter', 'filter'=>'trim'],
			[['name_en', 'name_fr', 'name_vi', 'name_local', 'country_code'], 'required'],
			[['name_en', 'name_fr', 'name_vi', 'name_local'], 'unique'],
		];
	}

	public function scenarios()
	{
		return [
			'create'=>['name_en', 'name_fr', 'name_vi', 'name_local', 'latlng', 'country_code'],
			'update'=>['name_en', 'name_fr', 'name_vi', 'name_local', 'latlng', 'country_code'],
		];
	}

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['code'=>'country_code']);
    }
}
