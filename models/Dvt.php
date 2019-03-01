<?php
namespace common\models;

class Dvt extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dvt';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [];
    }

    public function getTour()
    {
        return $this->hasOne(Tour::className(), ['id'=>'tour_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getDv()
    {
        return $this->hasOne(Dv::className(), ['id'=>'dv_id']);
    }

    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['dvt_id'=>'id']);
    }
}
