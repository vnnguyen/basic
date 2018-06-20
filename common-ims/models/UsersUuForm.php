<?

namespace common\models;
use yii;
use yii\base\Model;

class UsersUuForm extends Model
{
	public $tags = '';
	public $fname;
	public $lname;
	public $name;
	public $gender = 'male';
	public $bday = 0;
	public $bmonth = 0;
	public $byear = 0;
	public $country_code = 'fr';
	public $profession;
	public $pob;
	public $note;
	public $register_email ;
	public $address ='';
	public $email = '';
	public $website = '';
	public $type_website = '';
	public $tel ='';
	public $type_tel = '';	
	public $relation ='';
	public $type_rela ='';
	public $marital_status = '';
	public $v_address = '';
	public $city = '';
	public $country = '';
	public $type_email ='';
	
	public function attributeLabels()
	{
		return [
				'fname'=> Yii::t('app','First name'),
				'lname'=> Yii::t('app','Last name'),
				'name'=> Yii::t('app','Full name'),
				'gender'=> Yii::t('app',' Gender'),
				'bday'=> Yii::t('app','Day of birth'),
				'bmonth'=> Yii::t('app','Month of birth'),
				'byear'=> Yii::t('app','Year of birth'),
				'country_code'=> Yii::t('app','Country'),
				'profession'=> Yii::t('app','Profession'),
				'pob'=> Yii::t('app','Place of birth'),
				'address'=> Yii::t('app','Address'),
				'register_email' => Yii::t('app','Register receive email'),
				'email' => 'Email',
				'website' => 'Website',
				'type_website' => 'Type website',
				'tel' => 'Phone',
				'type_tel' => 'type phonenumber',
				'relation' => 'Relation',
				'type_relation' => 'Type relation',
				'marital_status' => 'Status of relation',
				'city' => 'Rity',
				'country' => 'Country',
				'type_email' => 'Type email',
		];
	}
	
	public function rules()
	{
		return [
				[['fname', 'lname', 'name', 'gender', 'bday', 'bmonth', 'byear', 'country_code', 'profession', 'pob', 'note', 'tags','register_email','address','email','website','marital_status'], 'trim'],
				[['fname', 'lname', 'email'], 'required', 'message'=>'This field is required'],
// 				[['email'], 'email', 'message'=>'Email khong hop le'],
// 				[['email'], 'filter', 'filter'=>'strtolower'],
// 				[['website'], 'url'],
		];
	}
}