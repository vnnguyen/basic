<?

$this->params['breadcrumb'] = [
	['Permissions', 'permissions'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'permissions/c', 'active'=>SEG2 == 'c'],
	],
];

if (isset($thePermission['id']) && in_array(SEG2, ['r', 'u', 'd', 'users'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'permissions/r/'.$thePermission['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'group', 'title'=>'Users', 'link'=>'permissions/users/'.$thePermission['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'permissions/u/'.$thePermission['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'permissions/d/'.$thePermission['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}
