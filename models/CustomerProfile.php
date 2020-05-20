<?php
namespace app\models;

/**
 * Represents profile of a B2C customer
 */

class CustomerProfile extends MyActiveRecord
{
    public static function tableName()
    {
        return 'customer_profiles';
    }

    public function rules()
    {
        return [
            [[
                'amba_star_rating',
                ], 'trim'],
        ];
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

}
