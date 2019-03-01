<?
namespace common\models;

class TourFeedback extends MyActiveRecord
{

	public static function tableName() {
		return '{{%tour_feedbacks}}';
	}

	public function rules()
	{
		return [
			[['who', 'say', 'what', 'feedback'], 'trim'],
			[['who', 'say', 'what', 'feedback'], 'required'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

}
