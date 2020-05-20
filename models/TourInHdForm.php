<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TourInHdForm extends Model
{
    public $language;
    public $days;
    public $printfor;
    public $until_day;
    public $payer;
    public $tourguide;
    public $driver;
    public $options;
    public $note;

    public $dntt_form = 'dntt', $dntt_nguoi = '', $dntt_bophan = 'Điều hành', $dntt_tbp = 'Nguyễn Thùy Dương', $dntt_kttt = 'Trần Thị Thanh', $dntt_tamung = 0, $dntt_hinhthuc = 'Tiền mặt', $dntt_taikhoan = '';

    public function attributeLabels()
    {
        return [
            'language'=>Yii::t('x', 'Language'),
            'days'=>'In các ngày (vd 1-3,4,5-7)',
            'printfor'=>Yii::t('x', 'Print for'),
            'tourguide'=>Yii::t('x', 'Tour guide'),
            'payer'=>Yii::t('x', 'Payer'),
            'driver'=>Yii::t('x', 'Tour driver'),
            'options'=>'Các lựa chọn khác',
            'note'=>Yii::t('x', 'Note'),
        ];
    }

    public function rules()
    {
        return [
            [[
                'language', 'days', 'printfor', 'payer', 'tourguide', 'driver', 'options', 'note',
                'dntt_form', 'dntt_nguoi', 'dntt_bophan', 'dntt_tbp', 'dntt_kttt', 'dntt_tamung', 'dntt_hinhthuc', 'dntt_taikhoan',
                ], 'trim'],
            [['language', 'days'], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}