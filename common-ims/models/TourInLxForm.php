<?

namespace common\models;

use yii\base\Model;

class TourInLxForm extends Model
{
    public $name;
    public $days;
    public $vp;
    public $pax;
    public $chuxe;
    public $laixe;
    public $loaixe;
    public $dieuhanh;
    public $huongdan;
    public $giakm;
    public $giadb;
    public $giatb;
    public $note;

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
            [['name', 'days', 'vp', 'pax', 'chuxe', 'laixe', 'loaixe', 'dieuhanh', 'huongdan', 'giakm', 'giadb', 'giatb', 'note'], 'trim'],
            [['pax', 'giakm', 'giadb', 'giatb'], 'default', 'value'=>0],
            [['vp', 'pax', 'dieuhanh'], 'required', 'message'=>'Required'],
        ];
    }

}