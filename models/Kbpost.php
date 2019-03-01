<?php
namespace common\models;

class Kbpost extends MyActiveRecord
{

	public static function tableName() {
		return '{{%kbposts}}';
	}

	public function attributeLabels()
	{
		return [
			'body'=>'Content',
			'author_id'=>'Author',
		];
	}

	public function rules()
	{
		return [
			[['title', 'cats', 'tags', 'online_from'], 'trim'],
			[['title', 'body', 'author_id', 'status'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'create'=>['title'],
			'update'=>['title', 'body', 'author_id', 'status', 'cats', 'tags', 'online_from'],
		];
	}

	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'author_id']);
	}

	public function getComments()
	{
		return $this->hasMany(Comment::className(), ['rid' => 'id'])->where(['rtype'=>'kbpost']);
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
