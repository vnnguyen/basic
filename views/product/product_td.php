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
    ['name'=>'Panduranga'],
    ['name'=>'Phnom Penh'],
    ['name'=>'Sapa, Bac Ha et alentours'],
    ['name'=>'Siem Reap'],
    ['name'=>'Sud du Cambodge'],
    ['name'=>'Tonle Sap'],
    ['name'=>'Nord Laos'],
    ['name'=>'Sud Laos'],
    ['name'=>'Sud Vietnam'],
];

$listO = [
    ['name'=>'-', 'region'=>'( Blank / Để trống )'],
    ['name'=>'La visite classique de la ville', 'region'=>'Hanoi'],
    ['name'=>'La visite classique de la ville et une découverte de l’artisanat au village de Ba Trang', 'region'=>'Hanoi'],
    ['name'=>'Un séjour au village de Pom Coong avec nuit chez l’habitant, spectacle de danse Thai et balades à pied ou à vélo dans la vallée', 'region'=>'Mai Chau'],
    ['name'=>'Un ou 2 jours incluant Hoa Lu et Trang An avec balade en sampan', 'region'=>'Baie d’Along Terrestre'],
    ['name'=>'Une croisière dans la Baie d’Along avec visite de la grotte de la Surprise', 'region'=>'Baie d’Along'],
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
    ['name'=>'Balade en bateau jusqu\'aux villages flottants touristiques', 'region'=>'Tonle Sap'],
    ['name'=>'Un court passage pour les visites des tours des Chams', 'region'=>'Panduranga'],
    ['name'=>'Croisiere en bateau à Siem Reap ou à Phnom Penh', 'region'=>'Tonle Sap'],
    ['name'=>'Circuit avec des courts passages des bourgs et de petites croisières sur les rivières', 'region'=>'Nord Laos'],
    ['name'=>'Découverte des 4000 îles en pirogue', 'region'=>'Sud Laos'],
    ['name'=>'Un parcours entre Saigon et le delta du Mékong', 'region'=>'Sud Vietnam'],
];

