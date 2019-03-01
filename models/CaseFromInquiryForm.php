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
	// public $new_user_name;

	public function rules()
	{
		return [
			[['case_id'], 'required'],
			[['user_name', 'user_id'], 'my_required_user'],
			[['case_name', 'case_id'], 'my_required_case']
			// [['user_name'], 'required', 'when'=>function($m) {return $m->user_id == 0;}, 'whenClient' => "function (attribute, value) {return $('#casefrominquiryform-user_id').val() == 0;}"],
			// [['case_name'], 'required', 'when'=>function($m) {return $m->case_id == 0;}, 'whenClient' => "function (attribute, value) {return $('#casefrominquiryform-case_id').val() == 0;}"],
		];
	}
	public function my_required_user($attribute_name,$params) 
    {
        if ($this->user_id === 'ext') {
            $this->addError('user_name', 'The name of user is required');
        }
    }
    public function my_required_case($attribute_name,$params) 
    {
        if ($this->case_id === 'ext') {
            $this->addError('case_name', 'The name of case is required');
        }
    }
	public function attributeLabels()
	{
		return [
			'user_id'=>'Inquiry contact',
			'user_name'=>'New person\'s name',
			'case_id'=>'Existing case',
			'case_name'=>'New case\'s name',
			'new_user_name' => 'New user\' name'
		];
	}

}
