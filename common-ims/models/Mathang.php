<?php
namespace common\models;

class Mathang extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'a_mathang';
    }

    public function getMonhang()
    {
        return $this->hasMany(Monhang::className(), ['mathang_id'=>'id']);
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
