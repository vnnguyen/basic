<?php

namespace common\models;

class TourGuide2 extends MyActiveRecord
{
    public static function tableName() {
        return '{{%tour_guides}}';
    }

    public function getGuide()
    {
        return $this->hasOne(User::className(), ['id'=>'guide_user_id']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getProfile()
    {
        return $this->hasOne(ProfileTourguide::className(), ['user_id'=>'guide_user_id']);
    }
}