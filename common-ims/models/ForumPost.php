<?php
namespace common\models;

class ForumPost extends MyActiveRecord
{

	public static function tableName() {
		return '{{%forum_posts}}';
	}

	public function attributeLabels() {
		return [
			'body'=>'Content',
			'author_id'=>'Author',
			'cats'=>'Categories',
		];
	}

	public function rules() {
		return [
			[['title', 'body', 'cats', 'tags'], 'filter', 'filter'=>'trim'],
			[['title', 'body', 'cats'], 'required'],
		];
	}

	public function scenarios() {
		return [
			'forum/topics/c'=>['title', 'body', 'cats', 'tags'],
			'forum/topics/r'=>['body'],
			'forum/topics/u'=>['title', 'body', 'cats', 'tags'],
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

	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'author_id']);
	}

	public function getParentPost()
	{
		return $this->hasOne(ForumPost::className(), ['id' => 'parent_post_id']);
	}

	public function getForum()
	{
		return $this->hasOne(Forum::className(), ['id' => 'forum_id']);
	}

	public function getReplies()
	{
		return $this->hasMany(ForumPost::className(), ['parent_post_id' => 'id']);
	}
}
