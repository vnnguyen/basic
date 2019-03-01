<?php
namespace common\models;

class Referral extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%referrals}}';
	}

	public function rules()
	{
		return [
			[['ngay_xac_nhan', 'ngay_cam_on', 'ngay_ban_tour', 'ngay_hoi_qua', 'gift', 'ngay_chon_qua', 'ngay_gui_qua', 'info'], 'filter', 'filter'=>'trim'],
			[['points', 'points_minus'], 'required'],
			[['points', 'points_minus'], 'integer', 'min'=>0],
		];
	}

	public function attributeLabels()
	{
		return [
			'user_id'=>'Referring person',
			'case_id'=>'Case',
			'points_minus'=>'Points used',
		];
	}

	public function scenarios()
	{
		return [
			'referrals_u'=>['ngay_xac_nhan', 'ngay_cam_on', 'ngay_ban_tour', 'ngay_hoi_qua', 'gift', 'points', 'points_minus', 'ngay_chon_qua', 'ngay_gui_qua', 'info'],
		];
	}

	public function getCase()
	{
		return $this->hasOne(Kase::className(), ['id'=>'case_id']);
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id'=>'user_id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}
}
