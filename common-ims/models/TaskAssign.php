<?php
namespace common\models;

class TaskAssign extends MyActiveRecord {

    public static function tableName()
    {
        return '{{%task_user}}';
    }

    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    public function getAssignee()
    {
        return $this->hasOne(User2::className(), ['id' => 'user_id']);
    }

}
