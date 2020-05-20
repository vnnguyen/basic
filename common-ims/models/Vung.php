<?php
namespace common\models;

class Vung extends MyActiveRecord
{
    public static function tableName()
    {
        return 'ngay_vung';
    }

    public function getDay()
    {
        return $this->hasOne(Day::className(), ['id'=>'day_id']);
    }
}
