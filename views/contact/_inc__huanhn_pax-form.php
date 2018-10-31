<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$languageList = [
    'de'=>'Deutsch',
    'en'=>'English',
    'es'=>'Espanol',
    'fr'=>'Francais',
    'it'=>'Italiano',
    'vi'=>'Tiếng Việt',
    'zh'=>'中文',
];

$countryList = \common\models\Country::find()
    ->select(['code', 'name'=>'name_en'])
    ->orderBy('name')
    ->asArray()
    ->all();

$genderList = [
    'male'=>Yii::t('x', 'Male'),
    'female'=>Yii::t('x', 'Female'),
    'other'=>Yii::t('x', 'Other'),
];

$maritalStatusList = [
    'single'=>Yii::t('p', 'Single'),
    'married'=>Yii::t('p', 'Married'),
    'separated'=>Yii::t('p', 'Separated'),
    'divorced'=>Yii::t('p', 'Divorced'),
    'widowed'=>Yii::t('p', 'Widowed'),
    'open'=>Yii::t('p', 'Open relationship'),
    'cohabiting'=>Yii::t('p', 'Cohabiting'),
    'other'=>Yii::t('p', 'Other'),
];

$relationList = [
    1=>'Grandparent',
    2=>'Parent',
    3=>'Child',
    4=>'Grandchild',
    5=>'Spouse',
    6=>'Sibling',
    7=>'Cousin',
    8=>'Relative',
    9=>'Friend',
    10=>'In-law',
    10=>'Acquaintance',
];

$customerProfileList = [
    1=>Yii::t('p', 'Grand voyageur'),
    2=>Yii::t('p', 'Backpacker'),
    3=>Yii::t('p', 'Expatrié'),
    4=>Yii::t('p', 'Origines Vietnamiennes'),
    5=>Yii::t('p', 'Origines Laotiennes'),
    6=>Yii::t('p', 'Origines Cambodgiennes'),
    7=>Yii::t('p', 'Adoption d’un enfant en Asie du Sud-Est'),
    8=>Yii::t('p', 'Membre d’une association : précisez'),
    10=>Yii::t('p', 'Photographe professionnel'),
    11=>Yii::t('p', 'Voyage avec un enfant en bas âge'),
    12=>Yii::t('p', 'Client très exigent'),
    13=>Yii::t('p', 'Artiste'),
    14=>Yii::t('p', 'Voyage de noce'),
];

$travelPrefList = [
    1=>Yii::t('p', 'Budget  critère principal'),
    2=>Yii::t('p', 'Confort comme priorité'),
    3=>Yii::t('p', 'Séjour Balnéaire'),
    4=>Yii::t('p', 'Interaction Locale'),
    5=>Yii::t('p', 'Aime le calme'),
    6=>Yii::t('p', 'Pas de nuits chez l’Habitant'),
    7=>Yii::t('p', 'Préférence pour hôtels de charme/boutique'),
    8=>Yii::t('p', 'Préférence pour hôtels de qualité'),
    9=>Yii::t('p', 'Classic'),
    10=>Yii::t('p', 'Hors de sentier battue'),
];

$dietList = [
    1=>Yii::t('p', 'Végétarien'),
    2=>Yii::t('p', 'Végétalien'),
    3=>Yii::t('p', 'Sans porc'),
    4=>Yii::t('p', 'Sans gluten'),
    5=>Yii::t('p', 'Pas de piments'),
    6=>Yii::t('p', 'Allergie spécifique : précisez'),
    7=>Yii::t('p', 'Autres : précisez'),
];

$healthList = [
    1=>Yii::t('p', 'Problème de mobilité : pas de marches longues'),
    2=>Yii::t('p', 'Problème de mobilité : pas d’escaliers'),
    3=>Yii::t('p', 'Problème de dos : literie confortable'),
    4=>Yii::t('p', 'Problème cardiaque'),
    5=>Yii::t('p', 'Diabète'),
    6=>Yii::t('p', 'Claustrophobie'),
    7=>Yii::t('p', 'Autre : Précisez'),
    8=>Yii::t('p', 'Problème de mobilité : pas de vélo'),
];

$transportationList =[
    1=>Yii::t('p', 'Pas de longs trajets'),
    2=>Yii::t('p', 'Mal des transports'),
    3=>Yii::t('p', 'Mal de mer'),
    4=>Yii::t('p', 'Autres : Préciser'),
];

