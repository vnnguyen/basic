<?

namespace app\models;

use Yii;
use yii\base\Model;

class HuanTestDvForm extends Model
{
	public $ncc_id;
	public $dv_id;
	public $xday;
	public $note;

	public function attributeLabels()
	{
		return [
			'ncc_id'=>Yii::t('mn', 'Service supplier'),
			'dv_id'=>Yii::t('mn', 'Service name'),
			'xday'=>Yii::t('mn', 'Days/Nights'),
			'note'=>Yii::t('mn', 'Note'),
		];
	}

	public function rules()
	{
		return [
			[['note'], 'trim'],
			[['ncc_id', 'dv_id', 'xday'], 'required', 'message'=>Yii::t('mn', 'Required')],
		];
	}

}