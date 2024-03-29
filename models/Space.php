<?php
namespace app\models;

class Space extends MyActiveRecord
{
    public static function tableName() {
        return 'spaces';
    }

    public function rules()
    {
        return [
            [['name', 'description'], 'trim'],
            [['name'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'spaces/c'=>['name', 'description'],
            'spaces/u'=>['name', 'description'],
        ];
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

}
