<?php
namespace common\models;

class Diemlx extends MyActiveRecord
{
	public static function tableName() {
		return '{{%diemlx}}';
	}

	public function attributeLabels() {
		return [
			'tour_id'=>'Tour',
			'driver_user_id'=>'Lái xe',
			'points'=>'Điểm',
			'from_dt'=>'Từ ngày',
			'until_dt'=>'Đến ngày',
			'note'=>'Ghi chú',
		];
	}

	public function rules() {
		return [
			[['note', 'from_dt', 'until_dt'], 'trim'],
			[['tour_id', 'driver_user_id'], 'required'],
			[['tour_id', 'driver_user_id'], 'integer'],
			[['points'], 'integer', 'min'=>0],
			[['points'], 'default', 'value'=>0],
		];
	}

	public function xscenarios() {
		return [
			'diemlx/c'=>['title', 'about', 'offer_type', 'language', 'pax', 'day_from', 'intro', 'points', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
			'diemlx/u'=>['title', 'about', 'offer_type', 'language', 'pax', 'day_from', 'intro', 'points', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getDriver()
	{
		return $this->hasOne(User::className(), ['id'=>'driver_user_id']);
	}

	public function getDays()
	{
		return $this->hasMany(Day::className(), ['rid'=>'id']);
	}

	public function getTour()
	{
		return $this->hasOne(Tour::className(), ['id'=>'tour_id']);
	}

	public function getProduct()
	{
		return $this->hasOne(Product::className(), ['id'=>'product_id']);
	}
}
