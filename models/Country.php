<?php
namespace app\models;

use yii\db\ActiveRecord;

class Country extends MyActiveRecord
{

    public static function tableName()
    {
        return 'countries';
    }


    public function getDestinations()
    {
        return $this->hasMany(Destination::className(), ['country_id'=>'id']);
    }
}
