<?php

$eventCalendars = array(
	'all'=>'Amica events',
	'my-tasks'=>'My tasks',
	'tours'=>'Tour activities',
	'customer-care'=>'Customer care tasks',
	'reception-hanoi'=>'Customer reception at Hanoi office',
	'birthdays'=>'Birthdays of Amica staff',
	'birthdays-guides'=>'Birthdays of tour guides',
	'birthdays-customers'=>'Birthdays of customers',
	'public-holidays'=>'Public holidays',
);

$eventTypeList = array(
	'nghiphep'=>'Nghỉ phép',
	'congtac'=>'Công tác',
	'hop'=>'Họp',
	'lienhoan'=>'Liên hoan',
	'nghile'=>'Nghỉ lễ',
);

$eventTypeColors = array(
	'nghiphep'=>'#c0c',
	'congtac'=>'#0c0',
	'hop'=>'#c00',
	'lienhoan'=>'#fc0',
	'nghile'=>'#03c',
);

$eventStatusList = array(
	'on'=>'Đã xác nhận',
	'off'=>'Đã bị huỷ',
	'draft'=>'Dự định',
	'deleted'=>'Đã bị xoá',
);


$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New', 'link'=>'events/c', 'active'=>SEG2 == ''],
	],
];

if (isset($theEvent['id'])) {
	$this->params['actions'][] = [
		['title'=>'View', 'icon'=>'eye', 'link'=>'events/r/'.$theEvent['id']],
		['title'=>'Edit', 'icon'=>'edit', 'link'=>'events/u/'.$theEvent['id']],
	];
	$this->params['actions'][] = [
		['title'=>'Delete', 'icon'=>'trash-o', 'link'=>'events/d/'.$theEvent['id'], 'class'=>'btn-danger'],
	];
}
$this->params['breadcrumb'] = [
	['Events', '@web/events'],
];
$this->title = 'Events';