<?
Yii::$app->params['acc1/eventful/cats'] =[
	['id'=>1, 'name'=>'Tin công ty'],
	['id'=>2, 'name'=>'Tin công đoàn'],
	['id'=>3, 'name'=>'Tin nhân sự'],
	['id'=>4, 'name'=>'Tin khác'],
];


$this->params['icon'] = 'fire';
$this->params['breadcrumb'] = [
	['Sự kiện', 'eventful'],
	['Tất cả sự kiện', 'eventful/events'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Thêm mới', 'link'=>'eventful/events/c', 'active'=>SEG3 == 'c',],
	],
];

if (isset($theEvent['id'])) {
	$this->params['actions'][] = [
		['icon'=>'file-text-o', 'title'=>'View', 'link'=>'eventful/events/r/'.$theEvent['id'], 'active'=>SEG3 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'eventful/events/u/'.$theEvent['id'], 'active'=>SEG3 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'eventful/events/d/'.$theEvent['id'], 'active'=>SEG3 == 'd', 'class'=>'btn-danger'],
	];
}