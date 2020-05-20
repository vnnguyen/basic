<?

namespace app\models;

use Yii;
use yii\base\Model;

class DkSdGheTreEmForm extends Model
{
    public $ghe;
    public $tour;
    public $tu;
    public $den;
    public $note;

    public function attributeLabels()
    {
        return [
            'ghe'=>Yii::t('x', 'Chair #'),
            'tour'=>Yii::t('x', 'Tour code'),
            'tu'=>Yii::t('x', 'From date/time'),
            'den'=>Yii::t('x', 'Until date/time'),
            'note'=>Yii::t('x', 'Note'),
        ];
    }

    public function rules()
    {
        return [
            [[
                'ghe', 'tour', 'tu', 'note',
            ], 'trim'],
            [[
                'ghe', 'tour', 'tu',
            ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}