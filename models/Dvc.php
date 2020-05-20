<?php
namespace app\models;

// Hop dong dich vu

class Dvc extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dvc';
    }

    public function rules() {
        return [
            [[
                'stype', 'name', 'number', 'signed_dt',
                'valid_from_dt', 'valid_until_dt',
                'signed_dt', 'amended_dt',
                'description', 'body', 'note',
                ], 'trim'],
            [[
                'stype', 'name',
                ], 'required'],
            [[
                'name',
                ], 'unique', 'targetAttribute' => ['name', 'venue_id'], 'message'=>'Name already exists'],
        ];
    }

    public function scenarios()
    {
        return [
            'dvc/c'=>[
                'stype', 'name', 'number', 'signed_dt',
                'valid_from_dt', 'valid_until_dt',
                'signed_dt', 'amended_dt',
                'description', 'body', 'note',
                ],
            'dvc/u'=>[
                'stype', 'name', 'number', 'signed_dt',
                'valid_from_dt', 'valid_until_dt',
                'signed_dt', 'amended_dt',
                'description', 'body', 'note',
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
