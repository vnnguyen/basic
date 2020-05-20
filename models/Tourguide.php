<?php
namespace app\models;

class TourGuide extends MyActiveRecord
{
    public static function tableName() {
        return 'tour_guides';
    }

    public function getGuide()
    {
        return $this->hasOne(Contact::className(), ['id'=>'guide_user_id']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getProfile()
    {
        return $this->hasOne(TourguideProfile::className(), ['user_id'=>'guide_user_id']);
    }
}