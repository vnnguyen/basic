<?php
namespace common\models;

use Yii;

class BookingReport extends MyActiveRecord
{
    public static function tableName()
    {
        return '{{%booking_reports}}';
    }

    public function rules()
    {
        return [
            [[
                'note',
                'price_vn', 'price_la', 'price_kh', 'price_mm',
                // 'price_th', 'price_my', 'price_id', 'price_ph',
                ], 'trim'],
            [[
                'pax_count', 'day_count', 'price', 'price_unit', 'cost', 'cost_unit',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'price', 'cost',
                'price_vn', 'price_la', 'price_kh', 'price_mm',
                // 'price_th', 'price_my', 'price_id', 'price_ph',
            ], 'integer', 'min'=>0, 'message'=>Yii::t('x', 'Invalid')],
            [[
                'price_unit', 'cost_unit',
            ], 'default', 'value'=>'USD'],
            [[
                'price_unit', 'cost_unit',
            ], 'in', 'range'=>['USD', 'VND', 'EUR'], 'message'=>Yii::t('x', 'Invalid')],
        ];
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getBooking() {
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }
}
