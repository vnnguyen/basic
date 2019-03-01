<?
namespace common\models;

use Yii;

class SampleTourProgram extends MyActiveRecord
{
	public static function tableName() {
		return '{{%sample_tour_programs}}';
	}

	public function rules()
	{
		return [
			[[
				'language', 'title', 'body', 'tags', 'note',
				], 'trim'],
			[[
				'language', 'title',
				], 'required', 'message'=>Yii::t('app', 'Required')],
		];
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function scenarios()
	{
		return [
			'program/c'=>[
				'language', 'title', 'body', 'tags', 'note',
			],
			'program/u'=>[
				'language', 'title', 'body', 'tags', 'note',
			],
		];
	}


	public function getDays()
	{
		return $this->hasMany(SampleTourDay::className(), ['program_id' => 'id']);
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
