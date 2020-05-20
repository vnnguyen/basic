<?php
namespace common\models;

class Incident extends MyActiveRecord
{
    public $test;
    public $tour_code;

    public static function tableName()
    {
        return 'incidents';
    }

    public function rules()
    {
        return [
            [[
                'test',
                'name', 'description',
                'tour_code', 'incident_date', 'stype', 'severity',
                'incident_location',
                'status', 'actions',
                'involving', 'owner_id', 'owners',
                ], 'trim'],
            [[
                'name', 'description',
                'tour_code', 'incident_date', 'stype', 'severity',
                'status',
                'owner_id',
                ], 'required', 'message'=>\Yii::t('app', 'Required')],
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

    public function getComplaints()
    {
        return $this->hasMany(Complaint::className(), ['incident_id'=>'id']);
    }

    public function getOwner()
    {
        return $this->hasOne(User2::className(), ['id'=>'owner_id']);
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
