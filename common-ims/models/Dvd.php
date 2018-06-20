<?php
namespace common\models;

class Dvd extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dvd';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [[
                'stype', 'code', 'def', 'desc',
                ], 'trim'],
            [[
                'stype', 'code', 'def',
                ], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'dvd/c'=>[
                'stype', 'code', 'def', 'desc',
                ],
            'dvd/u'=>[
                'stype', 'code', 'def', 'desc',
                ],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id'=>'supplier_id']);
    }

    public function getDvc()
    {
        return $this->hasOne(Dvc::className(), ['id'=>'dvc_id']);
    }
}
