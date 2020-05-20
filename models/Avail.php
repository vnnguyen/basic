<?php

namespace app\models;

use Yii;

class Avail extends MyActiveRecord
{
    public static function tableName()
    {
        return 'at_avails';
    }

    public function _rules()
    {
        return [
            [[
                'test',
                'name', 'description',
                'tour_code', 'complaint_date', 'stype', 'severity',
                'complaint_location',
                'status', 'actions',
                'involving', 'owner_id', 'incident_id', 'owners',
                ], 'trim'],
            [[
                'name', 'description',
                'tour_code', 'complaint_date', 'stype', 'severity',
                'status',
                'owner_id',
                ], 'required', 'message'=>\Yii::t('app', 'Required')],
            [[
                'incident_id',
                ], 'default', 'value'=>0],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

}
