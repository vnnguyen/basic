<?php

namespace common\models;

// 161121 phuc vu cho DVSP bo sung
class Ncc2 extends MyActiveRecord
{
    public static function tableName() {
        return 'ncc';
    }

    public function rules() {
        return [
            [['status', 'venue_id', 'ma', 'ten', 'ten_cty', 'mst', 'diachi', 'so_tk', 'nganhang', 'note'], 'trim'],
            [['status', 'ten', 'ten_cty'], 'required'],
        ];
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id' => 'venue_id']);
    }
}
