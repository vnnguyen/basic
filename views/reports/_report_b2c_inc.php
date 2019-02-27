<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
// Yii::$app->params['page_actions'][] = [
//     ['icon'=>'list', 'label'=>'R01', 'link'=>'reports/qhkh-01', 'active'=>SEG2 == 'qhkh-01'],
//     ['icon'=>'list', 'label'=>'R02', 'link'=>'reports/qhkh-02', 'active'=>SEG2 == 'qhkh-02'],
//     ['icon'=>'list', 'label'=>'R03', 'link'=>'reports/qhkh-03', 'active'=>SEG2 == 'qhkh-03'],
//     // ['submenu'=>[
//     //     ['icon'=>'file-text-o', 'label'=>'View/edit itinerary', 'link'=>'products/r/'.$theTour['id']],
//     //     ['-'],
//     //     ['icon'=>'car', 'label'=>'Tour guides and drivers', 'link'=>'tours/gx/'.$theTourOld['id']],
//     //     ['-'],
//     //     ['icon'=>'file-pdf-o', 'label'=>'PDF summary', 'link'=>'tours/summary/'.$theTour['id']],
//     //     ['-'],
//     //     ['icon'=>'edit', 'label'=>'Edit tour info', 'link'=>'tours/u/'.$theTourOld['id']],
//     //     ['icon'=>'times', 'label'=>'Cancel tour', 'link'=>'tours/cxl/'.$theTour['id'], 'visible'=>$theTourOld['status'] != 'deleted'],
//     //     ],
//     // ],
// ];

Yii::$app->params['page_icon'] = 'chart';

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'Reports'), '@web/reports'],
    [Yii::t('x', 'B2C'), 'reports/b2c'],
    ['View'],
];

$kaseViewByList = [
    'created'=>Yii::t('x', 'Case created date'),
    'closed'=>Yii::t('x', 'Case closed date'),
    'tourstart'=>Yii::t('x', 'Tour start date'),
    'tourend'=>Yii::t('x', 'Tour end date'),
];

$kaseGroupByList = [
    'seller'=>Yii::t('x', 'Group by seller'),
    'source'=>Yii::t('x', 'Group by source'),
];

$kaseLanguageList = [
    'fr'=>'Français',
    'en'=>'English',
    'vi'=>'Tiếng Việt',
];
$kasePriorityList = [
    'yes'=>'Priority',
    'no'=>'Non-priority',
];
$kaseStatusList = ['open'=>'Open', 'onhold'=>'On hold', 'closed'=>'Closed'];
$kaseDealStatusList = ['pending'=>'Pending', 'won'=>'Won', 'lost'=>'Lost'];
$kaseOwnerList = [];
$kaseOwnerList[] = [
    'id'=>'all',
    'name'=>Yii::t('x', 'Any seller'),
    'group'=>Yii::t('x', 'Other'),
];
$kaseOwnerList[] = [
    'id'=>'none',
    'name'=>Yii::t('x', 'No seller'),
    'group'=>Yii::t('x', 'Other'),
];
foreach ($ownerList as $seller) {
    $kaseOwnerList[] = [
        'id'=>$seller['id'],
        'name'=>$seller['lname'].' '.$seller['email'],
        'group'=>Yii::t('x', 'Sellers in Vietnam'),
    ];
    $kaseOwnerList[] = [
        'id'=>'cofr-13',
        'name'=>'Hoa (Hoa Bearez)',
        'group'=>Yii::t('x', 'Consultants in France'),
    ];
    $kaseOwnerList[] = [
        'id'=>'cofr-5246',
        'name'=>'Arnaud Levallet',
        'group'=>Yii::t('x', 'Consultants in France'),
    ];
    $kaseOwnerList[] = [
        'id'=>'cofr-1769',
        'name'=>'Hoa (Hoa Bearez)',
        'group'=>Yii::t('x', 'Consultants in France'),
    ];
    $kaseOwnerList[] = [
        'id'=>'cofr-13',
        'name'=>'Trân (Cao Lê Trân)',
        'group'=>Yii::t('x', 'Consultants in France'),
    ];
    $kaseOwnerList[] = [
        'id'=>'cofr-767',
        'name'=>'Cô Xuân (Vương Thị Xuân)',
        'group'=>Yii::t('x', 'Consultants in France'),
    ];
    $kaseOwnerList[] = [
        'id'=>'cofr-688',
        'name'=>'Frédéric (Frédéric Hoeckel)',
        'group'=>Yii::t('x', 'Consultants in France'),
    ];
}

