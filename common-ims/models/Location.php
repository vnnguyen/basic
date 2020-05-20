<?php
namespace common\models;

class Location extends MyActiveRecord
{
	public static function tableName() {
		return 'locations';
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
