<?php
namespace common\models;

class Site extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%sites}}';
	}

	public function getCreatedBy() {
		return $this->hasOne('User', ['id' => 'created_by']);
	}

	public function getUpdatedBy() {
		return $this->hasOne('User', ['id' => 'updated_by']);
	}
}