$kaseProspectList = [
    '1'=>'1 *',
    '2'=>'2 **',
    '3'=>'3 ***',
    '4'=>'4 ****',
    '5'=>'5 *****',
];

$kaseDeviceList = [
    'desktop'=>'Desktop',
    'tablet'=>'Tablet',
    'mobile'=>'Mobile',
    'none'=>'None/Unknown',
];

$kaseSiteList = [
    'fr'=>'FR',
    'vac'=>'VAC',
    'val'=>'VAL',
    'vpc'=>'VPC',
    'ami'=>'AMI',
    'en'=>'EN',
];

$kaseDestList = \common\models\Country::find()
    ->select(['code', 'name'=>'name_'.Yii::$app->language])
    ->where(['code'=>['vn', 'la', 'kh', 'mm', 'my', 'id', 'ph', 'cn']])
    ->orderBy('name')
    ->asArray()
    ->all();

$paxAgeGroupList = [
    '0_1'=>'<2',
    '2_11'=>'2-11',
    '12_17'=>'12-17',
    '18_25'=>'18-25',
    '26_34'=>'26-34',
    '35_50'=>'35-50',
    '51_60'=>'51-60',
    '61_70'=>'61-70',
    '71_up'=>'>70',
];
$dkdiemdenList = [];
$dkdiemdenList = [
    'any'=>Yii::t('x', 'Any of selected countries'),
    'all'=>Yii::t('x', 'All selected countries'),
    'only'=>Yii::t('x', 'Only selected countries'),
];

$kaseTravelTypeList = [
    'Family'=>'Family',
    'Couple'=>'Couple',
    'Friends'=>'Friends',
    'Group'=>'Group',
    'Solo'=>'Solo',
    'Business'=>'Business',
    'Other'=>'Other',
];

$caseHowContactedList = [
    'web'=>'Web',
        'web/adwords'=>'Adwords',
            'web/adwords/google'=>'Google Adwords',
            'web/adwords/bing'=>'Bing Ads',
            'web/adwords/other'=>'Other',
        'web/search'=>'Search',
            'web/search/google'=>'Google search',
            'web/search/bing'=>'Bing search',
            'web/search/yahoo'=>'Yahoo! search',
            'web/search/other'=>'Other',
        'web/link'=>'Referral',
            'web/link/360'=>'Blog 360',
            'web/link/facebook'=>'Facebook',
            'web/link/other'=>'Other',
        'web/adonline'=>'Ad online',
            'web/adonline/facebook'=>'Facebook',
            'web/adonline/voyageforum'=>'VoyageForum',
            'web/adonline/routard'=>'Routard',
            'web/adonline/sitevietnam'=>'Site-Vietnam',
            'web/adonline/other'=>'Other',
        'web/email'=>'Mailing',
        'web/direct'=>'Direct access',

    'nweb'=>'Non-web',
        'nweb/phone'=>'Phone',
        'nweb/email'=>'Email',
            'nweb/email/tripconn'=>'TripConnexion',
            'nweb/email/other'=>'Other',
        'nweb/walk-in'=>'Walk-in',
        'nweb/other'=>'Other', // web pages like Fb, fax, snail mail

    'agent'=>'Via a tour company', // OLD?
];

$caseHowContactedListFormatted = [];
foreach ($caseHowContactedList as $k=>$v) {
    $cnt = count(explode('/', $k));
    $v = str_repeat(' --- ', $cnt - 1). $v;
    $caseHowContactedListFormatted[$k] = $v;
}

$kaseHowFoundList = [
    'returning'=>Yii::t('x', 'Returning customer'),
    'new'=>Yii::t('x', 'New customer'),
    'referred'=>Yii::t('x', 'Referred customer'),
        'referred/customer'=>Yii::t('x', 'Referred by one of Amica\'s customers'),
        'referred/amica'=>Yii::t('x', 'Referred by one of Amica\'s staff'),
        'referred/org'=>Yii::t('x', 'Referred by an organization or one of its members'), // Ca nhan, to chuc
        'referred/expat'=>Yii::t('x', 'Referred by an expat in Vietnam'),
        'referred/other'=>Yii::t('x', 'Referred from other source'),
];

$kaseHowFoundListFormatted = [];
foreach ($kaseHowFoundList as $k=>$v) {
    $cnt = count(explode('/', $k));
    $v = str_repeat(' --- ', $cnt - 1). $v;
    $kaseHowFoundListFormatted[$k] = $v;
}

