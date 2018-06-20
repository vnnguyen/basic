<?php
namespace common\models;

class Cp extends MyActiveRecord
{

    public static function tableName()
    {
        return 'cp';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [['period', 'conds', 'price', 'currency', 'info'], 'trim'],
            [['dvc_id', 'price', 'currency'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'cp/c'=>['period', 'conds', 'price', 'currency', 'info'],
            'cp/u'=>['dvc_id', 'period', 'conds', 'price', 'currency', 'info'],
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

    public function getDv()
    {
        return $this->hasOne(Dv::className(), ['id'=>'dv_id']);
    }

    public function getViaCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'via_company_id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['cp_id'=>'id']);
    }
}
