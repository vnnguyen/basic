<?php
/**
 * This is the model for all Places
 */
namespace app\models;

use Yii;

class Place extends MyActiveRecord
{

    public $name_other, $name_previous;

    public static function tableName()
    {
        return 'places';
    }

    public function rules()
    {
        return [
            [[
                'name', 'name_local', 'status', 'destination_id', 'venue_id',
                'stype', 'ref', 'ref2', 'about', 'latitude', 'longitude',
                'description', 'note'
                ], 'trim'],
            [[
                'name', 'status',
                'stype', 'ref',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'places/c'=>[
                'name', 'name_local', 'destination_id', 'venue_id', 'status', 'stype', 'ref', 'ref2', 'description',
            ],
            'places/u'=>[
                'name', 'name_local', 'destination_id', 'venue_id', 'status', 'stype', 'ref', 'ref2', 'description',
            ],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid'=>'id'])->andWhere(['rtype'=>'place']);
    }

    public function getDestination()
    {
        return $this->hasOne(Destination::className(), ['id'=>'destination_id']);
    }

    public function getRoomTypes()
    {
        return $this->hasMany(RoomType::className(), ['place_id'=>'id']);
    }

    public function getDv3()
    {
        return $this->hasMany(Dv3::className(), ['place_id'=>'id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id'=>'contact_id'])
            ->viaTable('contact_place', ['place_id'=>'id']);
    }
}
