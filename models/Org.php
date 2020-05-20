<?php
/**
 * This is the model for all Places
 */
namespace app\models;

use Yii;

class Org extends MyActiveRecord
{

    public $test;

    public static function tableName()
    {
        return 'orgs';
    }

    public function rules()
    {
        return [
            [[
                'name', 'short_name', 'full_name',
                'intro', 'note'
                ], 'trim'],
            [[
                'name',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'orgs/c'=>[
                'name', 'name', 'short_name', 'full_name', 'intro', 'note',
            ],
            'orgs/u'=>[
                'name', 'name', 'short_name', 'full_name', 'intro', 'note',
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
        return $this->hasMany(Meta::className(), ['rid'=>'id'])->andWhere(['rtype'=>'org']);
    }

    public function getDestination()
    {
        return $this->hasOne(Destination::className(), ['id'=>'destination_id']);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id'=>'contact_id'])
            ->viaTable('contact_org', ['org_id'=>'id']);
    }
}
