<?php
namespace app\models;

class CptEdit extends MyActiveRecord
{

    public static function tableName()
    {
        return 'cpt_edits';
    }

    public function getCost()
    {
        return $this->hasOne(Cost::className(), ['id'=>'cpt_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'edit_by']);
    }

}
