<?php
namespace common\models;

use Yii;

class Member extends MyActiveRecord
{
    public $ext = '';
    public $facebook, $skype;
    public $test;

    public static function tableName()
    {
        return 'members';
    }

    public function rules()
    {
        return [
            [[
                'test',
                'fname', 'lname', 'name', 'bday', 'bmonth', 'byear', 'position', 'department', 'location', 'reports_to', 'bio', 'review', 'intro', 'note', 'ext',
                ], 'trim'],
            [[
                'reports_to',
            ], 'default', 'value'=>0],
            [[
                'since', 'position', 'department', 'location',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
            //[['since'], 'date', 'format'=>'Y-m-d'],
        ];
    }

    public function scenarios() {
        return [
            'member/c'=>['test', 'fname', 'lname', 'name', 'bday', 'bmonth', 'byear', 'since', 'position', 'department', 'location', 'intro', 'bio', 'ext'],
            'member/u'=>['test', 'fname', 'lname', 'name', 'bday', 'bmonth', 'byear', 'since', 'position', 'department', 'location', 'intro', 'bio', 'ext'],
        ];
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])->andWhere(['rtype'=>'member']);
    }

    public function getFacebook()
    {
        return $this->hasOne(Meta::className(), ['rid' => 'id'])->andWhere(['rtype'=>'member', 'name'=>'facebook']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
