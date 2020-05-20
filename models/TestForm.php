<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TestForm extends Model
{
    public $test;
    public $note;

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['test'], 'trim'],
            // [['service_time'], 'required', 'message'=>Yii::t('mn', 'Required')],
        ];
    }

}