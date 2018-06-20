<?php

namespace common\models;

class Group extends MyActiveRecord
{
    public static function tableName()
    {
        return 'groups';
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Name of group',
            'alias'=>'Short name',
        ];
    }

    public function rules()
    {
        return [
            [[
                'name', 'alias', 'info',
                ], 'trim'],
            [[
                'name', 'alias',
                ], 'required'],
        ];
    }

    public function getMembers()
    {
        // \fCore::expose($this);
        // echo $this->stype; exit; 
        // if ($this->stype == 'user') {
            return $this->hasMany(User::className(), ['id' => 'user_id'])
                ->viaTable('group_user', ['group_id'=>'id']);
        // }
        return null;
    }

}
