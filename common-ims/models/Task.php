<?php
namespace common\models;

class Task extends MyActiveRecord {

    public static function tableName()
    {
        return '{{%tasks}}';
    }

    public function getAssignees()
    {
        return $this->hasMany(User2::className(), ['id' => 'user_id'])
            ->viaTable('{{%task_user}}', ['task_id'=>'id']);
    }

    public function getTaskAssign()
    {
        return $this->hasMany(TaskAssign::className(), ['task_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User2::className(), ['id' => 'cb']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id' => 'updated_by']);
    }

    public function getTour()
    {
        // if ($this->rtype == 'tour') {
            return $this->hasOne(Tour::className(), ['id' => 'rid']);
        // }
    }

    public function getRelated()
    {
        // \fCore::expose($this); exit;
        // if ($this->rtype == 'case') {
            return $this->hasOne(Kase::className(), ['id' => 'rid']);
        // } elseif ($this->rtype == 'tour') {
            return $this->hasOne(Tour::className(), ['id' => 'rid']);
        // }
    }
}
