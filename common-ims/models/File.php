<?php
namespace common\models;

class File extends MyActiveRecord
{

	public static function tableName() {
		return '{{%files}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules()
	{
		return [
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'cb']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'ub']);
	}
}
