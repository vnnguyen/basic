<?
$this->params['breadcrumb'] = [
	['Notes', 'notes'],
];

$this->params['actions'] = [];
if (isset($theNote['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'notes/r/'.$theNote['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'notes/u/'.$theNote['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'notes/d/'.$theNote['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}
