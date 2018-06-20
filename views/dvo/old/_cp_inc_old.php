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

if (isset($theCp['id'])) {
	$this->params['actions'] = [
		[
			['title'=>'View', 'icon'=>'eye', 'link'=>'cp/r/'.$theCp['id']],
			['title'=>'Edit', 'icon'=>'edit', 'link'=>'cp/u/'.$theCp['id']],
		],
		[
			['title'=>'Delete', 'icon'=>'trash-o', 'link'=>'cp/d/'.$theCp['id'], 'class'=>'btn-danger'],
		],
	];
}
$this->params['breadcrumb'] = [
	['Chi phí dịch vụ', 'cp'],
];
$this->title = 'Các chi phí dịch vụ';