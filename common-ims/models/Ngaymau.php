<?
namespace common\models;

use Yii;

class Ngaymau extends MyActiveRecord
{
	public static function tableName() {
		return '{{%ngaymau}}';
	}

	public function rules()
	{
		return [
			[[
				'language', 'title', 'body', 'tags', 'meals', 'transport', 'hotels', 'guides', 'services', 'note',
				], 'trim'],
			[[
				'language', 'title', 'body', 'meals',
				], 'required', 'message'=>Yii::t('app', 'Required')],
		];
	}

	public function scenarios()
	{
		return [
			'day/c'=>[
				'language', 'title', 'body', 'tags', 'meals', 'transport', 'hotels', 'guides', 'services', 'note',
			],
			'day/u'=>[
				'language', 'title', 'body', 'tags', 'meals', 'transport', 'hotels', 'guides', 'services', 'note',
			],
		];
	}


	public function getProgram()
	{
		return $this->hasOne(SampleTourProgram::className(), ['id' => 'program_id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}
}
