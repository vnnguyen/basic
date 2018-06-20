<?

namespace common\models;

use yii\base\Model;

class TourRatingsForm extends Model
{
	public $tour_points = 0;

	public function attributeLabels()
	{
		return [
			'tour_points'=>'Tour points given by customers',
		];
	}

	public function rules()
	{
		return [
			[['tour_points'], 'integer', 'min'=>0, 'max'=>100],
		];
	}

}