<?
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

// require_once('/var/www/vendor/textile/php-textile/Parser.php');
// $parser = new \Netcarver\Textile\Parser();

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

<div class="col-md-4 col-md-push-8">
    <p>
        <a class="pull-right action-export-docx" href="#"><?= Yii::t('x', 'Export to Word/PDF') ?></a>
        <?php if ($theTour) { ?><span class="label op">OPERATING</span><?php } ?>
        &nbsp;
    </p>

    <?
    $devisImage = [
        'banner'=>'https://my.amicatravel.com/assets/tools/docx/b2c/banner-images/rizieres-en-terrasse.jpg',
        'table'=>'https://my.amicatravel.com/assets/tools/docx/b2c/table-images/vietnam-ethnies02.jpg',
        'footer'=>'https://my.amicatravel.com/assets/tools/docx/b2c/footer-images/voyage-famille.jpg',
    ];
    ?>
    <div id="div-export" style="padding:10px; background-color:#f6f6f0; margin-bottom:20px; display:none;">
        <div class="row">
            <div class="col-xs-6">
                <p><a href="#" class="action-select-img-cover"><?= Yii::t('x', 'Change cover image') ?></a><br><img id="img-cover" class="img-responsive img-thumbnail" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/cover-images/<?= $theProduct['image'] ?>" class="img-responsive"></p>
                <p><?= Yii::t('x', 'NOTE: Bạn thay ảnh ngày cho devis trong form sửa nội dung ngày.') ?></p>
            </div>
            <div class="col-xs-6">
                <p><a href="#" class="action-select-img-table"><?= Yii::t('x', 'Change table image') ?></a><br><img id="img-table" class="img-select img-responsive img-thumbnail" width="100%" src="<?= $devisImage['table'] ?>"></p>
                <p><a href="#" class="action-select-img-banner"><?= Yii::t('x', 'Change banner image') ?></a><br><img id="img-banner" class="img-select img-responsive img-thumbnail" width="100%" src="<?= $devisImage['banner'] ?>"></p>
                <p><a href="#" class="action-select-img-footer"><?= Yii::t('x', 'Change footer image') ?></a><br><img id="img-footer" class="img-select img-responsive img-thumbnail" width="100%" src="<?= $devisImage['footer'] ?>"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6"><button id="action-docx" class="btn btn-default btn-block"><i class="fa fa-file-word-o"></i> Download DOCX</button></div>
            <div class="col-xs-6"><button id="action-pdf" class="btn btn-default btn-block"><i class="fa fa-file-pdf-o"></i> Download PDF</button></div>
        </div>
        <div class="text-center"><a href="/products/print/<?= $theProduct['id'] ?>" target="_blank"><?= Yii::t('x', 'Text preview only') ?></a></div>
    </div>

    <table class="table table-xxs table-summary mb-20">
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

    <div class="section section-tags mb-20" id="section-tags">
        <div class="section-header section-body">
            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Tags') ?></span>
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

    <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Private note') ?></span> <span class="text-muted"><?= Yii::t('x', 'Client will not see this') ?></span></p>
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
<div class="col-md-8 col-md-pull-4">
    <? include('_huan1.php'); ?>
    <? include('_huan2.php'); ?>
    <?php if ($theProduct['owner'] == 'at' && $theProduct['language'] == 'fr') { ?>
    <div class="section section-table-devis mb-20" id="table-devis">
        <div class="section-header">
            <?= Html::a(Yii::t('app', 'Edit'), '/products/td/'.$theProduct['id'], ['class'=>'pull-right']) ?>
            <p class="text-bold text-uppercase"><?= Yii::t('x', 'Comparison table') ?></p>
        </div>
        <?php if (!empty($devisTableData)) { ?>
        <div class="section-body table-responsive">
            <table class="table table-xxs table-bordered">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Votre voyage, votre histoire, votre envies</th>
                        <th>Ce qu'Amica vous conseille</th>
                        <th>Ce que l'on vous propose souvent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devisTableData as $line) { ?>
                    <tr>
                        <td><?= $line[0] ?></td>
                        <td><?= $line[3] ?></td>
                        <td><?= $line[2] ?></td>
                        <td><?= $line[1] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <div class="section section-price mb-20" id="section-price">
        <div class="section-header">
            <? if (in_array(USER_ID, [1, $theProduct['created_by'], $theProduct['updated_by']])) { ?>
            <?= Html::a(Yii::t('app', 'Edit'), '/products/pt/'.$theProduct['id'], ['class'=>'pull-right']) ?>
            <? } ?>
            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Price table') ?></span></p>
        </div>
        <div class="section-body table-responsive">
            <table class="table table-bordered table-xxs">
<? // Gia va cac options
$ctpx = $theProduct['prices'];
$ctpx = explode(chr(10), $ctpx);
$unitp = '';
$minp = 99999;
$maxp = 0;
$optcnt = 0;
foreach ($ctpx as $ctp) {
    if (trim($ctp) != '') {
        $line = explode(':', $ctp);
        if (isset($line[1]) && trim($line[0]) == 'OPTION') {
            $optcnt ++;
?>
<tr class="info">
    <th colspan="4">Option <?= $optcnt ?> : <?= trim($line[1]) ?></th>
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
<tr>
    <td colspan="4" class="text-right"><?= $line[0] ?> <strong><?= number_format($line[1]) ?> <?= $theProduct['price_unit'] ?></strong></td>
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
    </div>

    <div class="section section-inex mb-20" id="section-inex">
        <div class="section-header">
            <p class="text-bold text-uppercase"><?= Yii::t('x', 'Inclusions & Exclusions') ?></p>
        </div>
        <div class="section-body">
            <?//= $parser->parse($theProduct['conditions']) ?>
        </div>
    </div>

    <div class="section section-conds mb-20" id="section-conds">
        <div class="section-header">
            <p class="text-bold text-uppercase"><?= Yii::t('x', 'Other conditions') ?></p>
        </div>
        <div class="section-body">
            <?//= $parser->parse($theProduct['others']) ?>
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
];

$coverImageList = [
    'Vietnam'=>[
        'vn-1'=>['Classique', 'devis_base_02_vietnam_classique.jpg', 'x'],
        'vn-2'=>['Immersion', 'devis_base_03_vietnam_immersion.jpg', 'x'],
        'vn-3'=>['Ethnies', 'devis_base_04_vietnam_ethnies.jpg', 'x'],
        'vn-4'=>['Balneaire', 'devis_base_06_vietnam_balneaire.jpg', 'x'],
        'vn-5'=>['Mekong', 'devis_base_08_vietnam_mekong.jpg', 'x'],
    ],
    'Laos'=>[
        'la-1'=>['Classique', 'devis_base_05_laos_classique.jpg', 'x'],
        'la-2'=>['Aventure', 'devis_base_07_laos_aventure.jpg', 'x'],
    ],
    'Cambodia'=>[
        'kh-1'=>['Classique', 'devis_base_09_cambodge_classique.jpg', 'x'],
        'kh-2'=>['Aventure', 'devis_base_10_cambodge_aventure.jpg', 'x'],
        'kh-3'=>['Aventure 2', 'devis_base_11_cambodge_balneaire.jpg', 'x'],
    ],
    'Myanmar'=>[
        'mm-1'=>['Classique', 'devis_base_16_birmanie_classique.jpg', 'x'],
        'mm-2'=>['Aventure', 'devis_base_17_birmanie_aventure.jpg', 'x'],
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h6 class="modal-title text-pink text-semibold"><?= Yii::t('x', 'Select image') ?></h6>
            </div> 
            <div class="modal-body">
                <div class="img-list" id="img-list-cover">
                    <div class="row cover-image-list">
                        <?
                        $currentCountry = '';
                        foreach ($coverImageList as $country=>$img) {
                            if ($currentCountry != $country) { ?>
                        </div><div class="text-bold clearfix"><?= $country ?></div><div class="row"><?
                                $currentCountry = $country;
                                $cnt = 0;
                            }
                            foreach ($img as $code => $data) {
                                $cnt ++; ?>
                        <div class="col-sm-2 text-center cover-image-list-item">
                            <div><img class="cursor-pointer img-responsive" title="<?= $data[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/cover-images/<?= $data[1] ?>"></div>
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
                    <?
                    $cnt = 0;
                    $bannerImageFiles = [];
                    // $findFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@webroot').'/assets/tools/docx/b2c/banner-images');
                    // foreach ($findFiles as $file) {
                    //     $bannerImageFiles[] = ['', substr(strrchr($file, '/'), 1)];
                    // }
                    foreach ($bannerImageFiles as $img) {
                        $cnt ++; ?>
                    <div class="col-sm-2 text-center banner-image-list-item">
                        <div><img class="cursor-pointer img-responsive" title="<?= $img[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/banner-images/<?= $img[1] ?>"></div>
                        <div><?= $img[0] ?></div>
                    </div><?
                        if ($cnt == 6) {
                            $cnt = 0; ?>
                    <div class="clearfix visible-sm-block visible-md-block visible-lg-block">&nbsp;</div><?
                        }
                    }
                    ?>
                    </div>
                </div>
                <div class="img-list" id="img-list-table">
                    <div class="row table-image-list">
                    <?
                    $cnt = 0;
                    foreach ($tableImageFiles as $img) {
                        $cnt ++; ?>
                    <div class="col-sm-2 text-center table-image-list-item">
                        <div><img class="cursor-pointer img-responsive" title="<?= $img[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/table-images/<?= $img[1] ?>"></div>
                        <div><?= $img[0] ?></div>
                    </div><?
                        if ($cnt == 6) {
                            $cnt = 0; ?>
                    <div class="clearfix visible-sm-block visible-md-block visible-lg-block">&nbsp;</div><?
                        }
                    }
                    ?>
                    </div>
                </div>
                <div class="img-list" id="img-list-footer">
                    <div class="row footer-image-list">
                        <?
                        $cnt = 0;
                        foreach ($footerImageFiles as $img) {
                            $cnt ++; ?>
                        <div class="col-sm-2 text-center footer-image-list-item">
                            <div><img class="cursor-pointer img-responsive" title="<?= $img[0] ?>" src="/timthumb.php?w=250&src=/assets/tools/docx/b2c/footer-images/<?= $img[1] ?>"></div>
                            <div><?= $img[0] ?></div>
                        </div><?
                            if ($cnt == 6) {
                                $cnt = 0; ?>
                        <div class="clearfix visible-sm-block visible-md-block visible-lg-block">&nbsp;</div><?
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
$('.cover-image-list-item img.img-responsive').on('click', function(){
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
$('.banner-image-list-item img.img-responsive').on('click', function(){
    var src = $(this).attr('src')
    banner_image = src.replace('/timthumb.php?w=250&src=/assets/tools/docx/b2c/banner-images/', '');

    $('#img-banner').attr('src', src)
    $('#img-list-banner').hide();
    $('#modal-select-image').modal('hide');
})

// Select table image
$('.table-image-list-item img.img-responsive').on('click', function(){
    var src = $(this).attr('src')
    table_image = src.replace('/timthumb.php?w=250&src=/assets/tools/docx/b2c/table-images/', '');

    $('#img-table').attr('src', src)
    $('#img-list-table').hide();
    $('#modal-select-image').modal('hide');
})

// Select footer image
$('.footer-image-list-item img.img-responsive').on('click', function(){
    var src = $(this).attr('src');
    footer_image = src.replace('/timthumb.php?w=250&src=/assets/tools/docx/b2c/footer-images/', '')

    $('#img-footer').attr('src', src)
    $('#img-list-footer').hide();
    $('#modal-select-image').modal('hide');
})

TXT;
$this->registerJs($js);