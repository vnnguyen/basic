<?php
namespace common\models;

use yii\base\Model;

// This form is used in inquiries/r to create a new case from a web inquiry

class CaseFromInquiryForm extends Model
{
	public $user_id;
	public $user_name;
	public $case_id;
	public $case_name;

	public function rules()
	{
		return [
			[['user_id', 'case_id'], 'required'],
			[['user_name'], 'required', 'when'=>function($m) {return $m->user_id == 0;}, 'whenClient' => "function (attribute, value) {return $('#casefrominquiryform-user_id').val() == 0;}"],
			[['case_name'], 'required', 'when'=>function($m) {return $m->case_id == 0;}, 'whenClient' => "function (attribute, value) {return $('#casefrominquiryform-case_id').val() == 0;}"],
		];
	}

	public function attributeLabels()
	{
		return [
			'user_id'=>'Inquiry contact',
			'user_name'=>'New person\'s name',
			'case_id'=>'Existing case',
			'case_name'=>'New case\'s name',
		];
	}

}
