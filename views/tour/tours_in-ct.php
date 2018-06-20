<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

require_once('_tours_inc.php');

$language = $theForm->language;

$countryList = \common\models\Country::find()
    ->select(['name_en', 'name_vi', 'code'])
    ->asArray()
    ->all();

Yii::$app->params['page_title'] = 'In chương trình: '.$theTour['op_code'];

Yii::$app->params['page_breadcrumbs'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['In chương trình'],
];

$dayIdList = explode(',', $theTour['day_ids']);

$sectionList = [
    'pax'=>Yii::t('in_ct', 'Pax list', null, $language),
    'summary'=>Yii::t('in_ct', 'Summary', null, $language),
    'itinerary'=>Yii::t('in_ct', 'Itinerary', null, $language),
    'price'=>Yii::t('in_ct', 'Price table', null, $language),
    'conditions'=>Yii::t('in_ct', 'Conditions', null, $language),
];

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');


Yii::$app->params['page_title'] = 'Print tour itinerary: '.$theTour['op_code'].' - '.$theTour['op_name'];
Yii::$app->params['page_layout'] = '-t -s -f';
Yii::$app->params['body_class'] = 'bg-white';


require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

$dayIdList = explode(',', $theTour['day_ids']);
$showDays = [];
$ranges = explode(',', $theForm->days);
foreach ($ranges as $range) {
    $rr = explode('-', $range);
    if (isset($rr[1])) {
        //1-3
        for ($i = (int)trim($rr[0]); $i <= (int)trim($rr[1]); $i ++) {
            $showDays[] = $i;
        }
    } else {
        //4
        $showDays[] = trim($range);
    }
}

$totalPaxCount = 0;
foreach ($theTour['bookings'] as $booking) {
    $totalPaxCount += $booking['pax'];
}

$languageList = [
    'en'=>'English',
    'fr'=>'Français',
    'vi'=>'Tiếng Việt',
];

Yii::$app->formatter->locale = $language;

