<?php
namespace app\models;

use Yii;

class Template extends MyActiveRecord
{
    public static function tableName()
    {
        return 'templates';
    }

    public function attributeLabels()
    {
        return [
            'start_dt'=>'Start date',
            'end_dt'=>'End date',
            'info'=>'More information',
        ];
    }

    public function rules()
    {
        return [
            [[
                'name', 'category', 'status', 'description', 'subject', 'content', 'note', 'sorder', 'language'
                ], 'trim'],
            [[
                'name', 'status', 'content'
                ], 'required', 'message'=>Yii::t('x', 'Required.')],
            [[
                'name'
                ], 'unique', 'message'=>Yii::t('x', 'Duplication found.')],
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
