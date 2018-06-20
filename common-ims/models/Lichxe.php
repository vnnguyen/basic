<?php

namespace common\models;

use Yii;

class Lichxe extends MyActiveRecord
{

    public static function tableName() {
        return 'lichxe';
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

    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['id' => 'cpt_id']);
    }
}
