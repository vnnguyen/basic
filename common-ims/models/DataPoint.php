<?php
namespace common\models;

// Client page links

class DataPoint extends MyActiveRecord
{
    public static function tableName()
    {
        return 'data_points';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
        ];
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }
}
