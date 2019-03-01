<?php
namespace common\models;

class Ct extends MyActiveRecord
{
	public static function tableName() {
		return '{{%ct}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules()
	{
		return [
			[['title', 'about', 'intro', 'points', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'tags'], 'filter', 'filter'=>'trim'],
			[['offer_type', 'language', 'title', 'about', 'pax', 'day_from', 'price_unit', 'price_for', 'price_until'], 'required'],
			[['pax'], 'integer', 'min'=>0],
			[['price'], 'number', 'min'=>0],
			[['day_from', 'price_until'], 'date', 'format'=>'Y-m-d', 'message'=>'Date must be of "yyyy-mm-dd" format'],
		];
	}

	public function scenarios()
	{
		return [
			'copy'=>['title', 'intro'],
			'create'=>['language', 'offer_type', 'title', 'about'],
			'update'=>[],
			'ct_c'=>['title', 'about', 'offer_type', 'language', 'pax', 'day_from', 'intro', 'points', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
			'ct_u'=>['title', 'about', 'offer_type', 'language', 'pax', 'day_from', 'intro', 'points', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
		];
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getDays()
	{
		return $this->hasMany(Day::className(), ['rid'=>'id']);
	}

	public function getTour()
	{
		return $this->hasOne(Tour::className(), ['ct_id'=>'id']);
	}

	public function getCases()
	{
		return $this->hasMany(Kase::className(), ['id'=>'case_id'])
			->viaTable('at_xproposals', ['rid'=>'id']);
	}

}
