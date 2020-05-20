<?
namespace common\models;

use Yii;

class TourFeedback extends MyActiveRecord
{

	public static function tableName() {
		return 'tour_feedbacks';
	}

	public function rules()
	{
		return [
			[['stype', 'who', 'say', 'what', 'feedback'], 'trim'],
			[['stype', 'who', 'say', 'what', 'feedback'], 'required', 'message'=>Yii::t('app', 'Required')],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User2::className(), ['id' => 'created_by']);
	}

}
