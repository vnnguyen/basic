<?

namespace common\models;

use yii\base\Model;

class TourInHdForm extends Model
{
    public $language;
    public $days;
    public $until_day;
    public $payer;
    public $tourguide;
    public $driver;
    public $options;
    public $note;

    public function attributeLabels()
    {
        return [
            'language'=>'Ngôn ngữ',
            'days'=>'In các ngày (vd 1-3,4,5-7)',
            'tourguide'=>'In cho tour guide',
            'payer'=>'Người thanh toán',
            'driver'=>'Lái xe',
            'options'=>'Các lựa chọn khác',
            'note'=>'Ghi chú cho tour guide',
        ];
    }

    public function rules()
    {
        return [
            [['language', 'days', 'payer', 'tourguide', 'driver', 'options', 'note'], 'trim'],
            [['language', 'days'], 'required', 'message'=>'Required'],
        ];
    }

}