<?php
yap('page_icon', 'briefcase');

yap('page_breadcrumbs', [
    [Yii::t('x', 'Cases'), 'cases'],
]);

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>Yii::t('x', 'New case'), 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
    ],
];

if (isset($theCase['id'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'cases/r/'.$theCase['id'], 'active'=>SEG2 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'cases/u/'.$theCase['id'], 'active'=>SEG2 == 'u'],
        ['submenu'=>[
            ['icon'=>'align-left', 'label'=>'View all email & notes', 'link'=>'cases/r/'.$theCase['id'].'?allnotes=yes'],
            ['-'],
            ['icon'=>'user', 'label'=>'People in this case', 'link'=>'cases/people/'.$theCase['id'], 'active'=>SEG2 == 'people'],
            ['icon'=>'comment', 'label'=>'Customer\'s request', 'link'=>'cases/request/'.$theCase['id'], 'active'=>SEG2 == 'request'],
            ['icon'=>'link', 'label'=>'Partners', 'link'=>'cases/partners/'.$theCase['id'], 'active'=>SEG2 == 'partners'],
            // ['icon'=>'link', 'label'=>'Send Client page link', 'link'=>'cases/send-cpl/'.$theCase['id'], 'active'=>SEG2 == 'send-cpl'],
            ['-'],
            ['icon'=>'lock', 'label'=>'Close', 'link'=>'cases/close/'.$theCase['id'], 'active'=>SEG2 == 'close', 'hidden'=>$theCase['status'] == 'closed'],
            ['icon'=>'unlock', 'label'=>'Re-open', 'link'=>'cases/reopen/'.$theCase['id'], 'active'=>SEG2 == 'reopen', 'hidden'=>$theCase['status'] != 'closed'],
            ['icon'=>'clock-o', 'label'=>'Put on hold', 'link'=>'cases/hold/'.$theCase['id'], 'active'=>SEG2 == 'hold', 'hidden'=>$theCase['status'] == 'onhold'],
            ['icon'=>'clock-o', 'label'=>'Re-activate', 'link'=>'cases/unhold/'.$theCase['id'], 'active'=>SEG2 == 'unhold', 'hidden'=>$theCase['status'] != 'onhold'],
            ['-'],
            ['icon'=>'list-alt', 'label'=>'Change to B2B', 'link'=>'cases/b2bc/'.$theCase['id'], 'active'=>SEG2 == 'b2bc', 'hidden'=>in_array(!USER_ID, [1, 4432, 26435])],
            ['-'],
            ['icon'=>'list-alt', 'label'=>'Achteur/Non-achteur', 'link'=>'cases/ana/'.$theCase['id'], 'active'=>SEG2 == 'ana', 'hidden'=>in_array(!USER_ID, [1, 4432, 35887])],
            ['-'],
            ['icon'=>'trash-o', 'label'=>'Delete', 'link'=>'cases/d/'.$theCase['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
            ],
        ],
    ];
}

// How they contacted us
$caseHowContactedList = [
    'web'=>'Website form',
    'agent'=>'Via a tour company',
    'email'=>'Email',
    'phone'=>'Phone',
    'direct'=>'In person',
    'other'=>'Other', // web pages like Fb, fax, snail mail
];

// Web referral
$caseWebReferralList = array(
    'direct'=>'Direct',
    'link'=>'Link',
    'search'=>'Search',
    'search/google'=>'- Google',
    'search/bing'=>'- Bing',
    'search/yahoo'=>'- Yahoo',
    'search/other'=>'- Other',
    'mediasearch'=>'Media search',
    'ad'=>'Advertisement',
    'ad/adwords'=>'- Adwords',
    'ad/adsense'=>'- Adsense',
    'ad/bing'=>'- Bing ad',
    'ad/yahoo'=>'- Yahoo ad',
    'ad/trip-connexion'=>'- Trip connexion',
    'ad/other'=>'- Other ad',
    'email'=>'Email',
    'syndication'=>'Syndication',
    'social'=>'Social media',
);

// How they found us
$kaseHowFoundList = array(
    'web'=>'Web search/ad/link',
    'print'=>'Book/Print',
    'tv'=>'TV/Radio',
    'event'=>'Event/Seminar',
    'returning'=>'Returning',
    'word'=>'Word of mouth',
    'other'=>'Other', // travel agent, by chance
    'unknown'=>'Not known',
);

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
        'web/unknown'=>'Web - unknown',

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
    'returning'=>'Returning',
        'returning/customer'=>'Returning customer',
    'new'=>'New',
        'new/returning/contact'=>'Returning contact (not a customer)',
        'new/nref'=>'Not referred',
            'new/nref/web'=>'Web',
            'new/nref/print'=>'Book/Print',
            'new/nref/event'=>'Event/Seminar',
            'new/nref/expat'=>'Expats in Vietnam',
            'new/nref/other'=>'Other', // travel agent, by chance
        'new/ref'=>'Referred',
            'new/ref/customer'=>'Referred by one of Amica\'s customer',
            'new/ref/amica'=>'Referred by one of Amica\'s staff',
            'new/ref/org'=>'Referred by an organization or one of its members', // Ca nhan, to chuc
            'new/ref/expat'=>'By an expat in Vietnam',
            'new/ref/other'=>'Referred from other source',
];

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


// Close
$caseWhyClosedList = array(
    'won'=>'A tour has been confirmed | Đã bán được tour',
    'lost/duplicate'=>'Doublon | Trùng với hồ sơ khác',

    //'lost/nodeal'=>'Not a potential customer | Khách không có tiềm năng',
    'lost/nodeal/01'=>'Client non-potentiel – demande de groupe ou voyageur seul (Khách không tiềm năng – hỏi đi tour ghép hoặc pax đi 1 mình)',
    'lost/nodeal/02'=>'Client non-potentiel – pas de budget (Khách không tiềm năng – không có tiền)',
    'lost/nodeal/03'=>'Client non-potentiel – prestations sèches  (Khách không tiềm năng – chỉ hỏi dịch vụ nhỏ lẻ)',
    'lost/nodeal/04'=>'Client non-potentiel – autre destination  (Khách không tiềm năng – hỏi 1 điểm đến khác)',
    'lost/nodeal/00'=>'Client non-potentiel – autre raison (Khách không tiềm năng – vì lý do khác)',

    'lost/noreply'=>'Sans réponse - demande potentielle mais le client ne répond pas (HS tiềm năng nhưng khách không có hồi âm)',

    //'lost/refused'=>'Person has refused | Khách từ chối mua tour',
    'lost/refused/01'=>'Refus – autre agence ou/et prix trop cher (Khách từ chối – chọn 1 công ty khác hoặc/và vì giá Amica quá cao)',
    'lost/refused/02'=>'Refus – report du voyage, annulation, maladie, changement de destination (khách từ chối – hoãn chuyến đi, huỷ chuyến đi, bị ốm, thay đổi điểm đến)',
    'lost/refused/03'=>'Refus – le voyageur se débrouille seul (Khách từ chối – tự đặt dịch vụ, tự đi tour)',

    'lost/other'=>'Autres raisons - Không bán được vì lý do khác',
);

// Including old text
$caseWhyClosedListAll = array(
    'won'=>'A tour has been confirmed | Đã bán được tour',
    'lost/duplicate'=>'Doublon | Trùng với hồ sơ khác',

    'lost/nodeal'=>'Client non-potentiel | Khách không có tiềm năng',

    'lost/nodeal/01'=>'Client non-potentiel – demande de groupe ou voyageur seul (Khách không tiềm năng – hỏi đi tour ghép hoặc pax đi 1 mình)',
    'lost/nodeal/02'=>'Client non-potentiel – pas de budget (Khách không tiềm năng – không có tiền)',
    'lost/nodeal/03'=>'Client non-potentiel – prestations sèches  (Khách không tiềm năng – chỉ hỏi dịch vụ nhỏ lẻ)',
    'lost/nodeal/04'=>'Client non-potentiel – autre destination  (Khách không tiềm năng – hỏi 1 điểm đến khác)',
    'lost/nodeal/00'=>'Client non-potentiel – autre raison (Khách không tiềm năng – vì lý do khác)',

    'lost/noreply'=>'Sans réponse - demande potentielle mais le client ne répond pas (HS tiềm năng nhưng khách không có hồi âm)',

    'lost/refused'=>'Refus | Khách từ chối mua tour',

    'lost/refused/01'=>'Refus – autre agence ou/et prix trop cher (Khách từ chối – chọn 1 công ty khác hoặc/và vì giá Amica quá cao)',
    'lost/refused/02'=>'Refus – report du voyage, annulation, maladie, changement de destination (khách từ chối – hoãn chuyến đi, huỷ chuyến đi, bị ốm, thay đổi điểm đến)',
    'lost/refused/03'=>'Refus – le voyageur se débrouille seul (Khách từ chối – tự đặt dịch vụ, tự đi tour)',

    'lost/other'=>'Autres raisons - Không bán được vì lý do khác',
);


$anaQuestions['na_160730'] = [
    '0. Time',
    '1. Comment avez-vous connu Amica Travel ?',
    '2. Pour ce voyage avez-vous également contacté d\'autres agences de voyage ?',
    '3. Si oui, pouvez-vous préciser leurs noms ?',
    '4. Finalement, pour quelle formule de voyage avez-vous opté ?',
    '5. Pour quelles raisons vous n\'avez pas choisi Amica Travel ?',
    '6. Quels sont vos commentaires sur les différents échanges entre vous et l\'équipe d\'Amica Travel ?',
    '7. Avez-vous certaines remarques ou suggestions qui nous permettraient d\'améliorer notre service ?',
    '8. Votre nom et/ou votre adresse de mail (pas obligatoire)',
];

$anaQuestions['a_160730'] = [
    '0. Time',
    '1. Comment avez-vous connu Amica Travel ?',
    '2. Pour ce voyage avez-vous également contacté d\'autres agences de voyage ?',
    '3. Si oui, pouvez-vous préciser leurs noms ?',
    '4. Pourquoi avez-vous choisi une agence locale, plutôt qu\'une de votre pays ?',
    '5. Qu\'est-ce qui vous a convaincu de choisir finalement Amica Travel ?',
    '6. Quels sont vos commentaires sur les différents échanges entre vous et l\'équipe d\'Amica Travel ?',
    '7. Avez-vous certaines remarques ou suggestions qui nous permettraient d\'améliorer notre service ?',
    '8. Votre nom et/ou votre adresse de mail (pas obligatoire)'
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

$kaseDeviceList = [
    'Desktop'=>'Desktop',
    'Mobile'=>'Mobile',
    'Tablet'=>'Tablet',
    'Other'=>'Other',
];

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

$kaseChannelList = [
    'k1'=>'K1',
    'k2'=>'K2',
    'k3'=>'K3',
    'k4'=>'K4',
    'k5'=>'K5',
    'k6'=>'K6',
    'k7'=>'K7',
    'k8'=>'K8',
];

$kaseSourceList = [
    't1'=>'New customer',
    't2'=>'Referred customer',
    't3'=>'Returning customer',
];

$priorityList = [
    'no'=>Yii::t('x', 'No'),
    'yes'=>Yii::t('x', 'Yes'),
    1=>1,
    2=>2,
    3=>3,
    4=>4,
];
