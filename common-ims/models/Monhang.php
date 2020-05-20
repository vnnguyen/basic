<?php
namespace common\models;

class Monhang extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'a_monhang';
    }

    public function getMathang()
    {
        return $this->hasOne(Mathang::className(), ['id'=>'mathang_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }
}
