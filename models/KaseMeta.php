<?php
namespace common\models;

class KaseMeta extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%case_metas}}';
	}

	public function attributeLabels()
	{
		return [
			'pax_count_min'=>'Pax',
			'pax_count_max'=>'Pax',
			'day_count_min'=>'Days',
			'day_count_max'=>'Days',
			'destinations'=>'Destinations',
		];
	}

	public function rules()
	{
		return [
			[['case_id'], 'unique'],
			[['pax_count_min', 'pax_count_max', 'day_count_min', 'day_count_max'], 'default', 'value'=>0],
			[['pax_count_min', 'pax_count_max', 'day_count_min', 'day_count_max'], 'integer', 'min'=>0],
			[['destinations'], 'trim'],
		];
	}

	public function scenarios()
	{
		return [
			'cases_c'=>['pax_count_min', 'pax_count_max', 'day_count_min', 'day_count_max', 'destinations'],
		];
	}

	public function getKase() {
		return $this->hasOne(Kase::className(), ['id' => 'case_id']);
	}

	public function getUpdatedBy() {
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}

}
