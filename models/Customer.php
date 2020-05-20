<?php
namespace app\models;

use Yii;

class Customer extends MyActiveRecord
{
    public static function tableName()
    {
        return 'customers';
    }

    public function rules()
    {
        return [
            [[
                'name',
                ], 'trim'],
            [[
                'name',
                ], 'required', 'message'=>Yii::t('x', 'Required.')],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id'=>'owner_user_id']);
    }
}
