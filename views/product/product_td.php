<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;

Yii::$app->params['page_title'] = 'Tableau devis - '.$theProduct['title'];
Yii::$app->params['page_breadcrumbs'] = [
    ['Products', 'products'],
    ['View', 'products/r/'.$theProduct['id']],
    ['Tableau devis'],
];

$listD = [
    ['name'=>'-'],
    ['name'=>'Baie d’Along'],
    ['name'=>'Baie d’Along Terrestre'],
    ['name'=>'Delta du Mékong'],
    ['name'=>'Ha Giang, Cao Bang et leurs régions'],
    ['name'=>'Hanoi'],
    ['name'=>'Ho Chi Minh Ville'],
    ['name'=>'Hoi An'],
    ['name'=>'Hué'],
    ['name'=>'Lac Ba Be'],
    ['name'=>'Luang Prabang et sa région'],
    ['name'=>'Mai Chau'],
    ['name'=>'Phnom Penh'],
    ['name'=>'Sapa, Bac Ha et alentours'],
    ['name'=>'Siem Reap'],
    ['name'=>'Sud du Cambodge'],
];

$listO = [
    ['name'=>'-', 'region'=>'( Blank / Để trống )'],
    ['name'=>'La visite classique de la ville', 'region'=>'Hanoi'],
    ['name'=>'La visite classique de la ville et une découverte de l’artisanat au village de Ba Trang', 'region'=>'Hanoi'],
    ['name'=>'Un séjour au village de Pom Coong avec nuit chez l’habitant, spectacle de danse Thai et balades à pied ou à vélo dans la vallée', 'region'=>'Mai Chau'],
    ['name'=>'Un ou 2 jours incluant Hoa Lu et Trang An avec balade en sampan', 'region'=>'Baie d’Along Terrestre'],
    ['name'=>'Une croisière dans la Baie d’Along avec visite de la grotte de la Surprise, kayak', 'region'=>'Baie d’Along'],
    ['name'=>'Une découverte d’un marché ethnique et des randonnées autour de Sapa à Ta Van, Cat Cat, Lao Chai, Ta Phin', 'region'=>'Sapa, Bac Ha et alentours'],
    ['name'=>'Une boucle classique Ha Giang, Dong Van, Meo Vac, Cao Bang avec quelques balades en montagne', 'region'=>'Ha Giang, Cao Bang et leurs régions'],
    ['name'=>'Un tour de la ville classique incluant les sites incontournables comme la Cathédrale Notre-Dame, le quartier de Cholon, la rue Dong Khoi…', 'region'=>'Ho Chi Minh Ville'],
    ['name'=>'Un séjour avec une nuit chez l’habitant au village de Pac Ngoi', 'region'=>'Lac Ba Be'],
    ['name'=>'Le tour des sites incontournables tels que la cité impériale, le marché de Dong Ba ou la Pagode de la Dame Céleste', 'region'=>'Hué'],
    ['name'=>'La visite d’un marché flottant, souvent celui de Cai Be', 'region'=>'Delta du Mékong'],
    ['name'=>'Un parcours classique entre My Tho, Vinh Long et Can Tho', 'region'=>'Delta du Mékong'],
    ['name'=>'Une visite de la ville avec ses sites incontournables tels que le musée national', 'region'=>'Phnom Penh'],
    ['name'=>'L’exploration du petit ou grand circuit des temples d’Angkor', 'region'=>'Siem Reap'],
    ['name'=>'La visite de la ville et ses sites culturels incontournables', 'region'=>'Luang Prabang et sa région'],
    ['name'=>'Un séjour balnéaire à Sihanoukville, plage ravagée par le tourisme de masse', 'region'=>'Sihanoukville'],
];

