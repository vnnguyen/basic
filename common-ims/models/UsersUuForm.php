<?

namespace common\models;

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
	public $email1;
	public $email2;
	public $email3;
	public $phone1;
	public $phone2;
	public $address;
	public $profession;
	public $pob;
	public $website;
	public $note;

	public function attributeLabels()
	{
		return [
			'fname'=>'Họ',
			'lname'=>'Tên',
			'name'=>'Tên đầy đủ',
			'gender'=>'Giới tính',
			'bday'=>'Ngày sinh',
			'bmonth'=>'Tháng sinh',
			'byear'=>'Năm sinh',
			'country_code'=>'Quốc tịch',
			'email1'=>'Email 1',
			'email2'=>'Email 2',
			'email3'=>'Email 3',
			'phone1'=>'Số ĐT 1',
			'phone2'=>'Số ĐT 2',
			'profession'=>'Nghề nghiệp',
			'pob'=>'Nơi sinh',
			'address'=>'Địa chỉ',
		];
	}

	public function rules()
	{
		return [
			[['fname', 'lname', 'name', 'gender', 'bday', 'bmonth', 'byear', 'country_code', 'email1', 'email2', 'email3', 'phone1', 'phone2', 'profession', 'pob', 'address', 'website', 'note', 'tags'], 'trim'],
			[['fname', 'lname', 'name', 'gender', 'country_code'], 'required', 'message'=>'Còn thiếu'],
			[['email1', 'email2', 'email3'], 'email', 'message'=>'Email không hợp lệ'],
			[['email1', 'email2', 'email3'], 'filter', 'filter'=>'strtolower'],
			[['website'], 'url'],
		];
	}

}