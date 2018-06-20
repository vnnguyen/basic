<?php

if (!isset($stars)) {
    $stars = '';
}

$venueMetaList = [
    'tel'=>'Tel',
    'fax'=>'Fax',
    'mobile'=>'Mobile',
    'email'=>'Email',
    'website'=>'Website',
    'address'=>'Address',
    'contact'=>'Contact person',
];

$venueTypes = [
    'hotel'=>'Khách sạn',
    'home'=>'Nhà dân',
    'cruise'=>'Tàu ngủ đêm',
    'train'=>'Tàu hoả',
    'restaurant'=>'Nhà hàng',
    'sightseeing'=>'Điểm tham quan',
    'office'=>'Văn phòng',
    'table'=>'Bảng giá',
    'other'=>'Khác',
];

$venueStatusList = [
    'on'=>'In use',
    'off'=>'Not in use',
    'draft'=>'Draft',
    'deleted'=>'Deleted',
];

$hotelFeatureList = array(
    'Facilities'=>array(
        '24hr room service',
        'airport transfer',
        'babysitting',
        'bar/pub',
        'bicycle rental',
        'business center',
        'casino',
        'coffee shop',
        'concierge',
        'disabled facilities',
        'elevator',
        'executive floor',
        'family room',
        'laundry service/dry cleaning',
        'meeting facilities',
        'nightclub',
        'pets allowed',
        'poolside bar',
        'restaurant',
        'room service',
        'safety deposit boxes',
        'salon',
        'shops',
        'smoking area',
        'tours',
        'Wi-Fi in public areas',
    ),
    'Sports and Recreation'=>array(
        'fitness center',
        'garden',
        'golf course (on site)',
        'indoor pool',
        'jacuzzi',
        'kids club',
        'massage',
        'outdoor pool',
        'pool (kids)',
        'private beach',
        'sauna',
        'spa',
        'squash courts',
        'steamroom',
        'tennis courts',
        'water sports (motorized)',
        'water sports (non-motorized)',
    ),
    'Internet in Rooms'=>array(
        'internet access – LAN',
        'internet access – LAN (charges apply)',
        'internet access – LAN (complimentary)',
        'internet access – wireless',
        'internet access – wireless (charges apply)',
        'internet access – wireless (complimentary)',
    ),
    'Car park'=>array(
        'car park',
        'valet parking',
    ),
);

$roomFeatureList = array(
    'non smoking rooms',
    'air conditioning',
    'bathrobes',
    'desk',
    'hair dryer',
    'internet access – LAN',
    'ironing facilities',
    'in room safe',
    'television LCD/plasma screen',
    'separate shower and tub',
    'mini bar',
    'satellite/cable TV',
    'DVD/CD player',
    'coffee/tea maker',
    'complimentary bottled water',
    'internet access – wireless (charges apply)',
    'internet access – LAN (charges apply)',
);

Yii::$app->params['page_breadcrumbs'][] = ['Venues', 'venues'];

if (isset($theVenue['id'])) {
    Yii::$app->params['page_breadcrumbs'][] = [$venueTypes[$theVenue['stype']] ?? $theVenue['stype'], 'venues?stype='.$theVenue['stype']];


    Yii::$app->params['page_breadcrumbs'][] = [$theVenue['name'].$stars, 'venues/r/'.$theVenue['id']];
}


Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'venues', 'active'=>SEG2 == ''],
        ['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'venues/c', 'active'=>SEG2 == 'c'],
    ],
];

if (isset($theVenue['id']) && in_array(SEG2, ['r', 'u', 'd', 'u-promo', 'uu'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>Yii::t('app', 'View'), 'link'=>'venues/r/'.$theVenue['id'], 'active'=>SEG2 == 'r'],
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'venues/u/'.$theVenue['id'], 'active'=>SEG2 == 'u'],
        ['submenu'=>[
            ['label'=>'View old page', 'link'=>'venues/old/'.$theVenue['id']],
            ['label'=>'Edit contact info', 'link'=>'venues/uold/'.$theVenue['id']],
            ['label'=>'Edit pricing/promo info', 'link'=>'venues/u-promo/'.$theVenue['id']],
            ['label'=>'Edit hotel attr (UU)', 'link'=>'venues/uu/'.$theVenue['id'], 'visible'=>$theVenue['stype'] == 'hotel'],
            ['label'=>'Edit hotel features', 'link'=>'venues/u-features/'.$theVenue['id'], 'visible'=>$theVenue['stype'] == 'hotel'],
            ['-'],
            ['icon'=>'trash-o', 'label'=>Yii::t('app', 'Delete'), 'link'=>'venues/d/'.$theVenue['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
            ],
        ],
    ];
}