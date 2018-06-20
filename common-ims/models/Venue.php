<?php
namespace common\models;

class Venue extends MyActiveRecord
{

    public static function tableName()
    {
        return 'venues';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['name', 'search', 'abbr', 'about', 'latlng', 'info', 'link_agoda', 'link_booking', 'link_tripadvisor', 'cruise_meta', 'stype'], 'trim'],
            [['name', 'destination_id', 'stype', 'abbr', 'seacrh'], 'required'],
            [['name', 'abbr'], 'unique'],
            [['supplier_id'], 'integer'],
            [['link_agoda', 'link_booking', 'link_tripadvisor', 'image'], 'url'],
        ];
    }

    public function scenarios()
    {
        return [
            'venues_c'=>['name', 'stype', 'destination_id'],
            'venues_u'=>[
                'name', 'search', 'abbr', 'about', 'supplier_id', 'latlng', 'info', 'info_facilities', 'image', 'destination_id', 'link_booking', 'link_tripadvisor', 'link_agoda',
                'cruise_meta', 'stype',
            ],
            'venue/u'=>[
                'abbr',
            ],
            'venues_u-promo'=>['info_pricing'],
            'venues_uu'=>[],
            'huan_venue_u'=>['name', 'destination_id', 'stype', 'abbr', 'about'],
            'venue_u_ovv'=> ['over_view_options']
        ];
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])
            ->where(['rtype'=>'venue']);
            // ->orderBy('k');
    }

    public function getDestination()
    {
        return $this->hasOne(Destination::className(), ['id' => 'destination_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User2::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id' => 'updated_by']);
    }

    public function getCompany()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    public function getNcc()
    {
        return $this->hasOne(Ncc::className(), ['id' => 'ncc_id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['venue_id' => 'id']);
    }

    public function getStats()
    {
        return $this->hasOne(VenueStats::className(), ['venue_id' => 'id']);
    }

    public function getDv()
    {
        return $this->hasMany(Dv::className(), ['venue_id' => 'id']);
    }

    public function getDvc()
    {
        return $this->hasMany(Dvc::className(), ['venue_id' => 'id']);
    }

    public function getDvo()
    {
        return $this->hasMany(Dvo::className(), ['venue_id' => 'id']);
    }

}
