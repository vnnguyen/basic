<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;


$this->title = 'Customers ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'group';
Yii::$app->params['page_breadcrumbs'] = [
    ['Customers'],
];

Yii::$app->params['page_layout'] = '-t';


$languageList = [
    'de'=>'Deutsch',
    'en'=>'English',
    'es'=>'Espanol',
    'fr'=>'Francais',
    'it'=>'Italiano',
    'vi'=>'Tiếng Việt',
    'zh'=>'中文',
];

$dataUrlList = [
    'website'=>'Website',
    'facebook'=>'Facebook',
    'twitter'=>'Twitter',
    'google-plus'=>'Google+',
    'youtube'=>'Youtube',
    'tripadvisor'=>'TripAdvisor',
    'linkedin'=>'LinkedIn',
    'skype'=>'Skype',
    'url'=>'Other URL',
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
$countryList = [
    'vn'=>'Vietnam',
    'la'=>'Laos',
    'kh'=>'Cambodia',
    'mm'=>'Myanmar',
    'id'=>'Indonesia',
    'my'=>'Malaysia',
    'th'=>'Thailand',
    'cn'=>'China',
    'ph'=>'Philippines',
];
$frenchDepartments = [
    ['code'=>'01', 'department'=>'Ain', 'name'=>'01 Ain', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'02', 'department'=>'Aisne', 'name'=>'02 Aisne', 'region'=>'Hauts-de-France'],
    ['code'=>'03', 'department'=>'Allier', 'name'=>'03 Allier', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'04', 'department'=>'Alpes-de-Haute-Provence', 'name'=>'04 Alpes-de-Haute-Provence', 'region'=>'Provence-Alpes-Côte d\'Azur'],
    ['code'=>'05', 'department'=>'Hautes-Alpes', 'name'=>'05 Hautes-Alpes', 'region'=>'Provence-Alpes-Côte d\'Azur'],
    ['code'=>'06', 'department'=>'Alpes-Maritimes', 'name'=>'06 Alpes-Maritimes', 'region'=>'Provence-Alpes-Côte d\'Azur'],
    ['code'=>'07', 'department'=>'Ardèche', 'name'=>'07 Ardèche', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'08', 'department'=>'Ardennes', 'name'=>'08 Ardennes', 'region'=>'Grand Est'],
    ['code'=>'09', 'department'=>'Ariège', 'name'=>'09 Ariège', 'region'=>'Occitanie'],
    ['code'=>'10', 'department'=>'Aube', 'name'=>'10 Aube', 'region'=>'Grand Est'],
    ['code'=>'11', 'department'=>'Aude', 'name'=>'11 Aude', 'region'=>'Occitanie'],
    ['code'=>'12', 'department'=>'Aveyron', 'name'=>'12 Aveyron', 'region'=>'Occitanie'],
    ['code'=>'13', 'department'=>'Bouches-du-Rhône', 'name'=>'13 Bouches-du-Rhône', 'region'=>'Provence-Alpes-Côte d\'Azur'],
    ['code'=>'14', 'department'=>'Calvados', 'name'=>'14 Calvados', 'region'=>'Normandy'],
    ['code'=>'15', 'department'=>'Cantal', 'name'=>'15 Cantal', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'16', 'department'=>'Charente', 'name'=>'16 Charente', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'17', 'department'=>'Charente-Maritime', 'name'=>'17 Charente-Maritime', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'18', 'department'=>'Cher', 'name'=>'18 Cher', 'region'=>'Centre-Val de Loire'],
    ['code'=>'19', 'department'=>'Corrèze', 'name'=>'19 Corrèze', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'2A', 'department'=>'Corse-du-Sud', 'name'=>'2A Corse-du-Sud', 'region'=>'Corsica'],
    ['code'=>'2B', 'department'=>'Haute-Corse', 'name'=>'2B Haute-Corse', 'region'=>'Corsica'],
    ['code'=>'21', 'department'=>'Côte-d\'Or', 'name'=>'21 Côte-d\'Or', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'22', 'department'=>'Côtes-d\'Armor', 'name'=>'22 Côtes-d\'Armor', 'region'=>'Brittany Brittany'],
    ['code'=>'23', 'department'=>'Creuse', 'name'=>'23 Creuse', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'24', 'department'=>'Dordogne', 'name'=>'24 Dordogne', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'25', 'department'=>'Doubs', 'name'=>'25 Doubs', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'26', 'department'=>'Drôme', 'name'=>'26 Drôme', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'27', 'department'=>'Eure', 'name'=>'27 Eure', 'region'=>'Normandy'],
    ['code'=>'28', 'department'=>'Eure-et-Loir', 'name'=>'28 Eure-et-Loir', 'region'=>'Centre-Val de Loire'],
    ['code'=>'29', 'department'=>'Finistère', 'name'=>'29 Finistère', 'region'=>'Brittany Brittany'],
    ['code'=>'30', 'department'=>'Gard', 'name'=>'30 Gard', 'region'=>'Occitanie'],
    ['code'=>'31', 'department'=>'Haute-Garonne', 'name'=>'31 Haute-Garonne', 'region'=>'Occitanie'],
    ['code'=>'32', 'department'=>'Gers', 'name'=>'32 Gers', 'region'=>'Occitanie'],
    ['code'=>'33', 'department'=>'Gironde', 'name'=>'33 Gironde', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'34', 'department'=>'Hérault', 'name'=>'34 Hérault', 'region'=>'Occitanie'],
    ['code'=>'35', 'department'=>'Ille-et-Vilaine', 'name'=>'35 Ille-et-Vilaine', 'region'=>'Brittany Brittany'],
    ['code'=>'36', 'department'=>'Indre', 'name'=>'36 Indre', 'region'=>'Centre-Val de Loire'],
    ['code'=>'37', 'department'=>'Indre-et-Loire', 'name'=>'37 Indre-et-Loire', 'region'=>'Centre-Val de Loire'],
    ['code'=>'38', 'department'=>'Isère', 'name'=>'38 Isère', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'39', 'department'=>'Jura', 'name'=>'39 Jura', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'40', 'department'=>'Landes', 'name'=>'40 Landes', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'41', 'department'=>'Loir-et-Cher', 'name'=>'41 Loir-et-Cher', 'region'=>'Centre-Val de Loire'],
    ['code'=>'42', 'department'=>'Loire', 'name'=>'42 Loire', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'43', 'department'=>'Haute-Loire', 'name'=>'43 Haute-Loire', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'44', 'department'=>'Loire-Atlantique', 'name'=>'44 Loire-Atlantique', 'region'=>'Pays de la Loire'],
    ['code'=>'45', 'department'=>'Loiret', 'name'=>'45 Loiret', 'region'=>'Centre-Val de Loire'],
    ['code'=>'46', 'department'=>'Lot', 'name'=>'46 Lot', 'region'=>'Occitanie'],
    ['code'=>'47', 'department'=>'Lot-et-Garonne', 'name'=>'47 Lot-et-Garonne', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'48', 'department'=>'Lozère', 'name'=>'48 Lozère', 'region'=>'Occitanie'],
    ['code'=>'49', 'department'=>'Maine-et-Loire', 'name'=>'49 Maine-et-Loire', 'region'=>'Pays de la Loire'],
    ['code'=>'50', 'department'=>'Manche', 'name'=>'50 Manche', 'region'=>'Normandy'],
    ['code'=>'51', 'department'=>'Marne', 'name'=>'51 Marne', 'region'=>'Grand Est'],
    ['code'=>'52', 'department'=>'Haute-Marne', 'name'=>'52 Haute-Marne', 'region'=>'Grand Est'],
    ['code'=>'53', 'department'=>'Mayenne', 'name'=>'53 Mayenne', 'region'=>'Pays de la Loire'],
    ['code'=>'54', 'department'=>'Meurthe-et-Moselle', 'name'=>'54 Meurthe-et-Moselle', 'region'=>'Grand Est'],
    ['code'=>'55', 'department'=>'Meuse', 'name'=>'55 Meuse', 'region'=>'Grand Est'],
    ['code'=>'56', 'department'=>'Morbihan', 'name'=>'56 Morbihan', 'region'=>'Brittany Brittany'],
    ['code'=>'57', 'department'=>'Moselle', 'name'=>'57 Moselle', 'region'=>'Grand Est'],
    ['code'=>'58', 'department'=>'Nièvre', 'name'=>'58 Nièvre', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'59', 'department'=>'Nord', 'name'=>'59 Nord', 'region'=>'Hauts-de-France'],
    ['code'=>'60', 'department'=>'Oise', 'name'=>'60 Oise', 'region'=>'Hauts-de-France'],
    ['code'=>'61', 'department'=>'Orne', 'name'=>'61 Orne', 'region'=>'Normandy'],
    ['code'=>'62', 'department'=>'Pas-de-Calais', 'name'=>'62 Pas-de-Calais', 'region'=>'Hauts-de-France'],
    ['code'=>'63', 'department'=>'Puy-de-Dôme', 'name'=>'63 Puy-de-Dôme', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'64', 'department'=>'Pyrénées-Atlantiques', 'name'=>'64 Pyrénées-Atlantiques', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'65', 'department'=>'Hautes-Pyrénées', 'name'=>'65 Hautes-Pyrénées', 'region'=>'Occitanie'],
    ['code'=>'66', 'department'=>'Pyrénées-Orientales', 'name'=>'66 Pyrénées-Orientales', 'region'=>'Occitanie'],
    ['code'=>'67', 'department'=>'Bas-Rhin', 'name'=>'67 Bas-Rhin', 'region'=>'Grand Est'],
    ['code'=>'68', 'department'=>'Haut-Rhin', 'name'=>'68 Haut-Rhin', 'region'=>'Grand Est'],
    ['code'=>'69', 'department'=>'Rhône', 'name'=>'69 Rhône', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'69M', 'department'=>'Metropolitan Lyon', 'name'=>'69M Metropolitan Lyon', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'70', 'department'=>'Haute-Saône', 'name'=>'70 Haute-Saône', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'71', 'department'=>'Saône-et-Loire', 'name'=>'71 Saône-et-Loire', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'72', 'department'=>'Sarthe', 'name'=>'72 Sarthe', 'region'=>'Pays de la Loire'],
    ['code'=>'73', 'department'=>'Savoie', 'name'=>'73 Savoie', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'74', 'department'=>'Haute-Savoie', 'name'=>'74 Haute-Savoie', 'region'=>'Auvergne-Rhône-Alpes'],
    ['code'=>'75', 'department'=>'Paris', 'name'=>'75 Paris', 'region'=>'Île-de-France'],
    ['code'=>'76', 'department'=>'Seine-Maritime', 'name'=>'76 Seine-Maritime', 'region'=>'Normandy'],
    ['code'=>'77', 'department'=>'Seine-et-Marne', 'name'=>'77 Seine-et-Marne', 'region'=>'Île-de-France'],
    ['code'=>'78', 'department'=>'Yvelines', 'name'=>'78 Yvelines', 'region'=>'Île-de-France'],
    ['code'=>'79', 'department'=>'Deux-Sèvres', 'name'=>'79 Deux-Sèvres', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'80', 'department'=>'Somme', 'name'=>'80 Somme', 'region'=>'Hauts-de-France'],
    ['code'=>'81', 'department'=>'Tarn', 'name'=>'81 Tarn', 'region'=>'Occitanie'],
    ['code'=>'82', 'department'=>'Tarn-et-Garonne', 'name'=>'82 Tarn-et-Garonne', 'region'=>'Occitanie'],
    ['code'=>'83', 'department'=>'Var', 'name'=>'83 Var', 'region'=>'Provence-Alpes-Côte d\'Azur'],
    ['code'=>'84', 'department'=>'Vaucluse', 'name'=>'84 Vaucluse', 'region'=>'Provence-Alpes-Côte d\'Azur'],
    ['code'=>'85', 'department'=>'Vendée', 'name'=>'85 Vendée', 'region'=>'Pays de la Loire'],
    ['code'=>'86', 'department'=>'Vienne', 'name'=>'86 Vienne', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'87', 'department'=>'Haute-Vienne', 'name'=>'87 Haute-Vienne', 'region'=>'Nouvelle-Aquitaine'],
    ['code'=>'88', 'department'=>'Vosges', 'name'=>'88 Vosges', 'region'=>'Grand Est'],
    ['code'=>'89', 'department'=>'Yonne', 'name'=>'89 Yonne', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'90', 'department'=>'Territoire de Belfort', 'name'=>'90 Territoire de Belfort', 'region'=>'Bourgogne-Franche-Comté'],
    ['code'=>'91', 'department'=>'Essonne', 'name'=>'91 Essonne', 'region'=>'Île-de-France'],
    ['code'=>'92', 'department'=>'Hauts-de-Seine', 'name'=>'92 Hauts-de-Seine', 'region'=>'Île-de-France'],
    ['code'=>'93', 'department'=>'Seine-Saint-Denis', 'name'=>'93 Seine-Saint-Denis', 'region'=>'Île-de-France'],
    ['code'=>'94', 'department'=>'Val-de-Marne', 'name'=>'94 Val-de-Marne', 'region'=>'Île-de-France'],
    ['code'=>'95', 'department'=>'Val-d\'Oise', 'name'=>'95 Val-d\'Oise', 'region'=>'Île-de-France'],
    ['code'=>'971', 'department'=>'Guadeloupe', 'name'=>'971 Guadeloupe', 'region'=>'Guadeloupe'],
    ['code'=>'972', 'department'=>'Martinique', 'name'=>'972 Martinique', 'region'=>'Martinique'],
    ['code'=>'973', 'department'=>'Guyane', 'name'=>'973 Guyane', 'region'=>'French Guiana'],
    ['code'=>'974', 'department'=>'La Réunion', 'name'=>'974 La Réunion', 'region'=>'Réunion'],
    ['code'=>'976', 'department'=>'Mayotte', 'name'=>'976 Mayotte', 'region'=>'Mayotte'],
];


?>
<div class="col-md-12">
    <div class="form-inline mb-20">
        <form>
        <?= Html::textInput('year', $year, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Year')]) ?>
        <?= Html::textInput('code', $code, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Tour code')]) ?>
        <?= Html::textInput('name', $name, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Name')]) ?>
        <?= Html::dropdownList('gender', $gender, ['all'=>'All genders', 'male'=>'Male', 'female'=>'Female'], ['class'=>'form-control']) ?>
        <?= Html::textInput('age', $age, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Age, eg 20-30']) ?>
        <div class="has-select" style="display: inline-block;">
            <?= Html::dropdownList('country', $country, ArrayHelper::map($countryList, 'code', 'name_en'), ['class'=>'form-control', 'prompt'=>Yii::t('x', 'All countries'), 'multiple'=>'multiple']) ?>
        </div>
        
        <?= Html::dropdownList('department', $department, ArrayHelper::map($frenchDepartments, 'code', 'department'), ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Department')]) ?>
        <?= Html::textInput('address', $address, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Address')]) ?>
        <?= Html::textInput('email', $email, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Email')]) ?>

        <?= Html::textInput('phone', $phone, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Phone'), 'type' => 'tel']) ?>
        <?= Html::textInput('passeport', $passeport, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Passeport')]) ?>

        <?= Html::dropdownList('typeOfWeb', $typeOfWeb, $dataUrlList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Type of web')]) ?>
        <?= Html::dropdownList('destination', $destination, $countryList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Visited country')]) ?>
        <?= Html::dropdownList('nextCountry', $nextCountry, $countryList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Next Countries')]) ?>

        <?= Html::dropdownList('lang', $lang, $languageList, ['class'=>'form-control','prompt'=>Yii::t('x', 'Language')]) ?>
        <?= Html::dropdownList('like', $like, $likeList, ['class'=>'form-control','prompt'=>Yii::t('x', 'Like')]) ?>
        <?= Html::dropdownList('dislike', $dislike, $dislikeList, ['class'=>'form-control','prompt'=>Yii::t('x', 'Dislike')]) ?>
        <?= Html::dropdownList('traveler_profile', $traveler_profile, $customerProfileList, ['class'=>'form-control','prompt'=>Yii::t('x', 'Traveler profile')]) ?>
        <?= Html::dropdownList('ambas', $ambas, ['Ampo', 'Amba'], ['class'=>'form-control','prompt'=>Yii::t('x', 'Ambassador potentiality')]) ?>
        <?= Html::dropdownList('traveler_pref', $traveler_pref, $travelPrefList, ['class'=>'form-control','prompt'=>Yii::t('x', 'Traveler preference')]) ?>



        <?= Html::dropdownList('bcount', $bcount, ['0'=>'Bookings', 1=>1,2=>2,3=>3,4=>4,5=>5], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('rcount', $rcount, ['0'=>'Referrals', 1=>1,2=>2,3=>3,4=>4,5=>5], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('output', 'view', ['view'=>'View', 'download'=>'Download'], ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '/customers', ['class'=>'btn btn-default']) ?>
        </form>
    </div>

<?

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.4/js/ion.rangeSlider.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);

?>

    <div class="panel panel-default">
        <? if (empty($theCustomers)) { ?>
        <div class="panel-body">
        No data found.
        </div>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th colspan="2">Name</th>
                        <th width="">Date of birth</th>
                        <th width="">Email</th>
                        <th width="">Phone</th>
                        <th width="">Address</th>
                        <th>Tour bookings</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theCustomers as $customer) { ?>
                    <tr>
                        <td class="text-nowrap">
                            <? if ($customer['country_code'] != '') { ?><span class="flag-icon flag-icon-<?=$customer['country_code'] ?>"></span><? } ?>
                        </td>
                        <td>
                            <? if ($customer['gender'] == 'male') { ?><i class="fa fa-male text-primary"></i><? } ?>
                            <? if ($customer['gender'] == 'female') { ?><i class="fa fa-female text-pink"></i><? } ?>
                        </td>
                        <td><?=Html::a($customer['fname'], 'users/r/'.$customer['id'])?></td>
                        <td><?=Html::a($customer['lname'], 'users/r/'.$customer['id'])?>
                        <?
                        if (Yii::$app->user->id == 1 && $customer['lname'] == '' && $customer['fname'] != '') {
                            $names = explode(' ', $customer['fname']);
                            if (count($names) == 2) {
                                echo Html::a($names[0].'/'.$names[1], 'users/d/'.$customer['id'].'?action=name&option=12');
                                echo ' - ';
                                echo Html::a($names[1].'/'.$names[0], 'users/d/'.$customer['id'].'?action=name&option=21');
                            }
                        }
                        ?>
                        </td>
                        <td><?= $customer['bday'] ?>/<?= $customer['bmonth'] ?>/<?= $customer['byear'] ?></td>
                        <td><?= $customer['email'] ?></td>
                        <td><?= $customer['phone'] ?></td>
                        <td><?
                        foreach ($customer['metas'] as $meta) {
                            if ($meta['name'] == 'address') {
                                echo $meta['value'];
                            }
                        }
                        ?>
                        </td>
                        <td>
                            <?
                            if ($customer['bookings']) {
                                foreach ($customer['bookings'] as $booking) {
                                    echo Html::a($booking['product']['op_code'], '/products/op/'.$booking['product']['id'], ['class'=>'text-success']);
                                    echo '&nbsp; ';
                                }
                            }
                            ?>
                        </td>
                        <td class="muted td-n">
                            <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>users/u/<?=$customer['id']?>"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>

    <? if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="_panel-body text-center">
        <?= LinkPager::widget([
                'pagination' => $pagination,
                'firstPageLabel'=>'<<',
                'prevPageLabel'=>'<',
                'nextPageLabel'=>'>',
                'lastPageLabel'=>'>>',
            ]) ?>
    </div>
    <? } // if pagination ?>

    <? } // if theUsers ?>
</div>
<?

$js = <<<TXT
$('.has-select select').select2({
    placeholder: 'select country'
});

TXT;
$this->registerJs($js);
?>