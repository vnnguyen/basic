<?php
namespace app\models;

class Task extends MyActiveRecord
{
    public $date, $time_fuzzy, $time_detail;

    public static function tableName()
    {
        return 'tasks';
    }

    public function rules()
    {
        return [
            [[
                'description', 'is_priority', 'date', 'time_fuzzy', 'time_detail', 'num', 'is_all',
            ], 'trim'],
        ];
    }

    public function scenarios()
    {
        return [
            'tasks/c'=>['description', 'is_priority', 'date', 'time_fuzzy', 'time_detail', 'num', 'is_all'],
            'tasks/u'=>['description', 'is_priority', 'date', 'time_fuzzy', 'time_detail', 'num', 'is_all'],
        ];
    }

    public function getAssignees()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('task_user', ['task_id'=>'id']);
    }

    public function getTaskAssign()
    {
        return $this->hasMany(TaskAssign::className(), ['task_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getTour()
    {
        // if ($this->rtype == 'tour') {
            return $this->hasOne(Product::className(), ['id' => 'rid']);
        // }
    }

    public function getRelated()
    {
        // \fCore::expose($this); exit;
        // if ($this->rtype == 'case') {
            return $this->hasOne(File::className(), ['id' => 'rid']);
        // } elseif ($this->rtype == 'tour') {
            return $this->hasOne(Tour::className(), ['id' => 'rid']);
        // }
    }
}
