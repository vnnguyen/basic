<?php
namespace common\models;

class Inquiry extends MyActiveRecord {

	public static function tableName()
	{
		return 'at_inquiries';
	}

	public function rules()
	{
		return [
			[['case_id'], 'default', 'value'=>0],
			[['case_id'], 'required'],
			[['case_id'], 'integer', 'min'=>0],
		];
	}

	public function scenarios()
	{
		return [
			'inquiries/c'=>[],
			'inquiries/u'=>['case_id'],
		];
	}

	public function getKase()
	{
		return $this->hasOne(Kase::className(), ['id' => 'case_id']);
	}
	public function getCase()
	{
		return $this->hasOne(Kase::className(), ['id' => 'case_id']);
	}
	public function getSite()
	{ 
		return $this->hasOne(Site::className(), ['id' => 'site_id']);
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
