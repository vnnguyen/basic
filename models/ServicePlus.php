<?php
namespace common\models;

use Yii;

class ServicePlus extends MyActiveRecord
{
    public $code;

    public static function tableName()
    {
        return 'services_plus';
    }

    public function rules()
    {
        return [
            [[
                'name', 'svc_date', 'svc_success', 'code', 'context', 'sv', 'cp', 'result',
            ], 'trim'],
            [[
                'name', 'svc_date', 'code',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

}
