<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SukienPhonghopForm extends Model
{
    public $status, $name, $info, $start_date, $mins, $venue, $attendee_count, $start_time;

    public function rules()
    {
        return [
            [[
                'status', 'name', 'info', 'venue', 'start_date', 'start_time', 'mins', 'attendee_count',
                ], 'trim'],
            [[
                'name', 'venue', 'start_date', 'start_time', 'mins', 'attendee_count',
                ], 'required', 'message'=>Yii::t('app', 'Required')],
            [[
                'start_time',
                ], 'match', 'pattern' => '/^([01]\d|2[0-3]):([0-5]\d)$/', 'message'=>Yii::t('app', 'Invalid').' - hh:mm'],

        ];
    }

}