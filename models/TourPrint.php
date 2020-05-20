<?php
namespace app\models;

use Yii;
/**
 * Print form data
 */
class TourPrint extends MyActiveRecord
{

    public static function tableName() {
        return 'tour_prints';
    }

    public function _rules()
    {
        return [
            [[
                'tourguide_contact_id', 'amount', 'currency', 'reason', 'date_needed', 'pay_method', 'date_needed', 'note',
                ], 'trim'],
            [[
                'tourguide_contact_id', 'amount', 'currency', 'reason', 'date_needed', 'pay_method', 'date_needed',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function _scenarios()
    {
        return [
            'advances/add'=>['tourguide_contact_id', 'amount', 'currency', 'reason', 'pay_method', 'date_needed',],
            'advances/quickadd'=>['tourguide_contact_id', 'amount', 'currency', 'reason', 'pay_method', 'date_needed', 'note'],
            'advances/edit'=>['tourguide_contact_id', 'amount', 'currency', 'reason', 'date_needed', 'pay_method',],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id'=>'contact_id']);
    }

    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['id'=>'cpt_id']);
    }

}
