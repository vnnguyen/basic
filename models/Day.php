<?php
namespace app\models;

class Day extends MyActiveRecord
{
    public static function tableName()
    {
        return 'days';
    }

    public function rules()
    {
        return [
            [[
                'day', 'name', 'body', 'image', 'meals', 'guides', 'transport', 'note', 'summary'
                ], 'trim'],
            [[
                'name', 'body'
                ], 'required'],
            [[
                'day'
                ], 'default', 'value'=>'0000-00-00'],
        ];
    }

    public function scenarios()
    {
        return [
            'day/c'=>[
                'day', 'name', 'body', 'summary', 'image', 'meals', 'guides', 'transport', 'note'
            ],
            'days/c'=>[
                'day', 'name', 'body', 'summary', 'image', 'meals', 'guides', 'transport', 'note'
            ],
            'days/u'=>[
                'day', 'name', 'body', 'summary', 'image', 'meals', 'guides', 'transport', 'note'
            ],
            'day/u'=>[
                'day', 'name', 'body', 'summary', 'image', 'meals', 'guides', 'transport', 'note'
            ],
            'products_copy'=>[],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id'=>'rid']);
    }

    public function getTrans()
    {
        return $this->hasOne(DayTrans::className(), ['day_id'=>'id']);
    }

    public function getVung()
    {
        return $this->hasMany(Vung::className(), ['day_id'=>'id']);
    }

}