$consultantInFranceList = [];
$consultantInFranceList[] = [
    'id'=>'13',
    'name'=>'Hoa Bearez',
];
$consultantInFranceList[] = [
    'id'=>'5246',
    'name'=>'Arnaud Levallet',
];
$consultantInFranceList[] = [
    'id'=>'1769',
    'name'=>'Trân (Cao Lê Trân)',
];
$consultantInFranceList[] = [
    'id'=>'767',
    'name'=>'Cô Xuân (Vương Thị Xuân)',
];
$consultantInFranceList[] = [
    'id'=>'688',
    'name'=>'Frédéric Hoeckel',
];

$countryList = \common\models\Country::find()
    ->select(['code', 'name'=>'name_'.Yii::$app->language])
    ->where(['status'=>'on'])
    ->orderBy('name')
    ->asArray()
    ->all();
$countryList = ArrayHelper::map($countryList, 'code', 'name');

// Requested tours
$kaseRequestedTourList = [
    "Vietnam Panorama"=>"Vietnam Panorama",
    "Vietnam Nature et Culture"=>"Vietnam Nature et Culture",
    "Incontournables du Nord"=>" Incontournables du Nord",
    "De la baie Lan Ha à Hoi An"=>"De la baie Lan Ha à Hoi An",
    "Le delta du Mékong au fil de l’eau"=>"Le delta du Mékong au fil de l’eau",
    "Peuple des cimes"=>"Peuple des cimes",
    "Evasion en terres inconnus"=>"Evasion en terres inconnus",
    "Couleurs tonkinoises"=>"Couleurs tonkinoises",
    "A la découverte au Nord-Ouest"=>"A la découverte au Nord-Ouest",
    "Sur les traces des légendaires d'Indochine"=>"Sur les traces des légendaires d'Indochine",
    "Les sentiers aériens du Haut Tonkin"=>"Les sentiers aériens du Haut Tonkin",
    "Au pays des hommes fleurs"=>"Au pays des hommes fleurs",
    "De la réserve de Pu Luong à la baie Halong"=>"De la réserve de Pu Luong à la baie Halong",
    "Croisière dans la baie d'Halong"=>"Croisière dans la baie d'Halong",
    "Croisière dans le delta du Mékong"=>"Croisière dans le delta du Mékong",
    "Le Vietnam a vélo"=>"Le Vietnam a vélo",
    "Douceur Vietnam"=>"Douceur Vietnam",
    "Mosaique Vietnam"=>"Mosaique Vietnam",
    "Danang"=>"Danang",
    "Nha Trang"=>"Nha Trang",
    "Mui Ne"=>"Mui Ne",
    "Phu Quoc (Plage Duong Dong)"=>"Phu Quoc (Plage Duong Dong)",
    "Sihanouville (Plage Serenditipy & Occheuteal)"=>"Sihanouville (Plage Serenditipy & Occheuteal)",
    "Hue (Plage Thuan An)"=>"Hue (Plage Thuan An)",
    "Hoi An"=>"Hoi An",
    "Quy Nhon"=>"Quy Nhon",
    "Ile de la Baleine (Nha Trang)"=>"Ile de la Baleine (Nha Trang)",
    "Baie de Ninh Van"=>"Baie de Ninh Van",
    "Baie de Ke Ga"=>"Baie de Ke Ga",
    "Baie de Cam Ranh (Nha Trang)"=>"Baie de Cam Ranh (Nha Trang)",
    "Phu Quoc (Plage Nord Ouest)"=>"Phu Quoc (Plage Nord Ouest)",
    "Kep (Cambodge)"=>"Kep (Cambodge)",
    "Sihanouville (Plage Otres)"=>"Sihanouville (Plage Otres)",
    "Vung Tau"=>"Vung Tau",
    "Sam Son"=>"Sam Son",
    "Cat Ba"=>"Cat Ba",
    "Ile de Quan Lan"=>"Ile de Quan Lan",
    "Parc National de Nui Chua"=>"Parc National de Nui Chua",
    "Con Dao"=>"Con Dao",
    "Koh Kong (Cambodge)"=>"Koh Kong (Cambodge)",
    "Essentiel Angkor"=>"Essentiel Angkor",
    "Cambodge Nature et Culture"=>"Cambodge Nature et Culture",
    "Angkor et Encore"=>"Angkor et Encore",
    "Cambodge Panorama"=>"Cambodge Panorama",
    "Aux confins du Cambodge"=>"Aux confins du Cambodge",
    "Cache-cache entre les Apsaras"=>"Cache-cache entre les Apsaras",
    "Le Cambodge Profond"=>"Le Cambodge Profond",
    "Laos Panorama"=>"Laos Panorama",
    "Laos Nature et Culture"=>"Laos Nature et Culture",
    "Montagnes sacrées du Nord Laos"=>"Montagnes sacrées du Nord Laos",
    "Mosaique Laos"=>"Mosaique Laos",
    "Sourires du Laos"=>"Sourires du Laos",
    "Croisière au Laos"=>"Croisière au Laos",
    "Croisière de Saigon à Siem Reap"=>"Croisière de Saigon à Siem Reap",
    "Trésors Birmans – Birmanie Panorama"=>"Trésors Birmans – Birmanie Panorama",
    "Paysages et sanctuaires de Birmanie"=>"Paysages et sanctuaires de Birmanie",
    "Royaumes disparus de Birmanie"=>"Royaumes disparus de Birmanie",
    "Terres sauvages de Birmanie"=>"Terres sauvages de Birmanie",
    "Les trésors de l’Indochine"=>"Les trésors de l’Indochine",
    "Le long du Mékong, de Saïgon à Siem Reap"=>"Le long du Mékong, de Saïgon à Siem Reap",
    "Douces splendeurs du Laos et du Cambodge"=>"Douces splendeurs du Laos et du Cambodge",
];

