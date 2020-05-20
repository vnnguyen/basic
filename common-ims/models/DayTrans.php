<?php
namespace common\models;

class DayTrans extends MyActiveRecord
{
    public static function tableName()
    {
        return 'day_trans';
    }

    public function rules()
    {
        return [
            [[
                'title_trans', 'body_trans',
                ], 'trim'],
        ];
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'updated_by']);
    }

    public function getDay()
    {
        return $this->hasOne(Day::className(), ['id'=>'day_id']);
    }
}
