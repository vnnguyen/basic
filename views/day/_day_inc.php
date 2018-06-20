<?php

$ctrMarkets = array(
	1=>'Amica Internal',
	2=>'French - Inbound',
	3=>'English - Inbound',
	4=>'Vietnamese',
);

$dayTypes = array(
	'sample'=>'Làm mẫu',
	'ctr'=>'Chương trình',
	'tour'=>'Ngày tour',
);

$ctrTourTypes = array(
	'private'=>'Tour riêng',
	'vpc'=>'Tour VPC',
);

$ctrStatuses = array(
	'on'=>'Được dùng',
	'off'=>'Không dùng',
	'draft'=>'Nháp',
	'deleted'=>'Bị xoá',
);

$dayMealList = [
	'---'=>'---',
	'B--'=>'B--',
	'-L-'=>'-L-',
	'--D'=>'--D',
	'BL-'=>'BL-',
	'B-D'=>'B-D',
	'-LD'=>'-LD',
	'BLD'=>'BLD',
];

$this->params['breadcrumb'] = [
	['Tour days', 'days'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New (B2C)', 'link'=>'nm/c'],
		['icon'=>'plus', 'label'=>'New (B2B)', 'link'=>'nm/c?b2b=yes'],
	]
];