$listA = [
    ['name'=>'-', 'region'=>'( Blank / Để trống )'],
    ['name'=>'Les sites incontournables culturels et historiques', 'region'=>'Hanoi'],
    ['name'=>'Une immersion dans la vie locale urbaine avec un déjeuner chez l’habitant', 'region'=>'Hanoi'],
    ['name'=>'Une immersion interactive à la découverte de l’artisanat dans des villages préservés du tourisme de masse', 'region'=>'Hanoi'],
    ['name'=>'Un moment unique avec un artisan d’instruments traditionnels de musique', 'region'=>'Hanoi'],
    ['name'=>'Des moments privilégiés au contact de la population pour mieux découvrir les métiers locaux', 'region'=>'Hanoi'],
    ['name'=>'Une découverte nocturne des délices culinaires locaux', 'region'=>'Hanoi'],
    ['name'=>'Un séjour à Mai Ich pour éviter la foule de touristes à Pom Coong. Une immersion chez un habitant de l’ethnie Thai et randonnée ou excursion à 2 roues dans des coins encore isolés', 'region'=>'Mai Chau'],
    ['name'=>'2 jours avec des découvertes isolées du tourisme de masse dont balade en sampan vers Tam Coc et Van Long', 'region'=>'Baie d’Along Terrestre'],
    ['name'=>'Une nuit magique au Tam Coc Garden, petit paradis au milieu des rizières et pitons karstiques', 'region'=>'Baie d’Along Terrestre'],
    ['name'=>'Une immersion exclusive au village de Yen Mac avec nuit chez l’habitant', 'region'=>'Baie d’Along Terrestre'],
    ['name'=>'Une croisière dans la Baie de Bai Tu Long, coin plus reculé où il y a moins de touristes, incluant toujours visite d’une grotte, kayak', 'region'=>'Baie d’Along'],
    ['name'=>'Une croisière dans la Baie de Lan Ha, Baie moins fréquentée que celle d’Along et découverte de l’Ile de Cat Ba', 'region'=>'Baie d’Along'],

    ['name'=>'Une découverte d’un ou plusieurs marchés ethniques et nuit chez un habitant de l’ethnie Tay à Bac Ha avec des randonnées aux alentours hors des sentiers battus', 'region'=>'Sapa, Bac Ha et alentours'],
    ['name'=>'Des randonnées à Sapa loin du tourisme de masse à Ta Giang Phin et Hang Da', 'region'=>'Sapa, Bac Ha et alentours'],
    ['name'=>'Une superbe halte à Mu Cang Chai, avec les plus belles rizières en terrasses du pays et une randonnée au milieu de celles-ci à la rencontre des ethnies Thai et Hmong', 'region'=>'Sapa, Bac Ha et alentours'],
    ['name'=>'Des rencontres uniques et authentiques avec des habitants locaux où ils vous accueillent pour la nuit au milieu de cadres naturels exceptionnels comme à Ha Thanh, Khuoi Khon ou Nam Ngu', 'region'=>'Ha Giang, Cao Bang et leurs régions'],
    ['name'=>'Une immersion magique à la rencontre de l’ethnie rare Lolo noir, au village où a été tourné Rencontres en Terre Inconnue avec M. Michalak', 'region'=>'Ha Giang, Cao Bang et leurs régions'],
    ['name'=>'La découverte des incontournables mais aussi une rencontre atypique avec un médecin traditionnel dans le quartier Chinois de Cholon', 'region'=>'Ho Chi Minh Ville'],
    ['name'=>'Moment immersif dans la vie saïgonnaise, à la découverte de ses « hem », marchés locaux et déjeuner avec des locaux', 'region'=>'Ho Chi Minh Ville'],
    ['name'=>'Un moment authentique chez un habitant du village de Coc Toc, géographiquement plus reculé que Pac Ngoi', 'region'=>'Lac Ba Be'],
    ['name'=>'Une visite de l’ancienne capitale riche en activités ponctuée le midi par un massage des pieds', 'region'=>'Hué'],
    ['name'=>'Des moments privilégiés au village de Kim Long avec M. Nguyen, ancien directeur du Centre de préservation des patrimoines de Hué', 'region'=>'Hué'],
    ['name'=>'Une immersion à la lagune de Tam Giang pour découvrir la vie des pêcheurs', 'region'=>'Hué'],
    ['name'=>'Un cours de cuisine chez un habitant local d’Hué, dans sa jolie maison au cœur de la ville', 'region'=>'Hué'],
    ['name'=>'Une balade à vélo entre rizières et rivières, à la découverte de la vie locale, ses activités journalières et son artisanat.', 'region'=>'Hoi An'],

    ['name'=>'Un moment exclusif chez un habitant du Delta, à l’ombre des cocotiers et à partager des activités', 'region'=>'Delta du Mékong'],
    ['name'=>'Demi-journée hors des sentiers battus à Go Cong, avec petite balade à vélo et déjeuner en bord de mer', 'region'=>'Delta du Mékong'],
    ['name'=>'Une balade à cheval dans la campagne environnante de la capitale Cambodgienne', 'region'=>'Phnom Penh'],
    ['name'=>'Un moment de détente pour apprécier la douceur du soir à bord du catamaran Kanika', 'region'=>'Phnom Penh'],
    ['name'=>'Les plus beaux temples du parc archéologique d’Angkor mais aussi certains plus reculés et sauvages', 'region'=>'Siem Reap'],
    ['name'=>'Un moment de partage avec une famille khmère', 'region'=>'Siem Reap'],
    ['name'=>'Les visites incontournables couplées par une balade à pied de l’autre côté de la rivière hors des sentiers battus au plus près de la population locale', 'region'=>'Luang Prabang et sa région'],
    ['name'=>'Matinée interactive au milieu des rizières pour comprendre la culture du riz', 'region'=>'Luang Prabang'],
    ['name'=>'Un séjour balnéaire à Sihanoukville, sur une plage plus éloignée, celle d’Otres', 'region'=>'Sihanoukville'],
    ['name'=>'Un séjour balnéaire à Kep, station plus petite que Sihanoukville mais beaucoup plus charmante', 'region'=>'Kep'],
];

