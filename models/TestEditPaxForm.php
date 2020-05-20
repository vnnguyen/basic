<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TestEditPaxForm extends Model
{
    public $test;
    public $id;
    public $name;
    public $abbr;
    public $amount;
    public $unit;
    public $status;
    public $note;

    public function rules()
    {
        return [
            [[
                'test', 'id', 'name', 'abbr', 'amount', 'unit', 'status', 'note',
                ], 'trim'],
            // [[
            //     'abbr',
            //     ], 'filter', 'filter'=>'strtolower'],
            // [[
            //     'name', 'abbr', 'amount', 'unit', 'status',
            //     ], 'required', 'message'=>'Required'],
            // [[
            //     'amount',
            //     ], 'integer', 'min'=>0, 'message'=>'Invalid'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Tên chặng xe'),
            'abbr' => Yii::t('app', 'Viết tắt'),
            'amount' => Yii::t('app', 'Số lượng'),
            'unit' => Yii::t('app', 'Đơn vị'),
            'status' => Yii::t('app', 'Trạng thái'),
            'note' => Yii::t('app', 'Ghi chú'),
        ];
    }
}
