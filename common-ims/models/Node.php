<?php

namespace common\models;

class Node extends MyActiveRecord
{

    public static function tableName() {
        return 'nodes';
    }

    public function attributeLabels() {
        return [
            'body'=>'Content',
        ];
    }

    public function rules() {
        return [
            [['title', 'body'], 'trim'],
            [['body'], 'required'],
        ];
    }

    public function scenarios() {
        // return [
        //  'any/c'=>['body'],
        //  'create'=>['body'],
        //  'update'=>['body'],
        //  'events/r'=>['body'],
        //  'cpt/r'=>['body'],
        // ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

}
