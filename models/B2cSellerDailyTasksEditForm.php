<?
namespace app\models;

use Yii;
use yii\base\Model;

class B2cSellerDailyTasksEditForm extends Model
{

    public $test, $c1, $c2, $c3;

    public function rules() {
        return [
            [[
                'c1', 'c2', 'c3',
            ], 'trim']
        ];
    }
}
