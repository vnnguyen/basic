<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TourPrintOfficeProgramForm extends Model
{
    public $guide_name, $guide_id_type, $guide_id_num, $guide_id_issued_on, $guide_id_issued_by, $guide_license_num, $guide_tax_account, $guide_address;
    public $guide_use_from, $guide_use_until;

    public function rules()
    {
        return [
            [[
                'guide_name', 'guide_id_type', 'guide_id_num', 'guide_id_issued_on', 'guide_id_issued_by', 'guide_license_num', 'guide_tax_account', 'guide_address',
                'guide_use_from', 'guide_use_until'
                ], 'trim'],
            [[
                'guide_name'
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}