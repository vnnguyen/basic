<?

$this->params['breadcrumb'] = [
	['Tour feedbacks', '@web/feedbacks'],
];

if (isset($theFeedback['id'])) {
	$this->params['breadcrumb'][] = ['View', '@web/feedbacks/r/'.$theFeedback['id']];
}
$this->params['actions'] = [
	//[
	//	['icon'=>'plus', 'label'=>'New', 'link'=>'bookings/c', 'active'=>SEG2 == 'c'],
	//],
];

if (isset($theFeedback['id']) && in_array(SEG2, ['r', 'u', 'd', 'mp', 'mw', 'ml'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'bookings/r/'.$theFeedback['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'bookings/u/'.$theFeedback['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'bookings/d/'.$theFeedback['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}
