<?
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Bookings', 'bookings'],
	['VPC tours', 'bookings?type=vpctour'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New tour', 'active'=>SEG3 == 'c', 'link'=>'bookings/c?type=vpctour'],
	]
];
