<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TourSettingsForm extends Model
{
    public $show_client;

    public function attributeLabels()
    {
        return [
            'show_client'=>Yii::t('tour', 'Display this tour on client page'),
        ];
    }

    public function rules()
    {
        return [
            [['show_client'], 'trim'],
        ];
    }

}