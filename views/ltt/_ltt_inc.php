<?php

Yii::$app->params['page_icon'] = 'dollar';

Yii::$app->params['page_breadcrumbs'][] = ['Chi phí tour', 'cpt'];
Yii::$app->params['page_breadcrumbs'][] = ['Thanh toán', 'ketoan/ltt'];


$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Thêm', 'link'=>'ketoan/ltt/c', 'active'=>SEG3 == 'c'],
	],
];

if (isset($theLtt['id'])) {
	Yii::$app->params['page_breadcrumbs'][] = ['Xem', 'ketoan/ltt/r/'.$theLtt['id']];

	$this->params['actions'][] = 
	[
		['icon'=>'dollar', 'title'=>'Các muc TT', 'link'=>'ketoan/ltt/mtt/'.$theLtt['id'], 'active'=>SEG3 == 'mtt'],
		['icon'=>'edit', 'title'=>'Sửa', 'link'=>'ketoan/ltt/u/'.$theLtt['id'], 'active'=>SEG3 == 'u'],
	];
	$this->params['actions'][] = 
	[
		['icon'=>'trash-o', 'title'=>'Xoá', 'link'=>'ketoan/ltt/d/'.$theLtt['id'], 'active'=>SEG3 == 'd', 'class'=>'btn btn-danger']
	];
}

$statusList = [
	''=>'-',
	0=>'Dự định',
	1=>'Đề nghị TT',
	2=>'KTT duyệt Đề nghị TT',
	3=>'GĐ duyệt TT',
	4=>'Đã thanh toán',
	5=>'Đã thanh toán, KTT xác nhận',
];
$methodList = [
	'cash'=>'Tiền mặt',
	'transfer'=>'Chuyển khoản',
	'card'=>'Thẻ tín dụng',
	'other'=>'Khác',
];
$currencyList = [
	'VND'=>'VND',
	'USD'=>'USD',
	'EUR'=>'EUR',
];
$ketoan = [
	'1'=>'Ngọc Huân',
	'4065'=>'Anh Tuấn',
	'28431'=>'Tú Phương',
	'11'=>'Thu Hiền',
	'17'=>'Đức Hạnh',
	'16'=>'Tr. Thị Lan',
	'20787'=>'Thanh Bình',
	'29739'=>'Thanh Huyền',
	'30085'=>'Đ. Thị Ngọc',
];
$check = [
	'c1'=>'CHECK-1',
	'c2'=>'CHECK-2',
	'c3'=>'TH/TOAN',
	'c4'=>'DUYET',

	'c5'=>'DC',
	'c6'=>'DC-OK',
	'c7'=>'TT',
	'c8'=>'TT-OK',
	'c9'=>'KTT',
];