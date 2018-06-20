<?php

namespace app\models;

use Yii;
use yii\base\Model;

class DvTestForm extends Model
{
    public $mua, $mua_cty, $mua_hd,
        $dat, $dat_dk, $dat_qua,
        $gia, $tien,
        $tra_dk, $tra_qua
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
                'mua', 'mua_cty', 'mua_hd',
                'dat', 'dat_dk', 'dat_qua',
                'gia', 'tien',
                'tra_dk', 'tra_qua'
            ], 'trim'],
        ];
    }

}