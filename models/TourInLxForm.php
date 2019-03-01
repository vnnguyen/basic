<?php

namespace common\models;

use Yii;
use yii\base\Model;

class TourInLxForm extends Model
{
    public $name, $days, $vp, $pax, $chuxe, $laixe, $loaixe;
    public $dieuhanh, $huongdan, $giakm, $giadb, $giatb;
    public $note;
    public $cpkhac_ten, $cpkhac_dvi, $cpkhac_gia, $cpkhac_sl;

    public function attributeLabels()
    {
        return [
            'days'=>'In các ngày (vd 1-3,4,5-7)',
            'pax'=>'Số khách',
            'giakm'=>'Giá VND/km',
            'giadb'=>'Giá VND/ ngày Đông Bắc',
            'giatb'=>'Giá VND/ ngày Tây Bắc',
            'note'=>'Ghi chú in kèm',
        ];
    }

    public function rules()
    {
        return [
            [[
                'name', 'days', 'vp', 'pax', 'chuxe', 'laixe', 'loaixe', 'dieuhanh', 'huongdan', 'giakm', 'giadb', 'giatb', 'note',
                'cpkhac_ten', 'cpkhac_dvi', 'cpkhac_gia', 'cpkhac_sl'
                ], 'trim'],
            [[
                'pax', 'giakm', 'giadb', 'giatb'
                ], 'default', 'value'=>0],
            [[
                'vp', 'pax', 'dieuhanh'
                ], 'required', 'message'=>Yii::t('app', 'Required')],
        ];
    }

}