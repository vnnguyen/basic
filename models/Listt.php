<?php
namespace app\models;

use Yii;

class Listt extends MyActiveRecord
{
    public static function tableName() {
        return 'lists';
    }

    public function getItems()
    {
        return $this->hasMany(ListItem::className(), ['list_id' => 'id']);
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
