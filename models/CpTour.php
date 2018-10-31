<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cp_tour".
 *
 * @property int $id
 * @property int $tour_id
 * @property int $venue_id
 * @property int $dv_id
 * @property int $qty
 * @property int $num_day
 * @property string $use_day
 * @property double $price
 * @property string $currency
 * @property string $book_of
 * @property string $pay_of
 * @property string $status_book
 * @property string $payment_dt
 * @property string $who_pay
 * @property int $parent_id
 */
class CpTour extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cp_tour';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['tour_id', 'venue_id', 'dv_id', 'qty', 'num_day', 'price', 'currency', 'book_of', 'pay_of', 'status_book', 'who_pay'], 'required'],
            // [['tour_id', 'venue_id', 'dv_id', 'qty', 'num_day', 'parent_id'], 'integer'],
            // [['use_day', 'payment_dt'], 'safe'],
            // [['price'], 'number'],
            // [['currency', 'book_of', 'pay_of', 'status_book', 'who_pay'], 'string', 'max' => 20],
        ];
    }

    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venue_id']);
    }
    public function getDv()
    {
        return $this->hasOne(DV::className(), ['id' => 'dv_id']);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tour_id' => Yii::t('app', 'Tour ID'),
            'venue_id' => Yii::t('app', 'Provider'),
            'dv_id' => Yii::t('app', 'Service'),
            'qty' => Yii::t('app', 'Quantity'),
            'num_day' => Yii::t('app', 'Number Day'),
            'use_day' => Yii::t('app', 'Use Day'),
            'price' => Yii::t('app', 'Price'),
            'currency' => Yii::t('app', 'Currency'),
            'book_of' => Yii::t('app', 'Book by Office'),
            'pay_of' => Yii::t('app', 'Pay Office'),
            'status_book' => Yii::t('app', 'Status Book'),
            'payment_dt' => Yii::t('app', 'Payment Date'),
            'who_pay' => Yii::t('app', 'Who Pay'),
            'parent_id' => Yii::t('app', 'Parent ID'),
        ];
    }
}