$listA = [
    ['name'=>'-', 'region'=>'( Blank / Để trống )'],

    ['region'=>'Hoang Su Phi', 'name'=>'Une expédition pour partager le quotidien des ethnies La Chi, Dao, … dans le cadre enchanteur des plus belles rizières en terrasse du Vietnam.'],
    ['region'=>'Seo Lung', 'name'=>'Une expérience inédite et hors des sentiers touristiques dans la province de Ha Giang, entre randonnées sur les flancs du Phu Ta Ca et immersion émouvante dans le quotidien des Hmongs Blancs, derniers cultivateurs de la cardamome.'],
    ['region'=>'Pu Luong', 'name'=>'Un séjour dans la réserve naturelle de Pu Luong, aux fabuleux paysages de rizières en terrasse, de cascades et de pitons karstiques. Balades à travers les villages des ethnies Thaï blanc et Muong.'],
    ['region'=>'Hua Tat', 'name'=>'Un soutien à la population des Hmong bariolés dans un village posé sur le versant d’une montagne. Un charmant couple Hmong vous accueille dans le cadre d’un projet de développement du tourisme communautaire.'],
    ['region'=>'Baie d\'Along Terrestre', 'name'=>'Partez à la découverte de ce havre de paix à pied, à vélo ou en barque. Animations pour petits ou grands vous y attendant !'],
    ['region'=>'Panduranga', 'name'=>'Une exploration de la région méconnue du Panduranga, territoire des cham, une civilisation raffinée et de culte hindousite. Une région sublime, à la beauté sauvage, abritant le magnifique parc national de Nui Chua et des plages aux dunes de sable à perte de vue.'],
    ['region'=>'Hoi An', 'name'=>'Rencontre avec un artisan d’Hoi An, spécialiste de la confection des lanternes. Construction de votre propre lanterne en bambou, sous les conseils avisés du maître (pour petits et grands). Un moment agréable et instructif à ne pas rater !'],
    ['region'=>'Hue', 'name'=>'Rencontre avec un artisan de Hué, renommé dans tout le Vietnam et passé maître dans la fabrication de cerf volants.'],
    ['region'=>'Lang Co', 'name'=>'Jalousement gardées depuis plusieurs générations, les techniques de pêche et ostréicoles de la lagune de Lang Co vous seront dévoilées en exclusivité par une famille de pêcheurs que nous connaissons bien. Déjeuner de fruit de mer dans une maison sur pilotis dans un site naturel paradisiaque entre la mer et les montagnes.'],
    ['region'=>'Delta du Mekong', 'name'=>'Une immersion originale dans le Delta du Mékong, alliant découverte des méandres du Delta, balades à vélo, immersion dans un marché flottant et action solidaire. Une occasion d’offrir un peu de votre temps à une œuvre caritative locale en apportant soins et réconfort aux patients d’un dispensaire.'],
    ['region'=>'Phnom Penh', 'name'=>'Une découverte de l’île de Koh Dach traditionnel fier du tissage de la soie, réputé dans tout le pays pour la délicatesse et la variété de ses motifs. Balade à vélo et découverte de la vie locale.'],
    ['region'=>'Siem Reap', 'name'=>'Un moment exclusif et hors des grandes voies touristiques à Phnom Kulen, montagne sacrée des khmers, entre visites des temples en ruine du 9ème siècle, la rivière des Milles Lingas et une belle immersion dans un village khmer traditionnel.'],
    ['region'=>'Koh Rong Saloem', 'name'=>'Séjour les pieds dans l’eau sur l’île de Koh Rong Saloem, un paradis secret sortant de la jungle préservée.'],
    ['region'=>'Kampot', 'name'=>'Une découverte des activités séculaires de la côte cambodgienne.  Ce séjour riche d’échange avec les pêcheurs cham,  les producteurs des salines et des proivirères vous dévoilera une autre facette du Cambodge.'],
    ['region'=>'Boping', 'name'=>'Village de Boping  - un endroit exclusivement proposé par Amica pour une belle expérience chez l’habitant, entre ciel et eau.'],
    ['region'=>'Phatsanday', 'name'=>'Séjour dans un village semi lacustre pour une découverte hors des sentiers battus et exclusives à l’embouchure d’un affluent du Tonlé Sap. Nuit chez l’habitant dans le village flottant et initiation à la pêche locale et aux travaux en rizière.'],

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
    ['name'=>'Une croisière dans la Baie de Bai Tu Long, coin plus reculé où il y a moins de touristes, incluant toujours visite d’une grotte', 'region'=>'Baie d’Along'],
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

    ['name'=>'Les visites incontournables couplées par une balade à pied de l’autre côté de la rivière hors des sentiers battus au plus près de la population locale.', 'region'=>'Luang Prabang et sa région'],
    ['name'=>'Tous les secrets du bamboo vous seront dévoilés dans cette découverte approfondie au coeur de la culture laotienne. Une approche tout à la fois ludique et interactive, responsable et repectueuse de l’environnement.', 'region'=>'Luang Prabang et sa région'],
    ['name'=>'Une rencontre exceptionnelle avec les élephants, dans le respect de leur environnement et dans une approche eco-responsable pour la sauvregarde de l’espèce et se son milieu naturel.', 'region'=>'Luang Prabang et sa région'],

    ['name'=>'Matinée interactive au milieu des rizières pour comprendre la culture du riz', 'region'=>'Luang Prabang'],
    ['name'=>'Un séjour balnéaire à Sihanoukville, sur une plage plus éloignée, celle d’Otres', 'region'=>'Sihanoukville'],
    ['name'=>'Un séjour balnéaire à Kep, station plus petite que Sihanoukville mais beaucoup plus charmante', 'region'=>'Kep'],

    ['region'=>'Rando dans le Sud Phou Sang (Ban Senkham, Hon Luc, ...)', 'name'=>'Une expérience à ne pas manquer en territoire Akhas Luma, Lao et Khmu pour une immersion dans la nature sauvage et préservée du sud Phou Sang. Découverte des villages presque hors du temps, balade à pied et nuits chez l’habitant vous feront cotoyer ces ethnies si particulières.'],
    ['region'=>'Centre de conservation des éléphants à Sayabury', 'name'=>'Une demarche 100% éco responsable pour séjourner au centre de conservation des éléphants, dans un environnement familial respectueux du bien être de ces animaux menacés et fragiles.'],
    ['region'=>'Balade à vélo Khong – Khone', 'name'=>'Séjour détente à Si Phan Don, les 4000 îles du Mékong. Vous partirez en balade à vélo entre les îles, à la rencontre des habitant et vous ferez une belle croisière à la découverte des endroits préservés du sud Laos.'],
    ['region'=>'Ile de Daeng', 'name'=>'A proximité immediate de Champasak, la grande île de Don Daeng vous acceuille pour une immersion dans la vie locale au bord du Mékong. Balade à vélo le long des rizières, traversée de village et nuit chez l’habitant sont au programme de cette belle excursion.'],
    ['region'=>'Ta Lai, parc national de Cat Tien', 'name'=>'Au coeur du parc national de Cat Tien, vous avez rendez-vous avec la nature et toutes les activités qui vous la dévoileront : kayak, excursion à vélo à travers les villages de Chau Ma et Stieng, les plantations de cacao et de café, randonnée à pied dans le parc National, etc'],
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
    ['name'=>'Envie d’être loin du tourisme de masse'],
    ['name'=>'Découverte des sites incontournables en évitant la foule touristique'],
];

$valuesD = array_map(function($x){ return $x['name']; }, $listD);
$valuesO = array_map(function($x){ return $x['name']; }, $listO);
$valuesA = array_map(function($x){ return $x['name']; }, $listA);
$valuesV = array_map(function($x){ return $x['name']; }, $listV);

//\fCore::expose($valuesD); exit;

