<?

$this->params['breadcrumb'] = [
	['Members', '@web/members'],
];

$this->params['actions'][] = [
	['icon'=>'plus', 'title'=>'+New member', 'link'=>'members/c', 'active'=>SEG2=='c'],
];
if (isset($theUser['id'])) {
	$this->params['actions'][] = [
		['icon'=>'user', 'title'=>'View', 'link'=>'members/r/'.$theUser['id'], 'active'=>SEG2=='r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'members/u/'.$theUser['id'], 'active'=>SEG2=='u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'members/d/'.$theUser['id'], 'active'=>SEG2=='d', 'class'=>'btn-danger'],
	];
}