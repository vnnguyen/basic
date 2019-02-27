<?php
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

require_once(Yii::getAlias('@webroot').'/../textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

include('_products_inc.php');

Yii::$app->params['page_icon'] = 'map-o';
Yii::$app->params['body_class'] = 'bg-white sidebar-xs';

Yii::$app->params['page_breadcrumbs'][] = [$ctTypeList[$theProduct['offer_type']] ?? $theProduct['offer_type'], 'products?type='.$theProduct['offer_type']];
Yii::$app->params['page_breadcrumbs'][] = ['By '.$theProduct['createdBy']['name'], 'products?ub='.$theProduct['created_by']];
Yii::$app->params['page_breadcrumbs'][] = ['View'];

$dayIdList = explode(',', $theProduct['day_ids']);
if (!$dayIdList) {
    $dayIdList = [];
}

if ($theProduct['image'] == '') {
    $theProduct['image'] = 'devis_base_02_vietnam_classique.jpg';
}

?>
<script>
var product_id = <?= $theProduct['id'] ?>;
var time = '<?= date('Ymd-Hi', strtotime('+7 hours')) ?>';
var header_image = '<?= $theProduct['image'] ?>';
</script>
<style>
.moxie-shim.moxie-shim-html5 {top:0!important; left:0!important;}

td, th {vertical-align:top!important;}
.label.b2b {background-color:#c60;}
.label.b2c {background-color:#999;}
.label.priority {background-color:#660;}
.label.vespa {background-color:purple;}
.label.status.open {background-color:#369;}
.label.status.closed {background-color:#333;}
.label.status.onhold {background-color:#666;}
.label.status.pending {background-color:#666;}
.label.status.lost {background-color:#c66;}
.label.status.won {background-color:#393;}
.popover {max-width:500px;}
.table.table-summary td {background-color:#f0f0f0; border:1px solid #fff;}
.label.op {background-color:#369;}
</style>

<div class="col-md-4 order-12">
    <p>
        <a class="pull-right action-export-docx" href="#"><?= Yii::t('x', 'Export to Word/PDF') ?></a>
        <?php if ($theTour) { ?><span class="label op">OPERATING</span><?php } ?>
        &nbsp;
    </p>

    <?php
    $devisImage = [
        'banner'=>'https://my.amicatravel.com/assets/tools/docx/b2c/banner-images/rizieres-en-terrasse.jpg',
        'table'=>'https://my.amicatravel.com/assets/tools/docx/b2c/table-images/vietnam-ethnies02.jpg',
        'footer'=>'https://my.amicatravel.com/assets/tools/docx/b2c/footer-images/voyage-famille.jpg',
    ];
    ?>
    <div id="div-export" style="padding:10px; background-color:#f6f6f0; margin-bottom:20px; display:none;">
        <div class="row">
            <div class="col">
                <p><a href="#" class="action-select-img-cover"><?= Yii::t('x', 'Change cover image') ?></a><br><img id="img-cover" class="img-fluid img-thumbnail" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/cover-images/<?= $theProduct['image'] ?>" class="img-fluid"></p>
                <p><?= Yii::t('x', 'NOTE: Bạn thay ảnh ngày cho devis trong form sửa nội dung ngày.') ?></p>
            </div>
            <div class="col">
                <p><a href="#" class="action-select-img-table"><?= Yii::t('x', 'Change table image') ?></a><br><img id="img-table" class="img-select img-fluid img-thumbnail" width="100%" src="<?= $devisImage['table'] ?>"></p>
                <p><a href="#" class="action-select-img-banner"><?= Yii::t('x', 'Change banner image') ?></a><br><img id="img-banner" class="img-select img-fluid img-thumbnail" width="100%" src="<?= $devisImage['banner'] ?>"></p>
                <p><a href="#" class="action-select-img-footer"><?= Yii::t('x', 'Change footer image') ?></a><br><img id="img-footer" class="img-select img-fluid img-thumbnail" width="100%" src="<?= $devisImage['footer'] ?>"></p>
            </div>
        </div>
        <div class="row">
            <div class="col"><button id="action-docx" class="btn btn-default btn-block"><i class="fa fa-file-word-o"></i> Download DOCX</button></div>
            <div class="col"><button id="action-pdf" class="btn btn-default btn-block"><i class="fa fa-file-pdf-o"></i> Download PDF</button></div>
        </div>
        <div class="text-center"><a href="/products/print/<?= $theProduct['id'] ?>" target="_blank"><?= Yii::t('x', 'Text preview only') ?></a></div>
    </div>

    <table class="table table-xxs table-summary mb-2">
        <tbody>
            <? if ($theProduct['op_status'] == 'op') { ?>
            <tr>
                <td style="white-space:nowrap;"><strong><?= Yii::t('x', 'Operated as') ?>:</strong></td><td><?= Html::a($theProduct['op_code'].' - '.$theProduct['op_name'], '@web/tours/r/'.$theTour['id']) ?></td>
            </tr>
            <? } ?>
            <tr>
                <td><strong><?= Yii::t('x', 'Bookings') ?>:</strong></td>
                <td>
                    <? if (empty($theProduct['bookings'])) { ?>
                    <?= Yii::t('x', 'No bookings found.') ?>
                    <? } else { ?>
                        <? foreach ($theProduct['bookings'] as $booking) { ?>
                    <div>
                    <?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$booking['createdBy']['image'], ['style'=>'width:20px; height:20px']) ?>
                    <span class="label status <?= $booking['status'] ?>"><?= strtoupper($booking['status']) ?></span>
                    <? if ($booking['finish'] == 'canceled') { ?><span class="label label-warning">CXL</span><? } ?>
                    &middot;<?= $booking['pax'] ?> pax &middot; <?= number_format($booking['price']) ?> <?= $booking['currency'] ?>
                    <br><i class="fa fa-briefcase text-muted"></i> <?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?>
                    </div>
                        <? } // foreach booking ?>
                    <? } ?>
                    <? if (empty($theProduct['bookings']) || ($theProduct['offer_type'] == 'combined2016' && strtotime('now') < strtotime($theProduct['day_from']))) { ?>
                    <?= Html::a(Yii::t('x', 'Add new proposal'), '@web/bookings/c?product_id='.$theProduct['id']) ?>
                    <? } ?>
                </td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('x', 'Start date') ?>:</strong></td>
                <td><?= Yii::$app->formatter->asDate($theProduct['day_from'], 'php:j/n/Y (l)') ?> <?= Yii::t('x', '+{days} days', ['days'=>$theProduct['day_count']]) ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('x', 'Price') ?>:</strong></td>
                <td><?= number_format($theProduct['price'], 0) ?> <?= $theProduct['price_unit'] ?> / <?= $theProduct['price_for'] ?>
                    <br><span class="text-muted"><?= Yii::t('x', 'Validity') ?>: <?= date('j/n/Y', strtotime($theProduct['price_from'])) ?> - <?= date('j/n/Y', strtotime($theProduct['price_until'])) ?></span></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('x', 'About') ?>:</strong></td><td><?= $theProduct['about'] ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('x', 'Updated') ?>:</strong></td><td><?= $theProduct['updatedBy']['name'] ?> <span class="text-muted"><?= Yii::$app->formatter->asRelativeTime($theProduct['updated_at']) ?></span></td>
            </tr>
        </tbody>
    </table>

    <? include('_product_r__map.php') ?>

    <div class="section section-tags mb-2" id="section-tags">
        <div class="section-header section-body">
            <p><span class="font-weight-bold text-uppercase"><?= Yii::t('x', 'Tags') ?></span>
            <?
            if (!empty($theProduct['tags'])) {
                $tags = explode(',', $theProduct['tags']);
                $html = [];
                foreach ($tags as $tag) {
                    $html[] = '<i class="fa fa-fw fa-tag text-muted"></i>'.Html::a(trim($tag), '/products?name='.trim($tag));
                }
                echo implode(' ', $html);
            } else {
                echo '<span class="text-muted">', Yii::t('x', '(No tags)'), '</span>';
            }
            ?></p>
        </div>
    </div>

    <p><span class="font-weight-bold text-uppercase"><?= Yii::t('x', 'Private note') ?></span> <span class="text-muted"><?= Yii::t('x', 'Client will not see this') ?></span></p>
    <div class="mb-1em"><?= Markdown::process($theProduct['summary']) ?></div>
</div>
    <?
    $cnt = 0;
    $devisTableData = [];
    if (!empty($metaData)) {
        foreach ($metaData as $line) {
            if (in_array($line[0], ['-', '']) && !empty($devisTableData)) {
                $devisTableData[$cnt - 1][2] .= '<br>'.$line[2];
            } else {
                $devisTableData[$cnt] = [$line[0], $line[1], $line[2], $line[3]];
                $cnt ++;
            }
        }
    }
    ?>
<div class="col-md-8 order-1">
    <? include('_huan1.php'); ?>
    <? include('_huan2.php'); ?>
    <?php if ($theProduct['owner'] == 'at' && $theProduct['language'] == 'fr') { ?>
    <div class="section section-table-devis mb-2" id="table-devis">
        <div class="section-header">
            <?= Html::a('<i class="fa fa-edit"></i>', '#', ['class'=>'pull-right', 'id'=>'action-edit-compair']) ?>
            <p class="font-weight-bold text-uppercase"><?= Yii::t('x', 'Comparison table') ?></p>
        </div>
        <?php if (!empty($devisTableData)) { ?>
        <div class="section-body table-responsive">
            <table id="tbl-compair" class="table table-xxs table-bordered">
                <thead>
                    <tr>
                        <th>Votre voyage, votre histoire, votre envies</th>
                        <th>Destination</th>
                        <th>Ce qu'Amica vous conseille</th>
                        <th>Ce que l'on vous propose souvent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devisTableData as $line) { ?>
                    <tr>
                        <td><?= $line[0] ?></td>
                        <td><?= $line[1] ?></td>
                        <td><?= $line[2] ?></td>
                        <td><?= $line[3] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php
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
            ?>
            <table id="table_x" class="table table-narrow" style="display: none;">
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
        <div id="edit-tbl-compair-buttons" style="display:none; margin-top:8px">
            <table id="table_x" class="table table-narrow">
                <tbody>
                    <tr>
                        <td width="20%"><?= Html::dropdownList('field_v[]', '', ArrayHelper::map($listV, 'name', 'name'), ['class'=>'form-control no-border']) ?></td>
                        <td width="15%"><?= Html::dropdownList('field_d[]', '', ArrayHelper::map($listD, 'name', 'name'), ['class'=>'form-control no-border']) ?></td>
                        <td width=""><?= Html::dropdownList('field_a[]', '', ArrayHelper::map($listA, 'name', 'name', 'region'), ['class'=>'form-control no-border']) ?></td>
                        <td width="25%"><?= Html::dropdownList('field_o[]', '', ArrayHelper::map($listO, 'name', 'name', 'region'), ['class'=>'form-control no-border']) ?></td>
                        <td width="30"><button id="add-new-line" class="btn btn-primary btn-block">+</button></td>
                    </tr>
                </tbody>
            </table>
            <button class="action-save btn btn-primary"><?= Yii::t('app', 'Save changes') ?></button>
            <button class="action-cancel btn btn-default"><?= Yii::t('app', 'Cancel') ?></button>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <div class="section section-price mb-2" id="section-price">
        <div class="section-header">
            <? if (in_array(USER_ID, [34718, 1, $theProduct['created_by'], $theProduct['updated_by']])) { ?>
            <?= Html::a('<i class="fa fa-edit"></i>', '#', ['class'=>'pull-right', 'id'=>'action-edit-price']) ?>
            <? } ?>
            <p><span class="font-weight-bold text-uppercase"><?= Yii::t('x', 'Price table') ?></span></p>
        </div>
        <div class="section-body table-responsive">
            <table class="table table-bordered table-xxs" id="tbl-price">
            <?php // Gia va cac options
                $ctpx = $theProduct['prices'];
                $ctpx = explode(chr(10), $ctpx);
                $unitp = '';
                $minp = 99999;
                $maxp = 0;
                $optcnt = 0;
                foreach ($ctpx as $ctp) {
                    if (trim($ctp) != '') {
                        $line = explode(':', $ctp);
                        if (isset($line[1]) && strpos($line[0], 'OPTION') !== false) {
                            $optcnt ++;
                ?>
                <tr class="info">
                    <th colspan="3">Option <?= $optcnt ?> : <?= trim($line[1]) ?></th>
                </tr>
                <tr>
                    <th>Ville</th>
                    <th>Hébergement</th>
                    <th>Categorie chambre</th>
                </tr>
                <?
                        }
                        if (isset($line[1]) && substr(trim($line[0]), 0, 1) == '+') {
                            for ($i = 1; $i < 4; $i ++) {
                                if (!isset($line[$i])) {
                                    $line[$i] = '';
                                }
                            }
                            $line[0] = trim(substr($line[0], 1));
                            if ($line[0] == 'Ville' && $line[1] == 'Hébergement' && $line[2] == 'Categorie chambre') {
                                continue;
                            }
                            if (trim($line[3]) != '') {
                                if (isset($line[4]) && in_array(trim($line[3]), ['http', 'https'])) {
                                    $line[3] = trim($line[3]).':'.trim($line[4]);
                                } else {
                                    $line[3] = 'http://'.trim($line[3]);
                                }
                            }
                ?>
                <tr>
                    <td><?= $line[0] ?></td>
                    <td><?= trim($line[3]) == '' ? trim($line[1]) : Html::a(trim($line[1]), $line[3], ['target'=>'_blank', 'title'=>trim($line[3])]) ?></td>
                    <td><?= $line[2] ?></td>
                </tr>
                <?
                        }
                        if (isset($line[1]) && substr(trim($line[0]), 0, 1) == '-') {
                            for ($i = 1; $i < 3; $i ++) {
                                if (!isset($line[$i])) {
                                    $line[$i] = '';
                                }
                            }
                            $line[0] = trim(substr($line[0], 1));
                            $line[1] = (int)trim($line[1]);
                            if ($minp > $line[1]) {
                                $minp = $line[1];
                            }
                            if ($maxp < $line[1]) {
                                $maxp = $line[1];
                            }
                            $unitp = trim($line[2]);
                ?>
                <tr class="tr-price">
                    <td colspan="2" class="text-right name-price"><?= $line[0] ?> </td><td class="price"><strong><span class="span-price"><?= number_format($line[1]) ?> </span> <?= $theProduct['price_unit'] ?></strong></td>
                </tr>
                <?
                        }
                    }
                }
                if (empty($ctpx)) $minp = 0;
                if ($minp > $maxp) $minp = 0;
                ?>
            </table>
        </div>
        <div id ="summary-price" class="row mt-2 d-none" >
                <div class="col-md-2"><div class="form-group field-product-price">
                    <label class="control-label" for="product-price">Price</label>
                    <input type="text" id="product-price" class="form-control" name="Product[price]" value="0">

                    <div class="help-block"></div>
                    </div></div>
                    <div class="col-md-2"><div class="form-group field-product-price_unit">
                    <label class="control-label" for="product-price_unit">&nbsp;</label>
                    <select id="product-price_unit" class="form-control" name="Product[price_unit]" aria-required="true" aria-invalid="false">
                        <option value="EUR" selected="">EUR</option>
                        <option value="USD">USD</option>
                        <option value="VND">VND</option>
                    </select>
                    </div></div>
                                    <div class="col-md-2"><div class="form-group field-product-price_for">
                    <label class="control-label" for="product-price_for">Price For</label>
                    <select id="product-price_for" class="form-control" name="Product[price_for]" aria-required="true" aria-invalid="false">
                    <option value="personne" selected="">personne</option>
                    <option value="groupe">groupe</option>
                    </select>

                    <div class="help-block"></div>
                    </div></div>
                                    <div class="col-md-3"><div class="form-group field-product-price_from">
                    <label class="control-label" for="product-price_from">Validity from</label>
                    <input type="text" id="product-price_from" class="form-control" name="Product[price_from]" value="<?= date('Y-m-d')?>" aria-required="true" aria-invalid="false">

                    <div class="help-block"></div>
                    </div></div>
                                    <div class="col-md-3"><div class="form-group field-product-price_until">
                    <label class="control-label" for="product-price_until">Validity until</label>
                    <input type="text" id="product-price_until" class="form-control" name="Product[price_until]" value="<?=$theProduct['price_until']?>" aria-required="true" aria-invalid="false">

                    <div class="help-block"></div>
                    </div></div>
            </div>
        <div id="edit-tbl-price-buttons" style="display:none; margin-top:8px">
            <div class="btn-group pull-right">
                <button class="add-new-option btn btn-info">+<?= Yii::t('x', 'Option')?></button>
                <button class="add-new-hotel btn btn-info">+<?= Yii::t('x', 'Hotel')?></button>
                <button class="add-new-price btn btn-info">+<?= Yii::t('x', 'Price')?></button>
                <button class="add-new-price-summary btn btn-info">+<?= Yii::t('x', 'Price summary')?></button>
            </div>
            <button class="action-save btn btn-primary"><?= Yii::t('app', 'Save changes') ?></button>
            <button class="action-cancel btn btn-default"><?= Yii::t('app', 'Cancel') ?></button>
        </div>
    </div>

    <div class="section section-inex mb-2" id="section-inex">
        <div class="section-header">
            <p class="font-weight-bold text-uppercase"><?= Yii::t('x', 'Inclusions & Exclusions') ?></p>
        </div>
        <div class="section-body">
            <?= $parser->parse($theProduct['conditions']) ?>
        </div>
    </div>

    <div class="section section-conds mb-2" id="section-conds">
        <div class="section-header">
            <p class="font-weight-bold text-uppercase"><?= Yii::t('x', 'Other conditions') ?></p>
        </div>
        <div class="section-body">
            <?= $parser->parse($theProduct['others']) ?>
        </div>
    </div>
</div>

<!-- SELECT HEADER IMAGE MODAL -->
<?php
// https://www.amica-travel.com/upload/img-devis/footer/voyage-famille.jpg
$footerImageFiles = [
    ['Mondulkiri, Cambodia', 'mondulkiri-cambodge.jpg'],
    ['Mandalay, Myanmar', 'mandalay-birmanie.jpg'],
    ['Voyage famille', 'voyage-famille.jpg'],
    ['Boping, Cambodia', 'boping-cambodge.jpg'],
    ['Katu village, Laos', 'village-katu-laos.jpg'],
    ['Kep, Cambodia', 'kep-cambodge.jpg'],
    ['Khmer, Cambodia', 'khmer-cambodge.jpg'],
    ['Tonle Sap, Cambodia', 'tonle-sap-cambodge.jpg'],
    ['Luang Prabang, Laos', 'luang-prabang-laos.jpg'],
    ['Trekking in Vietnam', 'trekking-vietnam.jpg'],
    ['Luma Phongsaly, Laos', 'luma-phongsaly-laos.jpg'],
];

$dayImageList = [
    'Vietnam'=>[
        ['', 'vietnam/vieux-quartier-hoi-an-vietnam.jpg'],
        ['', 'vietnam/hoi-an-vietnam.jpg'],
        ['', 'vietnam/hanoi-vietnam.jpg'],
        ['', 'vietnam/voyage-famille.jpg'],
        ['', 'vietnam/baie-dalong-2.jpg'],
        ['', 'vietnam/baie-dalong-vietnam.jpg'],
        ['', 'vietnam/sud-vietnam.jpg'],
        ['', 'vietnam/hue-vietnam.jpg'],
        ['', 'vietnam/saigon-vietnam.jpg'],
        ['', 'vietnam/delta-du-mekong-vietnam.jpg'],
        ['', 'vietnam/enfants-centre-vietnam.jpg'],
        ['', 'vietnam/plage-phu-quoc-vietnam.jpg'],
        ['', 'vietnam/ninh-binh-vietnam.jpg'],
        ['', 'vietnam/nord-vietnam-3.jpg'],
        ['', 'vietnam/mnong-daknong-vietnam.jpg'],
        ['', 'vietnam/plage-hoi-an-vietnam.jpg'],
        ['', 'vietnam/centre-vietnam.jpg'],
        ['', 'vietnam/cathedrale-saigon-vietnam.jpg'],
        ['', 'vietnam/nord-vietnam.jpg'],
        ['', 'vietnam/lolo-vietnam.jpg'],
        ['', 'vietnam/nord-vietnam-2.jpg'],
        ['', 'vietnam/nha-trang-vietnam.jpg'],
        ['', 'vietnam/rizieres-en-terrasse-vietnam.jpg'],
        ['', 'vietnam/ha-giang-vietnam.jpg'],
    ],
    'Laos'=>[
        ['', 'laos/ethnie-opa-laos.jpg'],
        ['', 'laos/jeunes-novices-laos.jpg'],
        ['', 'laos/xekong-rive-laos.jpg'],
        ['', 'laos/nong-khiaw-laos.jpg'],
        ['', 'laos/sud-laos.jpg'],
        ['', 'laos/village-katu-laos.jpg'],
        ['', 'laos/plaine-des-jarres-laos.jpg'],
        ['', 'laos/centre-laos.jpg'],
        ['', 'laos/si-phan-don-laos.jpg'],
        ['', 'laos/enfants-laos.jpg'],
        ['', 'laos/pagode-laos.jpg'],
        ['', 'laos/vat-phou-laos.jpg'],
        ['', 'laos/femme-opa-laos.jpg'],
        ['Rando Sud Laos', 'laos/rando-sud-laos.jpg'],
    ],
    'Cambodia'=>[
        ['', 'cambodge/mondulkiri-cambodge.jpg'],
        ['', 'cambodge/temple-angkor-cambodge.jpg'],
        ['', 'cambodge/ethnie-mondulkiri-cambodge.jpg'],
        ['', 'cambodge/bayon-cambodge.jpg'],
        ['', 'cambodge/phat-san-day-cambodge.jpg'],
        ['', 'cambodge/stung-sen-ratanakiri.jpg'],
        ['', 'cambodge/bokor-cambodge.jpg'],
        ['', 'cambodge/boping-cambodge.jpg'],
        ['', 'cambodge/preah-vihear-cambodge.jpg'],
        ['', 'cambodge/tuk-tuk-cambodge.jpg'],
        ['', 'cambodge/riviere-boping-cambodge.jpg'],
        ['', 'cambodge/tonle-sap-cambodge.jpg'],
        ['', 'cambodge/village-flottant-cambodge.jpg'],
        ['', 'cambodge/battambang-cambodge.jpg'],
        ['', 'cambodge/plage-cambodge.jpg'],
        ['', 'cambodge/angkor-cambodge.jpg'],
        ['', 'cambodge/chemin-vers-chez-san-cambodge.jpg'],
        ['', 'cambodge/battambang-cambodge.jpg'],
        ['', 'cambodge/enfants-cambodge.jpg'],
        ['', 'cambodge/phnom_penh_1.jpg'],
        ['', 'cambodge/phnom_penh_2.jpg'],
        ['', 'cambodge/phnom_penh_3.jpg'],
        ['', 'cambodge/kratie_1.jpg'],
        ['', 'cambodge/kratie_2.jpg'],
        ['', 'cambodge/kratie_3.jpg'],
        ['', 'cambodge/kratie_4.jpg'],
        ['', 'cambodge/kampot_1.jpg'],
        ['', 'cambodge/kampot_2.jpg'],
        ['', 'cambodge/kampot_3.jpg'],
        ['', 'cambodge/kampot_4.jpg'],
        ['Kep', 'cambodge/kep_1.jpg'],
        ['Kep', 'cambodge/kep_2.jpg'],
        ['Kep', 'cambodge/kep_3.jpg'],
        ['Kep', 'cambodge/kep_4.jpg'],
    ],
    'Myanmar'=>[
        ['', 'birmanie/mandalay-birmanie.jpg'],
        ['', 'cambodge/croisiere-tonle-sap-cambodge.jpg'],
        ['', 'birmanie/pagode-mandalay-birmanie.jpg'],
        ['', 'birmanie/bagan-birmanie.jpg'],
    ],
];

$tableImageFiles = [];

$tableImageFiles = [
    ['cambodge indochine', 'cambodge-indochine.jpg'],
    ['multipays classique', 'multipays-classique.jpg'],
    ['thailande classique', 'thailande-classique.jpg'],
    ['val multi pays', 'val-multi-pays.jpg'],
    ['val confins oublies', 'val-confins-oublies.jpg'],
    ['val initiation', 'val-initiation.jpg'],
    ['au dela des temples angkor', 'au-dela-des-temples-angkor.jpg'],
    ['dac lac vietnam', 'dac-lac-vietnam.jpg'],
    ['vietnam ethnies', 'vietnam-ethnies.jpg'],
    ['cambodge aventure3', 'cambodge-aventure3.jpg'],
    ['cham', 'cham.jpg'],
    ['vietnam mekong02', 'vietnam-mekong02.jpg'],
    ['laos classique', 'laos-classique.jpg'],
    ['cambodge classique', 'cambodge-classique.jpg'],
    ['vietnam balneaire', 'vietnam-balneaire.jpg'],
    ['indonesia', 'indonesia.jpg'],
    ['vietnam mekong03', 'vietnam-mekong03.jpg'],
    ['ninh binh vietnam', 'ninh-binh-vietnam.jpg'],
    ['temples angkor2', 'temples-angkor2.jpg'],
    ['laos aventure', 'laos-aventure.jpg'],
    ['vietnam classique', 'vietnam-classique.jpg'],
    ['vietnam mekong', 'vietnam-mekong.jpg'],
    ['vietnam ethnies02', 'vietnam-ethnies02.jpg'],
    ['cambodge autrement', 'cambodge-autrement.jpg'],
    ['val confin oublies', 'val-confin-oublies.jpg'],
    ['xekong river laos', 'xekong-river-laos.jpg'],
    ['lolo noir caobang', 'lolo-noir-caobang.jpg'],
    ['trek khan hoa', 'trek-khan-hoa.jpg'],
    ['cambodge aventure', 'cambodge-aventure.jpg'],
    ['multipays luxury', 'multipays-luxury.jpg'],
    ['cambodge aventure2', 'cambodge-aventure2.jpg'],
    ['vac cambodge autrement', 'vac-cambodge-autrement.jpg'],
    ['cambodge balneaire', 'cambodge-balneaire.jpg'],
    ['val immersion', 'val-immersion.jpg'],
    ['cambodge autrement2', 'cambodge-autrement2.jpg'],
    ['val aventure', 'val-aventure.jpg'],
    ['stung sen', 'stung-sen.jpg'],
    ['vietnam immersion', 'vietnam-immersion.jpg'],
    ['temples angkor', 'temples-angkor.jpg'],
    ['tam coc garden', 'tam-coc-garden.jpg'],
    ['vac au dela temples angkor', 'vac-au-dela-temples-angkor.jpg'],
    // 181001
    ['Preah Vihear', 'cambodge-preah-vihear.jpg'],
    ['Cambodia beach', 'cambodia_beach_1.jpg'],
    ['Cambodia beach', 'cambodia_beach_2.jpg'],
    ['Opa, Laos', 'Laos-Opa.jpg'],
    ['Phongsaly, Laos', 'Laos-Phongsaly.jpg'],
    ['Mekong 1', 'mekong_1.jpg'],
    ['Mekong 2', 'mekong_2.jpg'],
    ['Mekong 3', 'mekong_3.jpg'],
    ['Mekong 4', 'mekong_4.jpg'],
    ['Panduranga', 'panduranga.jpg'],
    ['Halong, Vietnam 1', 'vietnam_halong_1.jpg'],
    ['Halong, Vietnam 2', 'vietnam_halong_2.jpg'],
    ['Lake Lak, Vietnam', 'vietnam_lak_lake.jpg'],
    ['Lan Ha Bay', 'vietnam_lanha.jpg'],
    ['Vietnam forest', 'Vietnam-Foret.jpg'],
];

$coverImageList = [
    'Vietnam'=>[
        'vn-1'=>['Classique', 'devis_base_02_vietnam_classique.jpg', 'x'],
        'vn-2'=>['Immersion', 'devis_base_03_vietnam_immersion.jpg', 'x'],
        'vn-3'=>['Ethnies', 'devis_base_04_vietnam_ethnies.jpg', 'x'],
        'vn-4'=>['Balneaire', 'devis_base_06_vietnam_balneaire.jpg', 'x'],
        'vn-5'=>['Mekong', 'devis_base_08_vietnam_mekong.jpg', 'x'],
        'vn-6'=>['Aventure', 'vietnam_aventure.jpg', 'x'],
        'vn-7'=>['Ethnies', 'vietnam_ethnies_2.jpg', 'x'],
        'vn-8'=>['Ethnies', 'vietnam_ethnies_3.jpg', 'x'],
        'vn-9'=>['Halong', 'vietnam_halong.jpg', 'x'],
        'vn-10'=>['Immersion', 'vietnam_immersion_2.jpg', 'x'],
        'vn-11'=>['Mekong', 'vietnam_mekong_2.jpg', 'x'],
    ],
    'Laos'=>[
        'la-1'=>['Classique', 'devis_base_05_laos_classique.jpg', 'x'],
        'la-2'=>['Aventure', 'devis_base_07_laos_aventure.jpg', 'x'],
        'la-3'=>['Aventure', 'laos_aventure_2.jpg', 'x'],
        'la-4'=>['Aventure', 'laos_aventure_3.jpg', 'x'],
        'la-5'=>['Classique', 'laos_classique_2.jpg', 'x'],
        'la-6'=>['Immersion', 'laos_immersion.jpg', 'x'],
    ],
    'Cambodia'=>[
        'kh-1'=>['Classique', 'devis_base_09_cambodge_classique.jpg', 'x'],
        'kh-2'=>['Aventure', 'devis_base_10_cambodge_aventure.jpg', 'x'],
        'kh-3'=>['Aventure 2', 'devis_base_11_cambodge_balneaire.jpg', 'x'],
        'kh-4'=>['Aventure', 'cambodge_aventure.jpg', 'x'],
        'kh-5'=>['Balenaire', 'cambodge_balneaire.jpg', 'x'],
        'kh-6'=>['Classique', 'cambodge_classique_2.jpg', 'x'],
        'kh-7'=>['Classique', 'cambodge_classique_3.jpg', 'x'],
        'kh-8'=>['Classique', 'cambodge_classique_4.jpg', 'x'],
        'kh-9'=>['Immersion', 'cambodge_immersion_1.jpg', 'x'],
        'kh-10'=>['Immersion', 'cambodge_immersion_2.jpg', 'x'],
        'kh-11'=>['Immersion', 'cambodge_immersion_3.jpg', 'x'],
    ],
    'Myanmar'=>[
        'mm-1'=>['Aventure', 'devis_base_16_birmanie_classique.jpg', 'x'],
        'mm-2'=>['Classique', 'devis_base_17_birmanie_aventure.jpg', 'x'],
        'mm-3'=>['Classique', 'birmanie_classique_2.jpg', 'x'],
        'mm-4'=>['Classique', 'birmanie_classique_3.jpg', 'x'],
    ],
    'Indonesia'=>[
        'id-1'=>['Indonesia', 'devis_base_01_indonesie.jpg', 'x'],
    ],
    'Indochina'=>[
        'xx-1'=>['Classique', 'devis_base_12_multipays_classique.jpg', 'x'],
        'xx-2'=>['Aventure', 'devis_base_13_multipays_aventure.jpg', 'x'],
        'xx-3'=>['Luxury', 'devis_base_14_multipays_luxury.jpg', 'x'],
    ],
];
?>
<div class="modal fade modal-primary" id="modal-select-image" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-pink text-semibold"><?= Yii::t('x', 'Select image') ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="img-list" id="img-list-cover">
                    <div class="row cover-image-list">
                        <?php
                        $currentCountry = '';
                        foreach ($coverImageList as $country=>$img) {
                            if ($currentCountry != $country) { ?>
                        </div><div class="font-weight-bold clearfix"><?= $country ?></div><div class="row"><?
                                $currentCountry = $country;
                                $cnt = 0;
                            }
                            foreach ($img as $code => $data) {
                                $cnt ++; ?>
                        <div class="col-sm-2 text-center cover-image-list-item">
                            <div><img class="cursor-pointer img-fluid" title="<?= $data[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/cover-images/<?= $data[1] ?>"></div>
                            <div><?= $data[0] ?></div>
                        </div><?
                                if ($cnt == 6) {
                                    $cnt = 0; ?>
                        <div class="clearfix visible-sm-block visible-md-block visible-lg-block">&nbsp;</div><?
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="img-list" id="img-list-banner">
                    <div class="row banner-image-list">
                    <?php
                    $cnt = 0;
                    $bannerImageFiles = [];
                    $findFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@webroot').'/assets/tools/docx/b2c/banner-images');
                    foreach ($findFiles as $file) {
                        $bannerImageFiles[] = ['', substr(strrchr($file, '/'), 1)];
                    }
                    foreach ($bannerImageFiles as $img) {
                        $cnt ++; ?>
                    <div class="col-sm-4 col-md-3 col-lg-2 text-center banner-image-list-item mb-2">
                        <div><img class="cursor-pointer img-fluid" title="<?= $img[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/banner-images/<?= $img[1] ?>"></div>
                        <div class="small text-muted"><?= $img[0] ?></div>
                    </div><?php
                        if ($cnt == 6) {
                            $cnt = 0; ?>
                    <!-- div class="clearfix visible-sm-block visible-md-block visible-lg-block">&nbsp;</div --><?
                        }
                    }
                    ?>
                    </div>
                </div>
                <div class="img-list" id="img-list-table">
                    <div class="row table-image-list">
                    <?php
                    $cnt = 0;
                    foreach ($tableImageFiles as $img) {
                        $cnt ++; ?>
                    <div class="col-sm-4 col-md-3 col-lg-2 text-center table-image-list-item mb-2">
                        <div><img class="cursor-pointer img-fluid" title="<?= $img[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/table-images/<?= $img[1] ?>"></div>
                        <div class="small text-muted"><?= $img[0] ?></div>
                    </div><?php
                        if ($cnt == 6) {
                            $cnt = 0; ?>
                    <!-- div class="clearfix d-none d-sm-block d-md-block d-lg-block">&nbsp;</div --><?
                        }
                    }
                    ?>
                    </div>
                </div>
                <div class="img-list" id="img-list-footer">
                    <div class="row footer-image-list">
                        <?php
                        $cnt = 0;
                        foreach ($footerImageFiles as $img) {
                            $cnt ++; ?>
                        <div class="col-sm-4 col-md-3 col-lg-2 text-center footer-image-list-item mb-2">
                            <div><img class="cursor-pointer img-fluid" title="<?= $img[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/footer-images/<?= $img[1] ?>"></div>
                            <div class="small text-muted"><?= $img[0] ?></div>
                        </div><?
                            if ($cnt == 6) {
                                $cnt = 0; ?>
                        <!-- div class="clearfix visible-sm-block visible-md-block visible-lg-block">&nbsp;</div --><?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?
$js = <<<'TXT'
$('.action-export-docx').on('click', function(e){
    e.preventDefault()
    $('#div-export').toggle()
})

// Open cover image modal
$('.action-select-img-cover').on('click', function(e){
    e.preventDefault()
    $('.img-list').hide()
    $('#img-list-cover').show()
    $('#modal-select-image').modal('show');
})

$('.action-select-img-banner').on('click', function(e){
    e.preventDefault()
    $('.img-list').hide();
    $('#img-list-banner').show();
    $('#modal-select-image').modal('show');
})
$('.action-select-img-table').on('click', function(e){
    e.preventDefault()
    $('.img-list').hide();
    $('#img-list-table').show();
    $('#modal-select-image').modal('show');
})
$('.action-select-img-footer').on('click', function(e){
    e.preventDefault()
    $('.img-list').hide();
    $('#img-list-footer').show();
    $('#modal-select-image').modal('show');
})

$('#action-pdf').on('click', function(e){
    e.preventDefault()

    $('#div-export').hide()

    var table_image = $('#img-table').attr('src')
    var banner_image = $('#img-banner').attr('src')
    var footer_image = $('#img-footer').attr('src')

    table_image = table_image.substr(table_image.lastIndexOf('/') + 1)
    banner_image = banner_image.substr(banner_image.lastIndexOf('/') + 1)
    footer_image = footer_image.substr(footer_image.lastIndexOf('/') + 1)

    var url = '/products/print/' + product_id + '?output=pdf&xh&banner_image=' + banner_image+ '&table_image=' + table_image + '&footer_image=' + footer_image

    var w = window.open(url,'_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=600,left=300,top=200');
    this.target = '_blank';

})

$('#action-docx').on('click', function(e){
    e.preventDefault()

    $(this).addClass('disabled').text('Please wait...')
    var jqxhr = $.ajax({
        url: '/products/print/' + product_id + '?output=docx&xh',
        type: 'post',
        data: {
            banner_image: $('#img-banner').attr('src'),
            table_image: $('#img-table').attr('src'),
            footer_image: $('#img-footer').attr('src'),
        },
        dataType: 'json'
    }).
    done(function(data) {
        // console.log(data.file_name)
        location.href = '/' + data.file_name
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
    .always(function(data) {
        $('#action-docx').removeClass('disabled').html('<i class="fa fa-file-word-o"></i> Download DOCX')
    })
})
// Select cover image
$('.cover-image-list-item img.img-fluid').on('click', function(){
    var src = $(this).attr('src');
    cover_image = src.replace('/timthumb.php?w=250&src=/assets/tools/docx/b2c/cover-images/', '')
    var jqxhr = $.ajax({
        url: '/products/ajax?xh',
        type: 'post',
        data: {
            action: 'save_cover_image',
            cover_image: cover_image,
            product_id: product_id,
        },
        dataType: 'json'
    }).
    done(function(data) {
        $('#img-cover').attr('src', src)
        $('#img-list-cover').hide();
        $('#modal-select-image').modal('hide');
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
    .always(function(data) {
    })
})

// Select banner image
$('.banner-image-list-item img.img-fluid').on('click', function(){
    var src = $(this).attr('src')
    banner_image = src.replace('/timthumb.php?w=250&src=/assets/tools/docx/b2c/banner-images/', '');

    $('#img-banner').attr('src', src)
    $('#img-list-banner').hide();
    $('#modal-select-image').modal('hide');
})

// Select table image
$('.table-image-list-item img.img-fluid').on('click', function(){
    var src = $(this).attr('src')
    table_image = src.replace('/timthumb.php?w=250&src=/assets/tools/docx/b2c/table-images/', '');

    $('#img-table').attr('src', src)
    $('#img-list-table').hide();
    $('#modal-select-image').modal('hide');
})

// Select footer image
$('.footer-image-list-item img.img-fluid').on('click', function(){
    var src = $(this).attr('src');
    footer_image = src.replace('/timthumb.php?w=250&src=/assets/tools/docx/b2c/footer-images/', '')

    $('#img-footer').attr('src', src)
    $('#img-list-footer').hide();
    $('#modal-select-image').modal('hide');
})

TXT;
$this->registerJs($js);
// // Edit compair and price
$js = <<<'TXT'
var orig_text = '';
var orig_text_2 = '';
// Edit compair
var td_sort = '<td width="32" class="text-nowrap"><i title="Sort" class="sort-row fa fa-bars cursor-move text-muted"></i><i style="margin-left:2px" class="fa fa-copy text-muted cursor-pointer" title="Copy"></i></td>';
var td_delete = '<td width="16"><i title="Delete" class="rem-row fa fa-trash-o cursor-pointer text-danger"></i></td>';

$('#tbl-compair').on('click', 'i.fa-copy', function(){
    $(this).parent().parent().clone(true, true).appendTo($('#tbl-compair'))
});

$('#action-edit-compair').on('click', function(){
    orig_text = $('#tbl-compair').html()
    $(this).hide()
    $('#tbl-compair tbody').find('td, th').attr('contenteditable', 'true')
    $('#edit-tbl-compair-buttons').show()
    $('#tbl-compair tr').each(function(i){
        var tr = $(this)
        // tr.find('td.url').attr('data-autocomplete-spy', true)
        if (i == 0) {
            $('<td/>').attr('width', 16).prependTo(tr)
            $('<td/>').attr('width', 16).appendTo(tr)
        } else {
            tr.prepend(td_sort).append(td_delete)
        }
    })
    $('#tbl-compair tbody').sortable({
        handle: '.sort-row'
    });
    return false;
});
$('#edit-tbl-compair-buttons .action-cancel').on('click', function(){
    $('#tbl-compair').html(orig_text)
    $('#edit-tbl-compair-buttons').hide()
    $('#action-edit-compair').show()
});
$(document).on('click', '#tbl-compair>tbody>tr>td>i.fa-trash-o', function(){
    $(this).parents('tr').remove()
});
var arr_fields = [];
$('td select').on('change', function(){
    var index = $(this).closest('td').index();
    arr_fields[index] = $(this).val();
});
$('#edit-tbl-compair-buttons #add-new-line').on('click', function(){
    $('<tr/>').append(td_sort)
            .append(
                '<td contenteditable="true">' + arr_fields[0] + '</td>' +
                '<td contenteditable="true">' + arr_fields[1] + '</td>' +
                '<td  contenteditable="true">' + arr_fields[2] + '</td>' +
                '<td contenteditable="true">' + arr_fields[3] + '</td>'
            )
            .append(td_delete)
            .appendTo($('#tbl-compair tbody'));
    $('td select').val('-');
    arr_fields = [];
});

$('#edit-tbl-compair-buttons .action-save').on('click', function(){
    var arr_row_compair = [];
    $('#tbl-compair tbody tr').each(function(i){
        var arr_column_compair = [];
        $(this).find('td').each(function(j){
            if(j == 0 || j == 5) return;

            arr_column_compair.push($(this).text());
        })
        arr_row_compair[i] = arr_column_compair.join('|');
    });
    $.ajax({
        url: '/product/ajax',
        type: 'post',
        data: {
            action: 'text_compair',
            product_id: product_id,
            text: arr_row_compair
        },
        // dataType: 'json'
    }).
    done(function(data) {
        console.log(data);
        $('#tbl-compair tr').each(function(){
            $(this).find('td:first, td:last').remove()
            $(this).find('td').attr('contenteditable', 'false')
        })
        $('#tbl-compair-text').attr('contenteditable', 'false')
        $('#edit-tbl-compair-buttons').hide()
        $('#action-edit-compair').show()
        console.log('save done!');
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    });
});


//price
$('#tbl-price').on('click', 'i.fa-copy', function(){
    $(this).parent().parent().clone(true, true).appendTo($('#tbl-price'))
});

$('#action-edit-price').on('click', function(){
    orig_text = $('#tbl-price').html();
    $(this).hide();
    $('#tbl-price tbody').find('td, th').attr('contenteditable', 'true')
    $('#edit-tbl-price-buttons').show();
    $('#tbl-price tr').each(function(i){
        var tr = $(this)
        // tr.find('td.url').attr('data-autocomplete-spy', true)
        if (i == 0) {
            $('<td/>').attr('width', 16).prependTo(tr)
            $('<td/>').attr('width', 16).appendTo(tr)
        } else {
            tr.prepend(td_sort).append(td_delete)
        }
    })
    $('#tbl-price tbody').sortable({
        handle: '.sort-row'
    });
    return false;
});
$('#edit-tbl-price-buttons .action-cancel').on('click', function(){
    $('#tbl-price').html(orig_text)
    $('#edit-tbl-price-buttons').hide()
    $('#action-edit-price').show();
    $('#summary-price').addClass('d-none').find('#product-price').val('');
});
$(document).on('click', '#tbl-price>tbody>tr>td>i.fa-trash-o', function(){
    $(this).parents('tr').remove()
});
$('#edit-tbl-price-buttons .add-new-hotel').on('click', function(){
    $('<tr/>').append(td_sort)
            .append('<td contenteditable="true"></td><td contenteditable="true"></td><td  contenteditable="true"></td>')
            .append(td_delete)
            .appendTo($('#tbl-price tbody'));
    $('#tbl-price tbody')
    .find('tr:last td:first').focus();
});
$('#edit-tbl-price-buttons .add-new-option').on('click', function(){
    // var th_sort = td_sort.replace('<td', '<th').replace('</td>', '</th>'),
    //     th_delete = td_delete.replace('<td', '<th').replace('</td>', '</th>');
    $('<tr/>').addClass('info')
            .append(td_sort)
            .append('<th contenteditable="true" colspan="3"></th>')
            .append(td_delete)
            .appendTo($('#tbl-price tbody'));
    $('#tbl-price tbody')
    .find('tr:last td:first').focus();
});
$('#edit-tbl-price-buttons .add-new-price').on('click', function(){
    $('<tr/>').addClass('tr-price').append(td_sort)
            .append('<td class="text-right price-name" contenteditable="true" colspan="2"></td><td class="price" contenteditable="true"></td>')
            .append(td_delete)
            .appendTo($('#tbl-price tbody'));
    $('#tbl-price tbody')
    .find('tr:last td:first').focus();
});
$('#edit-tbl-price-buttons .add-new-price-summary').on('click', function(){
    $('#summary-price').removeClass('d-none');
    $('#product-price').val('0').focus();
});

$('#edit-tbl-price-buttons .action-save').on('click', function(){
    var text = '';
    $('#tbl-price thead tr').each(function(i){
        text += '\n'
        text += '_head;|'
        $(this).find('[contenteditable="true"]').each(function(j){
            text += $(this).text().trim() + ';|'
        })
    })
    $('#tbl-price tbody tr').each(function(i){
        text += '\n'
        var hasClassPrice = false;
        var hasClassInfo = false;
        if($(this).hasClass('tr-price')) {
            hasClassPrice = true;
            text += '- '
        } else if ( ! $(this).hasClass('info') ) {
            text += '+ '
        } else {
            hasClassInfo = true;
        }
        $(this).find('[contenteditable="true"]').each(function(j){
            var td_text = $(this).text();

            if (hasClassInfo && td_text.toLowerCase().indexOf('option') != -1 ) {
                td_text = td_text.replace('Option', 'OPTION').replace('option', 'OPTION');
            }
            if (hasClassPrice) {
                td_text = td_text.replace('EUR', '').replace(',', '');
            }
            text += td_text.trim() + ':'
        })
    })
    // console.log(text);return false;
    // text += '\n' + 'text_;|' + $('#tbl-price-text').text();
    var price_summary = {};
    if ($('#product-price').val() > 0) {
        price_summary.price = $('#product-price').val();
        price_summary.unit = $('#product-price_unit').val();
        price_summary.for = $('#product-price_for').val();
        price_summary.from = $('#product-price_from').val();
        price_summary.until = $('#product-price_until').val();
    }
    // console.log(price_summary);return false;
    $.ajax({
        url: '/product/ajax',
        type: 'post',
        data: {
            action: 'text_price',
            product_id: product_id,
            text: text,
            price_summary: price_summary
        },
        // dataType: 'json'
    }).
    done(function(data) {
        console.log(data);
        $('#tbl-price tr').each(function(){
            $(this).find('td:first, td:last').remove()
            $(this).find('td').attr('contenteditable', 'false')
        })
        $('#tbl-price-text').attr('contenteditable', 'false')
        $('#edit-tbl-price-buttons').hide()
        $('#action-edit-price').show()
        console.log('save done!');
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    });
});
$('#product-day_from, #product-price_from, #product-price_until').datepicker({
    weekStart: 1,
    format: 'yyyy-mm-dd',
    showDropdowns:true,
    autoclose: true,
    todayHighlight: true,
    todayBtn: "linked",
    clearBtn: true,
});
TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.34.0/handsontable.full.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/css/bootstrap-datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);