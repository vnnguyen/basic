<?php
namespace common\models;

use Yii;

class Venue extends MyActiveRecord
{
    public
        $c_amica,
        $new_o, $new_p, // New overview, new price
        $vstr, $vstar,
        $vclassi, $varchi, $vtype, $vstyle, $vdistc, $vdistb, $vdista, $vdistcmt, $vpricerange, $vfaci, $vreccfor,
        $vitinerary, $vdepart_from, $vcheck_in, $vcheck_out, $vship_profile, $vservice_include_price, $vservice_extra_charge, $vnote_itinerary, $contact_expried; //itinerary, departing from

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
            [[
                'c_amica',
                'new_o', 'new_p',
                'name', 'search', 'abbr', 'about', 'latlng', 'info', 'link_agoda', 'link_booking', 'link_tripadvisor', 'cruise_meta', 'stype', 'images', 'new_pricetable',
                'vstr', 'vstar',
                'vclassi', 'varchi', 'vtype', 'vstyle', 'vdistc', 'vdistb', 'vdista', 'vdistcmt', 'vpricerange', 'vfaci', 'vreccfor',
            ], 'trim'],
            [[
                'name', 'destination_id', 'stype', 'abbr', 'seacrh',
            ], 'required'],
            // [[
            //     'name', 'abbr'
            // ], 'unique'],
            [[
                'supplier_id'
            ], 'integer'],
            [[
                'link_agoda', 'link_booking', 'link_tripadvisor', 'image'
            ], 'url'],
        ];
    }

    public function scenarios()
    {
        return [
            'venue/c'=>['name', 'stype', 'destination_id'],
            'venue/u'=>[
                'new_o', 'new_p',
                'supplier_id', 'destination_id', 'name', 'about',
                'vtype', 'latlng',
                'vstr', 'vstar',
                'vclassi', 'varchi', 'vstyle', 'vdistc', 'vdistb', 'vdista', 'vdistcmt', 'vpricerange', 'vfaci', 'vreccfor',
                'info', 'new_pricetable', 'images',
                'image', 'link_booking', 'link_tripadvisor', 'link_agoda',
                'new_tags',
            ],
            'venue/u-cruise'=>[
                'new_o', 'new_p',
                'supplier_id', 'destination_id', 'name', 'about',
                'vtype', 'latlng',
                'vstr', 'vstar',
                'vclassi', 'vitinerary', 'vdepart_from', 'vcheck_in', 'vcheck_out', 'vpricerange', 'vservice_include_price', 'vservice_extra_charge', 'vreccfor', 'vship_profile', 'vnote_itinerary', 'contact_expried',
                'info', 'new_pricetable', 'images', 'cruise_meta',
                'image', 'link_booking', 'link_tripadvisor', 'link_agoda',
                'new_tags',
            ],
            'venues_u-promo'=>['info_pricing'],
            'venues_u-pricetable'=>['new_pricetable'],
            'venues_uu'=>[],
            'huan_venue_u'=>['name', 'destination_id', 'stype', 'abbr', 'about'],
        ];
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])
            ->where(['rtype'=>'venue'])
            ->orderBy('name');
    }

    public function getDestination()
    {
        return $this->hasOne(Destination::className(), ['id' => 'destination_id']);
    }

    public function getGiao()
    {
        return $this->hasOne(User2::className(), ['id' => 'giao_user_id']);
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

    public function getTmp()
    {
        return $this->hasOne(VenueTmp::className(), ['venue_id' => 'id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['venue_id' => 'id']);
    }

    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['rid' => 'id'])->andWhere(['rtype'=>'venue']);
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
