<?
$this->title = 'Điểm lái xe tour';

$this->params['breadcrumb'] = [
	['Tools', '@web/tools'],
	['Điểm lái xe', '@web/tools/diemlx'],
];

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Thêm', 'link'=>'tools/diemlx?action=c', 'active'=>Yii::$app->request->get('action') == 'c'],
	],
];

if (isset($thePayment['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'payments/r/'.$thePayment['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'payments/u/'.$thePayment['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'payments/d/'.$thePayment['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}

