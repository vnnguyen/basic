<?
namespace common\models;

class Mtt extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%mtt}}';
	}

	public function attributeLabels()
	{
		return [
			'amount'=>'Số tiền TT lần này',
			'currency'=>'Loại tiền TT lần này',
			'xrate'=>'Tỉ giá TT lần này',
			'tkgn'=>'TK ghi nợ',
			'mp'=>'Mã phí',
			'note'=>'Ghi chú',
		];
	}

	public function rules()
	{
		return [
			[['payment_dt', 'tkgn', 'mp', 'amount', 'currency', 'xrate', 'paid_in_full', 'note'], 'trim'],
			[['amount', 'xrate'], 'number', 'message'=>'Không hợp lệ'],
			[['amount', 'xrate', 'currency'], 'required', 'message'=>'Còn thiếu'],
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

	public function getLtt()
	{
		return $this->hasOne(Ltt::className(), ['id'=>'ltt_id']);
	}

	public function getCpt()
	{
		return $this->hasOne(Cpt::className(), ['dvtour_id'=>'cpt_id']);
	}

}
