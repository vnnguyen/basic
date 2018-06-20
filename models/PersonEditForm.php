<?php

namespace app\models;

use Yii;
use yii\base\Model;

class PersonEditForm extends Model
{
    public
        $name, $nickname, $fname, $lname, $gender, $bday, $bmonth, $byear, $country_code,
        $language, $marital, $pob, $pob_country,

        // Passport
        $pp_number, $pp_country_code, $pp_name, $pp_name2, $pp_gender,
        $pp_bday, $pp_bmonth, $pp_byear,
        $pp_iday, $pp_imonth, $pp_iyear,
        $pp_eday, $pp_emonth, $pp_eyear,

        $profession, $job_title, $employer,

        $tel, $tel2, $email, $email2, $email3, $email4, $website, $website2,
        $addr_street, $addr_city, $addr_state, $addr_country, $addr_postal,

        $traveler_profile, $traveler_profile_assoc_names,
        $travel_preferences, $diet, $allergies, $diet_note, $health_condition, $health_note,
        $transportation, $transportation_note, $future_travel_wishlist,
        $likes, $dislikes,

        $rel_with_amica, $customer_ranking, $ambassaddor_potentiality,
        $newsletter_optin, $active_social_networks,
        $info,
        $test;

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [[
                'name', 'nickname', 'fname', 'lname', 'gender', 'bday', 'bmonth', 'byear', 'country_code',
                'language', 'marital', 'pob', 'pob_country',

                'pp_number', 'pp_country_code', 'pp_name', 'pp_name2', 'pp_gender',
                'pp_bday', 'pp_bmonth', 'pp_byear',
                'pp_iday', 'pp_imonth', 'pp_iyear',
                'pp_eday', 'pp_emonth', 'pp_eyear',

                'profession', 'job_title', 'employer',

                'tel', 'tel2', 'email', 'email2', 'email3', 'email4', 'website', 'website2',
                'addr_street', 'addr_city', 'addr_state', 'addr_country', 'addr_postal',

                'traveler_profile', 'traveler_profile_assoc_names',
                'travel_preferences', 'diet', 'allergies', 'diet_note', 'health_condition', 'health_note',
                'transportation', 'transportation_note', 'future_travel_wishlist',
                'likes', 'dislikes',

                'rel_with_amica', 'customer_ranking', 'ambassaddor_potentiality',
                'newsletter_optin', 'active_social_networks',
                'info',
                'test',
            ], 'trim'],
            [[
                'name', 'fname', 'lname', 'gender'
                ], 'required', 'message'=>Yii::t('app', 'Required')],
            [[
                'email', 'email2', 'email3', 'email4',
                ], 'email', 'message'=>Yii::t('app', 'Invalid')],
            [[
                'website', 'website2',
                ], 'url', 'message'=>Yii::t('app', 'Invalid')],
        ];
    }

}