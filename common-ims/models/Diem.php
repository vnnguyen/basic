<?php
namespace common\models;

use Yii;

class Diem extends MyActiveRecord
{

    public static function tableName() {
        return 'diem';
    }

    public function rules()
    {
        return [
            [[
                'name', 'latlng', 'address', 'description', 'body', 'note'
                ], 'trim'],
            [[
                'name',
                ], 'required', 'message'=>Yii::t('app', 'Required')],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'created_by']);
    }
}
