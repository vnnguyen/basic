<?php
namespace app\models;

use Yii;

class FilePartner extends MyActiveRecord
{
    public static function tableName()
    {
        return 'case_partner';
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCase() {
        return $this->hasOne(Kase::className(), ['id' => 'case_id']);
    }

    public function getPartner() {
        return $this->hasOne(Client::className(), ['id' => 'partner_id']);
    }

}
