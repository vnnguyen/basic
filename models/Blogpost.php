<?
namespace common\models;

class Blogpost extends MyActiveRecord
{

	public static function tableName() {
		return '{{%blogposts}}';
	}

	public function attributeLabels() {
		return [
			'body'=>'Content',
			'author_id'=>'Author',
		];
	}

	public function rules() {
		return [
			[['title', 'summary', 'cats', 'tags'], 'trim'],
			[['title', 'summary', 'body', 'author_id', 'status', 'online_from', 'is_sticky'], 'required'],
			[['image'], 'url'],
		];
	}

	public function scenarios() {
		return [
			'create'=>['title', 'summary', 'online_from'],
			'update'=>['title', 'summary', 'online_from', 'body', 'author_id', 'status', 'image', 'is_sticky', 'cats', 'tags'],
		];
	}

	public function getAuthor()
	{
		return $this->hasOne(User::className(), ['id' => 'author_id']);
	}

	public function getBlog()
	{
		return $this->hasOne(Blog::className(), ['id' => 'blog_id']);
	}

	public function getComments()
	{
		return $this->hasMany(Comment::className(), ['rid' => 'id'])->where(['rtype'=>'blogpost']);
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
