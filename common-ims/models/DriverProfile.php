<?php
namespace common\models;

use Yii;

class DriverProfile extends MyActiveRecord
{

    public static function tableName()
    {
        return 'driver_profiles';
    }

    public function rules()
    {
        return [
            // [['reports_to'], 'default', 'value'=>0],
            [[
                'status', 'since', 'us_since', 'languages', 'tour_types', 'regions', 'points', 'pros', 'cons', 'vehicle_types', 'vehicle_numbers', 'note',
                ], 'trim'],
            [[
                'since', 'us_since'
                ], 'date', 'format'=>'Y', 'message'=>Yii::t('x', 'Invalid')],
            [[
                'since', 'us_since'
                ], 'default', 'value'=>'0000'],
            [[
                'status', 'regions',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
