<?php

namespace app\models;

use Yii;
use yii\base\Model;

class BookingPaxForm extends Model
{
    public $pp_number;
    public $pp_country_code;
    public $pp_name;
    public $pp_name2;
    public $pp_bday;
    public $pp_bmonth;
    public $pp_byear;
    public $pp_gender;
    public $pp_iday;
    public $pp_imonth;
    public $pp_iyear;
    public $pp_eday;
    public $pp_emonth;
    public $pp_eyear;
    public $tel;
    public $tel2;
    public $email;
    public $email2;
    public $email3;
    public $website;
    public $profession;
    public $place_of_birth;
    public $address;
    public $address_city_state;
    public $address_postal;
    public $address_country_code;
    public $note;
    public $name;
    public $is_repeating;
    public $pp_file;
    public $previous_tour;

    public function rules()
    {
        return [
            [[
                'name', 'is_repeating', 'previous_tour',
                'pp_country_code', 'pp_number',
                'pp_name', 'pp_name2',
                'pp_gender', 'pp_bday', 'pp_bmonth', 'pp_byear',
                'pp_iday', 'pp_imonth', 'pp_iyear', 'pp_eday', 'pp_emonth', 'pp_eyear',
                'tel', 'tel2', 'email', 'email2', 'email3', 'website',
                'profession', 'place_of_birth',
                'address', 'address_city_state', 'address_postal', 'address_country_code',
                'note', 'pp_file',
                ], 'trim'],
            [[
                'name', 'is_repeating'
                ], 'required', 'message'=>'Required'],
            [[
                'pp_bday', 'pp_iday', 'pp_eday'
                ], 'integer', 'min'=>1, 'max'=>31, 'message'=>'Invalid day'],
            [[
                'pp_bmonth', 'pp_imonth', 'pp_emonth'
                ], 'integer', 'min'=>1, 'max'=>12, 'message'=>'Invalid month'],
            [[
                'pp_byear', 'pp_iyear', 'pp_eyear'
                ], 'integer', 'min'=>1900, 'max'=>2049, 'message'=>'Invalid year', 'tooBig'=>'Year too big', 'tooSmall'=>'Year too small'],
            [[
                'email', 'email2', 'email3'
                ], 'email', 'message'=>'Invalid email'],
            [[
                'website'
                ], 'url', 'message'=>'Invalid link'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'booking_id' => Yii::t('app', 'Booking ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'is_repeating' => Yii::t('app', 'Is repeating'),
            'pp_file' => Yii::t('app', 'Passport file'),
            'data' => Yii::t('app', 'Data'),
            'note' => Yii::t('app', 'Note'),
            'pp_number' => Yii::t('app', 'Passport Number'),
            'pp_country_code' => Yii::t('app', 'Country Code'),
            'pp_name_1' => Yii::t('app', 'Passport Name 1'),
            'pp_name_2' => Yii::t('app', 'Passport Name 2'),
            'pp_bday' => Yii::t('app', 'Passport Birth Day'),
            'pp_bmonth' => Yii::t('app', 'Passport Birth Month'),
            'pp_byear' => Yii::t('app', 'Passport Birth Year'),
            'name' => Yii::t('app', 'Name'),
            'pp_gender' => Yii::t('app', 'Gender'),
            'pp_iday' => Yii::t('app', 'Passport Issue day'),
            'pp_imonth' => Yii::t('app', 'Passport Issue Month'),
            'pp_iyear' => Yii::t('app', 'Passport Issue Year'),
            'pp_eday' => Yii::t('app', 'Passport Expiry Day'),
            'pp_emonth' => Yii::t('app', 'Passport Expiry Month'),
            'pp_eyear' => Yii::t('app', 'Passport Expiry Year'),
            'tel' => Yii::t('app', 'Telephone'), 
            'tel2' => Yii::t('app', 'Telephone 2'),
            'email' => Yii::t('app', 'Email'),
            'email2' => Yii::t('app', 'Email 2'),
            'email3' => Yii::t('app', 'Email 3'),
            'website' => Yii::t('app', 'Website/Facebook'),
            'profession' => Yii::t('app', 'Profession'),
            'place_of_birth' => Yii::t('app', 'Place Of Birth'),
            'address' => Yii::t('app', 'Address'),
            'visa_vn_arrival' => Yii::t('app', 'Visa VietNames arrival'),
            'pay_deposit' => Yii::t('app', 'Pay Deposit'),
            'pay_balance' => Yii::t('app', 'Pay Balance'),
            'in_name' => Yii::t('app', 'Insurance Name'),
            'in_number' => Yii::t('app', 'Insurance Number'),
            'in_tel' => Yii::t('app', 'Insurance Telephone'),
            'in_email' => Yii::t('app', 'Insurance Email'),
            'em_name' => Yii::t('app', 'Name'),
            'em_relation' => Yii::t('app', 'Relation'),
            'em_tel' => Yii::t('app', 'Telephone'),
            'em_email' => Yii::t('app', 'Email'),
            'is_payer' => Yii::t('app', 'Is Payer')
        ];
    }
    public function upload()
    {//var_dump($this->pp_file);die('ok');
        if (count($this->pp_file) > 0) {//var_dump($this->pp_file);die();
            foreach ($this->pp_file as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