$likeList = [
    'Culture' => [
        "c_1" => "Art et architecture",
        "c_2" => "Activités artistiques : théâtre, spectacles, expositions",
        "c_3" => "Musique",
        "c_4" => "Photographie",
        "c_5" => "Histoire",
        "c_6" => "Peinture",
        "c_7" => "Artisanat",
        "c_8" => "Lecture",
        "c_9" => "Archéologie",
        "c_10" => "Les sites culturels et monuments",
    ],
    'Sportif' => [
        "s_1" => "VTT",
        "s_2" => "Snorkeling",
        "s_3" => "Sport nautiques",
        "s_" => "Equitation",
        "s_4" => "Danse",
        "s_5" => "Kayak ",
        "s_6" => "Vélo",
        "s_7" => "Plongée sous-marine",
        "s_8" => "Surfing",
        "s_9" => "Golf",
        "s_10" => "Yoga",
        "s_11" => "Ski",
        "s_12" => "Moto",
        "s_13" => "Marches / randonnées",
        "s_14" => "Pêche",
        "s_15" => "Trek/Randonnées",
        "s_16" => "Autres sports",
    ],
    'Relax' => [
        "r_1" => "Balade à pied",
        "r_2" => "Massage",
        "r_3" => "Shopping",
        "r_4" => "Nature, paysages, grands espaces",
        "r_5" => "Plage et farniente",
    ],
    'Autres' => [
        "a_1" => "Découvert",
        "a_2" => "Interaction locale",
        "a_3" => "Gastronomie locale",
        "a_4" => "Jardinage",
        "a_5" => "Bateau",
        "a_6" => "Bricolage",
        "a_7" => "Faune / sites animaliers",
        "a_8" => "Développement durable / Projet humanitaire",
    ]

];

$dislikeList = [
    1=>Yii::t('p', 'Les grandes villes'),
    2=>Yii::t('p', 'La foule'),
    3=>Yii::t('p', 'Trop de musées'),
    4=>Yii::t('p', 'Trop de sites à visiter (temples, monuments…)'),
    5=>Yii::t('p', 'Courir pendant le voyage'),
    6=>Yii::t('p', 'Faire des trajets longs'),
    7=>Yii::t('p', 'Sport / activité physique intense'),
    8=>Yii::t('p', 'Le luxe, un confort standard suffit'),
    9=>Yii::t('p', 'Arrêts shopping obligatoires'),
    10=>Yii::t('p', 'Etre trop encadré pendant le voyage'),
    11=>Yii::t('p', 'Nuit chez l’habitant'),
    12=>Yii::t('p', 'Les sites touristiques'),
];

$ambaList = [
    0=>Yii::t('p', 'Amba'),
    1=>Yii::t('p', 'Ampo'),
];

$newCustomerTypeList = [
    1=>Yii::t('x', 'Blacklist'),
    2=>Yii::t('x', 'Không tiềm năng'),
    3=>Yii::t('x', 'Tiếp cận được'),
    4=>Yii::t('x', 'Tiềm năng lớn'),
    5=>Yii::t('x', 'Ampo'),
    7=>Yii::t('x', 'Amba'),
    6=>Yii::t('x', 'Đối tượng đặc biệt khác'),
];
$arr_q = [
    '1. Khách đã từng đi với Amica Travel?' => [
        0 => 0,
        1 => 2,
        2 => 3,
        3 => 4,
        '> 4' => 5,
    ],
    '2. Mức độ chi trả cho chuyến đi? ( dựa trên khách sạn )' => [
            "Khách tự book khách sạn" => 2,
            "Hạng standard (2-3*)" => 3,
            "Hạng supérieure/de charme (3*, 3*+)" => 4,
            "Hạng de luxe (4-5*)" => 5,
    ],
    '3. Khách có điểm feedback giấy không?' => [
        'Có' => [
            'Điểm tốt' => [
                "5 - 7.9: Acceptable" => 2,
                "8 - 9.4: Satisfaisant" => 3,
                "9.5 - 10 : Très satisfaisant" => 5,
            ],
            'Điểm xấu' => [
                "0 - 2.9: Très insatisfaisant" => 0,
                "3 - 4.9: Insatisfaisant" => 0,
                "Mang tính góp ý xây dựng" => 1
            ]
        ],
        "Không" => [
            "Đánh giá bởi QHKH hoặc Hướng dẫn" => [
                "Tích cực" => 1,
                "Tiêu cực" => 0
            ],
            "Review trên các diễn đàn mạng xã hội (trong lúc đi tour)" => [
                "Tích cực" => 1,
                "Tiêu cực" => 'blacklist'
            ]
        ],
    ],
    '4. Khách có trả lời thư SV?' => [
        "Tích cực" => [
            "Có thư trả lời_checkbox_readonly" => 3,
            "Xin Tem, Blog_checkbox" => 1,
            "Review trực tiếp trên các diễn đàn mạng xã hội (sau khi đi tour)_checkbox" => 1,
        ],
        "Tiêu cực" => [
            "Mang tính góp ý xây dựng" => 1,
            "Tiêu cực" => 'blacklist'
        ],
        "Không phản hồi" => [
            "Review tốt trên các diễn đàn mạng xã hội" => 3,
            "Review xấu trên các diễn đàn mạng xã hội" => 'blacklist'
        ]
    ],
    "5. Số lần giới thiệu khách" => [
        0 => 0,
        1 => 2,
        2 => 3,
        3 => 4,
        '> 4' => 5,
    ]
];