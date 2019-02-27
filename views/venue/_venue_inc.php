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
    Yii::$app->params['page_breadcrumbs'][] = [$venueTypes[$theVenue['stype']] ?? $theVenue['stype'], 'venues'];
    Yii::$app->params['page_breadcrumbs'][] = isset($theVenue['destination']['name_en']) ? [$theVenue['destination']['name_en'], 'venues?dest='.$theVenue['destination']['id']] : null;
    Yii::$app->params['page_breadcrumbs'][] = [$theVenue['name'].$stars, 'venues/r/'.$theVenue['id']];
}


Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'venues', 'active'=>SEG2 == ''],
        ['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'venues/c', 'active'=>SEG2 == 'c'],
    ],
];

if (isset($theVenue['id'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>Yii::t('app', 'View'), 'link'=>'venues/r/'.$theVenue['id'], 'active'=>SEG2 == 'r'],
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'venues/u/'.$theVenue['id'], 'active'=>SEG2 == 'u'],
        ['submenu'=>[
            ['label'=>'Edit contact info', 'link'=>'venues/uold/'.$theVenue['id']],
            ['label'=>'Edit pricing/promo info', 'link'=>'venues/u-promo/'.$theVenue['id']],
            ['label'=>'Edit new price table', 'link'=>'x/venues/'.$theVenue['id'].'/u-pricetable'],
            ['label'=>'Edit closure dates', 'link'=>'venues/'.$theVenue['id'].'/events'],
            ['label'=>'+New contract', 'link'=>'dvc/c?venue_id='.$theVenue['id'], 'hidden'=>!in_array(USER_ID, [1, 8, 9198, 34718, 44378, 29739])],
            ['label'=>'+New promo', 'link'=>'dvc/c?stype=promo&venue_id='.$theVenue['id'], 'hidden'=>!in_array(USER_ID, [1, 8, 9198, 34718, 44378, 29739])],
            // ['label'=>'Edit hotel attr (UU)', 'link'=>'venues/uu/'.$theVenue['id'], 'visible'=>$theVenue['stype'] == 'hotel'],
            // ['label'=>'Edit hotel features', 'link'=>'venues/u-features/'.$theVenue['id'], 'visible'=>$theVenue['stype'] == 'hotel'],
            // ['-'],
            // ['icon'=>'trash-o', 'label'=>Yii::t('app', 'Delete'), 'link'=>'venues/d/'.$theVenue['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
            ],
        ],
    ];
}

// 2018-05-28
$venueClassiList = [
    '1_bud'=>Yii::t('xx', 'Budget'),
    '1_sta'=>Yii::t('x', 'Standard'),
    '1_sup'=>Yii::t('x', 'Superior'),
    '1_del'=>Yii::t('x', 'Deluxe'),
    '1_lux'=>Yii::t('x', 'Luxury'),
];

$venueArchiList = [
    '2_01'=>Yii::t('x', 'Small building'),
    '2_02'=>Yii::t('x', 'Big building'),
    '2_03'=>Yii::t('x', 'Colonial style'),
    '2_04'=>Yii::t('x', 'Traditional house'),
    '2_05'=>Yii::t('x', 'Bungalows'),
    '2_06'=>Yii::t('x', 'Atypical'),
];

$venueTypeList = [
    '3_01'=>Yii::t('x', 'Hotel'),
    '3_02'=>Yii::t('x', 'Apartment'),
    '3_03'=>Yii::t('x', 'Villa'),
    '3_04'=>Yii::t('x', 'Guesthouse'),
    '3_05'=>Yii::t('x', 'Farm stay'),
    '3_06'=>Yii::t('x', 'Resort'),
    '3_07'=>Yii::t('x', 'Campsite'),
    '3_08'=>Yii::t('x', 'Hostel'),
    '3_09'=>Yii::t('x', 'Homestay'),
    '3_10'=>Yii::t('x', 'Motel'),
    '3_11'=>Yii::t('x', 'Lodge'),
];

$venueStyleList = [
    '4_01'=>Yii::t('x', 'Charming'),
    '4_02'=>Yii::t('x', 'Boutique'),
    '4_03'=>Yii::t('x', 'Character'),
    '4_04'=>Yii::t('x', 'International'),
];

