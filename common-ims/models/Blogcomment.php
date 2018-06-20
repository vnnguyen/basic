<?php
namespace common\models;

class Blogcomment extends MyActiveRecord
{

	public static function tableName() {
		return '{{%blogcomments}}';
	}

	public function attributeLabels() {
		return [
			'body'=>'Comment',
			'author_id'=>'Author',
		];
	}

	public function rules() {
		return [
			[['body'], 'filter', 'filter'=>'trim'],
			[['body'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'create'=>['body'],
			'update'=>['body'],
		];
	}

	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}

}
