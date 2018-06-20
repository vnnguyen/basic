<?
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

include('_program_inc.php');

Yii::$app->params['page_icon'] = 'map-o';

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
    $theProgram['image'] = '/upload/devis-banners/halong2.jpg';
} else {
    $theProgram['image'] = '/upload/devis-banners/'.$theProgram['image'];
}

$productPdfFiles = [];
$productImageFiles = [];
$productExcelFiles = [];
$productUploadPath = Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'];
if (file_exists($productUploadPath.'/pdf')) {
    $productPdfFiles = FileHelper::findFiles($productUploadPath.'/pdf');
}
if (file_exists($productUploadPath.'/image')) {
    $productImageFiles = FileHelper::findFiles($productUploadPath.'/image');
}
if (file_exists($productUploadPath.'/excel')) {
    $productExcelFiles = FileHelper::findFiles($productUploadPath.'/excel');
}

?>
<style>
td, th {vertical-align:top!important;}
body {background-color:#fff;}
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
</style>
<!--
<div class="col-md-12">

    <ul class="nav nav-tabs">
        <? foreach ($productViewTabs as $tab) { ?>
        <li class="<?= URI == $tab['link'] ? 'active' : '' ?>"><?= Html::a($tab['label'], DIR.$tab['link']) ?></li>
        <? } ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Testing menu <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="xxx">
                <li role="presentation" class="dropdown-header">PRODUCT</li>
                <li class=""><a role="menuitem" href="">Product Overview</a></li>
                <li class=""><a role="menuitem" href="">Itinerary</a></li>
                <li class=""><a role="menuitem" href="">Prices</a></li>
                <li class=""><a role="menuitem" href="">Files & Notes</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation" class="dropdown-header">SALES</li>
                <li><?= Html::a('Sales Overview', '@web/products/sb/'.$theProgram['id']) ?></li>
                <li><?= Html::a('Bookings', '@web/bookings?product_id='.$theProgram['id']) ?></li>
                <li class=""><a role="menuitem" href="">People</a></li>
                <li class=""><a role="menuitem" href="">Payments</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation" class="dropdown-header">OPERATIONS</li>
                <li><?= Html::a('Operation Overview', '@web/products/op/'.$theProgram['id']) ?></li>
                <li class=""><a role="menuitem" href="">Service costs</a></li>
                <li class=""><a role="menuitem" href="">Customers</a></li>
                <li class=""><a role="menuitem" href="">Feedback</a></li>
                <li class=""><a role="menuitem" href="">Files & Notes</a></li>
            </ul>
        </li>
    </ul>
</div>
-->
<style type="text/css">
.label.op {background-color:#369;}
</style>

<div class="col col-1">
    <div class="row">
        <div class="col col-1-1">
            <p>
                <img class="img-circle" src="/timthumb.php?w=100&h=100&src=<?= $theProgram['updatedBy']['image'] ?>" style="border:1px solid #fff; width:64px; height:64px; position:absolute; margin:20px 0 0 20px;">
                <img class="img-responsive img-thumbnail" src="<?= $theProgram['image'] ?>">
            </p>
            <p>
                <?php if ($theProgram['owner'] == 'si') { ?>
                    <span class="label label-info">Secret Indochina</span>
                    <?php if ($theProgram['offer_type'] == 'b2b_prod') { ?>
                        <span class="label label-warning">PROD</span>
                    <?php } ?>
                <?php } ?>
                <?php if ($theTour) { ?><span class="label op">OPERATING</span><?php } ?>
            </p>
            <table class="table table-xxs table-summary mb-20">
                <tbody>
                    <? if ($theProgram['op_status'] == 'op') { ?>
                    <tr>                
                        <td style="white-space:nowrap;"><strong>Operated as:</strong></td><td><?= Html::a($theProgram['op_code'].' - '.$theProgram['op_name'], '@web/tours/r/'.$theTour['id']) ?></td>
                    </tr>
                    <? } ?>
                    <tr>
                        <td><strong>Bookings</strong></td>
                        <td>
                            <? if (empty($theProgram['bookings'])) { ?>
                            No bookings found.
                            <? } else { ?>
                                <? foreach ($theProgram['bookings'] as $booking) { ?>
                            <div>
                            <?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$booking['createdBy']['image'], ['style'=>'width:20px; height:20px']) ?>
                            <span class="label status <?= $booking['status'] ?>"><?= strtoupper($booking['status']) ?></span>
                            <? if ($booking['finish'] == 'canceled') { ?><span class="label label-warning">CXL</span><? } ?>
                            &middot;<?= $booking['pax'] ?> pax &middot; <?= number_format($booking['price']) ?> <?= $booking['currency'] ?>
                            <br><i class="fa fa-briefcase text-muted"></i> <?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?>
                            </div>
                                <? } // foreach booking ?>
                            <? } ?>
                            <? if (empty($theProgram['bookings']) || ($theProgram['offer_type'] == 'combined2016' && strtotime('now') < strtotime($theProgram['day_from']))) { ?>
                            <?= Html::a('Add new proposal', '@web/bookings/c?product_id='.$theProgram['id']) ?>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?= Yii::t('p', 'Start date') ?></strong></td><td><?= Yii::$app->formatter->asDate($theProgram['day_from'], 'php:j/n/Y (l)') ?> - <?= Yii::t('p', 'Length') ?> <?= $theProgram['day_count'] ?> <?= Yii::t('p', 'days') ?></td>
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

            <div class="mb-20">
                <?= Html::a('More', '/products/upload/'.$theProgram['id'], ['class'=>'text-muted pull-right']) ?>
                <p class="text-bold text-uppercase text-warning">Attachments</p>
            <?
            if (!empty($productImageFiles)) {
                foreach ($productImageFiles as $file) {
                    $fileName = urlencode(substr(strrchr($file, "/"), 1));
                    echo Html::a(Html::img('/timthumb.php?h=100&src=/upload/products/'.$theProgram['id'].'/image/'.$fileName, ['style'=>'margin:0 2px 2px 0']), '/upload/products/'.$theProgram['id'].'/image/'.$fileName, ['title'=>'View', 'target'=>'_blank']);
                    echo ' ', Html::a('<i class="fa fa-download"></i>', '/products/download/'.$theProgram['id'].'?action=download&type=image&file='.$fileName, ['class'=>'text-muted', 'title'=>'Download']);
                }
                echo '<div style="height:10px;"></div>';
            }
            if (file_exists(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$theProgram['id'].'.pdf')) {
                $fileName = 'PDF itinerary';
                echo '<i class="fa fa-file-pdf-o" style="color:#FC6249"></i> ', Html::a(urldecode($fileName), '/products/download/'.$theProgram['id'].'?action=download&type=oldpdf&file='.$fileName), ' &nbsp; ';
            }

            if (!empty($productPdfFiles)) {
                foreach ($productPdfFiles as $file) {
                    echo '<div>';
                    $fileName = urlencode(substr(strrchr($file, "/"), 1));
                    $fileExt = strtolower(substr(strrchr($fileName, "."), 1));
                    if ($fileExt == 'pdf') {
                        echo '<i class="fa fa-fw fa-file-pdf-o" style="color:#FC6249"></i> ';
                    } elseif (in_array($fileExt, ['doc', 'docx', 'docm'])) {
                        echo '<i class="fa fa-fw fa-file-word-o" style="color:#2A5699"></i> ';
                    } else {
                        echo '<i class="fa fa-fw fa-file-text-o"></i> ';
                    }
                    echo Html::a(urldecode($fileName), 'https://docs.google.com/viewer?url=https://my.amicatravel.com/upload/products/'.$theProgram['id'].'/pdf/'.$fileName, ['title'=>'View', 'target'=>'_blank']);
                    echo ' ', Html::a('<i class="fa fa-download"></i>', '/products/download/'.$theProgram['id'].'?action=download&type=pdf&file='.$fileName, ['class'=>'text-muted', 'title'=>'Download']);
                    echo '</div>';
                }
            }
            if (!empty($productExcelFiles)) {
                foreach ($productExcelFiles as $file) {
                    $fileName = urlencode(substr(strrchr($file, "/"), 1));
                    echo '<div>';
                    echo '<i class="fa fa-fw fa-file-excel-o" style="color:#207245"></i> ', Html::a(urldecode($fileName), 'https://docs.google.com/viewer?url=https://my.amicatravel.com/upload/products/'.$theProgram['id'].'/excel/'.$fileName, ['title'=>'View', 'target'=>'_blank']);
                    echo ' ', Html::a('<i class="fa fa-download"></i>', '/products/download/'.$theProgram['id'].'?action=download&type=excel&file='.$fileName, ['class'=>'text-muted', 'title'=>'Download']);
                    echo '</div>';
                }
            }
            ?>
            </div>
            <p>
                <span class="text-bold text-uppercase"><?= Yii::t('p', 'Tags') ?>:</span>
                <?
                $tags = explode(',', $theProgram['tags']);
                foreach ($tags as $tag) {
                    echo Html::a(trim($tag), '/products?name='.$tag), ', ';
                }
                ?>
            </p>
            <p><strong>NOTE</strong></p>
            <div class="mb-1em"><?= Markdown::process($theProgram['summary']) ?></div>

            <p><strong>INTRO</strong></p>
            <div class="mb-1em"><?= Markdown::process($theProgram['intro']) ?></div>

            <? if ($theProgram['points'] != '') { ?>
            <p><strong>KEY POINTS</strong></p>
            <div class="mb-1em"><?= Markdown::process($theProgram['points']) ?></div>
            <? } ?>

        </div>
        <div class="col col-1-2">
            <p><span class="text-bold text-uppercase text-warning">Price table</span> <?= Html::a('Edit', '/products/pt/'.$theProgram['id']) ?></p>
            <div class="table-responsive">
                <table class="table table-bordered table-xxs">
<? // Gia va cac options
$ctpx = $theProgram['prices'];
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
    <td colspan="4" class="text-right"><?= $line[0] ?> <strong><?= number_format($line[1]) ?> <?= $theProgram['price_unit'] ?></strong></td>
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
    </div><!-- col col-1 -->
</div>
<div class="col col-2">
    <? include('_huan1.php'); ?>
    <? include('_huan2.php'); ?>
    <?php if ($theProgram['owner'] == 'at' && $theProgram['language'] == 'fr') { ?>
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
    <hr>
    <p><strong>DRAW A MAP</strong> <?= Html::a('Click here to launch the map drawer', '/map?product='.$theProgram['id'], ['target'=>'_blank']) ?></p>
    <hr>
    <p><strong>TABLEAU DEVIS</strong> <?= Html::a('Edit', '/products/td/'.$theProgram['id']) ?></p>
    <?php if (!empty($devisTableData)) { ?>
    <div class="table-responsive">
        <table class="table table-xxs table-bordered">
            <thead>
                <tr>
                    <th>Destination</th>
                    <th>Ce que l'on vous propose souvent</th>
                    <th>Ce qu'Amica vous conseille</th>
                    <th>Votre voyage, votre histoire, votre envies</th>
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
    </div>
    <?php } ?>
    <?php } ?>
        <p><strong>TERMS AND CONDITIONS</strong></p>
        <div class="mb-1em"><?= $parser->parse($theProgram['conditions']) ?></div>
        <p><strong>MORE INFORMATION</strong></p>
        <div class="mb-1em"><?= $parser->parse($theProgram['others']) ?></div>
    </div>
</div><!-- col col-2 -->
