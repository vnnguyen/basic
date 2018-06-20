<?

$this->params['breadcrumb'] = [
	['Groups', 'groups'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New', 'link'=>'groups/c', 'active'=>SEG2 == 'c'],
	],
];

if (isset($theGroup['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'groups/r/'.$theGroup['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'groups/u/'.$theGroup['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'groups/d/'.$theGroup['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}

$groupTypeList = [
	'user'=>'User group',
	'permission'=>'Permission group',
	'company'=>'Company group',
	'tag'=>'Tag group',
	'cat'=>'Category group',
	''=>'Unknown group',
];