<?php
namespace common\models;

use yii\db\ActiveRecord;

class Kblist extends MyActiveRecord
{

	public static function tableName() {
		return '{{%kblists}}';
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
