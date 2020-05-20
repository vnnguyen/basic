<?php
namespace app\models;

class TourUser extends MyActiveRecord
{

    public static function tableName() {
        return 'tour_user';
    }

    public function getTour()
    {
        return $this->hasOne(Tour::className(), ['id'=>'tour_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

}
