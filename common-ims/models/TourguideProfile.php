<?php
namespace common\models;

use Yii;

class TourguideProfile extends MyActiveRecord
{

    public static function tableName()
    {
        return 'tourguide_profiles';
    }

    public function rules()
    {
        return [
            // [['reports_to'], 'default', 'value'=>0],
            [[
                'status', 'guide_since', 'guide_us_since', 'languages', 'tour_types', 'regions', 'ratings', 'ma_ncc', 'pros', 'cons', 'note',
                ], 'trim'],
            [[
                'guide_since', 'guide_us_since'
                ], 'date', 'format'=>'Y', 'message'=>Yii::t('x', 'Invalid')],
            [[
                'guide_since', 'guide_us_since'
                ], 'default', 'value'=>'0000'],
            [[
                'status', 'languages', 'regions',
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
