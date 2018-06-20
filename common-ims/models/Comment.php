<?php
namespace common\models;

class Comment extends MyActiveRecord
{

	public static function tableName() {
		return '{{%comments}}';
	}

	public function attributeLabels() {
		return [
			'body'=>'Comment',
		];
	}

	public function rules() {
		return [
			[['body'], 'trim'],
			[['body'], 'required'],
		];
	}

	public function scenarios() {
		return [
			'any/c'=>['body'],
			'create'=>['body'],
			'update'=>['body'],
			'events/r'=>['body'],
			'cpt/r'=>['body'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}

	public function getBlogpost()
	{
		return $this->hasOne(Post::className(), ['id'=>'rid'])->andWhere(['channel'=>'blog']);
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
