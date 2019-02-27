<?php
use yii\helpers\FileHelper;
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
use yii\helpers\Markdown;


$venueItinerary = [
    'day'=>'Day use',
    '2D1N'=>'2D1N',
    '3D2N'=>'3D2N',
    'other'=>'other',
];
$venueReccList = [
    '6_01'=>Yii::t('x', 'Couple'),
    '6_02'=>Yii::t('x', 'Family with kids'),
    '6_03'=>Yii::t('x', 'Group'),
    '6_04'=>Yii::t('x', 'Honeymoon'),
    '6_05'=>Yii::t('x', 'Demanding travelers'),
    '6_06'=>Yii::t('x', 'Old people'),
    '6_07'=>Yii::t('x', 'Young people'),
    '6_08'=>Yii::t('x', 'Family with teens'),
];
$venueServiceList_include_price = [
    //include price
    '5_1_01'=>Yii::t('x', 'Air conditioner'),
    '5_1_02'=>Yii::t('x', 'Bathtub'),
    '5_1_03'=>Yii::t('x', 'Breakfast'),
    '5_1_04'=>Yii::t('x', 'Brunch'),
    '5_1_05'=>Yii::t('x', 'Complimentary mineral water'),
    '5_1_06'=>Yii::t('x', 'Cooking class'),
    '5_1_07'=>Yii::t('x', 'Dinner'),
    '5_1_08'=>Yii::t('x', 'Doulbe room'),
    '5_1_09'=>Yii::t('x', 'English speaking guide on board'),
    '5_1_10'=>Yii::t('x', 'Entrance fees as itinerary'),
    '5_1_11'=>Yii::t('x', 'Family room'),
    '5_1_12'=>Yii::t('x', 'Fruit or drink'),
    '5_1_13'=>Yii::t('x', 'Gym / Fitness central'),
    '5_1_14'=>Yii::t('x', 'Hair dryer'),
    '5_1_15'=>Yii::t('x', 'LED TV'),
    '5_1_16'=>Yii::t('x', 'Life jacket'),
    '5_1_17'=>Yii::t('x', 'Lunch'),
    '5_1_18'=>Yii::t('x', 'private toilet'),
    '5_1_19'=>Yii::t('x', 'Room with balcony'),
    '5_1_20'=>Yii::t('x', 'Safety box'),
    '5_1_21'=>Yii::t('x', 'Shower'),
    '5_1_22'=>Yii::t('x', 'Shuttle bus'),
    '5_1_23'=>Yii::t('x', 'Sundeck'),
    '5_1_24'=>Yii::t('x', 'Tai chi'),
    '5_1_25'=>Yii::t('x', 'Twin room'),
    '5_1_26'=>Yii::t('x', 'Wifi'),
];
$venueServiceList_extra_charge = [
    // Extra charge
    '5_2_01'=>Yii::t('x', 'Breakfast'),
    '5_2_02'=>Yii::t('x', 'Casino'),
    '5_2_03'=>Yii::t('x', 'Dinner'),
    '5_2_04'=>Yii::t('x', 'English speaking guide on board'),
    '5_2_05'=>Yii::t('x', 'Entrance fees as itinerary'),
    '5_2_06'=>Yii::t('x', 'Family room'),
    '5_2_07'=>Yii::t('x', 'French speaking guide on board'),
    '5_2_08'=>Yii::t('x', 'Fruit or drink'),
    '5_2_09'=>Yii::t('x', 'Gym / Fitness central'),
    '5_2_10'=>Yii::t('x', 'Brunch'),
    '5_2_11'=>Yii::t('x', 'Laundry'),
    '5_2_12'=>Yii::t('x', 'Lunch'),
    '5_2_13'=>Yii::t('x', 'Safety box'),
    '5_2_14'=>Yii::t('x', 'Salon'),
    '5_2_15'=>Yii::t('x', 'Shop'),
    '5_2_16'=>Yii::t('x', 'Spa and massage serices'),
    '5_2_17'=>Yii::t('x', 'Sundeck'),
    '5_2_18'=>Yii::t('x', 'Tai chi'),
    '5_2_19'=>Yii::t('x', 'Transportation to the deck'),
    '5_2_20'=>Yii::t('x', 'Twin room'),
    '5_2_21'=>Yii::t('x', 'WifiLunch'),
    '5_2_22'=>Yii::t('x', 'Cooking class'),
    '5_2_23'=>Yii::t('x', 'Other activities'),
];
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
    <?php if (strpos($theVenue['new_tags'], 'new_o_new') !== false || strpos($theVenue['new_tags'], 'new_o_both') !== false) { ?>
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
        <?php $newTags = explode(';|', $theVenue['new_tags']); ?>
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
        <?php $cnt = 0; ?>
        <div class="row">
            <div class="col-sm-12">
                <!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"> --><?= ++ $cnt?>. </i> <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Classification') ?>:</strong>
            <?php foreach ($newTags as $newTag) { ?>
                <?php if (array_key_exists($newTag, $venueClassiList)) { ?>
                    <?= $venueClassiList[$newTag] ?>
                    <?php break; } ?>
                <?php } ?>
            </div>
        </div> <!-- end class row -->
        <div class="row">
            <div class="col-sm-12">
                <!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> --><?= ++ $cnt?>.  <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Itinerary') ?>:</strong>
                <?php
                $newTag = '';
                $arr_tag = [];
                $vnote_itinerary = '';
                ?>

                <?php foreach ($newTags as $tag) {?>
                    <?php if ( strpos($tag, 'vnote_itinerary_') !== false ) {
                        $vnote_itinerary = str_replace('vnote_itinerary_', '', $tag);
                        break;
                    } ?>
                <?php } ?>
                <?php foreach ($newTags as $tag) {?>
                    <?php if (array_key_exists($tag, $venueItinerary)) {
                        $note_itinerary = '';
                        if ($vnote_itinerary != '') {
                            $notes = explode(';', $vnote_itinerary);
                            foreach ($notes as $note) {
                                if ( strpos($note, $tag) !== false) {
                                    $note_itinerary = trim(str_replace($tag . ':', '', $note));
                                }
                            }
                        }
                        ?>
                        <?php $arr_tag[] = $venueItinerary[$tag] . ': ' . $note_itinerary ?>
                    <?php } ?>
                <?php } ?>
                <div><div> - <?= implode('</div><div> - ', $arr_tag) ?></div></div>
                <?//= implode(', ', $arr_tag); ?>
            </div>
        </div><!-- end class row -->
        <div class="row">
            <div class="col-sm-12">
                <!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> --><?= ++ $cnt?>.  <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Departing from') ?>:</strong>
                <?php $arr_tag = [];?>
                <?php foreach ($newTags as $tag) {
                    $newTag = ''; ?>
                    <?php if (strpos($tag, 'vdepart_from') !== false) {$newTag =  'vdepart_from'; $label = Yii::t('x', 'Depart from');}?>
                    <?php if (strpos($tag, 'vcheck_in') !== false) {$newTag =  'vcheck_in'; $label = Yii::t('x', 'Check in');}?>
                    <?php if (strpos($tag, 'vcheck_out') !== false) {$newTag =  'vcheck_out'; $label = Yii::t('x', 'Check out');}?>
                    <?php if ( $newTag != '' ) { ?>
                        <?= '<div>- ' . $label . ': ' . str_replace($newTag . '_', '', $tag) . '</div>';?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div><!-- end class row -->
        <div class="row">
            <div class="col-sm-12">
                <!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> --><?= ++ $cnt?>.  <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Ship profile') ?>:</strong>
                <?php foreach ($newTags as $tag) { //var_dump($newTags);die;?>
                    <?php if (strpos($tag, 'vship_profile_') !== false) { ?>
                        <?php $newTag = str_replace('vship_profile_', '', $tag); ?>
                        <?= $newTag ?>
                        <?php break; } ?>
                <?php } ?>
            </div>
        </div><!-- end class row -->


        <?php if ($theVenue['new_pricemin'] != 0) { ?>
        <div class="row">
            <div class="col-sm-12"><!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> --><?= ++ $cnt?>.  <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Price range') ?>:</strong> <span class="text-warning"><?= $theVenue['new_pricemin'] ?></span>
                <?php if ($theVenue['new_pricemax'] !=0 && $theVenue['new_pricemax'] > $theVenue['new_pricemin']) { ?> - <span class="text-warning"><?= $theVenue['new_pricemax'] ?></span><?php } ?>
                USD
            </div>
        </div><!-- end class row -->
        <?php } ?>

        <div class="row">
            <div class="col-sm-12">
                <!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> --><?= ++ $cnt?>.  <strong style="display:inline-block; "><?= Yii::t('x', 'Services include price') ?>:</strong>
                <?php $arr_service = []; ?>
                <?php foreach ($newTags as $tag) {?>
                    <?php if (array_key_exists($tag, $venueServiceList_include_price)) { ?>
                        <?php $arr_service[] =  $venueServiceList_include_price[$tag];?>
                    <?php } ?>
                <?php } ?>
                <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $arr_service) ?></div></div>
            </div>
        </div><!-- end class row -->
        <div class="row">
            <div class="col-sm-12">
                <!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> --><?= ++ $cnt?>.  <strong style="display:inline-block; "><?= Yii::t('x', 'Services extra charge') ?>:</strong>
                <?php $arr_service = []; ?>
                <?php foreach ($newTags as $tag) {?>
                    <?php if (array_key_exists($tag, $venueServiceList_extra_charge)) { ?>
                        <?php $arr_service[] =  $venueServiceList_extra_charge[$tag];?>
                    <?php } ?>
                <?php } ?>
                <div style="-webkit-column-count: 3; -moz-column-count: 3; column-count:3"><div> - <?= implode('</div><div> - ', $arr_service) ?></div></div>
            </div>
        </div><!-- end class row -->
        <div class="row">
            <div class="col-sm-12">
                <!-- <i class="fa fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> --><?= ++ $cnt?>.  <strong style="display:inline-block; width:120px"><?= Yii::t('x', 'Recomment for') ?>:</strong>

                <?php $arr_tag = []; ?>
                <?php foreach ($newTags as $tag) { ?>
                    <?php if (array_key_exists($tag, $venueReccList)) {?>
                        <?php $arr_tag[] = $venueReccList[$tag]; ?>
                    <?php } ?>
                <?php } ?>
                <?= implode(', ', $arr_tag);?>
            </div>
        </div><!-- end class row -->
    <?php } ?>
        <div><?= ++ $cnt?>. <?= $theVenue['info'] ?></div>


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
    </div>  <!-- end col-sm-8 -->
</div>