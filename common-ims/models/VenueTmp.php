<?php

namespace common\models;

use Yii;

class VenueTmp extends MyActiveRecord
{
    public $test;

    public static function tableName()
    {
        return 'venue_tmp';
    }

    public function rules()
    {
        return [
            [[
                'cat', 'cmt', 'loc', 'price_min', 'price_max',
                'room_total_count',
                'fac_lift', 'fac_pool', 'fac_garden', 'fac_spa', 'fac_restaurant', 'fac_breakfast_type',
                'rooms', 'is_eco',
                'rec_couple', 'rec_family', 'rec_group', 'rec_honeymoon', 'rec_demanding',
                'rating_bedding', 'rating_service', 'rating_value', 'rating_cleanliness', 'rating_general',
                'verdict',
                'test',
            ], 'trim'],
            [[
                'cat',
            ], 'required', 'message'=>Yii::t('app', 'Required')]
        ];
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'updated_by']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

}
