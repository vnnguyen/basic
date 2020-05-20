<?php
namespace common\models;

class Venuestest extends MyActiveRecord
{

    public static function tableName()
    {
        return 'venues_test';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['name', 'destination_id', 'location', 'rank', 'style', 'stype', 'map', 'min_price', 'max_price', 'restauran_number','restauran_note','amica_range','room_number','note','recommend_tag'], 'trim'],
            [['name', 'destination_id', 'stype', 'seacrh'], 'required'],
            [['name'], 'unique'],
            [['supplier_id'], 'integer'],
            [['link_agoda', 'link_booking', 'link_tripadvisor', 'image'], 'url'],
        ];
    }

    public function scenarios()
    {
        return [
            'venues_c'=>['name', 'stype', 'destination_id'],
            'venues_u'=>[
            		'name', 'destination_id', 'location', 'rank', 'style', 'stype', 'map', 'min_price', 'max_price', 'restauran_number','restauran_note','amica_range','room_number','note','recommend_tag'
            ],
            'venue/u'=>[
                'abbr',
            ],
            'venues_u-promo'=>['info_pricing'],
            'venues_uu'=>[],
            'huan_venue_u'=>['name', 'destination_id', 'stype', 'abbr', 'about'],
        ];
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])
            ->where(['rtype'=>'venue'])
            ->orderBy('k');
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
