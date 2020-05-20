<?php
namespace app\models;

use Yii;

class Dvt extends MyActiveRecord
{
    public $g_language, $g_region;

    public static function tableName()
    {
        return 'dvt';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [[
                'g_language', 'g_region', 'status',
                'qty', 'attr', 'place_id', 'place2_id', 'xdays', 'xnights',
                'name', 'use_time', 'contact_id', 'note'], 'trim'],
            [[
                'status', 'use_time'], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'status', 'qty', 'use_time', 'xnights'], 'required', 'message'=>Yii::t('x', 'Required'), 'on'=>'dvt/u/h'],
            [[
                'place_id', 'place2_id'
                ], 'required', 'message'=>Yii::t('x', 'Required'), 'on'=>['dvt/c/f', 'dvt/u/f']],
        ];
    }

    public function scenarios()
    {
        return [
            'dvt/c/f'=>['status', 'attr', 'place_id', 'place2_id', 'qty', 'use_time', 'note'],
            'dvt/u/f'=>['status', 'attr', 'place_id', 'place2_id', 'qty', 'use_time', 'note'],
            'dvt/c/g'=>['status', 'name', 'attr', 'contact_id', 'qty', 'use_time', 'note'],
            'dvt/u/g'=>['status', 'name', 'attr', 'contact_id', 'qty', 'use_time', 'note'],
            'dvt/c/h'=>['status', 'name', 'attr', 'place_id', 'qty', 'use_time', 'note', 'xnights'],
            'dvt/u/h'=>['status', 'name', 'attr', 'place_id', 'qty', 'use_time', 'note', 'xnights'],
        ];
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getOrg()
    {
        return $this->hasOne(Org::className(), ['id'=>'org_id']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id'=>'contact_id']);
    }

    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id'=>'place_id']);
    }

    public function getPlace2()
    {
        return $this->hasOne(Place::className(), ['id'=>'place2_id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['dvt_id'=>'id']);
    }

    public function getDv()
    {
        return $this->hasOne(Dv::className(), ['id'=>'dv_id']);
    }

}
