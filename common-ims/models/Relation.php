<?php
namespace common\models;

class Relation extends MyActiveRecord
{
    public static function tableName() {
        return 'relations';
    }

    public function getUpdatedBy() {
        return $this->hasOne(User2::className(), ['id' => 'updated_by']);
    }
}