$venueFaciList = [
    '5_01'=>Yii::t('x', 'Lift'),
    '5_02'=>Yii::t('x', 'Indoor swimming pool'),
    '5_03'=>Yii::t('x', 'Outdoor swimming pool'),
    '5_04'=>Yii::t('x', 'Kid’s pool'),
    '5_05'=>Yii::t('x', 'Garden'),
    '5_06'=>Yii::t('x', 'Private beach'),
    '5_07'=>Yii::t('x', 'Spa'),
    // '5_08'=>Yii::t('x', 'Massage sauna'),
    '5_09'=>Yii::t('x', 'Bicycle or motorbike'),
    '5_10'=>Yii::t('x', 'Restaurant to recommend'),
    '5_11'=>Yii::t('x', 'Breakfast international buffet'),
    '5_12'=>Yii::t('x', 'Gym/ Fitness centre'),
    // '5_13'=>Yii::t('x', 'Conference room'),
    '5_14'=>Yii::t('x', 'Meeting/ banquet facilities'),
    '5_15'=>Yii::t('x', 'Disabled facilities'),
    // '5_16'=>Yii::t('x', 'Eco-responsible approach'),
    '5_17'=>Yii::t('x', 'Room service'),
    '5_18'=>Yii::t('x', 'Free wifi outside'),
    '5_19'=>Yii::t('x', 'Airport shuttle'),
    '5_20'=>Yii::t('x', 'Laundry service'),
    '5_21'=>Yii::t('x', 'Terrace'),
    '5_22'=>Yii::t('x', 'Balcony'),
    '5_23'=>Yii::t('x', 'Pet allowed'),
    '5_24'=>Yii::t('x', 'Non-smoking room'),
    '5_25'=>Yii::t('x', 'Family rooms'),
    '5_26'=>Yii::t('x', 'Baby cot'),
    '5_27'=>Yii::t('x', 'Air conditioning'),
    '5_28'=>Yii::t('x', 'Bath tub'),
    '5_30'=>Yii::t('x', 'Internet computers'),
    '5_31'=>Yii::t('x', 'Coffee and tea facilities'),
    '5_32'=>Yii::t('x', 'Electric kettle'),
    '5_33'=>Yii::t('x', 'Iron'),
    '5_34'=>Yii::t('x', 'Hair dresser'),
    '5_35'=>Yii::t('x', 'Electric fan'),
    '5_36'=>Yii::t('x', 'Refrigerator'),
    '5_37'=>Yii::t('x', 'Massage'),
    '5_38'=>Yii::t('x', 'Sauna'),
    '5_40'=>Yii::t('x', 'French'),
    '5_41'=>Yii::t('x', 'English'),
    '5_42'=>Yii::t('x', 'Telephone'),
    '5_43'=>Yii::t('x', 'TV'),
    // '5_44'=>Yii::t('x', 'Airport drop off'),
    // '5_45'=>Yii::t('x', 'Airport pick up'),
    // '5_46'=>Yii::t('x', 'Children’s playground'),
    '5_47'=>Yii::t('x', 'BBQ facilities'),
    '5_50'=>Yii::t('x', 'Babysitter upon request'),

    //bo sung 28/6
    '5_48'=>Yii::t('x', 'German'),
    '5_51'=>Yii::t('x', 'Restaurant'),
    '5_52'=>Yii::t('x', 'Business Centre'),
    '5_53'=>Yii::t('x', '24h reception'),
    '5_54'=>Yii::t('x', 'Parking'),
    '5_55'=>Yii::t('x', 'Car hire'),
    '5_56'=>Yii::t('x', 'Library'),
    // '5_57'=>Yii::t('x', 'Transportation'),
    '5_58'=>Yii::t('x', 'Beauty salon'),
    '5_59'=>Yii::t('x', 'Deck chair'),
    '5_60'=>Yii::t('x', 'Desk'),
    '5_61'=>Yii::t('x', 'Electronic safe'),
    // '5_62'=>Yii::t('x', 'Fitness/spa locker rooms'),
    '5_63'=>Yii::t('x', 'Yoga classes'),
    '5_64'=>Yii::t('x', 'kid\'s menu'),
    '5_65'=>Yii::t('x', 'Wheelchair access'),
    '5_66'=>Yii::t('x', 'Mid-height light switches and power outlets'),
    '5_67'=>Yii::t('x', 'Raised toilet'),
    '5_68'=>Yii::t('x', 'Meeting room'),
    '5_69'=>Yii::t('x', 'Free wifi in room'),
    '5_70'=>Yii::t('x', 'Satellite TV'),
    '5_71'=>Yii::t('x', 'TV channels'), 
    '5_72'=>Yii::t('x', 'Play ground'),

    '5_73'=>Yii::t('x', 'Connecting room'),
    '5_74'=>Yii::t('x', 'Triple room'),

];

$venueFaciSearchList = [
    '5_02'=>Yii::t('x', 'Indoor swimming pool'),
    '5_03'=>Yii::t('x', 'Outdoor swimming pool'),
    '5_68'=>Yii::t('x', 'Meeting room'),
    '5_26'=>Yii::t('x', 'Baby cot'),
    '5_06'=>Yii::t('x', 'Private beach'),
    '5_18'=>Yii::t('x', 'Free wifi outside'),
    '5_74'=>Yii::t('x', 'Triple room'),
    '5_73'=>Yii::t('x', 'Connecting room'),
    '5_27'=>Yii::t('x', 'Air conditioning'),
    '5_28'=>Yii::t('x', 'Bath tub'),
    '5_15'=>Yii::t('x', 'Disabled facilities'),
    '5_09'=>Yii::t('x', 'Bicycle or motorbike'),
];

asort($venueFaciList);



$venueReccList = [
    '6_01'=>Yii::t('x', 'Couple'),
    '6_02'=>Yii::t('x', 'Family'),
    '6_03'=>Yii::t('x', 'Group'),
    '6_04'=>Yii::t('x', 'Honeymoon'),
    '6_05'=>Yii::t('x', 'Demanding travelers'),
    '6_06'=>Yii::t('x', 'Old people'),
    '6_07'=>Yii::t('x', 'Young people'),
];

$venueStraRecList = [
    'sr_s'=>Yii::t('x', 'Strategic to Amica'),
    'sr_r'=>Yii::t('x', 'Recommended by Amica'),
];

$venueStarList = [
    's_1s'=>Yii::t('x', '1 star'),
    's_2s'=>Yii::t('x', '2 stars'),
    's_3s'=>Yii::t('x', '3 stars'),
    's_4s'=>Yii::t('x', '4 stars'),
    's_5s'=>Yii::t('x', '5 stars'),
];