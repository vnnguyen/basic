<?php

use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

require_once(Yii::getAlias('@webroot').'/../textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

include('_program_inc.php');

Yii::$app->params['page_icon'] = 'map-o';
Yii::$app->params['body_class'] = 'bg-white sidebar-xs';

Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Programs', 'b2b/programs'],
    [$ctTypeList[$theProgram['offer_type']] ?? $theProgram['offer_type'], 'b2b/programs?type='.$theProgram['offer_type']],
    ['By '.$theProgram['createdBy']['name'], 'b2b/programs?ub='.$theProgram['created_by']],
    ['View'],
];

$dayIdList = explode(',', $theProgram['day_ids']);
if (!$dayIdList) {
    $dayIdList = [];
}

if ($theProgram['image'] == '') {
    $theProgram['image'] = '69-ha_long_vietnam2.jpg';
}

$tbl = '';

?>
<style>
.form-control.ex {padding:0; border:0; margin:2px; background-color:#eee}
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

[data-autocomplete] {
  position: relative;
}

[data-autocomplete] .suggestions {
  position: absolute;
  top: 100%;
  left: 0;
  min-width: 100%;
  border: 1px solid #ccc;
  border-top: 0;

  background: #fff;
  z-index: 2;
  white-space: nowrap;
  font-size: 13px;
  font-family: Helvetica, Arial;
  font-weight: normal;
  line-height: 18px;
}

[data-autocomplete] .suggestions > div {
  padding: 4px 7px;
  cursor: pointer;
}

[data-autocomplete] .suggestions > div.highlight,
[data-autocomplete] .suggestions > div:hover {
  background: #0A5CB4;
  color: #fff;
}

[data-autocomplete] .suggestions > div strong {
  font-weight: bold;
}

</style>
<script>
var hotels = [
'Sofitel Legend Metropole Hanoi : http://www.sofitel.com/1555',
'Kim Tho Hotel : http://www.kimtho.com',
'Thanh Lich : http://thanhlichhotel.com.vn/',
'Pilgrimage Village Hue Resort & Spa : http://www.pilgrimagevillage.com',
'La Residence Hotel & Spa : http://www.la-residence-hue.com',
'Vinh Hung 2 City : http://vinhhungcityhotel.com',
'Ancient House Resort : http://hoianancienthouseresort.com',
'Grand Hotel Saigon : http://www.grandhotel.vn',
'Victoria Sapa Resort & Spa : http://www.victoriahotels.asia',
'La Veranda Resort & Spa : http://www.laverandaresorts.com',
'La Dolce Vita Hotel : http://ladolcevita-hotel.com',
'Mango Bay Resort : http://www.mangobayphuquoc.com',
'Romance : http://www.romancehotel.com.vn',
'Dakruco Hotel 3* : http://www.dakrucohotels.com',
'Thuy Duong 3 : http://www.thuyduonghotel-hoian.com',
'Palm Garden Beach Resort & Spa : http://www.palmgardenresort.com.vn',
'Majestic Saigon Hotel : http://www.majesticsaigon.com.vn',
'Silk Path Luxury Hanoi : http://www.silkpathhotel.com',
'Boutique Sapa Hotel : http://www.boutiquesapahotel.com',
'Golden Lotus Hotel : http://www.goldenlotushotel.com.vn',
'Sambor Village : http://samborvillage.asia',
'Monorom II Vip : http://monoromviphotel.com',
'Sanouva Hotel : http://www.sanouvahotel.com',
'Long Beach Resort : http://www.longbeach-phuquoc.com/',
'Le Cochinchine - Anh Tấn : http://lecochinchine.vn',
'Villa Chitdara 1 : http://www.villachitdara.com',
'Veranda Natural Resort : http://veranda-resort.asia',
'The Elephant Crossing : http://www.theelephantcrossinghotel.com',
'Sanctuary Pakbeng Lodge : http://sanctuaryhotelsandresorts.com',
'Terres Rouges Lodge : http://www.ratanakiri-lodge.com/index.php?lng=fr',
'Hoi An Riverside Bamboo Resort : http://hoianbamboovillage.com',
'Lao Orchid Hotel : http://www.lao-orchid.com',
'Sala Done Khone Hotel : http://salalaoboutique.com',
'Residence Sisouk : http://www.residence-sisouk.com',
'Pon Arena Hotel : http://ponarenahotel.com/',
'Riverside Boutique Resort : http://www.riversidevangvieng.com/',
'Phou Iu Muang Sing : http://www.phouiu-ecotourism-laos.com',
'Vansana Plain Of Jars Hotel : http://www.vansanahotel-group.com',
'Inthira Champasak : ',
'La Folie Lodge : http://www.lafolie-laos.com',
'Cap Town : http://www.captownhotel.com',
'Gold Hotel Hue : http://www.goldhotelhue.com',
'Rajabori Villas : http://www.rajabori-kratie.com',
'My Dream Boutique Resort : http://www.mydreamresort.com',
'Villa Maly : http://www.villa-maly.com',
'Hau Giang : http://www.haugianghotel.com',
'Ansara : http://www.ansarahotel.com',
'Phasouk Residence : http://www.phasoukresidence.com',
'Hanoi Boutique Hotel & Spa : http://www.hanoiboutiquehotel.vn',
'Ruby River : http://www.rubyriverhotel.com.vn',
'Sunny Mountain : http://sunnymountainhotel.com',
'DC Bistro Boutique Restaurant : ',
'Vanna hill resort : http://www.vannahillresort.asia/',
'Tam Coc Garden : http://www.tamcocgarden.com',
'Burasari Heritage : http://www.burasariheritage.com',
'Lattanavongsa Guesthouse : ',
'Villa Vang Vieng Riverside : http://www.villavangvieng.com',
'Famiana Resort & Spa : http://famianaresort.com',
'Maison Dalabua : http://maison-dalabua.com',
'Mayura Hill : ',
'Phu Thinh Boutique Resort & Spa : http://phuthinhhotels.com',
'Houay xai Riverside : http://houayxairiverside.com',
'The Boat Landing Guest House : http://theboatlanding.com',
'Charming Lao : http://charminglaohotel.com',
'Mandala Ou Resort : http://www.mandala-ou.com',
'Eldora : http://eldorahotel.com',
'Goyavier Boutique (Natanha) : http://www.goyavierboutique.com',
'Sinouk Coffee Resort : http://sinoukcoffeeresort.com',
'The Island Lodge : http://theislandlodge.com.vn',
'Palm Tree Boutique Hotel : http://palmtreeboutiquehotel.com',
'Hotel des Arts Saigon : http://www.hoteldesartssaigon.com',
'La Belle Vie Tam Coc Homestay : http://labellevietamcochomestay.com',
'The Scarlett Boutique hotel : http://thescarletthotels.com',
'Green Boutique Villa : http://www.greenboutiquevilla.com',
];
</script>
<!-- SELECT HEADER IMAGE MODAL -->
<div class="modal fade modal-primary" id="modal-select-header-image" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-pink text-semibold"><?= Yii::t('p', 'Select Word header image') ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Click on an image to set as header image for Word file.</p>
                <div class="row header-image-list">
                    <?php $cnt = 0; foreach ($b2bBannerFiles as $img) { $cnt ++; ?>
                    <div class="col-6 col-sm-4 col-md-3 text-center header-image-list-item clearfix mb-1">
                        <div><img class="cursor-pointer img-responsive img-fluid" title="<?= $img[0] ?>" src="/timthumb.php?w=180&h=120&src=/upload/devis-banners/b2b/<?= $img[1] ?>"></div>
                        <div><?= $img[0] ?></div>
                    </div>
                    <?php if ($cnt == 2) { ?><!-- div class="w-100 d-none d-sm-block d-md-none"></div --><?php } ?>
                    <?php if ($cnt == 4) { $cnt = 0; ?><!-- div class="w-100 d-none d-md-block"></div --><?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4 order-12">
    <p id="header-image">
        <img class="img-responsive img-fluid" src="/upload/devis-banners/b2b/<?= $theProgram['image'] ?>">
        <a style="padding:4px; background-color:#000; color:#fff; position:absolute; top:8px; margin-left:8px;" data-toggle="modal" data-target="#modal-select-header-image" href="#">Change</a>
    </p>
    <p>
        <a class="pull-right" id="down-toggle" href="#">Export to Word</a>
        <?php if ($theProgram['owner'] == 'si') { ?>
            <span class="label label-info">Secret Indochina</span>
            <?php if ($theProgram['offer_type'] == 'b2b-prod') { ?>
                <span class="label label-warning">PROD</span>
            <?php } ?>
        <?php } ?>
        <?php if ($theTour) { ?><span class="label op">OPERATING</span><?php } ?>
    </p>
    <p id="down-p" style="display:none;"><button id="download-word-file" type="button" class="btn btn-primary btn-block">Click here to download Word file</button></p>
    <table class="table table-xxs table-summary mb-20">
        <tbody>
            <?php if ($theProgram['op_status'] == 'op') { ?>
            <tr>
                <td style="white-space:nowrap;"><strong>Operated as:</strong></td><td><?= Html::a($theProgram['op_code'].' - '.$theProgram['op_name'], '@web/tours/r/'.$theTour['id']) ?></td>
            </tr>
            <?php } ?>

            <?php if ($theProgram['client_id'] != 0) { ?>
            <tr>
                <td><strong><?= Yii::t('x', 'For') ?></strong></td>
                <td><?php
                $theClient = \common\models\Client::find()
                    ->select(['id', 'name'])
                    ->where(['id'=>$theProgram['client_id']])
                    ->asArray()
                    ->one();
                if ($theClient) {
                    echo Html::a($theClient['name'], '/b2b/clients/r/'.$theClient['id']);
                }
                ?>
                </td>
            </tr>
            <?php } ?>

            <tr>
                <td><strong>Bookings</strong></td>
                <td>
                    <?php if (empty($theProgram['bookings'])) { ?>
                    No bookings found.
                    <?php } else { ?>
                        <?php foreach ($theProgram['bookings'] as $booking) { ?>
                    <div>
                    <?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$booking['createdBy']['image'], ['style'=>'width:20px; height:20px']) ?>
                    <span class="label status <?= $booking['status'] ?>"><?= strtoupper($booking['status']) ?></span>
                    <?php if ($booking['finish'] == 'canceled') { ?><span class="label label-warning">CXL</span><?php } ?>
                    &middot;<?= $booking['pax'] ?> pax &middot; <?= number_format($booking['price']) ?> <?= $booking['currency'] ?>
                    <br><i class="fa fa-briefcase text-muted"></i> <?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?>
                    </div>
                        <?php } // foreach booking ?>
                    <?php } ?>
                    <?php if (empty($theProgram['bookings']) || ($theProgram['offer_type'] == 'combined2016' && strtotime('now') < strtotime($theProgram['day_from']))) { ?>
                    <?= Html::a('Add new proposal', '@web/bookings/c?product_id='.$theProgram['id']) ?>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <?php if ($theProgram['offer_type'] == 'b2b-prod') { ?>
                <td><strong><?= Yii::t('p', 'Duration') ?></strong></td><td><?= $theProgram['day_count'] ?> <?= Yii::t('p', 'days') ?></td>
                <?php } else { ?>
                <td><strong><?= Yii::t('p', 'Start date') ?></strong></td><td><?= Yii::$app->formatter->asDate($theProgram['day_from'], 'php:j/n/Y (l)') ?> - <?= Yii::t('p', 'Length') ?> <?= $theProgram['day_count'] ?> <?= Yii::t('p', 'days') ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td><strong>Price:</strong></td><td><?= number_format($theProgram['price'], 0) ?> <?= $theProgram['price_unit'] ?> / <?= $theProgram['price_for'] ?>
                <br><span class="text-muted">Valid until <?= date('d-m-Y', strtotime($theProgram['price_until'])) ?></span></td>
            </tr>
            <tr>
                <td><strong>About:</strong></td><td><?= $theProgram['about'] ?></td>
            </tr>
            <tr>
                <td><strong>Updated:</strong></td><td><?= $theProgram['updatedBy']['name'] ?> <span class="text-muted"><?= Yii::$app->formatter->asRelativeTime($theProgram['updated_at']) ?></span></td>
            </tr>
        </tbody>
    </table>

    <div class="section section-tags mb-20" id="section-tags">
        <div class="section-header section-body">
            <p><span class="text-bold text-uppercase"><?= Yii::t('p', 'Tags') ?></span>
            <?
            if (!empty($theProgram['tags'])) {
                $tags = explode(',', $theProgram['tags']);
                $html = [];
                foreach ($tags as $tag) {
                    $html[] = '<i class="fa fa-fw fa-tag text-muted"></i>'.Html::a(trim($tag), '/b2b/programs?name='.trim($tag));
                }
                echo implode(' ', $html);
            } else {
                echo '<span class="text-muted">(No tags)</span>';
            }
            ?></p>
        </div>
    </div>

    <?php include('_program_r__map.php') ?>

    <div class="section section-private-note mb-20" id="section-private-note">
        <div class="section-header">
            <p>
                <span class="text-bold text-uppercase">Private note</span>
                <span class="text-muted">Client will not see this</span>
            </p>
        </div>
        <div class="section-body">
            <?= Markdown::process($theProgram['summary']) ?>
        </div>
    </div>
</div>
<div class="col-md-8 order-1">
    <?php include('_huan1.php') ?>
    <?php include('_huan2.php') ?>
<?

$textIncl = '';
$textExcl = '';
if (isset($theProgram['metas']['text_inex']['value'])) {
    // Truong hop nay chac chan co in & ex
    // inc_exc IN \n;|\n EX
    $textInex = explode("\n;|\n", $theProgram['metas']['text_inex']['value']);
    $textIncl = $textInex[0];
    $textExcl = $textInex[1] ?? '';
} else {
    // Old text (conds)
    if (strpos($theProgram['conditions'], 'h3. INCLUSIONS :') !== false && strpos($theProgram['conditions'], 'h3. EXCLUSIONS :') !== false) {
        $textInex = explode('h3. EXCLUSIONS :', $theProgram['conditions']);
        $textIncl = $parser->parse(str_replace('h3. INCLUSIONS :', '', $textInex[0]));
        $textExcl = $parser->parse($textInex[1] ?? '');
    } else {
        $textIncl = $parser->parse($theProgram['conditions']);
        $textExcl = '';
    }
}

// Xem noi dung meta co hay khong
$hasTextEsprit = isset($theProgram['metas']['text_esprit']) ? true : false;
$hasTextPoints = isset($theProgram['metas']['text_points']) ? true : false;
$hasTextIncl = $textIncl != '';
$hasTextExcl = $textExcl != '';
$hasTextConds = isset($theProgram['metas']['text_conds']) ? true : false;
$hasTextNotes = isset($theProgram['metas']['text_notes']) ? true : false;

?>
    <div id="section-e" class="section mb-20 -hover-show" data-meta_name="text_esprit">
        <?php if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
        <div class="pull-right -hover-shown" -style="display:none;">
            <a class="action-add" style="<?= !$hasTextEsprit ? '' : 'display:none' ?>" href="#"><i class="fa fa-plus"></i></a>
            <a class="action-edit" style="<?= $hasTextEsprit ? '' : 'display:none' ?>" href="#"><i class="fa fa-edit"></i></a>
            <a class="action-delete text-danger" style="<?= $hasTextEsprit ? '' : 'display:none' ?>" href="#"><i class="fa fa-trash-o"></i></a>
        </div>
        <?php } ?>
        <p class="text-uppercase text-bold">Esprit</p>
        <div class="section-body" id="inline-e"><?= $theProgram['metas']['text_esprit']['value'] ?? '' ?></div>
    </div>

    <div id="section-p" class="section mb-20 -hover-show" data-meta_name="text_points">
        <?php if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
        <div class="pull-right -hover-shown" -style="display:none;">
            <a class="action-add" style="<?= !$hasTextPoints ? '' : 'display:none' ?>" href="#"><i class="fa fa-plus"></i></a>
            <a class="action-edit" style="<?= $hasTextPoints ? '' : 'display:none' ?>" href="#"><i class="fa fa-edit"></i></a>
            <a class="action-delete text-danger" style="<?= $hasTextPoints ? '' : 'display:none' ?>" href="#"><i class="fa fa-trash-o"></i></a>
        </div>
        <?php } ?>
        <p class="text-uppercase text-bold">Points forts</p>
        <div class="section-body" id="inline-p"><?= $theProgram['metas']['text_points']['value'] ?? '' ?></div>
    </div>

    <div id="section-hotels" class="section section-hotels mb-20" data-meta_name="table-hotels">
        <div class="section-header">
            <?= Html::a('<i class="fa fa-edit"></i>', '#', ['class'=>'pull-right', 'id'=>'action-edit-hotels']) ?>
            <p class="section-title text-uppercase text-bold">Accommodation</p>
        </div>
        <div class="section-body">
            <table id="tbl-hotels" class="table table-bordered table-narrow">
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th>Hébergement</th>
                        <th class="text-center">Nuitée(s)</th>
                        <th>Categorie chambre</th>
                    </tr>
                </thead>
                <tbody>
                <?
                // 170801 Hotel table format
                $text = $theProgram['metas']['text_hotels']['value'] ?? '' ;
                $text = strip_tags($text);
                if (substr($text, 0, 5) == 'OLD;|') {
                    $text = $this->context->upgradeTextHotels(substr($text, 5));
                }
                // \fCore::expose($text);
                $lines = explode("\n", $text);
                foreach ($lines as $line) {
                    if (trim($line) != '') {
                        $parts = explode(';|', $line);
                        if ($parts[0] == '_option') {
                ?>
                    <tr class="row-type-option info">
                        <td colspan="4"><?= $parts[1] ?? '' ?></td>
                    </tr>
                <?
                        }
                        if ($parts[0] == '_hotel') {
                ?>
                    <tr class="row-type-hotel">
                        <td><?= $parts[1] ?? '' ?></td>
                        <td class="url"><?
                        $url = explode(' : ', $parts[2] ?? '');
                        if (isset($url[1])) {
                            if (substr($url[1], 0, 4) != 'http') {
                                $url[1] = 'http://'.$url[1];
                            }
                            echo Html::a($url[0], $url[1], ['target'=>'_blank']);
                        } else {
                            echo $url[0];
                        }
                        ?></td>
                        <td class="text-center"><?= $parts[3] ?? '' ?></td>
                        <td><?= $parts[4] ?? '' ?></td>
                    </tr>
                <?
                        }
                        if ($parts[0] == '_price') {
                ?>
                    <tr class="row-type-price">
                        <td colspan="3"><?= $parts[1] ?? '' ?></td>
                        <td class="text-right"><?
                        $price = $parts[2] ?? '';
                        $price = trim(str_replace([',', ' EUR', ' VND', ' USD'], ['', '', '', ''], $price));
                        if ((int)$price != 0) {
                            echo number_format((int)$price), ' '.$theProgram['price_unit'];
                        }
                        ?></td>
                    </tr>
                <?
                        }
                        if ($parts[0] == '_text') {
                ?>
                    <tr class="row-type-text">
                        <td colspan="4" style="border:0;"><?= $parts[1] ?? '' ?></td>
                    </tr>
                <?
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
        <div id="tbl-hotels-text" class="editable"></div>
        <div id="edit-tbl-hotels-buttons" style="display:none; margin-top:8px">
            <div class="btn-group pull-right">
                <button class="add-option btn btn-info">+Option</button>
                <button class="add-hotel btn btn-info">+Hotel</button>
                <button class="add-price btn btn-info">+Price</button>
                <button class="add-text btn btn-info">+Text</button>
            </div>
            <button class="action-save btn btn-primary"><?= Yii::t('app', 'Save changes') ?></button>
            <button class="action-cancel btn btn-default"><?= Yii::t('app', 'Cancel') ?></button>
        </div>
    </div>

    <div id="section-price" class="section section-price mb-20" data-meta_name="text_price">
        <div class="section-header">
            <?= Html::a('<i class="fa fa-edit"></i>', '#', ['class'=>'pull-right', 'id'=>'action-edit-price']) ?>
            <p>
                <span class="text-uppercase text-bold">Price table</span>
            </p>
        </div>
        <div class="section-body">
    <?
    $groupSize = ['2 pax', '3 pax', '4 pax', '5 pax', '6 pax', '7 pax', '8 pax', '9 pax', '10 pax', '11 pax', '12 pax'];
    $text = $theProgram['metas']['text_price']['value'] ?? '';
    $lines = explode("\n", $text);
    $txt = '';
    $table = [
        'thead'=>[],
        'tbody'=>[],
        'tfoot'=>[],
    ];
    $numrow = 0;
    $numcol = 0;
    foreach ($lines as $line) {
        $parts = explode(';|', $line);
        if ($parts[0] == '_head') {
            foreach ($parts as $i=>$part) {
                if ($i > 0 && $part != '') {
                    $table['thead'][] = $part;
                }
            }
            $numcol = count($table['thead']);
        } elseif ($parts[0] == '_') {
            foreach ($parts as $i=>$part) {
                if ($i > 0) {
                    $table['tbody'][$numrow][$i - 1] = $part;
                }
            }
            $numrow ++;
        } else {
            $txt .= $parts[1] ?? '';
        }
    }

    // \fCore::expose($text);
    // \fCore::expose($table);

    ?><div id="div-price"><table id="tbl-price" class="table table-bordered table-narrow"><thead><tr><?
    foreach ($table['thead'] as $thead) {
    ?><th><?= $thead ?></th><?
    }
    ?></tr></thead><tbody><?
    foreach ($table['tbody'] as $tbody) {
    ?><tr><?
        $colspan = 1;
        foreach ($tbody as $i=>$cell) {
            if ($i < $numcol) {
                if ($cell == '_') {
                    $colspan ++;
                } else {
    ?><td <?= $colspan == 1 ? '' : 'colspan="'.$colspan.'"' ?>><?= $cell ?></td><?
                    $colspan = 1;
                }
            }
        }
        while ($i < $numcol - 1) {
            $i ++;
    ?><td>-</td><?
        }
    ?></tr><?
    }
    ?></tbody></table></div><?
    ?>
            <!-- table id="tbl-price" class="table table-bordered table-narrow">
                <thead>
                    <tr>
                        <th class="editable">Taille du groupe</th>
                        <?php foreach ($groupSize as $size) { ?>
                        <th class="editable text-center" width="<?= 75/count($groupSize) ?>%"><?= str_replace(' pax', '<br> pax', $size) ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="editable">Prix/Pax</td>
                        <?php foreach ($groupSize as $size) { ?>
                        <td class="editable text-center"></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td class="editable">SGL sup</td>
                        <td class="editable text-center" colspan="11"></td>
                    </tr>
                </tbody>
            </table -->
            <div id="tbl-price-text"><?= $txt ?></div>
            <div id="edit-tbl-price-buttons" style="display:none; margin-top:8px">
                <div class="btn-group pull-right">
                    <button class="add-text btn btn-info">+Text</button>
                    <button class="add-row btn btn-info">+Row</button>
<!--                     <button class="add-col btn btn-info">+Col</button>
                    <button class="rem-col btn btn-info">-Col</button>
                    <button class="add-span btn btn-info">+Span</button> -->
                </div>
                <button class="action-save btn btn-primary"><?= Yii::t('app', 'Save changes') ?></button>
                <button class="action-cancel btn btn-default"><?= Yii::t('app', 'Cancel') ?></button>
            </div>
        </div>
    </div><!-- #section-price -->

    <div class="row">
        <div class="col-md-6">
            <div id="section-incl" class="section section-incl mb-20" data-meta_name="text_incl">
                <div class="section-header">
                    <?php if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
                    <div class="pull-right">
                        <a class="action-add" style="<?= !$hasTextIncl ? '' : 'display:none' ?>" href="#"><i class="fa fa-plus"></i></a>
                        <a class="action-edit" style="<?= $hasTextIncl ? '' : 'display:none' ?>" href="#"><i class="fa fa-edit"></i></a>
                        <a class="action-delete text-danger" style="<?= $hasTextIncl ? '' : 'display:none' ?>" href="#"><i class="fa fa-trash-o"></i></a>
                    </div>
                    <?php } ?>
                    <p><span class="text-bold text-uppercase">Inclusions</span></p>
                </div>
                <div id="section-body-incl" class="section-body">
                    <?= $textIncl ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="section-excl" class="section section-excl mb-20" data-meta_name="text_excl">
                <div class="section-header">
                    <?php if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
                    <div class="pull-right">
                        <a class="action-add" style="<?= !$hasTextExcl ? '' : 'display:none' ?>" href="#"><i class="fa fa-plus"></i></a>
                        <a class="action-edit" style="<?= $hasTextExcl ? '' : 'display:none' ?>" href="#"><i class="fa fa-edit"></i></a>
                        <a class="action-delete text-danger" style="<?= $hasTextIncl ? '' : 'display:none' ?>" href="#"><i class="fa fa-trash-o"></i></a>
                    </div>
                    <?php } ?>
                    <p><span class="text-bold text-uppercase">Exclusions</span></p>
                </div>
                <div id="section-body-excl" class="section-body">
                    <?= $textExcl ?>
                </div>
            </div>
        </div>
    </div>

    <div id="section-c" class="section section mb-20" data-meta_name="text_conds">
        <?php if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
        <div class="pull-right">
            <a class="action-add" style="<?= !$hasTextConds ? '' : 'display:none' ?>" href="#"><i class="fa fa-plus"></i></a>
            <a class="action-edit" style="<?= $hasTextConds ? '' : 'display:none' ?>" href="#"><i class="fa fa-edit"></i></a>
            <a class="action-delete text-danger" style="<?= $hasTextConds ? '' : 'display:none' ?>" href="#"><i class="fa fa-trash-o"></i></a>
        </div>
        <?php } ?>
        <p class="text-uppercase text-bold">Conditions</p>
        <div class="section-body" id="inline-c"><?= $theProgram['metas']['text_conds']['value'] ?? '' ?></div>
    </div>

    <div id="section-n" class="section mb-20" data-meta_name="text_notes">
        <?php if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
        <div class="pull-right">
            <a class="action-add" style="<?= !$hasTextNotes ? '' : 'display:none' ?>" href="#"><i class="fa fa-plus"></i></a>
            <a class="action-edit" style="<?= $hasTextNotes ? '' : 'display:none' ?>" href="#"><i class="fa fa-edit"></i></a>
            <a class="action-delete text-danger" style="<?= $hasTextNotes ? '' : 'display:none' ?>" href="#"><i class="fa fa-trash-o"></i></a>
        </div>
        <?php } ?>
        <p class="text-uppercase text-bold">Notes</p>
        <div class="section-body" id="inline-n"><?= $theProgram['metas']['text_notes']['value'] ?? '' ?></div>
    </div>
</div><!-- col col-2 -->

<div id="save-cancel" class="save-cancel" style="display:none">
    <button class="action-save btn btn-primary"><?= Yii::t('app', 'Save changes') ?></button>
    <button class="action-cancel btn btn-default"><?= Yii::t('app', 'Cancel') ?></button>
</div>

<style type="text/css">
.section-body.-editable, .-editable {background-color:#ffd; padding:4px; margin-bottom:8px;}
</style>
<script>
var product_id = <?= $theProgram['id'] ?>;
var header_image = '<?= $theProgram['image'] ?>';
var docx_link = '/b2b/programs/print-b2b/<?= $theProgram['id'] ?>?docx&xh';
</script>
<?php
$js = <<<'TXT'
$('.hover-show').on('mouseenter mouseleave', function(){
    $(this).find('.hover-shown').toggle()
})

$('#down-toggle').on('click', function(){
    $('#down-p').toggle();
    return false;
});
$('#download-word-file').on('click', function(){
    $(this).html('Generating file. Please wait...').addClass('disabled')

    var jqxhr = $.ajax({
        url: docx_link,
        type: 'post',
        data: {
            header_image: header_image,
        },
        dataType: 'json'
    })
    .done(function(data) {
        var file_url = '/' + data.file_name;
        location.href = file_url;
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
    .always(function(data) {
        $('#download-word-file').html('Click here to download Word file').removeClass('disabled');
        $('#down-p').hide();
    })
})

var wrapper = null;
var inline_div = null;
var orig_text = '';
var orig_text_2 = '';

$('.action-add, .action-edit').on('click', function(){
    $(this).hide();
    wrapper = $(this).parents('.section');
    inline_div = wrapper.find('.section-body:eq(0)')
    wrapper.find('.action-add, .action-edit, .action-delete').hide()
    orig_text = inline_div.html()

    // for(name in CKEDITOR.instances) {
    //     CKEDITOR.instances[name].destroy(true);
    // }

    inline_div.attr('contenteditable', 'true').addClass('editable');
    CKEDITOR.inline(inline_div.attr('id'), {
        allowedContent: 'div p sub sup strong em s a i u ul ol li img blockquote;',
        entities: false,
        entities_greek: false,
        entities_latin: false,
        language: 'vi'
    });
    $('#save-cancel').appendTo(wrapper).show()
    inline_div.focus()
    return false;
})

// Click save button
$('#save-cancel .action-save').on('click', function(){
    var wrapper = $(this).parents('.section')
    var inline_div = wrapper.find('.section-body:eq(0)')
    var text = inline_div.html().replace('<p><br></p>', '');
    var jqxhr = $.ajax({
        url: '/b2b/programs/ajax?xh',
        type: 'post',
        data: {
            action: 'save_meta',
            meta_name: wrapper.data('meta_name'),
            product_id: product_id,
            text: text,
        },
        dataType: 'json'
    }).
    done(function(data) {
        inline_div.html(text).removeClass('editable').attr('contenteditable', 'false');
        CKEDITOR.instances[inline_div.attr('id')].destroy();
        $('#save-cancel').hide().appendTo('body')
        if (text == '') {
            wrapper.find('.action-add').show()
            wrapper.find('.action-edit, .action-delete').hide()
        } else {
            wrapper.find('.action-add').hide()
            wrapper.find('.action-edit, .action-delete').show()
        }
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
    .always(function(data) {
    })
});

// Cancel add or edit
$('#save-cancel .action-cancel').on('click', function(){
    var wrapper = $(this).parents('.section')
    var inline_div = wrapper.find('.section-body:eq(0)')
    CKEDITOR.instances[inline_div.attr('id')].destroy();
    inline_div.removeClass('editable').attr('contenteditable', 'false');
    $('#save-cancel').hide().appendTo('body')
    if (orig_text == '') {
        wrapper.find('.action-add').show()
        wrapper.find('.action-edit, .action-delete').hide()
    } else {
        wrapper.find('.action-add').hide()
        wrapper.find('.action-edit, .action-delete').show()
    }
    inline_div.html(orig_text);
});

// Click delete link
$('.action-delete').on('click', function(){
    if (!confirm('Delete text?')) {
        return false;
    }
    wrapper = $(this).parents('.section')
    inline_div = wrapper.find('.section-body:eq(0)')
    $.ajax({
        method: "POST",
        url: "/b2b/programs/ajax?xh",
        data: {
            action: 'delete_meta',
            meta_name: wrapper.data('meta_name'),
            product_id: product_id,
        }
    })
    .done(function(){
        inline_div.html('').removeClass('editable').attr('contenteditable', 'false');
        $('#save-cancel').hide().appendTo('body');
        wrapper.find('.action-add').show()
        wrapper.find('.action-edit, .action-delete').hide()
    })
    .fail(function(){
        alert('Could not delete text!')
    })
    return false;
});

// Select header image
$('.header-image-list-item img.img-responsive.img-fluid').on('click', function(){
    var src = $(this).attr('src').replace('/timthumb.php?w=180&h=120&src=', '');
    header_image = src.replace('/upload/devis-banners/b2b/', '')
    var jqxhr = $.ajax({
        url: '/b2b/programs/ajax?xh',
        type: 'post',
        data: {
            action: 'save_header_image',
            header_image: header_image,
            product_id: product_id,
        },
        dataType: 'json'
    }).
    done(function(data) {
        $('#header-image img').attr('src', src)
        $('#modal-select-header-image').modal('hide');
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
    .always(function(data) {
    })
})

// Edit hotels
var td_sort = '<td width="32" class="text-nowrap"><i title="Sort" class="sort-row fa fa-bars cursor-move text-muted"></i><i style="margin-left:2px" class="fa fa-copy text-muted cursor-pointer" title="Copy"></i></td>';
var td_delete = '<td width="16"><i title="Delete" class="rem-row fa fa-trash-o cursor-pointer text-danger"></i></td>';

$('#tbl-hotels').on('click', 'i.fa-copy', function(){
    $(this).parent().parent().clone(true, true).appendTo($('#tbl-hotels'))
})

$('#action-edit-hotels').on('click', function(){
    orig_text = $('#tbl-hotels').html()
    orig_text_2 = $('#tbl-hotels-text').html()
    $(this).hide()
    $('#tbl-hotels tbody').find('td, th').attr('contenteditable', 'true')
    $('#tbl-hotels-text').attr('contenteditable', 'true')
    $('#edit-tbl-hotels-buttons').show()
    $('#tbl-hotels td.url').each(function(){
        thistext = $(this).text()
        if ($(this).has('a[href]').length) {
            thistext += ' : ' + $(this).find('a').attr('href')
        }
        $(this).text(thistext)
    })
    $('#tbl-hotels tr').each(function(i){
        var tr = $(this)
        // tr.find('td.url').attr('data-autocomplete-spy', true)
        if (i == 0) {
            $('<td/>').attr('width', 16).prependTo(tr)
            $('<td/>').attr('width', 16).appendTo(tr)
        } else {
            tr.prepend(td_sort).append(td_delete)
        }
    })
    $('#tbl-hotels tbody').sortable({
        handle: '.sort-row'
    });
    return false;
})
// Save edit hotels
$('#edit-tbl-hotels-buttons .action-save').on('click', function(){
    var tbl = '';
    $('#tbl-hotels tbody tr').each(function(i){
        tbl += '\n'
        if ($(this).hasClass('row-type-text')) {
            tbl += '_text;|'
        } else if ($(this).hasClass('row-type-option')) {
            tbl += '_option;|'
        } else if ($(this).hasClass('row-type-hotel')) {
            tbl += '_hotel;|'
        } else if ($(this).hasClass('row-type-price')) {
            tbl += '_price;|'
        } else {
            tbl += '_;|'
        }

        $(this).find('[contenteditable="true"]').each(function(j){
            tbl += $(this).text().trim() + ';|'
        })
    })
    console.log(tbl)
    var jqxhr = $.ajax({
        url: '/b2b/programs/ajax?xh',
        type: 'post',
        data: {
            action: 'text_hotels',
            product_id: product_id,
            text: tbl,
        },
        dataType: 'json'
    }).
    done(function(data) {
        $('#tbl-hotels tr').each(function(){
            $(this).find('td:first, td:last').remove()
            $(this).find('td').attr('contenteditable', 'false')
        })
        $('#tbl-hotels-text').attr('contenteditable', 'false')
        $('#tbl-hotels td.url').each(function(){
            var url = $(this).text().split(' : ')
            $(this).html(url[1] == undefined || url[1] == 'undefined' ? url[0] : '<a href="' + url[1] + '" target="_blank">' + url[0] + '</a>')
        })
        $('#edit-tbl-hotels-buttons').hide()
        $('#action-edit-hotels').show()
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
})
// Cancel edit hotels
$('#edit-tbl-hotels-buttons .action-cancel').on('click', function(){
    $('#tbl-hotels').html(orig_text)
    $('#tbl-hotels-text').html(orig_text_2)
    $('#edit-tbl-hotels-buttons').hide()
    $('#action-edit-hotels').show()
})
$('#edit-tbl-hotels-buttons .add-option').on('click', function(){
    $('<tr/>').addClass('row-type-option info').append(td_sort).append('<td colspan="4" contenteditable="true">Option</td>').append(td_delete).appendTo($('#tbl-hotels tbody'))
    $('#tbl-hotels tbody').find('tr:last td:first').focus()
})
$('#edit-tbl-hotels-buttons .add-hotel').on('click', function(){
    $('<tr/>').addClass('row-type-hotel').append(td_sort).append('<td contenteditable="true"></td><td -data-autocomplete-spy class="url" contenteditable="true"></td><td class="text-center" contenteditable="true">1</td><td contenteditable="true"></td>').append(td_delete).appendTo($('#tbl-hotels tbody'))
    $('#tbl-hotels tbody').find('tr:last td:first').trigger('click')
})
$('#edit-tbl-hotels-buttons .add-price').on('click', function(){
    $('<tr/>').addClass('row-type-price').append(td_sort).append('<td colspan="3" contenteditable="true"></td><td class="text-right" contenteditable="true"></td>').append(td_delete).appendTo($('#tbl-hotels tbody'))
    $('#tbl-hotels tbody').find('tr:last td:first').focus()
})
$('#edit-tbl-hotels-buttons .add-text').on('click', function(){
    if ($('#tbl-hotels-text').html() == '') {
        $('#tbl-hotels-text').html('Text here...')
    }
})
$(document).on('click', '#tbl-hotels>tbody>tr>td>i.fa-trash-o', function(){
    $(this).parents('tr').remove()
})

// for(name in CKEDITOR.instances) {
//     CKEDITOR.instances[name].destroy(true);
// }

// Auto complete
$(document.body).on('autocomplete:request', function(event, query, callback) {
    query = query.toLowerCase();
    callback(hotels.filter(function(hotel) {
        return hotel.toLowerCase().indexOf(query) !== -1;
    }));
});

TXT;

$js .= USER_ID != 1 ? '' : <<<'TXT'

// Price table
$('#action-edit-price').on('click', function(){
    orig_text = $('#tbl-price').html()
    orig_text_2 = $('#tbl-price-text').html()
    $(this).hide()
    $('#edit-tbl-price-buttons').show()
    // $('#tbl-price tr').each(function(i){
    //     var tr = $(this)
    //     tr.find('td, th').attr('contenteditable', 'true')
    //     if (i == 0) {
    //         $('<td/>').attr('width', 16).prependTo(tr)
    //         $('<td/>').attr('width', 16).appendTo(tr)
    //     } else {
    //         tr.prepend(td_sort).append(td_delete)
    //     }
    // })
    // $('#tbl-price-text').attr('contenteditable', 'true')
    // $('#tbl-price tbody').sortable({
    //     handle: '.sort-row'
    // });

    // Header row renderer
    function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        // Add styles to the table cell
        td.style.fontWeight = '500';
        td.style.color = '#000';
        td.style.background = '#eee';
    }
var data = [
      ['', 'Kia', 'Nissan', 'Toyota', 'Honda', 'Mazda', 'Ford'],
      ['2012', 10, 11, 12, 13, 15, 16],
      ['2013', 10, 11, 12, 13, 15, 16],
      ['2014', 10, 11, 12, 13, 15, 16],
      ['2015', 10, 11, 12, 13, 15, 16],
      ['2016', 10, 11, 12, 13, 15, 16]
    ],
    container1 = document.getElementById('div-price'),
    hot1;
    $('#tbl-price').empty()
    hot1 = new Handsontable(container1, {
        cells: function (row, col, prop, td) {
            var cellProperties = {};

            if (row === 0 || this.instance.getData()[row][col] === 'Read only') {
                cellProperties.readOnly = true; // make cell read-only if it is first row or the text reads 'readOnly'
            }
            if (row === 0) {
                cellProperties.renderer = firstRowRenderer; // uses function directly
            }
            else {
                // cellProperties.renderer = "negativeValueRenderer"; // uses lookup map
            }

            return cellProperties;
        },
        contextMenu: true,
        data: data,
        margeCells: true,
        stretchH: 'all',
        tableClassName: 'table table-narrow table-bordered'
    });
    return false;
})

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
        text += '_;|'
        $(this).find('[contenteditable="true"]').each(function(j){
            text += $(this).text().trim() + ';|'
        })
    })
    text += '\n' + 'text_;|' + $('#tbl-price-text').text()
    // console.log(text)
    var jqxhr = $.ajax({
        url: '/b2b/programs/ajax?xh',
        type: 'post',
        data: {
            action: 'text_price',
            product_id: product_id,
            text: text,
        },
        dataType: 'json'
    }).
    done(function(data) {
        $('#tbl-price tr').each(function(){
            $(this).find('td:first, td:last').remove()
            $(this).find('td').attr('contenteditable', 'false')
        })
        $('#tbl-price-text').attr('contenteditable', 'false')
        $('#edit-tbl-price-buttons').hide()
        $('#action-edit-price').show()
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
})

// Cancel edit price
$('#edit-tbl-price-buttons .action-cancel').on('click', function(){
    $('#tbl-price').html(orig_text)
    $('#tbl-price-text').html(orig_text_2).attr('contenteditable', 'false')
    $('#edit-tbl-price-buttons').hide()
    $('#action-edit-price').show()
})

$('#edit-tbl-price-buttons .add-row').on('click', function(){
    $('#tbl-price tr:last').clone(true, true).appendTo('#tbl-price tbody').find('td.editable').html('')
})

$('#edit-tbl-price-buttons .add-text').on('click', function(){
    if ($('#tbl-price-text').html() == '') {
        $('#tbl-price-text').html('Text here...')
    }
})

$('#edit-tbl-price-buttons .add-span').on('click', function(){
    // var numcol = $('#tbl-price thead th.editable').length - 2
    // if (numcol > 1) {
    //     $('#tbl-price tbody tr:last .editable:gt(1)').remove()
    //     $('#tbl-price tbody tr:last td.editable:eq(1)').prop('colspan', numcol - 1)
    // }
})

$('#edit-tbl-price-buttons .add-col').on('click', function(){
    // var numcol = $('#tbl-price thead tr td').length
    // $('#tbl-price thead tr th.editable:last').after('<th width="10%" class="editable text-center" contenteditable="true"></th>')
    // $('#tbl-price tbody tr').each(function(){
    //     var newcell = $('<td class="text-center editable" contenteditable="true"></td>')
    //     $(this).find('.editable:last').after(newcell)
    // })
})
$('#edit-tbl-price-buttons .rem-col').on('click', function(){
    // var numcol = $('#tbl-price thead th.editable').length
    // if (numcol > 1) {
    //     $('#tbl-price tr').each(function(){
    //         $(this).find('.editable:last').remove()
    //     })
    // }
})

$('#tbl-price').on('click', '.rem-row', function(){
    $(this).parents('tr').remove()
})


TXT;

// $this->registerJsFile('https://unpkg.com/contenteditable-autocomplete@1.0.2/dist/contenteditable-autocomplete.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerCssFile('https://unpkg.com/contenteditable-autocomplete@1.0.2/dist/contenteditable-autocomplete.css', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.34.0/handsontable.full.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.34.0/handsontable.full.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($js);