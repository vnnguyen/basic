<?php
namespace common\models;

class ProfileDriver extends MyActiveRecord
{

	public static function tableName()
	{
		return '{{%profiles_driver}}';
	}

	public function attributeLabels()
	{
		return [
			'since'=>'Vào nghề từ',
			'us_since'=>'Làm với Amica từ',
			'languages'=>'Các ngôn ngữ nói được',
			'vehicle_types'=>'Các loại xe lái được',
			'tour_types'=>'Các loại tour đi được',
			'regions'=>'Vùng miền hoạt động',
			'pros'=>'Điểm mạnh',
			'cons'=>'Điểm yếu',
			'points'=>'Điểm đánh giá chung',
			'note'=>'Ghi chú khác',
		];
	}

	public function rules()
	{
		return [
			[['vehicle_types', 'languages', 'tour_types', 'regions', 'points'], 'required'],
			//[['guide_since', 'guide_us_since'], 'date', 'format'=>'Y-m-d'],
			[['since', 'us_since', 'vehicle_types', 'languages', 'tour_types', 'regions', 'pros', 'cons', 'points', 'note'], 'filter', 'filter'=>'trim'],
		];
	}

	public function scenarios() {
		return [
			'drivers/c'=>['since', 'us_since', 'vehicle_types', 'languages', 'tour_types', 'regions', 'pros', 'cons', 'points', 'note'],
			'drivers/u'=>['since', 'us_since', 'vehicle_types', 'languages', 'tour_types', 'regions', 'pros', 'cons', 'points', 'note'],
		];
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}
}
