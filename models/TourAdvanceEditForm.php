<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TourAdvanceEditForm extends Model
{
    public $sotien, $loaitien = 'VND', $hinhthuc, $ngay, $sotk, $bpdn, $ndn, $tbpdn, $bptn, $ntn, $tbptn, $ghichu, $cpt;

    public function rules()
    {
        return [
            [[
                'sotien', 'loaitien', 'hinhthuc', 'ngay', 'sotk', 'bpdn', 'ndn', 'tbpdn', 'bptn', 'ntn', 'tbptn', 'ghichu', 'cpt'
                ], 'trim'],
            [[
                'sotien', 'loaitien', 'hinhthuc', 'ngay', 'bpdn', 'ndn', 'tbpdn', 'bptn', 'ntn', 'tbptn'
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}
