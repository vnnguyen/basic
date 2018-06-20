<?

Yii::$app->params['page_breadcrumbs'] = [
    ['Invoices', SEG2 == '' ? null : 'invoices'],
    !in_array(SEG2, ['c']) ? null : ['Add new'],
    !in_array(SEG2, ['r', 'u', 'd']) ? null : ['View', SEG2 == 'r' ? null : 'invoices/r/'.$theInvoice['id']],
    in_array(SEG2, ['u']) ? ['Edit'] : null,
    in_array(SEG2, ['d']) ? ['Delete'] : null,
    in_array(SEG2, ['copy']) ? ['Copy'] : null,
];

Yii::$app->params['page_actions'] = [
    array_merge(
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'invoices'],
        ['icon'=>'plus', 'title'=>Yii::t('app', 'Add new'), 'link'=>'invoices/c'],
    ],
    !isset($theInvoice['id']) ? [] : [
        ['icon'=>'eye', 'title'=>Yii::t('app', 'View'), 'link'=>'invoices/r/'.$theInvoice['id']],
        ['icon'=>'copy', 'title'=>Yii::t('app', 'Copy as new'), 'link'=>'invoices/copy/'.$theInvoice['id']],
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'invoices/u/'.$theInvoice['id']],
        ['icon'=>'trash-o', 'class'=>'text-danger', 'title'=>Yii::t('app', 'Delete'), 'link'=>'invoices/d/'.$theInvoice['id']],
    ])
];

$statusList = [
	'active'=>'Active',
	'draft'=>'Draft',
	'canceled'=>'Canceled',
];

$currencyList = ['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND'];
$languageList = ['en'=>'English', 'fr'=>'Francais', 'vi'=>'Tiếng Việt'];
$methodList = ['transfer'=>'Bank transfer', 'card'=>'Credit Card', 'cash'=>'Cash'];
$typeList = ['invoice'=>'Standard invoice / Hoá đơn thường', 'credit'=>'Credit/Refund / Ghi nợ/Hoàn tiền'];

if (SEG2 == 'p') {
	unset($this->params['breadcrumb']);
}

$nhothuList = [
	''=>'Amica Travel Hanoi (không nhờ)',
	'Amica Saigon'=>'Amica Travel Saigon',
	'Amica Luang Prabang'=>'Amica Luang Prabang',
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