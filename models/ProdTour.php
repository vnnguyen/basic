<?php
namespace common\models;

class ProdTour extends MyActiveRecord
{

	public static function tableName() {
		return '{{%prod_tour}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($this->isNewRecord) {
				$this->created_at = NOW;
				$this->created_by = \Yii::$app->user->id;
			}
			$this->updated_at = NOW;
			$this->updated_by = \Yii::$app->user->id;
			return true;
		}
		return false;
	}

}
