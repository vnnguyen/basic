<?php
/**
 * This is the model for all Activities
 */
namespace app\models;

use Yii;

class Survey extends MyActiveRecord
{

    public static function tableName()
    {
        return 'surveys';
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
            'surveys/c'=>[
                'status', 'name', 'description', 'note',
            ],
            'activities/u'=>[
                'surveys', 'name', 'description', 'note',
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

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid'=>'id'])->andWhere(['rtype'=>'survey']);
    }

}
