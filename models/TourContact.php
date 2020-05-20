<?php
namespace app\models;

use Yii;

class TourContact extends MyActiveRecord
{

    public static function tableName()
    {
        return 'tour_contact';
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id' => 'contact_id']);
    }
}
