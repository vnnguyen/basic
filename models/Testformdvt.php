<?php 
namespace app\models;

use Yii;
use yii\base\Model;

class Testformdvt extends Model {
	public $test;
	
	public $name;
	public $destination;
	public $address;
	public $location;
	public $rank;
	public $style;
	public $eco;
	public $total_room = 0;
	public $min_price = 0;
	public $max_price = 0;
	public $maps;
	public $number_restauran = 0;
	public $note_restauran;
	public $amica_rate;
	public $note;
	public $tags;
	public $recommend_sale;
	public $amica_rank;
	public $date_inspection;
	public $p_inspection;
	public $email;
	public $type_email;
	public $website = '';
	public $type_website = '';
	public $tel ='';
	public $type_tel = '';	
	public $service = '';
	public $service_note = '';
	
	public function attributeLabels()
	{
		return [
			'name'=> Yii::t('app','Hotel name'),
			'destination' => Yii::t('app','Destination'),	
			'address' => Yii::t('app','Address'),	
			'location' => Yii::t('app','Location'),	
			'rank' => Yii::t('app','Rank'),	
			'eco' => Yii::t('app','Eco-Responsible Approach'),	
			'min_price' => Yii::t('app','Min Price'),	
			'max_price' => Yii::t('app','Max Price'),	
			'maps' => Yii::t('app','Maps'),
			'number_restauran' => Yii::t('app','Number'),
			'note_restauran' => Yii::t('app','Note'),
			'amica_rate' => Yii::t('app','Amica rate'),
			'note' => Yii::t('app','Note'),
			'tags' => Yii::t('app','Tags'),
			'total_room' => Yii::t('app','Total room'),	
			'date_inspection' => Yii::t('app','Last inspection'),
			'p_inspection' => Yii::t('app','Former inspection'),
		];
	}
	public function rules()
	{
		return [
				[['name','style', 'destination','address','location','rank','eco','min_price','max_price','maps','number_restauran','note_restauran','amica_rate','note','total_room','recommend_sale'], 'trim'],
				[['name', 'destination', 'style'], 'required'],
		];
	}

}
