<?php
/**
 * This is the model for all Activities
 */
namespace app\models;

use Yii;

class Activity extends MyActiveRecord
{

    public static function tableName()
    {
        return 'activities';
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
            'activities/c'=>[
                'status', 'name', 'description', 'note',
            ],
            'activities/u'=>[
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

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid'=>'id'])->andWhere(['rtype'=>'activity']);
    }

    public function getDestination()
    {
        return $this->hasOne(Destination::className(), ['id'=>'destination_id']);
    }

    public function getDv3()
    {
        return $this->hasMany(Dv3::className(), ['activity_id'=>'id']);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id'=>'contact_id'])
            ->viaTable('activity_contact', ['activity_id'=>'id']);
    }
}
