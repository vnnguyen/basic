<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SiTourSummaryForm extends Model
{
    public $tour_company;
    public $tour_code;
    public $tour_name;
    public $tour_note;

    public function attributeLabels()
    {
        return [
            'tour_company'=>Yii::t('mn', 'Tour company'),
            'tour_code'=>Yii::t('mn', 'Tour code'),
            'tour_name'=>Yii::t('mn', 'Tour name'),
            'tour_note'=>Yii::t('mn', 'Tour note'),
        ];
    }

    public function rules()
    {
        return [
            [['tour_company', 'tour_code', 'tour_name', 'tour_note'], 'trim'],
            [['tour_company', 'tour_code', 'tour_name'], 'required', 'message'=>Yii::t('mn', 'Required')],
        ];
    }

}