<?php
namespace app\models;

use Yii;

class Destination extends MyActiveRecord
{

	public static function tableName()
	{
		return 'destinations';
	}

	public function attributeLabels()
	{
		return [
			'name_en'=>'Name in English',
			'name_fr'=>'Name in French',
			'name_vi'=>'Name in Vietnamese',
			'name_local'=>'Name in local language',
			'parent_destination_id'=>'Parent destination',
			'latlng'=>'Lat/Long',
			'country_code'=>'Country',
			'parent_id'=>'Parent destination',
			'description'=>'Description',
		];
	}

	public function rules()
	{
		return [
			[[
				'latitude', 'longitude', 'parent_id', 'description',
				], 'trim'],
			[[
				'name_en', 'name_fr', 'name_vi', 'name_local', 'country_code',
				], 'required', 'message'=>Yii::t('x', 'Required')],
			[[
				'name_en', 'name_fr', 'name_vi', 'name_local',
				], 'unique', 'message'=>Yii::t('x', 'Duplication found')],
		];
	}

	public function scenarios()
	{
		return [
			'destinations/c'=>['country_code', 'parent_id', 'name_en', 'name_fr', 'name_vi', 'name_local', 'description', 'latitude', 'longitude'],
			'destinations/u'=>['country_code', 'parent_id', 'name_en', 'name_fr', 'name_vi', 'name_local', 'description', 'latitude', 'longitude'],
		];
	}

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['code'=>'country_code']);
    }
}
