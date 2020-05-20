<?php
namespace app\models;

use yii\base\Model;

class TourTamungForm extends Model
{
    public $lydo, $cho, $sotk, $sotien, $loaitien = 'VND', $tutk, $hinhthuc, $ngay, $cancu, $tigia, $bpdn, $ndn, $tbpdn, $bptn, $ntn, $tbptn, $note, $formdata, $cpt;

    public function rules()
    {
        return [
            [[
                'lydo', 'cho', 'sotk', 'sotien', 'loaitien', 'tutk', 'hinhthuc', 'ngay', 'cancu', 'tigia', 'bpdn', 'ndn', 'tbpdn', 'bptn', 'ntn', 'tbptn', 'note', 'formdata', 'cpt',
                ], 'trim'],
        ];
    }

}