<?

$this->params['breadcrumb'] = [
	['Invoices', '@web/invoices'],
];

$this->params['actions'] = [];

if (isset($theInvoice['id'])) {
	$this->params['breadcrumb'][] = ['View', '@web/invoices/r/'.$theInvoice['id']];

	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'invoices/r/'.$theInvoice['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'print', 'title'=>'Print', 'link'=>'invoices/p/'.$theInvoice['id'], 'active'=>SEG2 == 'p'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'invoices/u/'.$theInvoice['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'invoices/d/'.$theInvoice['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}

$statusList = [
	'active'=>'Active',
	'draft'=>'Draft',
	'canceled'=>'Canceled',
];

$currencyList = ['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND'];
$languageList = ['en'=>'English', 'fr'=>'Francais', 'vi'=>'Tiếng Việt'];
$methodList = ['transfer'=>'Bank transfer', 'card'=>'Credit Card', 'cash'=>'Cash'];
$typeList = ['invoice'=>'Standard invoice / Hoá đơn thường', 'credit'=>'Credit memo / Trả lại tiền'];

if (SEG2 == 'p') {
	unset($this->params['breadcrumb']);
}

$nhothuList = [
	''=>'Amica Travel Hanoi (không nhờ)',
	'Amica Saigon'=>'Amica Travel Saigon',
	'An Hoà'=>'An Hoà (Huế)',
	'Asia Adventure'=>'Asia Adventure (Nha Trang)',
	'Bunthol'=>'Bunthol (Siem Reap)',
	'Eric - Indosiam'=>'Eric - Indosiam (Bangkok)',
	'Feuang'=>'Feuang (Pakse)',
	'Hoa Bearez'=>'Hoa Bearez (France)',
	'Medsanh'=>'Medsanh (Vientiane)',
	'Miền Tây'=>'Miền Tây (Saigon)',
	'Tam Coc Garden'=>'Tam Coc Garden (Ninh Binh)',
	'Thonglish'=>'Thonglish (Luang Prabang)',
	'VEI Travel'=>'VEI Travel (Miền Trung)',
];