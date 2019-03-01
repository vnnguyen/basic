<?php
namespace common\models;

class Role extends MyActiveRecord
{
    public static function tableName()
    {
        return '{{%roles}}';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [[
                'name', 'alias', 'status', 'note',
                ], 'trim'],
            [[
                'name', 'alias', 'status',
                ], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'role/c'=>['name', 'alias', 'status', 'note'],
            'role/u'=>['name', 'alias', 'status', 'note'],
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('at_role_user', ['role_id'=>'id']);
    }

}
