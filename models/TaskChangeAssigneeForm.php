<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TaskChangeAssigneeForm extends Model
{
    public $from_owner, $to_owner, $from_date, $to_date, $open_tasks_only = 'yes', $confirm = 'no';

    public function rules()
    {
        return [
            [[
                'from_owner', 'to_owner', 'from_date', 'to_date', 'open_tasks_only', 'confirm',
                ], 'trim'],
            [[
                'from_owner', 'to_owner', 'from_date', 'to_date', 'open_tasks_only',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }
}
