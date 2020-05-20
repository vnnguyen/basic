<?php
namespace app\models;

class Sysnote extends MyActiveRecord
{
	public static function tableName() {
		return '{{%sysnotes}}';
	}

	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}
