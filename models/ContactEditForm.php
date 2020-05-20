<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ContactEditForm extends Model
{
    public
        $name, $nickname, $fname, $lname, $gender, $bday, $bmonth, $byear, $country_code,
        $language, $marital, $pob, $pob_country,

        // Passport
        $pp_number, $pp_country_code, $pp_name, $pp_name2, $pp_gender,
        $pp_bdate, $pp_idate, $pp_edate,

        $profession, $job_title, $employer,

        $tel, $tel2, $email, $email2, $email3, $email4, $website, $website2,
        $addr_street, $addr_city, $addr_state, $addr_country, $addr_postal,

        $star, $comm, $tags,

        $traveler_profile, $traveler_profile_assoc_names,
        $travel_preferences, $diet, $allergies, $diet_note, $health_condition, $health_note,
        $transportation, $transportation_note, $future_travel_wishlist,
        $likes, $dislikes,

        $rel_with_amica, $customer_ranking, $ambassaddor_potentiality,
        $newsletter_optin,
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
                'pp_bdate', 'pp_idate', 'pp_edate',

                'profession', 'job_title', 'employer',

                'star', 'comm', 'tags',

                'traveler_profile', 'traveler_profile_assoc_names',
                'travel_preferences', 'diet', 'allergies', 'diet_note', 'health_condition', 'health_note',
                'transportation', 'transportation_note', 'future_travel_wishlist',
                'likes', 'dislikes',

                'rel_with_amica', 'customer_ranking', 'ambassaddor_potentiality',
                'newsletter_optin',
                'info',
                'test',
            ], 'trim'],
            [[
                'name', 'gender'
                ], 'required', 'message'=>Yii::t('app', 'Required')],
        ];
    }

}