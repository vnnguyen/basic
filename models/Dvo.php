<?php
namespace common\models;

class Dvo extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dvo';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [['stype', 'tk', 'grouping', 'name', 'unit', 'search', 'note'], 'trim'],
            [['stype', 'name', 'unit', 'search'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'dvo/c'=>['stype', 'tk', 'grouping', 'name', 'unit', 'search', 'note'],
            'dvo/u'=>['stype', 'tk', 'grouping', 'name', 'unit', 'search', 'note'],
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

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

    public function getByCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'by_company_id']);
    }

    public function getViaCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'via_company_id']);
    }

    public function getCpo()
    {
        return $this->hasMany(Cpo::className(), ['dvo_id'=>'id']);
    }

}
