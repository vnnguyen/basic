<?php
namespace app\models;

use Yii;

class RoomType extends MyActiveRecord
{
    public static function tableName()
    {
        return 'room_types';
    }

    public function rules()
    {
        return [
            [[
                'status', 'stype', 'name', 'place_id', 'info',
            ], 'trim'],
            [[
                'status', 'stype', 'name',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
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

    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id'=>'place_id']);
    }

}
