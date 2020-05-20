<?php
namespace common\models;

use Yii;

class InvItem extends MyActiveRecord
{
    public static function tableName() {
        return 'inv_items';
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
}
