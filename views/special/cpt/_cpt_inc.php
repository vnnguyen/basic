<?

if (!isset($payerList)) {
    $payerList = [
""=>"(Người trả $)",
"Amica Hà Nội"=>"Amica Hà Nội",
"An Hoà"=>"An Hoà",
"Anh Tấn"=>"Anh Tấn",
"Anh Thơ"=>"Anh Thơ",
"Anh Vinh"=>"Anh Vinh",
"Bunthol"=>"Bunthol",
"Chita"=>"Chita",
"Đức Minh"=>"Đức Minh",
"Farid"=>"Farid",
"Hướng dẫn"=>"Hướng dẫn",
"Hướng dẫn MB 1"=>"Hướng dẫn MB 1",
"Hướng dẫn MB 2"=>"Hướng dẫn MB 2",
"Hướng dẫn MB 3"=>"Hướng dẫn MB 3",
"Hướng dẫn MB 4"=>"Hướng dẫn MB 4",
"Hướng dẫn MT 1"=>"Hướng dẫn MT 1",
"Hướng dẫn MT 2"=>"Hướng dẫn MT 2",
"Hướng dẫn MN 1"=>"Hướng dẫn MN 1",
"Hướng dẫn MN 2"=>"Hướng dẫn MN 2",
"Indo-Siam"=>"Indo-Siam",
"iTravelLaos"=>"iTravelLaos",
"Thonglish"=>"Thonglish",
"Medsanh"=>"Medsanh",
"Feuang"=>"Feuang",
"Jason"=>"Jason",
"Khác"=>"Khác",
"Nanco"=>"Nanco",
"Siem Reap"=>"Siem Reap",
    ];
}

Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Check chi phí tour';

Yii::$app->params['page_breadcrumbs'] = [
    ['Dự án đặc biệt', 'special'],
    ['Chi phí tour', SEG3 == '' ? null : 'special/cpt'],
];