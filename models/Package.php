<?php

namespace common\models;

class Package extends MyActiveRecord
{
    public static function tableName() {
        return 'packages';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'unique'],
            [['name', 'info'], 'trim'],
            [['name'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'package/c'=>['name', 'info'],
            'package/u'=>['name', 'info'],
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
}
