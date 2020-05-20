<?php
namespace app\models;

use Yii;

class Cpt3 extends MyActiveRecord
{
    // TODO: tỉ giá phân loại nhỏ hơn nữa, vd tỉ giá mua vào bán ra etc

    public static function tableName()
    {
        return 'cpt3';
    }

    public function _attributeLabels()
    {
        return [
            'rate_dt'=>Yii::t('xrate', 'Time and date'),
            'currency1'=>Yii::t('xrate', 'Currency 1'),
            'currency2'=>Yii::t('xrate', 'Currency 2'),
            'rate'=>Yii::t('xrate', 'Exchange rate'),
            'note'=>Yii::t('xrate', 'Note'),
        ];
    }

    public function rules()
    {
        return [
            [[
                'name', 'qty', 'price', 'unit', 'currency',
                ], 'trim'],
            [[
                'name', 'qty', 'price', 'unit', 'currency',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
