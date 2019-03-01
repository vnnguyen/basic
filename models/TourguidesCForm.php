<?php
namespace common\models;

use yii\base\Model;

class TourguidesCForm extends Model
{
	public $fname;
	public $lname;
	public $gender;
	public $nationality;
	public $phone;
	public $email;
	public $languages;
	public $regions;
	public $tour_types;
	public $pros;
	public $cons;
	public $ratings;
	public $note;
	public $create;

	public function attributeLabels()
	{
		return [
			'fname'=>'Họ và đệm',
			'lname'=>'Tên',
			'gender'=>'Giới tính',
			'nationality'=>'Quốc tịch',
			'phone'=>'ĐT di động',
			'email'=>'Email',
			'languages'=>'Các thứ tiếng nói được',
			'regions'=>'Các vùng hoạt động',
			'note'=>'Ghi chú',
			'pros'=>'Strong points',
			'cons'=>'Weak points',
			'ratings'=>'Rating (1-10)',
			'create'=>'Xác nhận thêm tour guide',
		];
	}

	public function rules()
	{
		return [
			[['fname', 'lname', 'gender', 'nationality', 'phone', 'email'], 'trim'],
			[['pros', 'cons', 'tour_types', 'regions', 'ratings', 'languages', 'note'], 'trim'],
			[['fname', 'lname', 'gender', 'nationality', 'phone'], 'required', 'message'=>'Required'],
			/*[['create'], 'required', 'message'=>'Required', 'when'=>function() {
				return $this->fname !='';
			}],*/
			[['email'], 'email', 'message'=>'Invalid email'],
			[['email'], 'filter', 'filter'=>'strtolower'],
		];
	}

}