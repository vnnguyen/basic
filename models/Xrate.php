<?php
namespace common\models;
use Yii;
use app\helpers\DateTimeHelper;

class Xrate extends MyActiveRecord
{
	// TODO: tỉ giá phân loại nhỏ hơn nữa, vd tỉ giá mua vào bán ra etc

	public static function tableName()
	{
		return '{{%xrates}}';
	}

	public function attributeLabels()
	{
		return [
			'rate_dt'=>Yii::t('xrate', 'Time and date'),
			'currency1'=>Yii::t('xrate', 'Currency 1'),
			'currency2'=>Yii::t('xrate', 'Currency 2'),
			'rate'=>Yii::t('xrate', 'Exchange rate'),
			'note'=>Yii::t('xrate', 'Note'),
		];
	}

	public function rules()
	{
		return [
			[['rate_dt', 'currency1', 'currency2', 'rate', 'note'], 'filter', 'filter' => 'trim'],
			[['rate_dt', 'currency1', 'currency2', 'rate'], 'required'],
			[['currency2'], 'compare', 'compareAttribute'=>'currency1', 'operator'=>'!='],
		];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($insert) {
				$this->created_at = NOW;
				$this->created_by = \Yii::$app->user->identity->id;
			}
			$this->updated_at = NOW;
			$this->updated_by = \Yii::$app->user->identity->id;
			return true;
		}
		return false;
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->rate_dt = DateTimeHelper::format($this->rate_dt, 'Y-m-d H:i:s', Yii::$app->timezone);
		$this->rate_dt = DateTimeHelper::format($this->rate_dt, 'Y-m-d H:i:s', Yii::$app->timezone);
	}

}
