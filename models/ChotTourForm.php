<?php
namespace app\models;

use Yii;
use yii\base\Model;

class ChotTourForm extends Model
{
    public $qhkh_ketthuc, $qhkh_khaithac, $qhkh_da_khaithac, $qhkh_dexuat_khaithac, $mkt_da_khaithac, $qhkh_diem, $khach_diem;

    public function rules()
    {
        return [
            [[
                'qhkh_ketthuc', 'qhkh_diem', 'khach_diem',
                'qhkh_da_khaithac', 'qhkh_dexuat_khaithac', 'mkt_da_khaithac',
                ], 'trim'],
            [[
                'qhkh_ketthuc'
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}