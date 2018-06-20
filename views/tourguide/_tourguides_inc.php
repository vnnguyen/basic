<?
/*
id = user id, not profile id
tourguides
tourguides/c Search for people with similar name
tourguides/r/id Tour guide profile
tourguides/calendar/id Tour guide profile
tourguides/u/id Edit profile or add profile for user

*/

$this->params['breadcrumb'] = [
	['Tour guides', 'tourguides'],
];

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Thêm', 'link'=>'tourguides/c', 'active'=>SEG2=='c'],
	]	
];


if (in_array(SEG2, ['r', 'u', 'd']) && isset($theGuide['id'])) {
	$this->params['actions'][] = [
		['icon'=>'user', 'label'=>'Xem user', 'link'=>'users/r/'.$theGuide['id']],
	];
	$this->params['actions'][] = [
		['icon'=>'user', 'title'=>'View', 'link'=>'tourguides/r/'.$theGuide['id'], 'active'=>SEG2=='r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'tourguides/u/'.$theGuide['id'], 'active'=>SEG2=='u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'tourguides/d/'.$theGuide['id'], 'active'=>SEG2=='d', 'class'=>'btn-danger'],
	];
}

