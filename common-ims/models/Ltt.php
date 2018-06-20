<?
namespace common\models;

class Ltt extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%ltt}}';
	}

	public function attributeLabels()
	{
		return [
			'payment_dt'=>'Ngày thanh toán',
			'tkgn'=>'TK ghi nợ',
			'mp'=>'Mã phí',
			'currency'=>'Loại tiền TT',
			'xrate'=>'Tỉ giá',
			'note'=>'Ghi chú',
		];
	}

	public function rules()
	{
		return [
			[['payment_dt', 'tkgn', 'mp', 'currency', 'xrate', 'note'], 'trim'],
			[['payment_dt', 'currency', 'xrate'], 'required', 'message'=>'Còn thiếu'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getMtt()
	{
		return $this->hasMany(Mtt::className(), ['ltt_id'=>'id']);
	}

}
