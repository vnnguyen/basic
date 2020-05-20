<?php
namespace app\models;

use Yii;

class Vendor extends MyActiveRecord
{
    public static function tableName()
    {
        return 'vendor_profiles';
    }

    public function rules()
    {
        return [
            [[
                'name',
                ], 'trim'],
            [[
                'name',
                ], 'required', 'message'=>Yii::t('x', 'Required.')],
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

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id'=>'owner_user_id']);
    }

    public function getOrg()
    {
        return $this->hasOne(Org::className(), ['id'=>'org_id']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id'=>'contact_id']);
    }
}
