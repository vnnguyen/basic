<?php
namespace common\models;

use Yii;

class MemberProfile extends MyActiveRecord
{
    public static function tableName()
    {
        return 'member_profiles';
    }

    public function rules()
    {
        return [
            // [['reports_to'], 'default', 'value'=>0],
            [[
                'status', 'since', 'until', 'department', 'position', 'location', 'ext', 'is_intern', 'is_on_leave', 'is_remote', 'intro', 'bio', 'reports_to',
                ], 'trim'],
            [[
                'since', 'until'
                ], 'date', 'format'=>'Y-m-d', 'message'=>Yii::t('x', 'Invalid')],
            [[
                'status', 'since', 'department', 'position', 'location', 'is_intern', 'is_on_leave', 'is_remote',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function getBoss()
    {
        return $this->hasOne(Contact::className(), ['id' => 'reports_to']);
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
