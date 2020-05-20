<?php
namespace common\models;

use Yii;

class QAccount extends MyActiveRecord
{
    public static function tableName() {
        return 'q_accounts';
    }

    public function rules()
    {
        return [
            [[
                'name', 'description', 'image', 'code',
            ], 'trim'],
            [[
                'name',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
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

    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['account_id' => 'id']);
    }

}
