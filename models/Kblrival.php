<?php
namespace common\models;

class Kblrival extends MyActiveRecord
{

	public static function tableName() {
		return '{{%kbl_rivals}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [
			[['name', 'status'], 'filter', 'filter' => 'trim'],
		];
	}

	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'author_id']);
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
