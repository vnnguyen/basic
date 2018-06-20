<?php

$this->params['icon'] = 'desktop';

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Import new inquires', 'link'=>'inquiries/c', 'active'=>SEG2 == 'c'],
	],
];

if (in_array(Yii::$app->user->id, [1, 4432]) && isset($theInquiry['id'])) {
	$this->params['actions'][] = 
	[
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'inquiries/u/'.$theInquiry['id'], 'active'=>SEG2 == 'u']
	];
	$this->params['actions'][] = 
	[
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'inquiries/d/'.$theInquiry['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger']
	];
}