$listV = [
    ['name'=>'-', 'region'=>'( Blank / Để trống )'],
    ['name'=>'Découverte des sites incontournables'],
    ['name'=>'Immersion dans la vie locale'],
    ['name'=>'Randonnées et balades à pied'],
    ['name'=>'Séjour farniente au bord de la mer'],
    ['name'=>'Envie de repos et de détente'],
    ['name'=>'Envie de rencontres au plus près de la population locale'],
    ['name'=>'Curiosité pour l’artisanat et l’art'],
    ['name'=>'Moments interactifs avec les ethnies du Grand Nord'],
    ['name'=>'Soif d’aventure'],
    ['name'=>'Balades ludiques pour toute la famille'],
    ['name'=>'Moment romantique en amoureux'],
    ['name'=>'Initiation aux délices culinaires du pays'],
    ['name'=>'S’imprégner des coutumes et traditions locales'],
    ['name'=>'Découverte de l’architecture traditionnelle'],
    ['name'=>'Etre au plus proche de la nature et des grands espaces'],
    ['name'=>'Envie de balades à vélo'],
];

$valuesD = array_map(function($x){ return $x['name']; }, $listD);
$valuesO = array_map(function($x){ return $x['name']; }, $listO);
$valuesA = array_map(function($x){ return $x['name']; }, $listA);
$valuesV = array_map(function($x){ return $x['name']; }, $listV);

//\fCore::expose($valuesD); exit;

