<?

if (!function_exists('trim00')) {
    function trim00($str)
    {
        return rtrim(rtrim($str, '0'), '.');
    }
}

Yii::$app->params['page_breadcrumbs'] = [
    ['CPDV', '#'],
    ['Dịch vụ', 'dv'],
];

$this->params['actions'] = [
    [
        ['icon'=>'book', 'title'=>'Doc', 'link'=>'cp/doc', 'active'=>SEG2 == 'doc'],
    ],
    [
        ['icon'=>'plus', 'label'=>'New', 'link'=>'dv/c', 'active'=>SEG2 == 'c'],
    ],
];

$dvTypeList = [
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

$dvObjectTypeList = [
    0=>['id'=>0, 'icon'=>'minus', 'name'=>'Unknown',],
    1=>['id'=>1, 'icon'=>'plane', 'name'=>'Flight',],
    2=>['id'=>2, 'icon'=>'car', 'name'=>'Car',],
    3=>['id'=>3, 'icon'=>'ticket', 'name'=>'Entrance ticket',],
    4=>['id'=>4, 'icon'=>'hotel', 'name'=>'Hotel room',],
    5=>['id'=>5, 'icon'=>'cutlery', 'name'=>'Meal',],
    6=>['id'=>6, 'icon'=>'user', 'name'=>'Tour guide',],
    7=>['id'=>7, 'icon'=>'flag', 'name'=>'Package tour',],
    8=>['id'=>8, 'icon'=>'file-text-o', 'name'=>'Documents',],
    9=>['id'=>9, 'icon'=>'train', 'name'=>'Train',],
];

$dvABCTypeList = [
    'a'=>'? Accomodation / Ngủ nghỉ',
    'b'=>'Breakfast / Bữa sáng',
    'c'=>'Car / Ô tô',
    'd'=>'Dinner / Bữa tối',
    'e'=>'',
    'f'=>'Flight / Chuyến bay',
    'g'=>'Guide / HDV, nhân sự',
    'h'=>'',
    'i'=>'',
    'j'=>'',
    'k'=>'',
    'l'=>'Lunch / Bữa trưa',
    'm'=>'',
    'n'=>'',
    'o'=>'',
    'p'=>'Package / Tour trọn gói',
    'q'=>'',
    'r'=>'',
    's'=>'Stay / Ngủ đêm',
    't'=>'Transport / Vận chuyển',
    'u'=>'',
    'v'=>'Visit / Xem thăm',
    'w'=>'',
    'x'=>'',
    'y'=>'',
    'z'=>'',
];