?>
<style>
#print-body {background:#fff; font:15px/20px 'Times New Roman', serif;}
#print-body h1, #print-body h2, #print-body h3, #print-body h4, #print-body h5, #print-body h6, #print-body .heading, #print-body .sserif {font-family: Roboto, Helvetica, Arial, sans-serif; padding:0; margin:0;}
.table>tbody>tr>td, .table>tbody>tr>th {vertical-align:top;}
#print-body .hidden-print {color:#eee!important;}
.hidden-print.flag-icon {display:none;}
@media print {
    @page {size: A4 portrait;}
    #print-body {background:#fff; font:13px/16px 'Times New Roman', serif;}
    #print-body h1, #print-body h2, #print-body h3, #print-body h4, #print-body h5, #print-body h6, #print-body .heading, #print-body .sserif {font-family: Roboto, Helvetica, Arial, sans-serif; padding:0; margin:0;}
}
</style>
<div class="col-md-4 hidden-print">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('in_ct', 'Print options') ?></h6>
        </div>
        <div class="panel-body">
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theForm, 'language')->dropdownList($languageList)->label(Yii::t('in_ct', 'Print language')) ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theForm, 'days')->label(Yii::t('in_ct', 'Print days')) ?></div>
                <div class="col-md-6"><?= $form->field($theForm, 'logo')->dropdownList(ArrayHelper::map($logoList, 'id', 'company'), ['class'=>count($logoList) == 1 ? 'hidden' : 'form-control'])->label(count($logoList) == 1 ? false : null) ?></div>
            </div>
            <?= $form->field($theForm, 'sections')->checkboxList($sectionList)->label(Yii::t('in_ct', 'Sections to print')) ?>
            <?= $form->field($theForm, 'note')->textArea(['rows'=>5])->label(Yii::t('in_ct', 'Also print this note')) ?>
            <div class="form-group">
                <label class="control-label" for="ducanh"><input type="checkbox" id="ducanh" name="ducanh" value="ducanh"> <?= Yii::t('in_ct', 'Print full summary section even though not all days have been selected') ?></label>
            </div>
            <p class="text-info">
                <i class="fa fa-info-circle"></i> <?= Yii::t('in_ct', 'Text that is grayed out will not be printed. You can also click and edit the tour program\'s text before printing it out.') ?>
            </p>
            <div class="row">
                <div class="col-md-6 col-md-offset-3"><?= Html::a('<i class="fa fa-print"></i> '.Yii::t('in_ct', 'Print now'), '#', ['class'=>'btn btn-default btn-block', 'onclick'=>'window.print(); return false;']) ?></div>
            </div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="col-md-8">
    <div id="print-body">
        <div class="clearfix">
            <img id="print-logo" style="width:220px; float:left; margin-bottom:25px; display:inline-block;" src="<?= $logo ?>" alt="Logo" >
            <div style="margin-left:245px; margin-bottom:25px;">
                <h1><?= $theTour['op_code'] ?> &middot; <?= $theTour['op_name'] ?></h1>
                <div class="clearfix sserif" style="border-top:1px solid #333; margin-top:8px; padding-top:8px;">
                    <div><strong><?= $theTour['title'] ?></strong></div>
                    <div><?=$theTour['about']?></div>
                    <div><strong><?= Yii::t('in_ct', 'Number of pax', null, $language) ?>:</strong> <?= $totalPaxCount ?> <?= Yii::t('in_ct', 'pax', null, $language) ?></div>
                    <div><strong><?= Yii::t('in_ct', 'Duration', null, $language) ?> :</strong> <?=$theTour['day_count']?> <?= Yii::t('in_ct', 'days', null, $language) ?>, <?= Yii::t('in_ct', 'from', null, $language) ?> <?= Yii::$app->formatter->asDate($theTour['day_from'], 'php:l j/n/Y') ?></div>
                    <div><strong><?= Yii::t('in_ct', 'Seller', null, $language) ?> :</strong> <?= $theTour['updatedBy']['fname'].' '.$theTour['updatedBy']['lname'] ?></div>
                </div>
            </div>
        </div>

        <div id="section-note" class="<? if ($theForm->note == '') { ?>hidden hidden-print<? } ?>">
            <h2 class="mb-10"><?= Yii::t('in_ct', 'Note', null, $language) ?> :</h2>
            <div class="mb-20" id="note-content">
                <?= nl2br(Html::encode($theForm->note)) ?>
            </div>
        </div><!-- #section-note -->

        <div id="section-pax" class="<?= in_array('pax', $theForm->sections) ? '' : 'hidden-print' ?>">
            <h2 class="mb-10"><?= Yii::t('in_ct', 'Pax list', null, $language) ?></h2>
            <div class="mb-20">
                <table id="tbl-paxlist" class="table table-xxs">
                    <thead>
                        <tr>
                            <th width="20"></th>
                            <th><?= Yii::t('in_ct', 'Family name(s)', null, $language) ?></th>
                            <th><?= Yii::t('in_ct', 'Given name(s)', null, $language) ?></th>
                            <th class="text-center"><?= Yii::t('in_ct', 'Gender', null, $language) ?></th>
                            <th class="text-center"><?= Yii::t('in_ct', 'Date of birth', null, $language) ?></th>
                            <th><?= Yii::t('in_ct', 'Nationality', null, $language) ?></th>
                            <th class="text-center"><?= Yii::t('in_ct', 'Passport', null, $language) ?></th>
                            <th class="text-center"><?= Yii::t('in_ct', 'Issue date', null, $language) ?></th>
                            <th class="text-center"><?= Yii::t('in_ct', 'Expiry date', null, $language) ?></th>
