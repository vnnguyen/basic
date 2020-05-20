<?php
namespace app\models;

use Yii;
use yii\base\Model;

class VenueEventForm extends Model
{
    public $time_from, $time_until, $note
        ;

    public function attributeLabels()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [[
                'time_from', 'time_until', 'note',
            ], 'trim'],
            [[
                'time_from', 'time_until', 'note',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}