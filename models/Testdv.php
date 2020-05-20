<?php
namespace app\models;

class Testdv extends MyActiveRecord
{
    public $type1, $type2, $type3, $type4, $type5;
    public $name1, $name2, $name3, $name4, $name5;

    public static function tableName()
    {
        return 'testdv';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [[
                'status', 'stype', 'sorder', 'is_dependent', 'maxpax',
                'grouping', 'venue_id',
                'search_loc', 'name', 'search', 'xday',
                'whobooks', 'whopays',
                'unit', 'receipt', 'default_vendor', 'conds',
                'note',
                'type1', 'name1', 'type2', 'name2', 'type3', 'name3', 'type4', 'name4', 'type5', 'name5'
                ], 'trim'],
            [[
                'name', 'search', 'is_dependent',
                ], 'trim'],
            [[
                'name',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'dv/c'=>[
                'status', 'stype', 'sorder', 'is_dependent', 'maxpax',
                'grouping', 'venue_id',
                'search_loc', 'name', 'search', 'xday',
                'whobooks', 'whopays',
                'unit', 'receipt', 'default_vendor', 'conds',
                'note',
                'type1', 'name1', 'type2', 'name2', 'type3', 'name3', 'type4', 'name4', 'type5', 'name5'
                ],
            'dv/u'=>[
                'status', 'stype', 'sorder', 'is_dependent', 'maxpax',
                'grouping', 'venue_id',
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

    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id'=>'place_id']);
    }

    public function getVendors()
    {
        return $this->hasMany(Vendors::className(), ['testdv_id'=>'id']);
    }

}
