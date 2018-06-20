<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Contact extends MyActiveRecord
{

    public $rawPassword;
    public $rawPasswordAgain;
    public $search;
    public $password_again;

    public static $metaLabels = [
        'address'=>'Địa chỉ',
        'tel'=>'Điện thoại',
    ];

    public static function tableName()
    {
        return 'at_users';
    }

    public function attributeLabels()
    {
        return [
            'fname'=>'First name',
            'lname'=>'Second name',
            'name'=>'Display name',
            'email'=>'Email address',
            'bday'=>'Birth day',
            'bmonth'=>'Birth month',
            'byear'=>'Birth year',
            'country_code'=>'Nationality',
            'info'=>'Information',
        ];
    }

    public function rules()
    {
        return [
            [['fname', 'lname', 'name', 'country_code', 'email', 'phone', 'language', 'timezone', 'info'], 'filter', 'filter'=>'trim'],
            [['fname', 'lname', 'name', 'gender'], 'required'],
            [['nickname'], 'required', 'on'=>'meprefs'],
            [['gender'], 'in', 'range'=>['male', 'female']],
            [['bday', 'bmonth', 'byear'], 'default', 'value'=>0],
            [['bday'], 'integer', 'max'=>31],
            [['bmonth'], 'integer', 'max'=>12],
            [['byear'], 'integer', 'max'=>date('Y')],
            [['email'], 'email'],
            [['image'], 'url'],

            array('email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup'),
            array('email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'forgot'),

            array('password', 'required'),
            array('password', 'string', 'min' => 6),
            [['fname, lname, name, gender, country_code'], 'required'],
            //

            // Change password
            [['rawPassword', 'rawPasswordAgain'], 'required', 'on'=>'changePassword'],
            [['rawPassword'], 'string', 'min'=>6, 'max'=>20],
            [['rawPasswordAgain'], 'compare', 'compareAttribute'=>'rawPassword', 'on'=>'changePassword', 'message'=>'Please repeat your password exactly'],
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['fname', 'lname', 'name', 'gender', 'email', 'phone', 'country_code', 'language', 'timezone'],
            'update' => ['fname', 'lname', 'name', 'email', 'language', 'timezone'],
            'user/u' => ['fname', 'lname', 'name', 'email', 'language', 'timezone', 'image'],
            'signup' => ['username', 'email', 'password'],
            'changePassword'=>['rawPassword', 'rawPasswordAgain'],
            'meprefs'=>['fname', 'lname', 'name', 'nickname', 'country_code', 'gender', 'bday', 'bmonth', 'byear', 'language', 'timezone', 'email', 'phone', 'info', 'image'],
            'login_forgot'=>['email'],
            'login_reset'=>[],
            'inquiries/r'=>['created_at', 'created_by', 'uo', 'ub', 'status', 'fname', 'lname', 'name', 'gender', 'country_code', 'email', 'phone', 'language', 'timezone'],
            'tourguide/c'=>['name', 'country_code'],
            'tourguide/u'=>['fname', 'lname', 'name', 'bday', 'bmonth', 'byear', 'gender', 'country_code', 'phone', 'email', 'image'],
            'driver/c'=>['name', 'country_code'],
            'driver/u'=>['fname', 'lname', 'name', 'bday', 'bmonth', 'byear', 'gender', 'country_code', 'phone', 'email', 'image'],
            'member/c'=>['name', 'country_code'],
            'member/u'=>['fname', 'lname', 'name', 'bday', 'bmonth', 'byear', 'gender', 'country_code', 'phone', 'email', 'image'],
        ];
    }


    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])->where(['rtype'=>'user']);
    }

    public function getRefCases()
    {
        return $this->hasMany(Kase::className(), ['id' => 'case_id'])
            ->viaTable('at_referrals', ['user_id'=>'id']);
    }

    public function getProfileMember()
    {
        return $this->hasOne(ProfileMember::className(), ['user_id' => 'id']);
    }

    public function getProfileTourguide()
    {
        return $this->hasOne(ProfileTourguide::className(), ['user_id' => 'id']);
    }

    public function getProfileCustomer()
    {
        return $this->hasOne(ProfileCustomer::className(), ['user_id' => 'id']);
    }

    public function getProfileDriver()
    {
        return $this->hasOne(ProfileDriver::className(), ['user_id' => 'id']);
    }

    public function getSearch()
    {
        return $this->hasOne(Search::className(), ['rid' => 'id'])->where(['rtype'=>'user']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['code' => 'country_code']);
    }

    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['id' => 'role_id'])
            ->viaTable('at_role_user', ['user_id'=>'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable('at_group_user', ['user_id'=>'id']);
    }


    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    // User belongs to all of these roles
    public function hasRoles()
    {
        $roles = func_get_args();
        foreach ($roles as $role) {
            if (is_int($role) && !in_array($role, $this->roles)) return false;
            if (!is_int($role) && !in_array($role, $this->roleNames)) return false;
        }
        return true;
    }

    public function getCases()
    {
        return $this->hasMany(Kase::className(), ['id' => 'case_id'])
            ->viaTable('at_case_user', ['user_id'=>'id']);
    }
    
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['id' => 'booking_id'])
            ->viaTable('at_booking_user', ['user_id'=>'id']);
    }
    
    public function hasGroups()
    {
        return $this->hasMany(Term::className(), ['id'=>'term_id'])
            ->viaTable('at_term_rel', ['rid'=>'id'])
            ->where(['at_terms.taxonomy_id'=>1]);
    }

    public function hasTags()
    {
        return $this->hasMany(Term::className(), ['id'=>'term_id'])
            ->viaTable('at_term_rel', ['rid'=>'id'])
            ->where(['at_terms.taxonomy_id'=>2]);
    }

    // User belongs to at least one of these roles
    public function hasOneOfRoles()
    {
        $roles = func_get_args();
        foreach ($roles as $role) {
            if (is_int($role) && in_array($role, $this->roles)) return true;
            if (!is_int($role) && in_array($role, $this->roleNames)) return true;
        }
        return false;
    }


}
