<?php

$this->params['icon'] = 'money';

$this->params['actions'][] = [['icon'=>'plus', 'label'=>'New rate', 'link'=>'xrates/c', 'active'=>SEG2 == 'c']];

if (isset($model) && $model->id != 0) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'xrates/r/'.$model['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'xrates/u/'.$model['id'], 'active'=>SEG2 == 'u'],
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'xrates/d/'.$model['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}

