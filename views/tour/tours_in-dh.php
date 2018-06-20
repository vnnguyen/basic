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

$theTour['gender'] = 'f';
$theTour['avatar'] = 'avatar';

$dayIdList = explode(',', $theTour['day_ids']);

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');


Yii::$app->params['page_title'] = 'Amica Travel | Tour itinerary: '.$theTour['op_code'].' - '.$theTour['op_name'];
Yii::$app->params['page_layout'] = '-t -s -f';


require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

?>
<style>
html, body {background:#fff; font:15px/20px Arial, Tahoma, sans-serif;}
@media print {
html, body {background:#fff; font:13px/18px Arial, Tahoma, sans-serif; color:#474747;}
#wrap {background:#fff; text-align:justify;}
.wrap {padding:20px;}
.pl-30 {padding-left:30px;}
.mb-10 {margin-bottom:10px;}
.mb-5 {margin-bottom:5px;}
.h-20 {height:20px;}
ul, ol {padding-left:2em;}
.esprit ul {padding-left:1em;}
h1#print-ct-code {text-align:right; font:bold 26px/32px Arial, sans-serif; color:#000;margin:0;}
h2.auto {font:normal 20px/35px Arial, Tahoma, sans-serif; height:35px; color:#333; border-top:1px solid #333; border-bottom:1px solid #333; margin:0; padding-left:20px;}
h2.sqs {font:normal 20px/20px Arial, Tahoma, sans-serif; background:#fff url(/images/h2-4sq.png) left center no-repeat; margin:0 0 10px; padding-left:20px;}
h3 {font:bold 16px/25px Arial, Tahoma, sans-serif;}

table {border-collapse:collapse;}
table td, table th {border:1px solid #474747; vertical-align:top;}
table th {padding:10px 5px; text-align:center; background:#eee;}
table td.price {text-align:right; font:bold 15px Arial, Tahoma, sans-serif; letter-spacing:2px;}

table#tomtat td {padding:3px;}
table#tomtat th {text-align:left;}

#print-ft {border-top:1px solid #474747; padding:10px; text-align:center;}
#print-seller-info {border:1px solid #A1B5D0; padding:5px; float:right;}
.ta-j {text-align:justify;}
.ta-r {text-align:right;}
.ta-c {text-align:center;}
}
</style>
<div class="alert alert-info hidden-print">
    <strong>Chú ý:</strong>
    <br>Click icon Xoá <i class="fa fa-trash-o"></i> cạnh tên các ngày trong bảng tóm tắt để xoá khỏi danh sách ngày cần in (xoá trên sẽ xoá cả ở bên dưới)
</div>
<div class="col-md-12">
    <div class="clearfix">
        <img style="width:25%; float:left; margin-right:25px; display:inline-block;" src="/assets/img/logo-amica-2016-ims-mcw.png?x=y" alt="Amica Travel logo" >
        <h1 id="print-ct-code"><?= $theTour['op_code'] ?> &middot; <?= $theTour['op_name'] ?></h1>
    </div>
    <hr>
    <div class="wrap">
        <div class="clearfix">
            <h2 class="sqs"><?= $theTour['title'] ?></h2>
            <div class="pl-30"><strong><?=$theTour['about']?></strong></div>
            <div class="pl-30"><strong><?= Yii::t('tour_print', 'Number of pax') ?> :</strong> <?=$theTour['pax']?> <?= Yii::t('tour_print', 'personnes') ?></div>
            <div class="pl-30"><strong><?= Yii::t('tour_print', 'Duration') ?> :</strong> <?=$theTour['day_count']?> <?= Yii::t('tour_print', 'days') ?>, <?= Yii::t('tour_print', 'from') ?> <?=str_replace($ngay_en, $ngay_fr, date('D d/m/Y', strtotime($theTour['day_from'])))?></div>
            <div class="pl-30"><strong><?= Yii::t('tour_print', 'Seller') ?> :</strong> <?= $theTour['updatedBy']['fname'].' '.$theTour['updatedBy']['lname'] ?></div>
        </div>
    </div><!-- .wrap -->

    <h2 class="auto"><?= Yii::t('tour_print', 'Your intinerary') ?> :</h2>
    <div class="wrap">
        <table id="tomtat">
            <thead>
                <tr>
                    <th><?= Yii::t('tour_print', 'Day') ?></th>
                    <th><?= Yii::t('tour_print', 'Date') ?></th>
                    <th><?= Yii::t('tour_print', 'Activity (Meals)') ?></th>
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
                <td width="10%"><?= Yii::t('tour_print', 'Day') ?> <?= $cnt ?></strong></td>
                <td width="20%"><?= $ngay ?></td>
                <td width="70%"><?= $ng['name'] ?> (<?= $ng['meals'] ?>)</td>
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

    <h2 class="auto xpagebreak"><?= Yii::t('tour_print', 'Your detailed program') ?> :</h2>
    <div class="wrap">
        <div id="print-iti">
<?
$cnt = 0;
foreach ($dayIdList as $di) {
    foreach ($theTour['days'] as $ng){
        if ($di == $ng['id']) {
            $cnt ++;
            $ngay = strtotime($theTour['day_from'].' + '.($cnt - 1).'days');
            $ngay = Yii::$app->formatter->asDate($ngay, 'php:j/n/Y l');
?>
            <p style="font-size:120%; margin:20px 0 10px; border:1px solid #999; padding:10px;">
                <?= Yii::t('tour_print', 'Day') ?> <?=$cnt?> (<?=$ngay?>) <?=$ng['name']?> (<?=$ng['meals']?>)
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
?>
        </div>
    </div><!-- .wrap -->

    <h2 class="auto xpagebreak"><?= Yii::t('tour_print', 'Tour prices') ?> :</h2>
    <div class="wrap">
        <p><?= Yii::t('tour_print', 'Prices as of') ?> <?=date('d-m-Y', strtotime($theTour['updated_at']))?></p>
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
            <th width="15%"><?= Yii::t('tour_print', 'Destination') ?></th>
            <th width="30%"><?= Yii::t('tour_print', 'Hotel') ?></th>
            <th width="25%"><?= Yii::t('tour_print', 'Room type') ?></th>
            <th width="30%"><?= Yii::t('tour_print', 'Website') ?></th>
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

    <h2 class="auto"><?= Yii::t('tour_print', 'Price conditions') ?> :</h2>
    <div class="wrap">
        <?= $parser->parse($theTour['conditions']) ?>
        <?= $parser->parse($theTour['others']) ?>
    </div>
    <hr>
    <div id="print-ft">&copy; 2007-<?= date('Y') ?> <strong>Amica Travel</strong>. This document was printed on <?= Yii::$app->formatter->asDate(NOW, 'php:l, j/n/Y') ?> by <?= Yii::$app->user->identity->name ?></div>
</div>