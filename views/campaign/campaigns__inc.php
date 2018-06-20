<?php

$campaignStatusList = [
	'on'=>'On',
	'off'=>'Off',
	'draft'=>'Draft',
	'deleted'=>'Deleted',
];

$this->params['active'] = 'sales';
$this->params['active2'] = 'campaigns';

if (isset($model) && $model->id != 0) {
	$this->params['buttons'] = [
		['icon'=>'eye', 'label'=>'View', 'link'=>'campaigns/r/'.$model['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'label'=>'Edit', 'link'=>'campaigns/u/'.$model['id'], 'active'=>SEG2 == 'u'],
		['icon'=>'trash-o', 'label'=>'Delete', 'link'=>'campaigns/d/'.$model['id'], 'active'=>SEG2 == 'd'],
	];
}

if (SEG2 != 'c')
	$this->params['buttons'][] = ['icon'=>'plus', 'label'=>'New campaign', 'link'=>'campaigns/c', 'active'=>SEG2 == ''];
