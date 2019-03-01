<?php

namespace common\models;

use Yii;

class Account extends MyActiveRecord
{

    public static function tableName()
    {
        return 'accounts';
    }

    public function rules()
    {
        return [
            [['name', 'status', 'fu_name', 'fu_email', 'fu_password'], 'trim'],
            [['fu_email'], 'email', 'message'=>'Invalid email address'],
            [['name', 'status'], 'required', 'message'=>'Required'],
        ];
    }

    public function scenarios()
    {
        return [
            'account/c'=>[
                'status', 'name', 'subscriptions', 'note'
            ],
            'account/u'=>[
                'status', 'name', 'subscriptions', 'note', 'fu_name', 'fu_email', 'fu_password'
            ],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['account_id' => 'id']);
    }

}
