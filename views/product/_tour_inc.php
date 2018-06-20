<?
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Products', 'products'],
	['Private tours', 'products/tour'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New tour', 'active'=>SEG3 == 'c', 'link'=>'products/c?type=tour'],
	]
];