?>
<style>
.ui-state-highlight {background-color:#fffff3;}
</style>
<div class="col-md-12">
    <div class="alert alert-info">
        CHÚ Ý: Để trống cột <code>Destination</code> thì nội dung cột <code>Ce qu'Amica vous conseille</code> sẽ được ghép với dòng trên (tức là 2 dòng ghép làm 1).
        <br>NOTE: Leave <code>Destination</code> blank to merge the content of <code>Ce qu'Amica vous conseille</code> column with previous line.
    </div>
    <div class="card">
        <div class="table-responsive">
            <form method="post" action="">
                <table id="table_f" class="table table-narrow mb-20">
                    <thead>
                        <tr>
                            <th width="20"></th>
                            <th width="20%"><?= Yii::t('si_tour_summary', 'Votre voyage, votre histoire, votre envies') ?></th>
                            <th width="15%"><?= Yii::t('si_tour_summary', 'Destination') ?></th>
                            <th width=""><?= Yii::t('si_tour_summary', 'Ce qu\'Amica vous conseille') ?></th>
                            <th width="25%"><?= Yii::t('si_tour_summary', 'Ce que l\'on vous propose souvent') ?></th>
                            <th width="20"></th>
                        </tr>
                    </thead>
                    <tbody class="sortable" style="overflow:auto;">
                        <?php
                        for ($i = 0; $i < count($metaData); $i ++) {
                            ?>
                        <tr>
                            <td style="vertical-align:top"><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                            <td style="vertical-align:top"><?= Html::textArea('field_v[]', $metaData[$i][3], ['class'=>'form-control no-border']) ?></td>
                            <td style="vertical-align:top"><?= Html::textArea('field_d[]', $metaData[$i][0], ['class'=>'form-control no-border']) ?></td>
                            <td style="vertical-align:top"><?= Html::textArea('field_a[]', $metaData[$i][2], ['class'=>'form-control no-border']) ?></td>
                            <td style="vertical-align:top"><?= Html::textArea('field_o[]', $metaData[$i][1], ['class'=>'form-control no-border']) ?></td>
                            <td style="vertical-align:top"><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="p-3">
                    <?= Html::submitButton(Yii::t('x', 'Save changes'), ['class'=>'btn btn-primary']) ?>
                    <?= Yii::t('x', 'or') ?>
                    <?= Html::a(Yii::t('x', 'Reset'), '/products/td/'.$theProduct['id'], ['class'=>'text-danger']) ?>
                    <?= Yii::t('x', 'or') ?>
                    <?= Html::a(Yii::t('x', 'Cancel'), '/products/r/'.$theProduct['id'], ['class'=>'text-danger']) ?>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white">
            <h6><span class="font-weight-bold text-uppercase"><?= Yii::t('x', 'Add new line') ?></span> Select and press + to add to above table</h6>
        </div>
        <div class="table-responsive">
            <table id="table_x" class="table table-narrow">
                <tbody>
                    <tr>
                        <td width="20%"><?= Html::dropdownList('field_v[]', '', ArrayHelper::map($listV, 'name', 'name'), ['class'=>'form-control no-border']) ?></td>
                        <td width="15%"><?= Html::dropdownList('field_d[]', '', ArrayHelper::map($listD, 'name', 'name'), ['class'=>'form-control no-border']) ?></td>
                        <td width=""><?= Html::dropdownList('field_a[]', '', ArrayHelper::map($listA, 'name', 'name', 'region'), ['class'=>'form-control no-border']) ?></td>
                        <td width="25%"><?= Html::dropdownList('field_o[]', '', ArrayHelper::map($listO, 'name', 'name', 'region'), ['class'=>'form-control no-border']) ?></td>
                        <td width="30"><button id="addme" class="btn btn-primary btn-block">+</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<table id="table_f2" class="table table-narrow">
    <tbody>
        <tr style="display:none;">
            <td style="vertical-align:top"><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
            <td style="vertical-align:top"><?= Html::textArea('field_v[]', '', ['class'=>'form-control no-border']) ?></td>
            <td style="vertical-align:top"><?= Html::textArea('field_d[]', '', ['class'=>'form-control no-border']) ?></td>
            <td style="vertical-align:top"><?= Html::textArea('field_a[]', '', ['class'=>'form-control no-border']) ?></td>
            <td style="vertical-align:top"><?= Html::textArea('field_o[]', '', ['class'=>'form-control no-border']) ?></td>
            <td style="vertical-align:top"><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
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
    $('table#table_f tbody tr:last').find('textarea:eq(0)').val($('#table_x').find('select:eq(0)').val());
    $('table#table_f tbody tr:last').find('textarea:eq(1)').val($('#table_x').find('select:eq(1)').val());
    $('table#table_f tbody tr:last').find('textarea:eq(2)').val($('#table_x').find('select:eq(2)').val());
    $('table#table_f tbody tr:last').find('textarea:eq(3)').val($('#table_x').find('select:eq(3)').val());
    $('#table_f textarea').autogrow({vertical: true, horizontal: false});
});

$('#table_f textarea').autogrow({vertical: true, horizontal: false});
TXT;

$this->registerJs($js);
$this->registerJsFile('https://code.jquery.com/ui/1.12.0/jquery-ui.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.ns-autogrow/1.1.6/jquery.ns-autogrow.min.js', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.6/select2-bootstrap.min.css');
