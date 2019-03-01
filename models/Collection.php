<?
namespace common\models;

class Collection extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%collections}}';
	}

	public function rules() {
		return [
			[['title', 'summary', 'external_url', 'event_date'], 'trim'],
			[['title', 'summary', 'author_id', 'status', 'online_from', 'is_sticky'], 'required'],
			[['image'], 'url'],
		];
	}

	public function scenarios() {
		return [
			'collection/c'=>['title', 'summary'],
			'collection/u'=>['title', 'summary', 'status', 'image', 'is_sticky', 'external_url', 'event_date'],
		];
	}
}
