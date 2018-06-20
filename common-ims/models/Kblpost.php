<?php
namespace common\models;

class Kblpost extends MyActiveRecord
{
	public $total = 0;

	public static function tableName() {
		return '{{%kbl_posts}}';
	}

	public function attributeLabels() {
		return [
			'url'=>'Địa chỉ link',
			'entry_order'=>'Thứ tự bài viết',
		];
	}

	public function rules() {
		return [
			[['name', 'status', 'category', 'url'], 'filter', 'filter' => 'trim'],
			[['category', 'name', 'summary', 'status'], 'required'],
			[['entry_order'], 'integer'],
			['url', 'url']
		];
	}

	public function scenarios()
	{
		return [
			'default'=>['category', 'name', 'summary', 'entry_order', 'status', 'url'],
			'delete'=>[],
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
