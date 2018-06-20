<?
$this->title = 'People database';
$this->params['icon'] = 'user';

$this->params['breadcrumb'] = [
	['People', '@web/users'],
];

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New', 'link'=>'users/c', 'active'=>SEG2 == 'c'],
	],
];

if (isset($theUser['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'users/r/'.$theUser['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'users/u/'.$theUser['id'], 'active'=>SEG2 == 'u'],
		['submenu'=>[
			['icon'=>'key', 'label'=>'Account & Login', 'link'=>'users/account/'.$theUser['id'], 'active'=>SEG2 == 'account', 'visible'=>Yii::$app->user->id == 1],
			['icon'=>'user', 'label'=>'Log in as user', 'link'=>'users/loginas/'.$theUser['id'], 'active'=>SEG2 == 'loginas', 'visible'=>Yii::$app->user->id <= 4],
			['-'],
			['icon'=>'edit', 'label'=>'Amica member profile', 'link'=>'members/u/'.$theUser['id']],
			['icon'=>'edit', 'label'=>'Tour guide profile', 'link'=>'tourguides/u/'.$theUser['id']],
			['icon'=>'edit', 'label'=>'Driver profile', 'link'=>'drivers/u/'.$theUser['id']],
			],
		],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'users/d/'.$theUser['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}
