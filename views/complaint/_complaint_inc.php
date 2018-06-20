<?

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('complaint', 'Tour complaints'), SEG2 == '' ? null : 'complaints'],
    SEG2 == 'c' ? [Yii::t('app', 'New')] : null,
    in_array(SEG2, ['r', 'u', 'd']) ? [Yii::t('app', 'View'), SEG2 == 'r' ? null : 'complaints/r/'.$theComplaint['id']] : null,
    SEG2 == 'u' ? [Yii::t('app', 'Edit'), SEG2 == 'u' ? null : 'complaints/u/'.$theComplaint['id']] : null,
    SEG2 == 'd' ? [Yii::t('app', 'Delete'), SEG2 == 'd' ? null : 'complaints/d/'.$theComplaint['id']] : null,
];

$this->params['actions'] = [
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'complaints', 'active'=>SEG2 == ''],
        ['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'complaints/c', 'active'=>SEG2 == 'c'],
    ],
];

$complaintTypeList = [
    6=>Yii::t('complaint', 'Visa/Travel documents'),
    7=>Yii::t('complaint', 'Payment'),
    8=>Yii::t('complaint', 'Transportation'),
    9=>Yii::t('complaint', 'Air travel'),
    10=>Yii::t('complaint', 'Accommodation'),
    11=>Yii::t('complaint', 'Meal/Restaurant'),
    12=>Yii::t('complaint', 'Guide'),
    3=>Yii::t('complaint', 'Security'),
    2=>Yii::t('complaint', 'Health'),
    1=>Yii::t('complaint', 'Service'),
    4=>Yii::t('complaint', 'Internal'),
    5=>Yii::t('complaint', 'Other'),
];
$severityList = [
    1 => '1 - '.Yii::t('incident', 'Not a problem'),
    2 => '2 - '.Yii::t('incident', 'Slight problem or severity'),
    3 => '3 - '.Yii::t('incident', 'Somewhat severe'),
    4 => '4 - '.Yii::t('incident', 'Severe'),
    5 => '5 - '.Yii::t('incident', 'Fatal or very severe'),
];
$destList = [
    0=>'Không cố định',
    1=>'Hà Nội',
];
$complaintStatusList = [
    1 => Yii::t('complaint', 'New'),
    2 => Yii::t('complaint', 'Confirmed'),
    3 => Yii::t('complaint', 'Under surveillance'),
    4 => Yii::t('complaint', 'Solved'),
    5 => Yii::t('complaint', 'Not solved'),
    6 => Yii::t('complaint', 'Not a problem'),
];

$complaintActionList = [
    1 => Yii::t('complaint', 'Apology'),
    2 => Yii::t('complaint', 'Gift and/or complementary service'),
    3 => Yii::t('complaint', 'Compensation'),
    6 => Yii::t('complaint', 'Itinerary change'),
    4 => Yii::t('complaint', 'Legal action'),
    5 => Yii::t('complaint', 'Other'),
];

$complaintInvolvementList = [
    1 => Yii::t('complaint', 'Customer'),
    2 => Yii::t('complaint', 'Service / Supplier'),
    3 => Yii::t('complaint', 'Tour guide / Driver'),
    4 => Yii::t('complaint', 'Amica / Our staff'),
    5 => Yii::t('complaint', 'Other'),
];