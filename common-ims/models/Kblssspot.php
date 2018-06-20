<?php
namespace common\models;

class Kblssspot extends MyActiveRecord
{

	public static function tableName() {
		return '{{%kbl_ssspots}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [
			[['name', 'status'], 'filter', 'filter' => 'trim'],
			[['destination_id', 'name', 'summary', 'entry_order', 'status'], 'required'],
		];
	}

	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'author_id']);
	}

	public function getDestination()
	{
		return $this->hasOne(Destination::className(), ['id' => 'destination_id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($this->isNewRecord) {
				$this->created_at = NOW;
				$this->created_by = \Yii::$app->user->id;
			}
			$this->updated_at = NOW;
			$this->updated_by = \Yii::$app->user->id;
			return true;
		}
		return false;
	}
}
