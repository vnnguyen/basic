<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TourAdvanceXForm extends Model
{
    public $sotien, $loaitien = 'VND', $hinhthuc, $ngay, $ghichu;

    public function rules()
    {
        return [
            [[
                'sotien', 'loaitien', 'hinhthuc', 'ngay', 'ghichu'
                ], 'trim'],
            [[
                'sotien', 'loaitien', 'hinhthuc', 'ngay'
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}
