<?php
namespace common\models;

use Yii;

class InvWarehouse extends MyActiveRecord
{
    public static function tableName() {
        return 'inv_warehouses';
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

    public function getItems()
    {
        return $this->hasMany(InvItem::className(), ['warehouse_id' => 'id']);
    }

}
