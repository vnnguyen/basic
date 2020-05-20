<?php
namespace app\models;

use Yii;

class Client extends MyActiveRecord
{
    public $newpassword;
    public $info_type_of_cooperation, $info_client_service, $info_tour_operation, $info_payment_conditions, $info_bank_accounts, $info_urgent_contact;
    public $info_debt;

    public static function tableName() {
        return 'clients';
    }

    public function rules() {
        return [
            [[
                'name',
                'owner_id',
                'body', 'note',
                'login', 'password', 'newpassword',
            ], 'trim'],
            [[
                'info_type_of_cooperation', 'info_client_service', 'info_tour_operation',
                'info_payment_conditions', 'info_bank_accounts', 'info_urgent_contact',
                'info_debt',
            ], 'trim'],
            [[
                'name', 'owner_id',
            ], 'required', 'message'=>Yii::t('app', 'Required')],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])->where(['rtype'=>'client']);
    }

    public function getCases()
    {
        return $this->hasMany(Kase::className(), ['company_id' => 'id']);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id' => 'user_id'])
            ->viaTable('at_company_user', ['company_id'=>'id']);
    }
}
