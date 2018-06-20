<?
$dvTypeList = [
	'1'=>'Ngủ nghỉ',
	'2'=>'Ăn uống',
	'3'=>'Tham quan, mua sắm',
	'4'=>'Họp, hội thảo, gặp gỡ',
	'5'=>'Sức khoẻ, làm đẹp',
	'6'=>'Vận chuyển, di chuyển',
	'7'=>'Nhân sự, hướng dẫn',
	'8'=>'Thủ tục, giấy tờ',
	'9'=>'Khác',
];

if (isset($theDvc['id'])) {
	$this->params['actions'] = [
		[
			['title'=>'View', 'icon'=>'eye', 'link'=>'dvc/r/'.$theDvc['id']],
			['title'=>'Edit', 'icon'=>'edit', 'link'=>'dvc/u/'.$theDvc['id']],
		],
		[
			['title'=>'Delete', 'icon'=>'trash-o', 'link'=>'dvc/d/'.$theDvc['id'], 'class'=>'text-danger'],
		],
	];
}
Yii::$app->params['page_breadcrumbs'] = [
	['Dịch vụ', '/dv'],
	['Hợp đồng', '/dvc'],
];
Yii::$app->params['page_title'] = 'Các chi phí dịch vụ';