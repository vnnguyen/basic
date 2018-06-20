<?

namespace app\models;

use yii\base\Model;

class PersonForm extends Model
{
	public $fname;
	public $lname;
	public $gender = 'male';
	public $bday;
	public $profession;
	public $note;

	public function attributeLabels()
	{
		return [
			'fname'=>'Họ',
			'lname'=>'Tên',
			'gender'=>'Giới tính',
			'bday'=>'Ngày sinh',
			'profession'=>'Nghề nghiệp',
			'note' => 'Ghi chú'
		];
	}

	public function rules()
	{
		return [
			[['fname', 'lname', 'gender', 'bday', 'profession', 'note'], 'trim'],
			[['fname', 'lname', 'name', 'gender'], 'required', 'message'=>'Còn thiếu'],
		];
	}

}