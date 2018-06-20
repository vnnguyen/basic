<?
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Products', 'products'],
	['VPC tours', 'products?type=vpctour'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New tour', 'active'=>SEG3 == 'c', 'link'=>'products/c?type=vpctour'],
	]
];
