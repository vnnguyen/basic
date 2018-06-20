<?php
namespace common\models;

class ProfileTourguide extends MyActiveRecord
{

	public static function tableName()
	{
		return '{{%profiles_tourguide}}';
	}

	public function attributeLabels()
	{
		return [
			'guide_since'=>'Vào nghề từ',
			'guide_us_since'=>'Làm với Amica từ',
			'languages'=>'Các ngôn ngữ nói được',
			'tour_types'=>'Các loại tour đi được',
			'regions'=>'Vùng miền hoạt động guide',
			'pros'=>'Điểm mạnh',
			'cons'=>'Điểm yếu',
			'ratings'=>'Điểm đánh giá',
			'note'=>'Ghi chú khác',
		];
	}

	public function rules()
	{
		return [
			[['languages', 'tour_types', 'regions', 'ratings'], 'required'],
			[['guide_since', 'guide_us_since'], 'default', 'value'=>'0000-00-00'],
			[['guide_since', 'guide_us_since', 'languages', 'tour_types', 'regions', 'pros', 'cons', 'ratings', 'note'], 'trim'],
		];
	}

	public function scenarios() {
		return [
			'tourguide/c'=>['guide_since', 'guide_us_since', 'languages', 'tour_types', 'regions', 'pros', 'cons', 'ratings', 'note'],
			'tourguide/u'=>['guide_since', 'guide_us_since', 'languages', 'tour_types', 'regions', 'pros', 'cons', 'ratings', 'note'],
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
