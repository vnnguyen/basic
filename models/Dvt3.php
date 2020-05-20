<?php
namespace app\models;

class Dvt3 extends MyActiveRecord
{
    public $type1, $type2, $type3, $type4, $type5;
    public $name1, $name2, $name3, $name4, $name5;

    public static function tableName()
    {
        return 'dvt';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [[
                'status', 'stype', 'sorder', 'is_dependent', 'maxpax',
                'grouping', 'place_id', 'intro',
                'search_loc', 'name', 'search', 'xday',
                'whobooks', 'whopays',
                'unit', 'receipt', 'default_vendor', 'conds',
                'note',
                'type1', 'name1', 'type2', 'name2', 'type3', 'name3', 'type4', 'name4', 'type5', 'name5'
                ], 'trim'],
            [[
                'name', 'search', 'is_dependent',
                ], 'trim'],
        ];
    }

    public function scenarios()
    {
        return [
            'dv3/c'=>[
                'status', 'stype', 'sorder', 'is_dependent', 'maxpax',
                'grouping', 'place_id', 'intro',
                'search_loc', 'name', 'search', 'xday',
                'whobooks', 'whopays',
                'unit', 'receipt', 'default_vendor', 'conds',
                'note',
                'type1', 'name1', 'type2', 'name2', 'type3', 'name3', 'type4', 'name4', 'type5', 'name5'
                ],
            'dv3/u'=>[
                'status', 'stype', 'sorder', 'is_dependent', 'maxpax',
                'grouping', 'place_id', 'intro',
                'search_loc', 'name', 'search', 'xday',
                'whobooks', 'whopays',
                'unit', 'receipt', 'default_vendor', 'conds',
                'note',
                'type1', 'name1', 'type2', 'name2', 'type3', 'name3', 'type4', 'name4', 'type5', 'name5'
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
        return $this->hasOne(Venue::className(), ['id'=>'place_id']);
    }

    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id'=>'place_id']);
    }

    public function getDestination()
    {
        return $this->hasOne(Place::className(), ['id'=>'destination_id']);
    }

    // Chi phi (gia cua dv)
    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getDvt()
    {
        return $this->hasMany(Dvt::className(), ['dv_id'=>'id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['dv_id'=>'id']);
    }
}
