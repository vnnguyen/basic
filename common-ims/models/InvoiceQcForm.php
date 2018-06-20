<?php
namespace common\models;

use yii\base\Model;

class InvoiceQcForm extends Model
{
	public $opCode = '';
	public $opName = '';
	public $paxName = '';
	public $paxAddr = '';
	public $lang = 'fr';
	public $currency = 'EUR';
	public $cost = 0;
	public $deposit = 0;
	public $depositMethod = '';
	public $xrate = 1;
	public $link = '';

	public function attributeLabels() {
		return [
			'opCode'=>'Tour code',
			'opName'=>'Tour name',
			'paxName'=>'Bill to (name)',
			'paxAddr'=>'Bill to (address)',
			'lang'=>'Invoice language',
			'currency'=>'Invoice currency',
			'cost'=>'Total tour cost',
			'deposit'=>'Deposit',
			'xrate'=>'Exchange rate',
			'link'=>'Payment link',
		];
	}

	public function rules()
	{
		return [
			[['link'], 'unique'],
			[['opCode', 'opName', 'paxName', 'paxAddr'], 'trim'],
			[['currency'], 'in', 'range'=>['EUR', 'USD', 'VND']],
			[['lang'], 'in', 'range'=>['en', 'fr', 'vi']],
			[['cost', 'deposit'], 'number', 'min'=>0],
			[['link'], 'url'],
			[['opCode', 'opName', 'paxName', 'paxAddr', 'lang', 'currency', 'cost', 'deposit', 'depositMethod'], 'required'],
		];
	}


}
