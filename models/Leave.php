<?php
namespace app\models;

use Yii;

class Leave extends MyActiveRecord
{

    public static function tableName()
    {
        return 'leaves';
    }

    public function rules() {
        return [
            [[
                'from_dt', 'until_dt', 'stype', 'request',
                ], 'trim'],
            [[
                'from_dt', 'until_dt', 'stype', 'request',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function _scenarios()
    {
        return [
            'requests/c'=>[
                'from_dt', 'until_dt', 'stype', 'request',
                ],
            'requests/u'=>[
                'from_dt', 'until_dt', 'stype', 'request',
                ],
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

}
