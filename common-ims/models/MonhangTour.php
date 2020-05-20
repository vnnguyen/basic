<?php
namespace common\models;

use Yii;

class MonhangTour extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'a_monhang_tour';
    }

    public function rules()
    {
        return [
            [[
                'monhang_id', 'tour_id', 'use_from_dt', 'use_until_dt', 'status', 'note'
                ], 'trim'],
            [[
                'monhang_id', 'tour_id', 'use_from_dt', 'use_until_dt', 'status', 'note'
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function getMonhang()
    {
        return $this->hasOne(Monhang::className(), ['id'=>'monhang_id']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }
}
