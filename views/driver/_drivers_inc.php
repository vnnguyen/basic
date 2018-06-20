<?

$this->params['breadcrumb'] = [
	['Drivers', '@web/drivers'],
];

$this->params['actions'][] = [
	['icon'=>'plus', 'title'=>'+New driver', 'link'=>'drivers/c', 'active'=>SEG2=='c'],
];
if (in_array(SEG2, ['r', 'u', 'd']) && isset($theUser['id'])) {
	$this->params['actions'][] = [
		['icon'=>'user', 'title'=>'View', 'link'=>'drivers/r/'.$theUser['id'], 'active'=>SEG2=='r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'drivers/u/'.$theUser['id'], 'active'=>SEG2=='u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'drivers/d/'.$theUser['id'], 'active'=>SEG2=='d', 'class'=>'btn-danger'],
	];
}
