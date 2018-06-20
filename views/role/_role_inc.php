<?

$this->params['breadcrumb'] = [
	['Roles', 'roles'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'roles/c', 'active'=>SEG2 == 'c'],
	],
];

if (isset($theRole['id']) && in_array(SEG2, ['r', 'u', 'd', 'users'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'roles/r/'.$theRole['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'group', 'title'=>'Users', 'link'=>'roles/users/'.$theRole['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'roles/u/'.$theRole['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'roles/d/'.$theRole['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}
