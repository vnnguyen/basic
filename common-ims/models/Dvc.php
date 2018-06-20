<?php
namespace common\models;

// Hop dong dich vu

class Dvc extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dvc';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [[
                'name', 'number', 'signed_dt',
                'valid_from_dt', 'valid_until_dt',
                'body', 'note',
                ], 'trim'],
            [[
                'name',
                ], 'required'],
            [[
                'signed_dt', 'valid_from_dt', 'valid_until_dt',
                ], 'default', 'value'=>'0000-00-00 00:00:00'],
            [[
                'name',
                ], 'unique', 'targetAttribute' => ['name', 'venue_id'], 'message'=>'Name already exists'],
        ];
    }

    public function scenarios()
    {
        return [
            'dvc/c'=>[
                'name', 'number', 'signed_dt',
                'valid_from_dt', 'valid_until_dt',
                'body', 'note',
                ],
            'dvc/u'=>[
                'name', 'number', 'signed_dt',
                'valid_from_dt', 'valid_until_dt',
                'body', 'note',
                ],
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

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id'=>'supplier_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

    public function getDvd()
    {
        return $this->hasMany(Dvd::className(), ['dvc_id'=>'id']);
    }

    public function getCp()
    {
        return $this->hasMany(Cp::className(), ['dvc_id'=>'id']);
    }
}
