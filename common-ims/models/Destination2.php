<?php
namespace common\models;

class Destination2 extends MyActiveRecord
{

    public static function tableName() {
        return 'destinations';
    }

    public function attributeLabels() {
        return [
            'name'=>Yii::t('x', 'Name'),
        ];
    }

    public function rules()
    {
        return [
            [[
                'name',
                ], 'trim'],
            [[
                'name',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
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
