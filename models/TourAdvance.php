<?php
namespace app\models;

use Yii;
/**
 * TEMP: Advances for tourguides
 */
class TourAdvance extends MyActiveRecord
{

    public static function tableName() {
        return 'tour_advances';
    }

    public function rules()
    {
        return [
            [[
                'tour_id', 'payee_contact_id', 'amount', 'currency', 'date_needed', 'payment_method', 'date_needed', 'note',
                ], 'trim'],
            [[
                'tour_id', 'payee_contact_id', 'amount', 'currency', 'date_needed', 'payment_method', 'date_needed',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'advances/add'=>['payee_contact_id', 'amount', 'currency', 'reason', 'pay_method', 'date_needed',],
            'advances/quickadd'=>['payee_contact_id', 'amount', 'currency', 'reason', 'pay_method', 'date_needed', 'note'],
            'advances/edit'=>['payee_contact_id', 'amount', 'currency', 'reason', 'date_needed', 'pay_method',],

            'advances/c'=>['tour_id', 'payee_contact_id', 'amount', 'currency', 'reason', 'date_needed', 'payment_method', 'note'],
            'advances/u'=>['amount', 'currency', 'reason', 'date_needed', 'payment_method', 'note'],
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

    public function getTourguide()
    {
        return $this->hasOne(Contact::className(), ['id'=>'tourguide_contact_id']);
    }

    public function getPayee()
    {
        return $this->hasOne(Contact::className(), ['id'=>'payee_contact_id']);
    }

    public function getApprovedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'approved_by']);
    }

}
