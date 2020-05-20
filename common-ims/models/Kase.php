<?php
namespace common\models;

use Yii;

class Kase extends MyActiveRecord
{
    public $_kx = '';
    public $_kx_cost = '';
    public $_kx_cost_currency = '';
    public $emails = '';
    public static function tableName()
    {
        return 'files';
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Case name',
            'cofr'=>'Amica contact in France',
            'company_id'=>'Travel / Tour company',
            'ref'=>'ID of referrer user',
            'info'=>'Note',
        ];
    }

    public function rules()
    {
        return [
            [[
                'name'
            ], 'unique'],
            [[
                'cofr', 'campaign_id'
            ], 'default', 'value'=>0],
            [[
                '_kx', '_kx_cost', '_kx_cost_currency', 'name', 'stype', 'info', 'why_closed', 'closed_note', 'web_referral', 'web_keyword', 'company_id', 'ref', 'campaign_id',
            ], 'trim'],
            [[
                'name', 'is_priority', 'language', 'is_b2b', 'how_contacted', 'how_found'
            ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'why_closed', 'closed_note'
            ], 'required', 'on'=>'kase/close'],
        ];
    }

    public function scenarios()
    {
        return [
            'kase/c'=>['name', 'language', 'is_b2b', 'is_priority', 'owner_id', 'orig_seller_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword', 'emails', '_kx'],
            'b2b/kase/c'=>['name', 'stype', 'language', 'is_priority', 'owner_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword'],
            'kase/u'=>['name', 'language', 'is_b2b', 'is_priority', 'owner_id', 'orig_seller_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword', 'emails', '_kx'],
            'b2b/kase/u'=>['name', 'stype', 'language', 'is_priority', 'owner_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword'],
            'kase/upa'=>['_kx', '_kx_cost', '_kx_cost_currency', 'how_contacted', 'web_keyword', 'campaign_id', 'company_id', 'how_found', 'ref', 'info'],
            'inquiries_r'=>['name'],
            'update'=>['name', 'is_priority', 'owner_id'],
            'kase/close'=>['why_closed', 'closed_note'],
            'cases_reopen'=>[],
        ];
    }

    public function getPeople() {
        return $this->hasMany(Contact::className(), ['id' => 'user_id'])
            ->viaTable('at_case_user', ['case_id'=>'id']);
    }

    public function getOwner() {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getReferrer() {
        return $this->hasOne(Contact::className(), ['id' => 'ref']);
    }

    public function getCompany() {
        return $this->hasOne(Client::className(), ['id' => 'company_id']);
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getStats() {
        return $this->hasOne(KaseStats::className(), ['case_id' => 'id']);
    }

    public function getTasks() {
        return $this->hasMany(Task::className(), ['rid' => 'id'])->where(['rtype'=>'case'])->orderBy('fuzzy, due_dt');
    }

    public function getKasePartners() {
        return $this->hasMany(KasePartner::className(), ['case_id' => 'id']);
    }

    public function getMetas() {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])->where(['rtype'=>'case']);
    }

    public function getFiles() {
        return $this->hasMany(File::className(), ['rid' => 'id'])->where(['rtype'=>'case'])->orderBy('uo DESC');
    }

    public function getBookings() {
        return $this->hasMany(Booking::className(), ['case_id' => 'id']);
    }

    public function getInquiries() {
        return $this->hasMany(Inquiry::className(), ['case_id' => 'id']);
    }
}
