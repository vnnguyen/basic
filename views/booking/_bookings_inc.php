<?

Yii::$app->params['page_breadcrumbs'] = [
	['Bookings', '@web/bookings'],
];

if (isset($theBooking['id'])) {
	Yii::$app->params['page_breadcrumbs'][] = ['View', '@web/bookings/r/'.$theBooking['id']];
}
$this->params['actions'] = [
	//[
	//	['icon'=>'plus', 'label'=>'New', 'link'=>'bookings/c', 'active'=>SEG2 == 'c'],
	//],
];

if (isset($theBooking['id']) && in_array(SEG2, ['r', 'u', 'd', 'mp', 'mw', 'ml'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'bookings/r/'.$theBooking['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'bookings/u/'.$theBooking['id'], 'active'=>SEG2 == 'u'],
		['submenu'=>[
			['icon'=>'meh-o', 'label'=>'Mark as PENDING', 'link'=>'bookings/mp/'.$theBooking['id'], 'active'=>SEG2 == 'mp', 'visible'=>$theBooking['status'] != 'pending'],
			['icon'=>'smile-o', 'label'=>'Mark as WON', 'link'=>'bookings/mw/'.$theBooking['id'], 'active'=>SEG2 == 'mw', 'visible'=>$theBooking['status'] != 'won'],
			['icon'=>'frown-o', 'label'=>'Mark as LOST', 'link'=>'bookings/ml/'.$theBooking['id'], 'active'=>SEG2 == 'ml', 'visible'=>$theBooking['status'] != 'lost'],
			],
		],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'bookings/d/'.$theBooking['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}
