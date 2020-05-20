<?php
/**
 * This is the model for all Activities
 */
namespace app\models;

use Yii;

use app\models\Product as Tour;

class SurveyAnswer extends MyActiveRecord
{

    public static function tableName()
    {
        return 'survey_answers';
    }

    public function rules()
    {
        return [
            [[
                'status', 'name', 'description', 'note',
                ], 'trim'],
            [[
                'status', 'name',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'survey_answers/c'=>[
                'status', 'name', 'description', 'note',
            ],
            'survey_answers/u'=>[
                'status', 'name', 'description', 'note',
            ],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getTour()
    {
        return $this->hasOne(Tour::className(), ['id'=>'tour_id']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id'=>'contact_id']);
    }

}
