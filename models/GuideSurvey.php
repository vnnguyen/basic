<?php
namespace app\models;

// Clien page link
use Yii;

class GuideSurvey extends MyActiveRecord
{

    public static function tableName()
    {
        return 'survey_answers';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            // [[
            //     'subject', 'message',
            //     ], 'trim'],
            // [[
            //     'subject', 'message',
            //     ], 'required', 'message'=>Yii::t('x', 'Required.')],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }


}
