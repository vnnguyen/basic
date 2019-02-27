<?php

namespace common\models;

use Yii;

class Complaint extends MyActiveRecord
{
    public $test;
    public $tour_code;

    public static function tableName()
    {
        return 'complaints';
    }

    public function rules()
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
        return $this->hasOne(User2::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'updated_by']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getOwner()
    {
        return $this->hasOne(User2::className(), ['id'=>'owner_id']);
    }

    public function getIncident()
    {
        return $this->hasOne(Incident::className(), ['id'=>'incident_id']);
    }

    public function beforeSave($insert)
    {
        $this->involving = isset($this->involving) && is_array($this->involving) ? implode('|', $this->involving) : '';
        $this->actions = isset($this->actions) && is_array($this->actions) ? implode('|', $this->actions) : '';
        $this->owners = isset($this->owners) && is_array($this->owners) ? implode('|', $this->owners) : '';
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->involving = array_filter(explode('|', $this->involving));
        $this->actions = array_filter(explode('|', $this->actions));
        $this->owners = array_filter(explode('|', $this->owners));
        return parent::afterFind();
    }
}
