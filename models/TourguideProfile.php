<?php
namespace app\models;

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
            [[
                'status', 'guide_since', 'guide_us_since', 'languages', 'tour_types', 'regions', 'ratings', 'ma_ncc', 'so_cmt', 'so_tk', 'so_the_hdv', 'pros', 'cons', 'note',
                ], 'trim'],
            [[
                'guide_since', 'guide_us_since'
                ], 'date', 'format'=>'Y', 'message'=>Yii::t('x', 'Invalid')],
            [[
                'status', 'languages', 'regions',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];

    }

    public function scenarios()
    {
        return [
            'edit'=>['status', 'guide_since', 'guide_us_since', 'languages', 'tour_types', 'regions', 'ratings', 'ma_ncc', 'so_cmt', 'so_tk', 'so_the_hdv', 'pros', 'cons', 'note',],
            'edit-kt'=>['ma_ncc', 'so_cmt', 'so_tk',],
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
