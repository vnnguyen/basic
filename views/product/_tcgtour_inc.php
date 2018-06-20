<?
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Products', 'products'],
	['TCG tours', 'products/tcgtour'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New tour', 'active'=>SEG3 == 'c', 'link'=>'products/c?type=tcgtour'],
	]
];
