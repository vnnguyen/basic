<?php

namespace app\models;

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
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getCosts()
    {
        return $this->hasMany(Cost::className(), ['id'=>'cpt_id'])
            ->viaTable('cpt_tietkiem_link', ['tkiem_id'=>'id']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

}
