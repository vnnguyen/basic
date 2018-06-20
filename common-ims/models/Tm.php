<?php

namespace common\models;

// Tour mau

class Tm extends Product
{

    public static function tableName() {
        return '{{%ct}}';
    }

    public function rules() {
        return [
            [['language', 'title', 'intro', 'tags', 'summary'], 'trim'],
            [['language', 'title'], 'required'],
        ];
    }

    public function scenarios() {
        return [
            'tm/c'=>['language', 'title', 'intro', 'tags', 'summary'],
            'tm/u'=>['language', 'title', 'intro', 'tags', 'summary'],
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

    public function getDays()
    {
        return $this->hasMany(Nm::className(), ['rid'=>'id']);
    }

}
