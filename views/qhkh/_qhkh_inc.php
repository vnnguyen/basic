<?

Yii::$app->params['page_title'] = Yii::T('a', 'Customer relations');

$this->params['breadcrumb'] = [
	[Yii::t('a', 'Customer relations'), SEG2 != '' ? 'qhkh' : null],
	SEG2 == 'quy-qhkh' ? [Yii::t('a', 'Fund')] : null,
];
// $this->params['actions'] = [
// 	[
// 		['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'permissions/c', 'active'=>SEG2 == 'c'],
// 	],
// ];

// if (isset($thePermission['id']) && in_array(SEG2, ['r', 'u', 'd', 'users'])) {
// 	$this->params['actions'][] = [
// 		['icon'=>'eye', 'title'=>'View', 'link'=>'permissions/r/'.$thePermission['id'], 'active'=>SEG2 == 'r'],
// 		['icon'=>'group', 'title'=>'Users', 'link'=>'permissions/users/'.$thePermission['id'], 'active'=>SEG2 == 'r'],
// 		['icon'=>'edit', 'title'=>'Edit', 'link'=>'permissions/u/'.$thePermission['id'], 'active'=>SEG2 == 'u'],
// 	];
// 	$this->params['actions'][] = [
// 		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'permissions/d/'.$thePermission['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
// 	];
// }
