<?php
namespace common\models;

use Yii;

class QItem extends MyActiveRecord
{
    public static function tableName() {
        return 'q_items';
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

    public function getTransactions()
    {
        return $this->hasMany(QItemTransaction::className(), ['item_id' => 'id']);
    }

}
