<?php
namespace app\models;

use Yii;

class Cpt extends MyActiveRecord
{

    public static function tableName()
    {
        return 'cpt';
    }

    public function scenarios() {
        return [
            'costs/u/huan'=>['c_type', 'r_status', 'use_time', 'name', 'name2'],
        ];
    }

    public function rules() {
        return [
            [[
                'c_type', 'r_status', 'name', 'name2', 'use_time',], 'trim'],
            [[
                'r_status', 'name', ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getDvt()
    {
        return $this->hasOne(Dvt::className(), ['id'=>'dvt_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'by_company_id']);
    }

    public function getViaCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'via_company_id']);
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
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getVendor()
    {
        return $this->hasOne(Org::className(), ['id'=>'venue_id']);
    }

    public function getVendorContact()
    {
        return $this->hasOne(Contact::className(), ['id'=>'vendor_contact_id']);
    }

    public function getPayee()
    {
        return $this->hasOne(Contact::className(), ['id'=>'p_to_contact_id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['rid'=>'id'])->onCondition(['rtype'=>'cpt'])->orderBy('created_at');
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['cpt_id'=>'id']);
    }

    public function getReactions()
    {
        return $this->hasMany(Reaction::className(), ['rid'=>'id'])->andWhere(['rtype'=>'cpt']);
    }

    public function getMtt()
    {
        return $this->hasMany(Mtt::className(), ['cpt_id'=>'id']);
    }

    public function getCptTietkiem()
    {
        return $this->hasMany(CptTietkiem::className(), ['cpt_id'=>'id']);
    }

    public function getEdits()
    {
        return $this->hasMany(CptEdit::className(), ['cpt_id'=>'id'])->orderBy('edit_dt DESC');
    }

}
