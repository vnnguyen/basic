<?

$this->params['breadcrumb'] = [
	['Payments', 'payments'],
];
$this->params['actions'] = [];

if (isset($thePayment['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'payments/r/'.$thePayment['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'payments/u/'.$thePayment['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'payments/d/'.$thePayment['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}
