<?
namespace app\models;

class Dv extends \yii\db\ActiveRecord
{

	public static function tableName()
	{
		return '{{%dv2}}';
	}

	public function attributeLabels() {
		return [
			'dest_id'=>'Địa điểm',
			'venue_id'=>'Nơi sử dụng dịch vụ',
			'provider_id'=>'Công ty cung cấp dịch vụ',
			'name'=>'Tên',
		];
	}

	public function rules() {
		return [
			[['stype', 'dest_id', 'venue_id', 'provider_id', 'name', 'note', 'conditions'], 'trim'],
			[['stype', 'dest_id', 'name'], 'required'],
			[['venue_id', 'provider_id'], 'default', 'value'=>0],
		];
	}

	public function scenarios() {
		return [
			'dv/c'=>['stype', 'dest_id', 'venue_id', 'provider_id', 'name', 'conditions', 'note'],
			'dv/u'=>['stype', 'dest_id', 'venue_id', 'provider_id', 'name', 'conditions', 'note'],
		];
	}

	public function getTour()
	{
		return $this->hasOne(\common\models\Tour::className(), ['id'=>'tour_id']);
	}

	public function getCp()
	{
		return $this->hasOne(\app\models\Cp::className(), ['id'=>'dv_id']);
	}

	public function getVenue()
	{
		return $this->hasOne(\common\models\Venue::className(), ['id'=>'venue_id']);
	}

	public function getProvider()
	{
		return $this->hasOne(\common\models\Company::className(), ['id'=>'provider_id']);
	}

	public function getDestination()
	{
		return $this->hasOne(\common\models\Destination::className(), ['id'=>'dest_id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(\common\models\User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(\common\models\User::className(), ['id'=>'updated_by']);
	}

}
