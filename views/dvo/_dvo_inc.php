<?

Yii::$app->params['page_breadcrumbs'] = [
    ['CPDV', 'cp'],
];

$this->params['actions'] = [
    [
        ['icon'=>'book', 'title'=>'Doc', 'link'=>'cp/doc', 'active'=>SEG2 == 'doc'],
    ],
    [
        ['icon'=>'list', 'title'=>'List', 'link'=>'cp', 'active'=>SEG2 == ''],
    ],
    [
        ['icon'=>'plus', 'title'=>'New', 'link'=>'cp/c', 'active'=>SEG2 == 'c'],
    ],
];

$cpTypeList = [
    1=>'Ăn uống',
    2=>'Ngủ nghỉ',
    3=>'Đi lại, vận chuyển',
    4=>'Tham quan, giải trí',
    5=>'Sức khoẻ, y tế',
    6=>'Hội nghị, học tập',
    7=>'Nhân lực',
    8=>'Giấy tờ, thủ tục',
    9=>'Khác',
];

$cpTypeIconList = [
    1=>'cutlery',
    2=>'bed',
    3=>'car',
    4=>'flag',
    5=>'plus',
    6=>'bullhorn',
    7=>'user',
    8=>'pencil',
    9=>'ticket',
];

$cpStatusList = [
    'on'=>'OK dữ liệu',
    'draft'=>'Chưa OK dữ liệu',
    'off'=>'Không dùng',
    'deleted'=>'Bị xoá',
];

$destList = [
    0=>'Không cố định',
    1=>'Hà Nội',
];