<?php
namespace app\models;

class Transaction extends MyActiveRecord
{

    public static function tableName()
    {
        return 'transactions';
    }

    public function rules() {
        return [];
    }

    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['id'=>'cpt_id']);
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
