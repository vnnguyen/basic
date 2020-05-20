<?php
namespace app\models;

use Yii;

class ServicePlus extends MyActiveRecord
{
    public $code;
    public $reasons = [];

    public static function tableName()
    {
        return 'services_plus';
    }

    public function rules()
    {
        return [
            [[
                'svc_type', 'svc_link', 'svc_gifts', 'svc_date', 'svc_success', 'reasons', 'reason_detail', 'sv', 'cost_detail', 'result',
            ], 'trim'],
            [[
                'svc_type', 'sv', 'svc_date',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'svc_link',
            ], 'url', 'message'=>Yii::t('x', 'Invalid URL')],
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

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['rid'=>'id'])->andWhere(['rtype'=>'service-plus']);
    }

}
