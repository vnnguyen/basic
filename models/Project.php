<?php
/**
 * This is the model for projects
 */
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class Project extends MyActiveRecord
{

    public static function tableName()
    {
        return 'projects';
    }

    // public function attributeLabels()
    // {
    //     return [
    //         'name'=>Yii::t('x', 'Name'),
    //     ];
    // }

    public function rules()
    {
        return [
            [[
                'name', 'project_type', 'start_date', 'status', 'description', 'note',
                ], 'trim'],
            [[
                'name', 'project_type', 'start_date', 'status', 'description',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function _scenarios()
    {
        return [
            'projects/c'=>['name', 'name_local', 'note'],
            'projects/u'=>['name', 'name_local', 'note'],
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
        return $this->hasMany(Meta::className(), ['rid'=>'id'])->andWhere(['rtype'=>'project']);
    }
}