<!--                             <th><?= Yii::t('in_ct', 'Email', null, $language) ?></th>
                            <th><?= Yii::t('in_ct', 'Phone', null, $language) ?></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        if (!empty($theTour['pax'])) {
                            foreach ($theTour['pax'] as $cnt=>$pax) {
                                $pax['data'] = @unserialize($pax['data']);
                        ?>
                        <tr>
                            <td class="text-muted"><?= 1 + $cnt ?></td>
                            <td><?= $pax['data']['pp_name'] ?></td>
                            <td><?= $pax['data']['pp_name2'] ?></td>
                            <td class="text-center"><?= ucwords(Yii::t('in_ct', $pax['data']['pp_gender'], null, $language)) ?></td>
                            <td class="text-nowrap text-center"><?= implode('/', [$pax['data']['pp_bday'], $pax['data']['pp_bmonth'], $pax['data']['pp_byear']]) ?></td>
                            <td class="text-nowrap">
                                <? if ($pax['data']['pp_country_code'] != '') { ?>
                                <span class="hidden-print flag-icon flag-icon-<?= $pax['data']['pp_country_code'] ?>"></span>
                                <?
                                foreach ($countryList as $country) {
                                    if ($country['code'] == $pax['data']['pp_country_code']) {
                                        if (Yii::$app->language == 'vi') {
                                            echo $country['name_vi'];
                                        } else {
                                            echo $country['name_en'];
                                        }
                                        break;
                                    }
                                }
                                ?>
                                <? } ?>
                            </td>
                            <td class="text-nowrap text-center"><?= $pax['data']['pp_number'] ?></td>
                            <td class="text-nowrap text-center"><?= implode('/', [$pax['data']['pp_iday'], $pax['data']['pp_imonth'], $pax['data']['pp_iyear']]) ?></td>
                            <td class="text-nowrap text-center"><?= implode('/', [$pax['data']['pp_eday'], $pax['data']['pp_emonth'], $pax['data']['pp_eyear']]) ?></td>
<!--                             <td><?= $pax['data']['email'] ?></td>
                            <td><?= $pax['data']['tel'] ?></td> -->
                        </tr>
                        <?
                            }
                        } else {
                            $cnt = 0;
                            foreach ($theTour['bookings'] as $booking) {
                                foreach ($booking['people'] as $user) {
                        ?>
                        <tr>
                            <td class="text-muted"><?= ++$cnt ?></td>
                            <td><?= $user['fname'] ?></td>
                            <td><?= $user['lname'] ?></td>
                            <td class="text-center"><?= $user['gender'] ?></td>
                            <td class="text-nowrap text-center"><?= implode('/', [$user['bday'], $user['bmonth'], $user['byear']]) ?></td>
                            <td class="text-nowrap">
                                <? if ($user['country']['code'] != '') { ?>
                                <span class="hidden-print flag-icon flag-icon-<?= $user['country']['code'] ?>"></span> <?= $user['country']['name_en'] ?>
                                <? } ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <!--
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['phone'] ?></td>
                            -->
                        </tr>
                        <?
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="section-summary" class="<?= in_array('summary', $theForm->sections) ? '' : 'hidden-print' ?>">
            <h2 class="mb-10"><?= Yii::t('in_ct', 'Summary', null, $language) ?> :</h2>
            <table class="table table-borderless table-xxs mb-20">
                <thead>
                    <tr>
                        <th width="5%"><?= Yii::t('in_ct', 'Day', null, $language) ?></th>
                        <th width="15%"><?= Yii::t('in_ct', 'Date', null, $language) ?></th>
                        <th><?= Yii::t('in_ct', 'Activity & Meals', null, $language) ?></th>
                    </tr>
                </thead>
                <tbody class="day-list">
<?
$cnt = 0;
foreach ($dayIdList as $di) {
    foreach ($theTour['days'] as $ng){
        if ($di == $ng['id']) {
            $cnt ++;
            if (in_array($cnt, $showDays)) {
                $ngay = date('Y-m-d D', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
                $ngay = Yii::$app->formatter->asDate($ngay, 'php:j/n/Y l');
?>
                    <tr class="day-list-item">
                        <td class="text-center"><?= $cnt ?></strong></td>
                        <td class="text-nowrap"><?= $ngay ?></td>
                        <td contenteditable="true"><?= $ng['name'] ?> (<?= $ng['meals'] ?>)</td>
                    </tr>
<?
            } else {
                // 161411 Khong can nhung in ra
                $ngay = date('Y-m-d D', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
                $ngay = Yii::$app->formatter->asDate($ngay, 'php:j/n/Y l');
?>
                    <tr class="hidden-print" title="<?= Yii::t('in_ct', 'This text will not be printed', null, $language) ?>">
                        <td class="text-info text-center"><?= $cnt ?></strong></td>
                        <td class="text-info text-nowrap"><?= $ngay ?></td>
                        <td class="text-info" contenteditable="true"><?= $ng['name'] ?> (<?= $ng['meals'] ?>)</td>
                    </tr>
<?
            }
        }
    }
}
?>
                </tbody>
            </table>
        </div><!-- #section-summary -->

        <div id="section-itinerary" class="<?= in_array('itinerary', $theForm->sections) ? '' : 'hidden-print' ?>">
            <h2><?= Yii::t('in_ct', 'Detailed itinerary', null, $language) ?> :</h2>
            <div id="day-list" class="mb-20">
<?
$cnt = 0;
foreach ($dayIdList as $di) {
    foreach ($theTour['days'] as $ng){
        if ($di == $ng['id']) {
            $cnt ++;
            if (in_array($cnt, $showDays)) {
                $ngay = strtotime($theTour['day_from'].' + '.($cnt - 1).'days');
                $ngay = Yii::$app->formatter->asDate($ngay, 'php:j/n/Y l');
?>
                <div class="day-list-item" data-id="<?= $ng['id'] ?>">
                    <h5 style="border-bottom:1px solid #999; padding:8px 0;" class="mb-10">
                        <?= Yii::t('in_ct', 'Day', null, $language) ?> <?=$cnt?> (<?=$ngay?>) <?=$ng['name']?> (<?=$ng['meals']?>)
                    </h5>
                    <div contenteditable="true" style="padding-left:25px;">
                        <?= $ng['guides'] == '' ? '' : '<p> &rarr; '.$ng['guides'].'</p>' ?>
                        <?= $ng['transport'] == '' ? '' : '<p> &rarr; '.$ng['transport'].'</p>' ?>
                        <?
                        if (substr($ng['body'], 0, 1) == '<') {
                            $ng['body'] = str_replace(['class=', 'style='], ['c=', 's='], $ng['body']);
                            echo $ng['body'];
                        } else {
                            echo $parser->parse($ng['body']);
                        }
                        ?>
                    </div>
                </div><!-- .day-list-item -->
<? 
            }
        }
    }
}
?>
            </div>
        </div>

        <div id="section-price" class="<?= in_array('price', $theForm->sections) ? '' : 'hidden-print' ?>">
            <h2 class="mb-10"><?= Yii::t('in_ct', 'Tour prices', null, $language) ?> :</h2>
            <p><?= Yii::t('in_ct', 'Prices as of', null, $language) ?> <?=date('j/n/Y', strtotime($theTour['updated_at']))?></p>
            <table class="table table-bordered table-xxs mb-20">
                <tbody>
<? // Gia va cac options
$theTourpx = $theTour['prices'];
$theTourpx = explode(chr(10), $theTourpx);
$optcnt = 0;
foreach ($theTourpx as $theTourp) {
    if (substr($theTourp, 0, 7) == 'OPTION:') {
        $optcnt ++;
        if ($optcnt != 1) {
            echo '</tbody>
            </table>
            <table class="table table-bordered table-xxs mb-20">';
        }
        echo '<h4>'.trim(substr($theTourp, 7)).'</h4>';
        ?>
                    <tr>
                        <th width="15%"><?= Yii::t('in_ct', 'Destination', null, $language) ?></th>
                        <th width="30%"><?= Yii::t('in_ct', 'Hotel', null, $language) ?></th>
                        <th width="25%"><?= Yii::t('in_ct', 'Room type', null, $language) ?></th>
                        <th width="30%"><?= Yii::t('in_ct', 'Website', null, $language) ?></th>
                    </tr><?
    }
    if (substr($theTourp, 0, 2) == '+ ') {
        $line = trim(substr($theTourp, 2));
        $line = explode(':', $line);
        for ($i = 0; $i < 4; $i ++) {
            if (!isset($line[$i])) {
                $line[$i] = '';
            }
        }
        ?>
                    <tr>
                        <td><i title="Click to toggle print" onclick="$(this).parent().parent().toggleClass('hidden-print');" class="fa fa-trash-o cursor-pointer hidden-print"></i> <?= $line[0] ?></td>
                        <td><?= $line[1] ?></td>
                        <td><?= $line[2] ?></td>
                        <td class="a-href"><?= trim($line[3]) ?></td>
                    </tr><?
    }
    if (substr($theTourp, 0, 2) == '- ') {
        $line = trim(substr($theTourp, 2));
        $line = explode(':', $line);
        for ($i = 0; $i < 3; $i ++) {
            if (!isset($line[$i])) {
                $line[$i] = '';
            }
        }
        $line[1] = trim($line[1]);
        // echo '<tr><td colspan="3" class="text-right">'.$line[0].'</td><td class="price">Amica Travel</th></tr>';
    }
}
?>
                </tbody>
            </table>

            <h2 class="mb-10"><?= Yii::t('in_ct', 'Price conditions', null, $language) ?> :</h2>
            <div class="mb-20" contenteditable="true">
                <?= $parser->parse($theTour['conditions']) ?>
            </div>
        </div><!-- #section-price -->
        <div id="section-conditions" class="<?= in_array('conditions', $theForm->sections) ? '' : 'hidden-print' ?>">
            <div class="mb-20" contenteditable="true">
                <?= $parser->parse($theTour['others']) ?>
            </div>
        </div><!-- #section-conditions -->
        <hr>
        <div class="text-muted"><?= Yii::t('in_ct', 'This document was printed on {date} by {user}', ['date'=>\app\helpers\DateTimeHelper::convert(NOW, 'j/n/Y H:i'), 'user'=>Yii::$app->user->identity->name], $language) ?></div>
    </div><!-- #print-body -->
</div>
<script type="text/javascript">
var logoList = {};
<? foreach ($logoList as $cnt=>$logo) { ?>
logoList['<?= $logo['id'] ?>'] = '<?= $logo['logo'] ?>';
<? } ?>
</script>
<?
$js = <<<'TXT'
$('#tourinctform-days').on('change', function(){
    nums = parseRange($(this).val());
    if (nums.length > 0) {
        // $('.page-title h1').html(nums.join(', '));
        $('#section-summary .day-list-item').each(function(i){
            if ($.inArray(1 + i, nums) === -1) {
                $(this).addClass('hidden-print');
            } else {
                $(this).removeClass('hidden-print');
            }
        });
        $('#section-itinerary .day-list-item').each(function(i){
            if ($.inArray(1 + i, nums) === -1) {
                $(this).addClass('hidden-print');
            } else {
                $(this).removeClass('hidden-print');
            }
        });
    } else {
        $('#section-summary .day-list-item').removeClass('hidden-print');
        $('#section-itinerary .day-list-item').removeClass('hidden-print');
    }
});

// Logo
$('#tourinctform-logo').on('change', function(){
    var val = $(this).val();
    $('#print-logo').attr('src', logoList[val]);
})

// Note
$('#tourinctform-note').on('change', function(){
    var val = $(this).val();
    $('#note-content').html(nl2br(val));
    if (val == '') {
        $('#section-note').addClass('hidden hidden-print')
    } else {
        $('#section-note').removeClass('hidden hidden-print')
    }
})

// Sections to print
$('#tourinctform-sections input:checkbox').on('change', function(){
    var section = $(this).val();
    $('#section-' + section).toggleClass('hidden-print');
})

$('#tourinctform-language').on('change', function(){
    location.href='?language=' + $(this).val();
})

// Sections to print
$('#ducanh').on('change', function(){
    if ($(this).is(':checked')) {
        $('#section-summary .day-list-item.hidden-print').removeClass('hidden-print').addClass('temp-print');
    } else {
        $('#section-summary .day-list-item.temp-print').removeClass('temp-print-print').addClass('hidden-print');
    }
})
TXT;
$this->registerJs($js);