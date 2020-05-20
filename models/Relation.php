<?php
namespace app\models;

class Relation extends MyActiveRecord
{
    public static function tableName() {
        return 'relations';
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
