<?
$cpTypeList = [
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

if (isset($theCpo['id'])) {
	$this->params['actions'] = [
		[
			['title'=>'View', 'icon'=>'eye', 'link'=>'cpo/r/'.$theCpo['id']],
			['title'=>'Edit', 'icon'=>'edit', 'link'=>'cpo/u/'.$theCpo['id']],
		],
		[
			['title'=>'Delete', 'icon'=>'trash-o', 'link'=>'cpo/d/'.$theCpo['id'], 'class'=>'btn-danger'],
		],
	];
}
$this->params['breadcrumb'] = [
	['Giá chi phí dịch vụ', '@web/cpo'],
];
$this->title = 'Giá các chi phí dịch vụ';