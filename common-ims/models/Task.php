<?php
namespace common\models;

class Task extends MyActiveRecord {

    public static function tableName()
    {
        return '{{%tasks}}';
    }

    public function getAssignees()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('{{%task_user}}', ['task_id'=>'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'cb']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getTour()
    {
        //if ($this->rtype == 'tour') {
            return $this->hasOne(Tour::className(), ['id' => 'rid']);
        //}
    }
}