$kaseFormuleList = [
    "Initiation à la Danse Céleste à Siem Reap"=>"Initiation à la Danse Céleste à Siem Reap",
    "Initiation à la médecine traditionnelle vietnamienne"=>"Initiation à la médecine traditionnelle vietnamienne",
    "Initiation à la méditation zen dans un monastère tonkinois"=>"Initiation à la méditation zen dans un monastère tonkinois",
    "Rencontre avec un maître laquier au village de Ha Thai"=>"Rencontre avec un maître laquier au village de Ha Thai",
    "Rencontre au temple Cao Dai de Sadec "=>"Rencontre au temple Cao Dai de Sadec ",
    "Rencontre avec M. Nguyen, «guide expert»"=>"Rencontre avec M. Nguyen, «guide expert»",
    "Le bonheur à fleur d’eau au village de Boping"=>"Le bonheur à fleur d’eau au village de Boping",
    "Halte buissonnière au vieux village de Non Khe"=>"Halte buissonnière au vieux village de Non Khe",
    "Étape haute en couleurs chez les Lolo noirs"=>"Étape haute en couleurs chez les Lolo noirs",
    "Un séjour-rencontre dans un village tay, au pied du massif de Tay Con Linh"=>"Un séjour-rencontre dans un village tay, au pied du massif de Tay Con Linh",
    "Au coeur de l’ancien Annam"=>"Au coeur de l’ancien Annam",
    "Immersion dans le monde des O’Pa"=>"Immersion dans le monde des O’Pa",
    "L’auberge de M. Pa, le bonheur à flanc de montagne"=>"L’auberge de M. Pa, le bonheur à flanc de montagne",
    "Rendez-vous en terre Thaï"=>"Rendez-vous en terre Thaï",
    "Immersion chez les Tay du lac Ba Be"=>"Immersion chez les Tay du lac Ba Be",
    "Le massif de Thong Nong, paradis pour marcheurs"=>"Le massif de Thong Nong, paradis pour marcheurs",
    "Mai Ich, vallée oasis pleine de charme"=>"Mai Ich, vallée oasis pleine de charme",
    "Vie aquatique du Tonlé Sap"=>"Vie aquatique du Tonlé Sap",
    "L’Eden nonchalant, au coeur du delta du Mékong"=>"L’Eden nonchalant, au coeur du delta du Mékong",
    "Séjour-partage sur l’île de Don Daeng"=>"Séjour-partage sur l’île de Don Daeng",
    "Chez l’habitant aux portes d’Angkor"=>"Chez l’habitant aux portes d’Angkor",
    "Amanoi"=>"Amanoi",
    "Bai Tram Hideaway"=>"Bai Tram Hideaway",
    "Auberge de Meo Vac"=>"Auberge de Meo Vac",
    "Hotel de Residence de Hue"=>"Hotel de Residence de Hue",
    "Ile de la Baleine"=>"Ile de la Baleine",
    "Bains de Hieu"=>"Bains de Hieu",
    "Jardin du Mekong"=>"Jardin du Mekong",
    "Mango Bay"=>"Mango Bay",
    "Suoi Mu Lodge"=>"Suoi Mu Lodge",
    "Tam Coc Garden "=>"Tam Coc Garden ",
    "Topas Ecolodge"=>"Topas Ecolodge",
    "La Folie Lodge"=>"La Folie Lodge",
    "Maison Souvanaphoum"=>"Maison Souvanaphoum",
    "Mandala Boutique Hotel"=>"Mandala Boutique Hotel",
    "Nong Khiaw Riverside"=>"Nong Khiaw Riverside",
    "Sanctuary Pakbeng Lodge"=>"Sanctuary Pakbeng Lodge",
    "Déjeuner chez un paysan dans la baie d’Along terrestre"=>"Déjeuner chez un paysan dans la baie d’Along terrestre",
    "Hanoi comme un local"=>"Hanoi comme un local",
    "Initiation à la riziculture dans la campagne de Hoi An"=>"Initiation à la riziculture dans la campagne de Hoi An",
    "Séance de peche"=>"Séance de peche",
    "Senteurs maraîchères au village de Tra Que à Hoi An"=>"Senteurs maraîchères au village de Tra Que à Hoi An",
    "Bucolisme dans la baie d’Halong terrestre"=>"Bucolisme dans la baie d’Halong terrestre",
    "Labourage des champs avec les Lolo noirs"=>"Labourage des champs avec les Lolo noirs",
    "Les marchés ethniques de la région de Bac Ha"=>"Les marchés ethniques de la région de Bac Ha",
    "Dong Ngac vieux village aux portes de Hanoi "=>"Dong Ngac vieux village aux portes de Hanoi ",
    "Immersion dans la baie de Lang Co"=>"Immersion dans la baie de Lang Co",
    "Saigon comme un local"=>"Saigon comme un local",
    "Terroir de la région poivrière du Cambodge"=>"Terroir de la région poivrière du Cambodge",
    "Vam Xang, au cœur du delta du Mékong"=>"Vam Xang, au cœur du delta du Mékong",
    "Le bambou à toutes les sauces"=>"Le bambou à toutes les sauces",
    "Percer les secrets de la soie laotienne"=>"Percer les secrets de la soie laotienne",
    "Randonnée avec les éléphants à Luang Prabang "=>"Randonnée avec les éléphants à Luang Prabang ",
    "Hanoi street food tour"=>"Hanoi street food tour",
    "Cours de cuisine à Luang Prabang"=>"Cours de cuisine à Luang Prabang",
    "Cours de cuisine (Hue)"=>"Cours de cuisine (Hue)",
    "Routes aériennes d’Ha Giang"=>"Routes aériennes d’Ha Giang",
    "La baie de Lan Ha, la petite sœur de Halong"=>"La baie de Lan Ha, la petite sœur de Halong",
    "Les jungles à cocotiers de Ben Tre"=>"Les jungles à cocotiers de Ben Tre",
    "Les terres mystiques du Haut Xekong"=>"Les terres mystiques du Haut Xekong",
    "Koh Kong, robinsonnade cambodgienne"=>"Koh Kong, robinsonnade cambodgienne",
    "Les contours du lac Ba Be"=>"Les contours du lac Ba Be",
    "Le règne de la nature à Meo Vac"=>"Le règne de la nature à Meo Vac",
    "Les routes du Panduranga, de Cam Ranh à Mui Ne"=>"Les routes du Panduranga, de Cam Ranh à Mui Ne",
    "Bain de nature à la réserve naturelle de Pu Luong"=>"Bain de nature à la réserve naturelle de Pu Luong",
    "Le monde rural aux portes d’Angkor"=>"Le monde rural aux portes d’Angkor",
    "Montagnes sacrés du nord laos"=>"Montagnes sacrés du nord laos",

];

$kaseReqTourThemeList = [
    'Découverte des sites incontournables' => 'Découverte des sites incontournables',
    'Immersion dans la vie locale' => 'Immersion dans la vie locale',
    'Randonnées et treks' => 'Randonnées et treks',
    'Balades à vélo' => 'Balades à vélo',
    'Cours de cuisine et autres découvertes culinaires' => 'Cours de cuisine et autres découvertes culinaires',
    'Détente' => 'Détente',
    'Séjour balnéaire' => 'Séjour balnéaire',
    'Croisière' => 'Croisière',
    'Artisanat local' => 'Artisanat local',
    'Bien-être et massages' => 'Bien-être et massages',
    'Retour aux sources' => 'Retour aux sources',
    'Voyage en amoureux' => 'Voyage en amoureux',
];