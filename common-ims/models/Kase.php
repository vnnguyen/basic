<?php
namespace common\models;

class Kase extends MyActiveRecord
{
    public $emails = '';
    public static function tableName()
    {
        return '{{%cases}}';
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Case name',
            'cofr'=>'Seller in France',
            'company_id'=>'Travel / Tour company',
            'ref'=>'ID of referrer user',
            'info'=>'Note',
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'unique'],
            [['cofr', 'campaign_id'], 'default', 'value'=>0],
            [['name', 'stype', 'info', 'why_closed', 'closed_note', 'web_referral', 'web_keyword', 'ref'], 'trim'],
            [['name', 'owner_id', 'is_priority', 'language', 'is_b2b', 'how_contacted', 'how_found'], 'required', 'message'=>'Field is required'],
            [['why_closed', 'closed_note'], 'required', 'on'=>'kase/close'],
            [['company_id', 'ref', 'campaign_id'], 'default', 'value'=>0],
            [['ref'], 'default', 'value'=>0],
        ];
    }

    public function scenarios()
    {
        return [
            'kase/c'=>['name', 'language', 'is_b2b', 'is_priority', 'owner_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword', 'emails'],
            'b2b/kase/c'=>['name', 'stype', 'language', 'is_priority', 'owner_id', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword'],
            'kase/u'=>['name', 'language', 'is_b2b', 'is_priority', 'owner_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword', 'emails'],
            'b2b/kase/u'=>['name', 'stype', 'language', 'is_priority', 'owner_id', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword'],
            'kase/upa'=>['how_contacted', 'web_keyword', 'campaign_id', 'company_id', 'how_found', 'ref', 'info'],
            'inquiries_r'=>['name'],
            'update'=>['name', 'is_priority', 'owner_id'],
            'kase/close'=>['why_closed', 'closed_note'],
            'cases_reopen'=>[],
        ];
    }

    public function getPeople() {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('at_case_user', ['case_id'=>'id']);
    }
    public function getCperson() {
        return $this->hasMany(Person::className(), ['id' => 'user_id'])
            ->viaTable('at_case_user', ['case_id'=>'id']);
    }
    public function getContact() {
        return $this->hasMany(Contact::className(), ['id' => 'user_id'])
            ->viaTable('at_case_user', ['case_id'=>'id']);
    }

    public function getOwner() {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getReferrer() {
        return $this->hasOne(User::className(), ['id' => 'ref']);
    }

    public function getCompany() {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
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
