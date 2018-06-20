<?
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
    'male'=>'Male',
    'female'=>'Female',
    'other'=>'Other',
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
];

$travelPrefList = [
    1=>Yii::t('p', 'Client très exigent'),
    2=>Yii::t('p', 'Budget  critère principal'),
    3=>Yii::t('p', 'Confort comme priorité'),
    4=>Yii::t('p', 'Séjour Balnéaire'),
    5=>Yii::t('p', 'Interaction Locale'),
    6=>Yii::t('p', 'Aime le calme'),
    7=>Yii::t('p', 'Pas de nuits chez l’Habitant'),
    8=>Yii::t('p', 'Préférence pour hôtels de charme/boutique'),
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
];

$transportationList =[
    1=>Yii::t('p', 'Pas de longs trajets'),
    2=>Yii::t('p', 'Mal des transports'),
    3=>Yii::t('p', 'Mal de mer'),
    4=>Yii::t('p', 'Autres : Préciser'),
];

$likeList = [
    1=>Yii::t('p', 'Photographie'),
    2=>Yii::t('p', 'Vélo'),
    3=>Yii::t('p', 'VTT'),
    4=>Yii::t('p', 'Plongée sous-marine'),
    5=>Yii::t('p', 'Snorkeling'),
    6=>Yii::t('p', 'Sport nautiques'),
    7=>Yii::t('p', 'Golf'),
    8=>Yii::t('p', 'Equitation'),
    9=>Yii::t('p', 'Yoga'),
    10=>Yii::t('p', 'Danse'),
    11=>Yii::t('p', 'Ski'),
    12=>Yii::t('p', 'Autres sports'),
    13=>Yii::t('p', 'Moto'),
    14=>Yii::t('p', 'Gastronomie locale'),
    15=>Yii::t('p', 'Nature, paysages, grands espaces'),
    16=>Yii::t('p', 'Les sites culturels et monuments'),
    17=>Yii::t('p', 'Artisanat'),
    18=>Yii::t('p', 'Art et architecture'),
    19=>Yii::t('p', 'Activités artistiques : théâtre, spectacles, expositions'),
    20=>Yii::t('p', 'Musique'),
    21=>Yii::t('p', 'Lecture'),
    22=>Yii::t('p', 'Jardinage'),
    23=>Yii::t('p', 'Plage et farniente'),
    24=>Yii::t('p', 'Histoire'),
    25=>Yii::t('p', 'Les rencontres'),
    26=>Yii::t('p', 'Bateau'),
    27=>Yii::t('p', 'Pêche'),
    28=>Yii::t('p', 'Bricolage'),
    29=>Yii::t('p', 'Archéologie'),
    30=>Yii::t('p', 'Faune / sites animaliers'),
    31=>Yii::t('p', 'Développement durable'),
    32=>Yii::t('p', 'Shopping'),
    33=>Yii::t('p', 'Marches / randonnées'),
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
];