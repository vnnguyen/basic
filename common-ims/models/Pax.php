<?php

namespace common\models;

use Yii;

// Pax info
class Pax extends MyActiveRecord
{

    public static function tableName()
    {
        return 'pax';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function _rules()
    {
        return [
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

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id' => 'tour_id']);
    }
}
