<?

namespace common\models;

use yii\base\Model;

class TourAssignCsForm extends Model
{
	public $css = [];

	public function attributeLabels()
	{
		return [
			'css'=>'Customer care staff',
		];
	}

	public function rules()
	{
		return [
			[['css'], 'required', 'message'=>'Required'],
		];
	}

}