<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TourLichxeForm extends Model
{
    public $name, $days, $vp, $pax, $chuxe, $laixe, $loaixe;
    public $dieuhanh, $huongdan = [], $giakm, $giadb, $giatb, $invat;
    public $note;
    public $cpkhac_ten, $cpkhac_dvi, $cpkhac_gia, $cpkhac_sl;

    public function attributeLabels()
    {
        return [
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
                'cpkhac_ten', 'cpkhac_dvi', 'cpkhac_gia', 'cpkhac_sl', 'invat',
                ], 'trim'],
            [[
                'pax', 'giakm', 'giadb', 'giatb'
                ], 'default', 'value'=>0],
            [[
                'vp', 'pax', 'dieuhanh'
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}