<?php

namespace common\models;

class TourDriver extends MyActiveRecord
{
    public static function tableName() {
        return '{{%tour_drivers}}';
    }

    public function getDriver()
    {
        return $this->hasOne(User::className(), ['id'=>'driver_user_id']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }
}