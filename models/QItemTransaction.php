<?php
namespace common\models;

use Yii;

class QItemTransaction extends MyActiveRecord
{
    public static function tableName() {
        return 'q_item_transactions';
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

    public function getItem()
    {
        return $this->hasOne(QItem::className(), ['id' => 'item_id']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id' => 'tour_id']);
    }

}
