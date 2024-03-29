<?
use yii\helpers\Html;
use yii\helpers\Markdown;

Yii::$app->language = $theTour['language'];

/*
$q = $db->query('SELECT * FROM at_tours WHERE id=%i LIMIT 1', seg3);
$theTour = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404);

$q = $db->query('SELECT * FROM at_ct WHERE id=%i LIMIT 1', $theTour['ct_id']);
$theTour = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404);

// Days
$q = $db->query('SELECT * FROM at_days WHERE rid=%i ORDER BY id', $theTour['ct_id']);
$ctDays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();


// Seller
$q = $db->query('SELECT * FROM persons WHERE id=%i LIMIT 1', $theTour['created_by']);
$seller = $q->fetchRow();

*/

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');


Yii::$app->params['page_title'] = 'Amica Travel | Tour itinerary: '.$theTour['op_code'].' - '.$theTour['op_name'];
Yii::$app->params['page_layout'] = '-t -s -f';


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

?>
<style>
html, body {background:#fff; font:15px/20px Arial, Tahoma, sans-serif;}
@media print {
html, body {background:#fff; font:11px/16px Arial, Tahoma, sans-serif; color:#474747;}
#wrap {background:#fff; text-align:justify;}
.wrap {padding:20px;}
.pl-30 {padding-left:30px;}
.mb-10 {margin-bottom:10px;}
.mb-5 {margin-bottom:5px;}
.h-20 {height:20px;}
ul, ol {padding-left:2em;}
.esprit ul {padding-left:1em;}
h1#print-ct-code {font:bold 20px/24px Arial, sans-serif; color:#000;margin:0;}
h2.auto {font:normal 16px/24px Arial, Tahoma, sans-serif; color:#333; border-top:1px solid #333; border-bottom:1px solid #333; margin:0; padding:5px 5px 5px 20px;}
h2.sqs {font:normal 16px/24px Arial, Tahoma, sans-serif; margin:0 0 10px; padding-left:20px;}
h3 {font:bold 13px/16px Arial, Tahoma, sans-serif;}

table {border-collapse:collapse;}
table th {background-color:#eee;}
table td, table th {padding:8px 4px; border:1px solid #ccc; vertical-align:top;}
table td.price {text-align:right;}
table.simple th {text-align:left;}

#print-ft {text-align:center;}
#print-seller-info {border:1px solid #A1B5D0; padding:5px; float:right;}
.ta-j {text-align:justify;}
.ta-r {text-align:right;}
.ta-c {text-align:center;}
}
</style>
<div class="col-md-12">
    <div class="alert alert-info hidden-print">
        Click to go
        <?= Html::a('Back to tour', '/tours/r/'.$theTourOld['id'], ['class'=>'text-bold']) ?>
        or
        <?= Html::a('Back to select print options', '/tours/in-ct/'.$theTour['id'], ['class'=>'text-bold']) ?>
    </div>
    <div class="clearfix">
        <img style="width:220px; float:left; margin-bottom:25px; display:inline-block;" src="<?= $logo ?>" alt="Logo" >
        <div style="margin-left:245px; margin-bottom:25px;">
            <h1 id="print-ct-code"><?= $theTour['op_code'] ?> &middot; <?= $theTour['op_name'] ?></h1>
            <hr>
            <div class="clearfix">
                <h2 style="margin:0 0 10px; font-weight:normal;"><?= $theTour['title'] ?></h2>
                <div><strong><?=$theTour['about']?></strong></div>
                <div><strong><?= Yii::t('in_ct', 'Number of pax') ?> :</strong> <?=$theTour['pax']?> <?= Yii::t('in_ct', 'pax') ?></div>
                <div><strong><?= Yii::t('in_ct', 'Duration') ?> :</strong> <?=$theTour['day_count']?> <?= Yii::t('in_ct', 'days') ?>, <?= Yii::t('in_ct', 'from') ?> <?=str_replace($ngay_en, $ngay_fr, date('D d/m/Y', strtotime($theTour['day_from'])))?></div>
                <div><strong><?= Yii::t('in_ct', 'Seller') ?> :</strong> <?= $theTour['updatedBy']['fname'].' '.$theTour['updatedBy']['lname'] ?></div>
            </div>
        </div>
    </div>

    <? if ($theForm->note != '') { ?>
    <h2 class="auto"><?= Yii::t('in_ct', 'Note') ?> :</h2>
    <div class="wrap">
        <?= nl2br(Html::encode($theForm->note)) ?>
    </div>
    <? } ?>

    <? if (in_array('summary', $theForm->sections)) { ?>
    <h2 class="auto"><?= Yii::t('in_ct', 'Your intinerary') ?> :</h2>
    <div class="wrap">
        <table class="simple">
            <thead>
                <tr>
                    <th width="5%"><?= Yii::t('in_ct', 'Day') ?></th>
                    <th width="15%"><?= Yii::t('in_ct', 'Date') ?></th>
                    <th><?= Yii::t('in_ct', 'Activity (Meals)') ?></th>
                </tr>
            </thead>
            <tbody>
<?
$cnt = 0;
foreach ($dayIdList as $di) {
    foreach ($theTour['days'] as $ng){
        if ($di == $ng['id']) {
            $cnt ++;
            $ngay = date('Y-m-d D', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
            $ngay = Yii::$app->formatter->asDate($ngay, 'php:j/n/Y l');
?>
            <tr>
                <td class="text-center"><?= $cnt ?></strong></td>
                <td class="text-nowrap"><?= $ngay ?></td>
                <td><?= $ng['name'] ?> (<?= $ng['meals'] ?>)</td>
            </tr>
<?
        }
    }
}
?>
            <tbody>
        </table>
    
        <div class="clear"></div>
    </div><!-- .wrap -->
    <? } ?>

    <? if (in_array('itinerary', $theForm->sections)) { ?>
    <h2 class="auto xpagebreak"><?= Yii::t('in_ct', 'Your detailed program') ?> :</h2>
    <div class="wrap">
        <div id="print-iti">
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
            <p style="font-size:110%; margin:20px 0 10px; border:1px solid #999; padding:8px;">
                <?= Yii::t('in_ct', 'Day') ?> <?=$cnt?> (<?=$ngay?>) <?=$ng['name']?> (<?=$ng['meals']?>)
            </p>
            <div>
                <?= $ng['guides'] == '' ? '' : ' &rarr; '.$ng['guides']?>
                <?= $ng['transport'] == '' ? '' : ' &rarr; '.$ng['transport']?>
                <? // = $ng['hotels'] == '' ? '' : '<br />&rarr; '.$ng['hotels']?>
                <?= $parser->parse($ng['body'])?>
            </div>
<? 
            }
        }
    }
}
?>
        </div>
    </div><!-- .wrap -->
    <? } ?>

    <? if (in_array('price', $theForm->sections)) { ?>
    <h2 class="auto xpagebreak"><?= Yii::t('in_ct', 'Tour prices') ?> :</h2>
    <div class="wrap">
        <p><?= Yii::t('in_ct', 'Prices as of') ?> <?=date('d-m-Y', strtotime($theTour['updated_at']))?></p>
        <table class="simple">
<? // Gia va cac options
$theTourpx = $theTour['prices'];
$theTourpx = explode(chr(10), $theTourpx);
$optcnt = 0;
foreach ($theTourpx as $theTourp) {
    if (substr($theTourp, 0, 7) == 'OPTION:') {
        $optcnt ++;
        if ($optcnt != 1) {
            echo '</table>'.chr(10).'<table class="simple">';
        }
        echo '<h3>'.trim(substr($theTourp, 7)).'</h3>';
        ?>
        <tr>
            <th width="15%"><?= Yii::t('in_ct', 'Destination') ?></th>
            <th width="30%"><?= Yii::t('in_ct', 'Hotel') ?></th>
            <th width="25%"><?= Yii::t('in_ct', 'Room type') ?></th>
            <th width="30%"><?= Yii::t('in_ct', 'Website') ?></th>
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
        echo '<tr><td>'.$line[0].'</td><td>'.$line[1].'</td><td>'.$line[2].'</td><td class="a-href">'.trim($line[3]).'</td></tr>';
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
        echo '<tr><td colspan="3" class="ta-r">'.$line[0].'</td><td class="price">Amica Travel</th></tr>';
    }
}
?>
        </table>
    </div>
    <? } ?>

    <? if (in_array('conditions', $theForm->sections)) { ?>
    <h2 class="auto"><?= Yii::t('in_ct', 'Price conditions') ?> :</h2>
    <div class="wrap">
        <?= $parser->parse($theTour['conditions']) ?>
        <?= $parser->parse($theTour['others']) ?>
    </div>
    <? } ?>
    <hr>
    <div id="print-ft">&copy; 2007-<?= date('Y') ?> <strong>Amica Travel</strong>. This document was printed on <?= Yii::$app->formatter->asDate(NOW, 'php:j/n/Y') ?> by <?= Yii::$app->user->identity->name ?></div>
</div>
<?
$this->registerJs('window.print();');