<?
namespace common\models;

class Note extends MyActiveRecord
{
	public static function tableName() {
		return 'posts';
	}

	public function rules()
	{
		return [
			[['title', 'body'], 'filter', 'filter'=>'trim'],
			[['title', 'body'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'notes_c'=>['title', 'body'],
			'notes_ce'=>['title', 'body'],
			'notes_u'=>['title', 'body'],
		];
	}

	public function getFrom() {
		return $this->hasOne(User2::className(), ['id' => 'from_id']);
	}

	public function getCreatedBy() {
		return $this->hasOne(User2::className(), ['id' => 'created_by']);
	}

	public function getUpdatedBy() {
		return $this->hasOne(User2::className(), ['id' => 'updated_by']);
	}

	public function getTo()
	{
		return $this->hasMany(User2::className(), ['id'=>'user_id'])
			->viaTable('post_to', ['post_id' => 'id']);
	}

	public function getSto()
	{
		// Used in search
		return $this->hasMany(User::className(), ['id'=>'user_id'])
			->viaTable('post_to', ['post_id' => 'id']);
	}

	public function getRelatedCase() {
		return $this->hasOne(Kase::className(), ['id' => 'rid']);
	}

	public function getRelatedTour() {
		return $this->hasOne(Tour::className(), ['id'=>'rid']);
	}

	public function getComments() {
		return $this->hasMany(Note::className(), ['n_id' => 'id']);
	}

	public function getReplies() {
		return $this->hasMany(Note::className(), ['n_id' => 'id']);
	}

	public function getFiles() {
		return $this->hasMany(File::className(), ['n_id' => 'id']);
	}

}
