<?php
namespace app\models;

class Reaction extends MyActiveRecord
{

    public static function tableName()
    {
        return 'reactions';
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

}
