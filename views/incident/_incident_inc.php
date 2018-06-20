<?

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('incident', 'Tour incidents'), SEG2 == '' ? null : 'incidents'],
    SEG2 == 'c' ? [Yii::t('app', 'New')] : null,
    in_array(SEG2, ['r', 'u', 'd']) ? [Yii::t('app', 'View'), SEG2 == 'r' ? null : 'incidents/r/'.$theIncident['id']] : null,
    SEG2 == 'u' ? [Yii::t('app', 'Edit'), SEG2 == 'u' ? null : 'incidents/u/'.$theIncident['id']] : null,
    SEG2 == 'd' ? [Yii::t('app', 'Delete'), SEG2 == 'd' ? null : 'incidents/d/'.$theIncident['id']] : null,
];

$this->params['actions'] = [
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'incidents', 'active'=>SEG2 == ''],
        ['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'incidents/c', 'active'=>SEG2 == 'c'],
    ],
];

$incidentTypeList = [
    6=>Yii::t('incident', 'Visa/Travel documents'),
    7=>Yii::t('incident', 'Payment'),
    8=>Yii::t('incident', 'Transportation'),
    9=>Yii::t('incident', 'Air travel'),
    10=>Yii::t('incident', 'Accommodation'),
    11=>Yii::t('incident', 'Meal/Restaurant'),
    12=>Yii::t('incident', 'Guide'),
    3=>Yii::t('incident', 'Security'),
    2=>Yii::t('incident', 'Health'),
    1=>Yii::t('incident', 'Service'),
    4=>Yii::t('incident', 'Internal'),
    5=>Yii::t('incident', 'Other'),
];

$destList = [
    0=>'Không cố định',
    1=>'Hà Nội',
];

$severityList = [
    1 => '1 - '.Yii::t('incident', 'Not a problem'),
    2 => '2 - '.Yii::t('incident', 'Slight problem or severity'),
    3 => '3 - '.Yii::t('incident', 'Somewhat severe'),
    4 => '4 - '.Yii::t('incident', 'Severe'),
    5 => '5 - '.Yii::t('incident', 'Fatal or very severe'),
];

$incidentStatusList = [
    1 => Yii::t('incident', 'New'),
    2 => Yii::t('incident', 'Confirmed'),
    3 => Yii::t('incident', 'Under surveillance'),
    4 => Yii::t('incident', 'Solved'),
    5 => Yii::t('incident', 'Not solved'),
    6 => Yii::t('incident', 'Not a problem'),
];

$incidentActionList = [
    1 => Yii::t('incident', 'Apology'),
    2 => Yii::t('incident', 'Gift and/or complementary service'),
    3 => Yii::t('incident', 'Compensation'),
    6 => Yii::t('incident', 'Itinerary change'),
    4 => Yii::t('incident', 'Legal action'),
    5 => Yii::t('incident', 'Other'),
];

$incidentInvolvementList = [
    1 => Yii::t('incident', 'Customer'),
    2 => Yii::t('incident', 'Service / Supplier'),
    3 => Yii::t('incident', 'Tour guide / Driver'),
    4 => Yii::t('incident', 'Amica / Our staff'),
    5 => Yii::t('incident', 'Other'),
];