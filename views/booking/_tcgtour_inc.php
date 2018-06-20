<?
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Bookings', 'bookings'],
	['TCG tours', 'bookings?type=tcgtour'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New tour', 'active'=>SEG3 == 'c', 'link'=>'bookings/c?type=tcgtour'],
	]
];
