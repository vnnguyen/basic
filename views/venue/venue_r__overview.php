<?php
use yii\helpers\FileHelper;
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
use yii\helpers\Markdown;

$list_rate = ['1'=>'Poor','2'=>'Average', '3'=>'Good', '4'=>'Very good', '5'=>'Excellent'];
?>
<div class="row">
<div id="venueside" class="col-sm-4 order-12">
    <?php
    if ($theVenue['image'] == '') {
        if ($theVenue['images'] != '') {
            $pos = strpos($theVenue['images'], '">');
            if (false !== $pos) {
                $img = substr($theVenue['images'], 0, $pos + 2);
                $img = str_replace('src=', 'class="img-fluid" src=', $img);
                echo $img;
            } else {
                $imgs = explode(';|', $theVenue['images']);
                if (isset($imgs[0]) && $imgs[0] != '') {
                    $theVenue['image'] = $imgs[0];
                }
            }

        }
    }

    echo Html::a(Html::img($theVenue['image'], ['class'=>'img-fluid']), $theVenue['image'], ['data-fancybox'=>'gallery', 'title'=>'View image gallery']);
    ?>
    <div style="height:51px; overflow:hidden;" class="nicescroll">
        <div style="height:50px; width:1200px; margin:1px 0 0 0">
        <?php
        if ($theVenue['images'] != '') {
            if (substr($theVenue['images'], 0, 4) == 'http') {
                $imgs = explode(';|', $theVenue['images']);
            } else {
                $imgs = str_replace(['<img src="', '<img src= "', '">', chr(10), chr(13)], ['', '', '|', '', ''], $theVenue['images']);
                $imgs = explode('|', trim($imgs, '|'));
            }

            foreach ($imgs as $img) {
                if ($img != $theVenue['image']) {
                    echo Html::a(Html::img($img, ['style'=>'float:left; height:50px; margin:0 1px 0;']), $img, ['data-fancybox'=>'gallery', 'data-caption'=>$theVenue['name']]);
                }
            }
        }
        $uploadDir = Yii::getAlias('@webroot').'/upload/venues/'.substr($theVenue['created_at'], 0, 7).'/'.$theVenue['id'];
        if (is_dir($uploadDir)) {
            $imgs = FileHelper::findFiles($uploadDir);
            foreach ($imgs as $img) {
                $img = str_replace(Yii::getAlias('@webroot'), '', $img);
                echo Html::a(Html::img($img, ['style'=>'float:left; height:50px; margin:0 1px 0;']), $img, ['data-fancybox'=>'gallery', 'data-caption'=>$theVenue['name']]);
            }
        }

        ?>
        </div>
    </div>

    <div class="card table-responsive">
        <table class="table card-table table-narrow table-framed" style="border-bottom:1px solid #ddd;">
            <tr class="info">
                <td colspan="2" class="text-center" style="padding-left:0!important; padding-right:0!important;">
                    <div class="row">
                        <div class="col">
                            <h4 style="margin:0"><?= $starNum ?></h4>
                            <?= $starNum == '' ? 'rating' : 'stars' ?>
                        </div>
                        <div class="col">
                            <h4 style="margin:0"><?= $numYears ?></h4>
                            years
                        </div>
                        <div class="col">
                            <h4 style="margin:0"><?= $numTours ?></h4>
                            tours
                        </div>
                        <div class="col">
                            <h4 style="margin:0">
                                <?php
                                $y = date('y');
                                $yyyy = '**';
                                $contractYears = [$y + 2, $y + 1, $y];
                                foreach ($theVenue['dvc'] as $dvc) {
                                    foreach ($contractYears as $contractYear) {
                                        if ((strpos($dvc['valid_until_dt'], '20'.$contractYear) !== false || strpos($dvc['valid_until_dt'], '-'.$contractYear) !== false) && $contractYear > (int)$yyyy) {
                                            $yyyy = $contractYear;
                                        }
                                    }
                                }
                                echo '20'.$yyyy;
                                ?>
                            </h4>
                            contract
                        </div>
                    </div>
                </td>
            </tr>

            <?php if ($theVenue['latlng'] != '') { ?>
            <tr>
                <th>Map</th>
                <td>
                    <a href="javascript:;" onclick="$('.view_map').toggle()"><span class="view_map">Show map</span><span class="view_map" style="display:none;">Hide map</span></a>
                    -
                    <a target="_blank" href="https://www.google.com/maps/search/<?= urlencode($theVenue['name']) ?>+Hotel/@<?= $theVenue['latlng'] ?>,16z">Google Maps</a>
                    -
                    <a target="_venuemap" href="/venues/map?center=<?= $theVenue['latlng'] ?>">Hotel map</a>
                </td>
            </tr>
            <tr style="display:none;" class="view_map">
                <td colspan="2" style="padding:0!important;">
                    <a target="_blank" href="https://www.google.com/maps/search/<?= urlencode($theVenue['name']) ?>+Hotel/@<?= $theVenue['latlng'] ?>,16z"><img class="img-fluid" src="https://maps.googleapis.com/maps/api/staticmap?markers=color:blue%7Clabel:V%7C<?= $theVenue['latlng'] ?>&center=<?= $theVenue['latlng'] ?>&zoom=16&scale=2&size=480x300&sensor=true"></a>
                </td>
            </tr>
            <?php } ?>
            <?php foreach ($venueMetas as $li) { ?>
            <tr>
                <th><?= ucfirst($li['name']) ?></th>
                <td>
                    <?
                    if ($li['name'] == 'website') {
                        if (substr($li['value'], 0, 7) != 'http://' && substr($li['value'], 0, 8) != 'https://') {
                            $li['value'] = 'http://'.$li['value'];
                        }
                        echo Html::a($li['value'], $li['value'], ['target'=>'_blank']);
                    } else {
                        echo $li['value'];
                    }
                    ?>
                    <?= $li['note'] != '' ? '<em>'.$li['note'].'</em>' : '' ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <th>View on</th>
                <td>
    <?php if ($theVenue['link_tripadvisor'] != '') { ?><?= Html::a(Html::img(DIR.'assets/img/logo-tripadvisor.jpg', ['style'=>'height:20px; margin-right:16px;']), $theVenue['link_tripadvisor'], ['rel'=>'external', 'title'=>'Hotel on TripAdvisor.com']) ?><?php } ?>
    <?php if ($theVenue['link_booking'] != '') { ?><?= Html::a(Html::img(DIR.'assets/img/logo-booking.jpg', ['style'=>'height:20px; margin-right:16px;']), $theVenue['link_booking'], ['rel'=>'external', 'title'=>'Hotel on Booking.com']) ?><?php } ?>
    <?php if ($theVenue['link_agoda'] != '') { ?><?= Html::a(Html::img(DIR.'assets/img/logo-agoda.jpg', ['style'=>'height:20px; margin-right:16px;']), $theVenue['link_agoda'], ['rel'=>'external', 'title'=>'Hotel on Agoda.com']) ?><?php } ?>
    <?= Html::a(Html::img('https://www.google.com.vn/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png', ['style'=>'height:20px; margin-right:16px;']), 'https://www.google.com.vn/search?hl=vi&q='.urlencode($theVenue['name']), ['rel'=>'external', 'title'=>'Search on Google.com']) ?>
                </td>
            </tr>
            <?php if ($venueSupplier) { ?>
            <tr>
                <th>Supplier</th>
                <td>
                    <?= $venueSupplier['name'] ?>
                    (<a href="javascript:;" onclick="$('.view_supplier').toggle()">
                    <span class="view_supplier">More</span>
                    <span class="view_supplier" style="display:none;">Less</span>
                    </a>)
                </td>
            </tr>
            <tr class="view_supplier" style="display:none;">
                <th></th>
                <td style="-padding-left:0!important; padding-right:0!important">
                    <p><strong><?= $venueSupplier['name'] ?></strong>
                        <br><?= $venueSupplier['name_full'] ?>
                    </p>
                    <p><strong>Tax info:</strong><br><?= nl2br($venueSupplier['tax_info']) ?></p>
                    <p><strong>Bank info:</strong><br><?= nl2br($venueSupplier['bank_info']) ?></p>
                    <p><?= Html::a('View supplier', '@web/suppliers/r/'.$theVenue['company_id']) ?></p>
                    <?php if (count($venueSupplier['venues']) > 1) { ?>
                    <hr>
                    <p><strong>All venues by this supplier</strong></p>
                    <?php foreach ($venueSupplier['venues'] as $venue) { ?>
                    <div class="mb-10">
                        <?php if ($venue['image'] != '') { ?>
                        <img src="<?= $venue['image'] ?>" class="float:left;" style="width:50%; margin:0 1em 1em 0;">
                        <?= Html::a($venue['name'], '/venues/r/'.$venue['id']) ?>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
<div id="venuemain" class="col-sm-8 order-1">
    <?php if (!empty($venueEvents)) { ?>
    <div class="alert alert-warning">
        <?php foreach ($venueEvents as $event) { ?>
        <div>
            <i class="fa fa-lock"></i>
            <strong><?= Yii::t('x', 'TEMPORARY CLOSED') ?>:</strong>
            <strong><?= date('j/n/Y', strtotime($event['from_dt'])) ?> -- <?= date('j/n/Y', strtotime($event['until_dt'])) ?></strong>
            <br><?= $event['note'] ?>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <?php if (in_array($theVenue['stype'], ['hotel', 'homestay'])) { ?>

    <?php if (strpos($theVenue['new_tags'], 'new_o_new') !== false || strpos($theVenue['new_tags'], 'new_o_both') !== false) { ?>
    <?php $newTags = explode(';|', $theVenue['new_tags']); ?>
    <div style="padding:20px; background-color:#fcfcf3; border:1px solid #eee;">
        <div class="row">
            <?php if (strpos($theVenue['new_tags'], 'sr_s') !== false || strpos($theVenue['new_tags'], 'sr_r') !== false) { ?>
            <div class="col-sm-6">
                <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Amica') ?>:</strong>
                <?php if (strpos($theVenue['new_tags'], 'sr_s') !== false) { ?><span class="text-bold text-pink"><?= Yii::t('x', 'strategic') ?></span><?php } ?>
                <?php if (strpos($theVenue['new_tags'], 'sr_r') !== false) { ?><span class="text-bold text-primary"><?= Yii::t('x', 'recommended') ?></span><?php } ?>
            </div>
            <?php } ?>
            <?php if ($theVenue['about'] != '') { ?>
            <div class="col-sm-6">
                <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Old name') ?>:</strong>
                <em><?= $theVenue['about'] ?></em>
            </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Type') ?>:</strong>
                <?php foreach ($newTags as $newTag) { ?>
                    <?php if (array_key_exists($newTag, $venueTypeList)) { ?>
                    <?= $venueTypeList[$newTag] ?>
                    <?php break; } ?>
                <?php } ?>
            </div>
            <div class="col-sm-6">
                <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Classification') ?>:</strong>
                <?php foreach ($newTags as $newTag) { ?>
                    <?php if (array_key_exists($newTag, $venueClassiList)) { ?>
                    <?= $venueClassiList[$newTag] ?>
                    <?php break; } ?>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Style') ?>:</strong>
                <?php foreach ($newTags as $newTag) { ?>
                    <?php if (array_key_exists($newTag, $venueStyleList)) { ?>
                    <?= $venueStyleList[$newTag] ?>
                    <?php break; } ?>
                <?php } ?>
            </div>
            <div class="col-sm-6">
                <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Architecture') ?>:</strong>
                <?php foreach ($newTags as $newTag) { ?>
                    <?php if (array_key_exists($newTag, $venueArchiList)) { ?>
                    <?= $venueArchiList[$newTag] ?>
                    <?php break; } ?>
                <?php } ?>
            </div>
        </div>

        <?php
        $hasDistInfo = false;
        foreach ($newTags as $newTag) {
            if (substr($newTag, 0, 5) == 'vdist' && strlen($newTag) != 7) {
                $hasDistInfo = true;
            }
        }
        if ($hasDistInfo) { ?>
        <div class="row">
            <div class="col-sm-12">
                <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Distances') ?>:</strong>
                <?php foreach ($newTags as $newTag) { ?>
                    <?php if (substr($newTag, 0, 7) == 'vdistc_') { echo substr($newTag, 7), 'km ', Yii::t('x', 'from city center'), ';'; } ?>
                    <?php if (substr($newTag, 0, 7) == 'vdista_') { echo substr($newTag, 7), 'km ', Yii::t('x', 'from airport'), ';'; } ?>
                    <?php if (substr($newTag, 0, 7) == 'vdistb_') { echo substr($newTag, 7), 'km ', Yii::t('x', 'from beach'), ';'; } ?>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

        <?php if ($theVenue['new_pricemin'] != 0) { ?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Price range') ?>:</strong> <span class="text-warning"><?= $theVenue['new_pricemin'] ?></span>
                <?php if ($theVenue['new_pricemax'] !=0 && $theVenue['new_pricemax'] > $theVenue['new_pricemin']) { ?> - <span class="text-warning"><?= $theVenue['new_pricemax'] ?></span><?php } ?>
                USD
            </div>
        </div>
        <?php } ?>

        <?php
        /*
        $faciList = [];
        foreach ($venueFaciList as $code=>$faci) {
            if (strpos($theVenue['new_tags'], $code) !== false) {
                $faciList[] = $faci;
            }
        }

        if (!empty($faciList)) {
        ?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'Facilities/Services') ?>:</strong></div>
        </div>
        <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $faciList) ?></div></div>
        <?php
        }
        */

        $faciList = [];
        $c_faci = ['5_19', '5_47', '5_22', '5_25', '5_23', '5_02', '5_03', '5_51', '5_52', '5_30', '5_53', '5_21', '5_54', '5_55', '5_09', '5_56', '5_11', '5_18', '5_57', '5_20', '5_17', '5_14', '5_12', '5_68', '5_24', '5_01', '5_58', '5_06', '5_59'];
        $c_faci_room = ['5_27', '5_43', '5_42', '5_60', '5_33', '5_32', '5_31', '5_35', '5_69', '5_28', '5_34', '5_70', '5_71', '5_36', '5_61'];
        $c_wellness = ['5_37', '5_62', '5_63', '5_38', '5_62'];
        $c_children = ['5_26', '5_50', '5_72', '5_64'];
        $c_mul_staff = ['5_41', '5_40', '5_48'];
        $c_faci_dis_guests = ['5_65', '5_66', '5_67'];
        $c_faci_list = [];
        $c_faci_room_list = [];
        $c_wellness_list = [];
        $c_children_list = [];
        $c_mul_staff_list = [];
        $c_faci_dis_guests_list = [];
        foreach ($venueFaciList as $code=>$faci) {
            if (strpos($theVenue['new_tags'], $code) !== false) {
                if(strpos($theVenue['new_tags'], $code.'_') !== false) {
                    $faci .= ' <i class="fa fa-dollar text-warning" title="Available at a fee"></i>';
                }
                if (in_array($code, $c_faci)) {
                    $c_faci_list[] = $faci;
                }
                if (in_array($code, $c_faci_room)) {
                    $c_faci_room_list[] = $faci;
                }
                if (in_array($code, $c_wellness)) {
                    $c_wellness_list[] = $faci;
                }
                if (in_array($code, $c_children)) {
                    $c_children_list[] = $faci;
                }
                if (in_array($code, $c_mul_staff)) {
                    $c_mul_staff_list[] = $faci;
                }
                if (in_array($code, $c_faci_dis_guests)) {
                    $c_faci_dis_guests_list[] = $faci;
                }
            }
        }
        // var_dump($c_faci_list);die;

        ?>
        <?php if (!empty($c_faci_list)) {?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'Facilities') ?>:</strong></div>
        </div>
        <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $c_faci_list) ?></div></div>
        <?php }?>
        <?php if (!empty($c_faci_room_list)) {?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'Facilities in the room') ?>:</strong></div>
        </div>
        <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $c_faci_room_list) ?></div></div>
        <?php }?>
        <?php if (!empty($c_wellness_list)) {?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'Wellness') ?>:</strong></div>
        </div>
        <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $c_wellness_list) ?></div></div>
        <?php }?>
        <?php if (!empty($c_children_list)) {?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'For children') ?>:</strong></div>
        </div>
        <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $c_children_list) ?></div></div>
        <?php }?>
        <?php if (!empty($c_mul_staff_list)) {?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'Multilingual staff') ?>:</strong></div>
        </div>
        <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $c_mul_staff_list) ?></div></div>
        <?php }?>
        <?php if (!empty($c_faci_dis_guests_list)) {?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'Facilities for disabled guests') ?>:</strong></div>
        </div>
        <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $c_faci_dis_guests_list) ?></div></div>
        <?php }

        $reccList = [];
        foreach ($venueReccList as $code=>$recc) {
            if (strpos($theVenue['new_tags'], $code) !== false) {
                $reccList[] = $recc;
            }
        }

        if (!empty($reccList)) {

        ?>
        <div class="row">
            <div class="col-sm-12"><i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> <strong><?= Yii::t('x', 'Recommended for') ?>:</strong> <?= implode(', ', $reccList) ?></div>
        </div>
        <?php
        }
        ?>
    </div>
    <?php } // if newTags ?>
    <br>

    <?php } // if hotel ?>

    <div><?= $theVenue['info'] ?></div>

    <?php if (strpos($theVenue['new_tags'], 'new_o_new') === false || strpos($theVenue['new_tags'], 'new_o_both') !== false) { ?>
    <hr>
    <!-- MANH CHEN CODE VAO DAY -->
    <?php if ($the_venue_temp) { ?>
    <p class="text-info"><i class="fa fa-info-circle"></i> Đây là phần thông tin đã được update cho đến hiện tại</p>

    <div class="row">
        <div class="col-md-12" style="margin-top: -15px">
            <h3>HOTEL INFORMATION</h3>
            <b>Hotel category: </b><?= $the_venue_temp['cat']?><br>
            <?php if($the_venue_temp['cmt']!= ''){?>
            <b>Comment by Amica: </b>
            <p><?= $the_venue_temp['cmt']?></p>
            <?php }?>
            <b>Location: </b><?= $the_venue_temp['loc']?> <br>
            <b>Price Range : </b> <?= $the_venue_temp['price_min'] . ' - ' . $the_venue_temp['price_max']?> USD<br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <b>Facilities/services : </b><br>
            <ul>
                <li>Lift : <?= $the_venue_temp['fac_lift']?></li>
                <li>Swimming pool : <?= $the_venue_temp['fac_pool']?></li>
                <li>Garden : <?= $the_venue_temp['fac_garden']?></li>
                <li>Spa : <?= $the_venue_temp['fac_spa']?></li>
                <li>Restaurant  to recommend : <?= $the_venue_temp['fac_restaurant']?> - Breakfast : <?= $the_venue_temp['fac_breakfast_type']?></li>
            </ul>
            <b>Eco-Responsible Approach : </b><?= $the_venue_temp['is_eco']?><br>
        </div>
        <div class="col-md-6">
            <b>Recommended for: </b> <br>
            <ul>
                <li>Couple : <?= $the_venue_temp['rec_couple']?></li>
                <li>Family : <?= $the_venue_temp['rec_family']?></li>
                <li>Group : <?= $the_venue_temp['rec_group']?></li>
                <li>Honeymoon : <?= $the_venue_temp['rec_honeymoon']?></li>
                <li>Demanding travelers : <?= $the_venue_temp['rec_demanding']?></li>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12" style="margin-top: -15px">
        <h3>ROOM: </h3>
        <table class="table table-narrow table-framed mb-20" >
            <tbody>
                <tr>
                    <th style="text-align: center;">Category</th>
                    <th style="text-align: center;">Number</th>
                    <th style="text-align: center;">Features</th>
                    <th style="text-align: center;">DBL\TWN</th>
                    <th style="text-align: center;">Triple</th>
                    <th style="text-align: center;">Connecting</th>
                    <th style="text-align: center;">Extra bed</th>
                    <th style="text-align: center;">Ok for selling</th>
                    <th style="text-align: center;">Price</th>
                    <th style="text-align: center;">Note</th>
                </tr>
                <?
                $the_room = [];
                foreach ($the_room as $room){
                ?>
                <tr>
                    <th><?= $room['name'] ?></th>
                    <td><?= $room['count'] ?></td>
                    <td><?= $room['features'] ?></td>
                    <td><?= $room['dbl'].' / '.$room['twn'] ?></td>
                    <td><?= $room['tpl'] ?></td>
                    <td><?= $room['conn'] ?></td>
                    <td><?= $room['eb'] ?></td>
                    <td ><?= $room['sell'] ?></td>
                    <td><?= $room['price'] ?></td>
                    <td><?= $room['note'] ?></td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-top: -15px">
        <h3>SERVICES AND FACILITIES</h3>
        <b>Bedding : </b> <?= $list_rate[$the_venue_temp['rating_bedding']] ?? '' ?><br>
        <b>Amica rating : </b>
        <ul>
            <li>Services : <?= $list_rate[$the_venue_temp['rating_service']] ?? '' ?> </li>
            <li>Value for money : <?= $list_rate[$the_venue_temp['rating_value']] ?? '' ?> </li>
            <li>General cleanliness : <?= $list_rate[$the_venue_temp['rating_cleanliness']] ?? '' ?> </li>
        </ul>
        <b>General rating :</b><?= $list_rate[$the_venue_temp['rating_general']] ?? '' ?><br>
        <b>Final verdict: </b><br>
        <p>
            <?= $the_venue_temp['verdict'] ?>
        </p>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-12" style="margin-top: -15px">
        <h3>INSPECTION HISTORY</h3>
            <?php $insp = unserialize($the_venue_temp['inspections']); if (!is_array($insp)) $insp = []; ?>
            <?php foreach ($insp as $v){
                echo $v['date']. ' : ' .$v['by'];
            }?>
        </div>
    </div>
    <?php } ?>
    <!-- /MANH CHEN CODE VAO DAY -->

    <?php if ($theVenue['hotel_meta'] != '') { ?>
    <!-- DATA CHI LOAN/ THU -->
    <table class="table table-narrow table-framed mb-20">
        <tbody>
            <tr>
                <th>Tags</th><td><?= str_replace(['str ', 're '], ['<span class="text-pink">strategic</span> ', '<span class="text-success">recommended</span> ', ], $theVenue['search']) ?></td>
            </tr>
            <?
            $data = unserialize($theVenue['hotel_meta']);
            foreach ($data as $k=>$v) {
                 if ($k != 'image2') {
            ?>
            <tr>
                <th><?= ucfirst($k) ?></th>
                <td><?= nl2br($v) ?></td>
            </tr>
            <?
                }
            }
            ?>
        </tbody>
    </table>
    <?php } ?>

    <?php } // new_o_old or new_o_both ?>

    <ul class="media-list">
        <?php foreach ($venueNotes as $li) { ?>
        <div style="padding:15px 0; margin:15px 0 0; border-top:1px solid #eee;">
            <a class="pull-left" style="margin-right:15px;" href="<?= DIR ?>users/<?= $li['updatedBy']['id'] ?>"><img style="width:64px; height:64px;" class="media-object img-circle" src="<?= DIR.'timthumb.php?w=100&h=100&src='.$li['updatedBy']['image'] ?>" alt="Avatar"></a>
            <?= Html::a('<i class="fa fa-trash-o"></i>', '@web/posts/'.$li['id'].'/d', ['class'=>'text-muted pull-right', 'title'=>'Delete']) ?>
            <?= Html::a('<i class="fa fa-edit"></i>', '@web/posts/'.$li['id'].'/u', ['class'=>'text-muted pull-right', 'title'=>'Edit']) ?>
            <h4 style="font-weight:bold; margin:0;"><?= Html::a($li['title'] == '' ? Yii::t('x', '(No title)') : $li['title'], '@web/posts/'.$li['id']) ?></h4>
            <div><?= $li['updatedBy']['name'] ?> <em><?= DateTimeHelper::convert(($li['updated_dt'] ?? $li['created_dt']), 'j/n/Y H:i') ?></em></div>
            <div style="margin-left:80px" class="clear clearfix">
            <?php if (!empty($li['attachments'])) { ?>
            <div class="list list-files mb-1em">
                <?php foreach ($li['attachments'] as $file) { ?>
                <div>+ <?= Html::a($file['name'], '@web/attachments/'.$file['id']) ?></div>
                <?php } ?>
            </div>
            <?php } ?>
            <?= $li['body']?>
            </div>
        </div>
        <?php } ?>
    </ul>
</div>

</div>