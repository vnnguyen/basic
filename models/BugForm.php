<?php
namespace common\models;

use yii\base\Model;

class BugForm extends Model
{
	public $uri;
	public $expected;
	public $happened;
	public $comment;

	public function rules()
	{
		return [
			[['uri', 'expected', 'happened', 'comment'], 'filter', 'filter'=>'trim'],
			[['uri', 'expected', 'happened'], 'required', 'message'=>'Thông tin này còn thiếu'],
			[['uri'], 'url', 'message'=>'Địa chỉ không hợp lệ! Hãy copy đủ cả phần http://'],
		];
	}

	public function attributeLabels()
	{
		return [
			'uri'=>'Địa chỉ trang gặp lỗi',
			'expected'=>'Đúng ra / nếu không bị lỗi thì theo bạn phải như thế nào',
			'happened'=>'Khi truy cập trang nói trên, điều gì đã xảy ra',
			'comment'=>'Ý kiến khác của bạn',
		];
	}


}
