<?php
namespace common\models;

use Yii;

class Vendor extends MyActiveRecord
{
    public $groups;

    public static function tableName() {
        return 'companies';
    }

    public function rules()
    {
        return [
            [[
                'name', 'name_full', 'search', 'info',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'accounting_code', 'name', 'name_full', 'search', 'info', 'tax_info', 'bank_info', 'image',
                ], 'trim'],
            [[
                'accounting_code',
                ], 'filter', 'filter'=>'strtoupper'],
            [[
                'image',
                ], 'url', 'message'=>Yii::t('x', 'Invalid')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Short name',
            'name_full'=>'Full name',
            'search'=>'Search keywords',
            'tax_info'=>'Tax information',
            'bank_info'=>'Bank information',
            'info'=>\Yii::t('mn', 'Information'),
        ];
    }

    public function scenarios()
    {
        return [
            'vendors/c'=>['name', 'name_full'],
            'vendors/u'=>['name', 'name_full', 'search', 'tax_info', 'bank_info', 'info', 'accounting_code', 'image'],
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

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])->where(['rtype'=>'company']);
    }

    public function getDv()
    {
        return $this->hasMany(Dv::className(), ['vendor_id' => 'id']);
    }

    public function getVenues()
    {
        return $this->hasMany(Venue::className(), ['vendor_id' => 'id']);
    }

    public function getSearch()
    {
        // TODO
        return $this->hasOne(Search::className(), ['rid'=>'id'])->where(['rtype'=>'vendor']);;
    }
}