?>
<style>
.bg-grey-100 {background-color:#f3f3f3;}
.table-xxs th, .table-xxs td {padding:6px!important;}
.ui-state-highlight {background-color:#fffff3;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="alert alert-info">
                CHÚ Ý: Để trống cột <code>Destination</code> thì nội dung cột <code>Ce qu'Amica vous conseille</code> sẽ được ghép với dòng trên (tức là 2 dòng ghép làm 1).
                <br>NOTE: Leave <code>Destination</code> blank to merge the content of <code>Ce qu'Amica vous conseille</code> column with previous line.
            </div>
            <form method="post" action="">
                <table id="table_f" class="table table-xxs mb-20">
                    <thead>
                        <tr>
                            <th width="20"></th>
                            <th><?= Yii::t('si_tour_summary', 'Destination') ?></th>
                            <th><?= Yii::t('si_tour_summary', 'Votre voyage, votre histoire, votre envies') ?></th>
                            <th><?= Yii::t('si_tour_summary', 'Ce qu\'Amica vous conseille') ?></th>
                            <th><?= Yii::t('si_tour_summary', 'Ce que l\'on vous propose souvent') ?></th>
                            <th width="20"></th>
                        </tr>
                    </thead>
                    <tbody class="sortable" style="overflow:auto;">
                        <?php
                        for ($i = 0; $i < count($metaData); $i ++) {
                            ?>
                        <tr>
                            <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                            <td><?= Html::textInput('i1[]', $metaData[$i][0], ['class'=>'form-control no-border bg-grey-100']) ?></td>
                            <td><?= Html::textInput('i2[]', $metaData[$i][1], ['class'=>'form-control no-border bg-grey-100']) ?></td>
                            <td><?= Html::textInput('i3[]', $metaData[$i][2], ['class'=>'form-control no-border bg-grey-100']) ?></td>
                            <td><?= Html::textInput('i4[]', $metaData[$i][3], ['class'=>'form-control no-border bg-grey-100']) ?></td>
                            <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div>
                    <?= Html::submitButton(Yii::t('app', 'Save and return'), ['class'=>'btn btn-primary']) ?>
                    or
                    <?= Html::a(Yii::t('app', 'Reset'), '/products/td/'.$theProduct['id'], ['class'=>'text-danger']) ?>
                    or 
                    <?= Html::a(Yii::t('app', 'Cancel'), '/products/r/'.$theProduct['id'], ['class'=>'text-danger']) ?>
                </div>
            </form>

            <hr>
            <p><span class="text-bold text-uppercase"><?= Yii::t('xx', 'Add new line') ?></span> Select and press + to add to above table</p>
            <table id="table_x" class="table table-xxs">
                <tbody>
                    <tr>
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><?= Html::dropdownList('i1[]', '', ArrayHelper::map($listD, 'name', 'name'), ['class'=>'form-control no-border bg-grey-100']) ?></td>
                        <td><?= Html::dropdownList('i2[]', '', ArrayHelper::map($listV, 'name', 'name'), ['class'=>'form-control no-border bg-grey-100']) ?></td>
                        <td><?= Html::dropdownList('i3[]', '', ArrayHelper::map($listA, 'name', 'name', 'region'), ['class'=>'form-control no-border bg-grey-100']) ?></td>
                        <td><?= Html::dropdownList('i4[]', '', ArrayHelper::map($listO, 'name', 'name', 'region'), ['class'=>'form-control no-border bg-grey-100']) ?></td>
                        <td><button id="addme" class="btn btn-primary btn-block">+</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<table id="table_f2" class="table table-xxs">
    <tbody>
        <tr style="display:none;">
            <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
            <td><?= Html::textInput('i1[]', '', ['class'=>'form-control no-border bg-grey-100']) ?></td>
            <td><?= Html::textInput('i2[]', '', ['class'=>'form-control no-border bg-grey-100']) ?></td>
            <td><?= Html::textInput('i3[]', '', ['class'=>'form-control no-border bg-grey-100']) ?></td>
            <td><?= Html::textInput('i4[]', '', ['class'=>'form-control no-border bg-grey-100']) ?></td>
            <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
        </tr>
    </tbody>
</table>
<style>
    
</style>
<?php

$js = <<<'TXT'
$('tbody.sortable').sortable({
    axis: 'y',
    containment: "parent",
    handle: 'i.fa-arrows-v',
    helper: "clone",
    placeholder: 'ui-state-highlight'
});
$( "tbody.sortable" ).on( "click", "i.fa-trash-o", function() {
    $(this).parents('tr').fadeOut(100, function(){$(this).remove();});
});
/*
$('a.add').on('click', function(){
    var tableid = $(this).data('table');
    $('table#'+tableid+' tbody tr:first').clone().appendTo('table#'+tableid+' tbody').show();
    return false;
});
$('a.addb').on('click', function(){
    var tableid = $(this).data('table');
    $('table#'+tableid+' tbody tr:first').clone().appendTo('table#table_f tbody').show();
    return false;
});
*/
$('#addme').click(function($q) {
    $('table#table_f2 tbody tr:first').clone().appendTo('table#table_f tbody').show();
    $('table#table_f tbody tr:last').find('input:eq(0)').val($('#table_x').find('select:eq(0)').val());
    $('table#table_f tbody tr:last').find('input:eq(1)').val($('#table_x').find('select:eq(1)').val());
    $('table#table_f tbody tr:last').find('input:eq(2)').val($('#table_x').find('select:eq(2)').val());
    $('table#table_f tbody tr:last').find('input:eq(3)').val($('#table_x').find('select:eq(3)').val());
});
TXT;

$this->registerJs($js);
$this->registerJsFile('https://code.jquery.com/ui/1.12.0/jquery-ui.js', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.6/select2-bootstrap.min.css');
