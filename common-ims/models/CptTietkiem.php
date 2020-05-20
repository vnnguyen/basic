<?php

namespace common\models;

class CptTietkiem extends MyActiveRecord
{
    public static function tableName() {
        return 'cpt_tietkiem';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['amount', 'currency'], 'trim'],
            [['amount'], 'number', 'min'=>0],
            [['amount', 'currency'], 'required'],
        ];
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'updated_by']);
    }

    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['id'=>'cpt_id']);
    }

}
