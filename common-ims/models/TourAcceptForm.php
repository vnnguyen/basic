<?

namespace common\models;

use yii\base\Model;

class TourAcceptForm extends Model
{
    public $op_code;
    public $op_name;
    public $client_ref;
    public $owner;
    public $operators = [];
    public $also = [];

    public function attributeLabels()
    {
        return [
            'op_code'=>'Tour code',
            'op_name'=>'Tour name',
            'owner'=>'Tour owner',
            'operators'=>'Tour operators',
        ];
    }

    public function rules()
    {
        return [
            [['op_code', 'op_name', 'owner', 'client_ref'], 'trim'],
            [['op_code', 'op_name', 'owner', 'operators'], 'required', 'message'=>'Còn thiếu'],
        ];
    }

}