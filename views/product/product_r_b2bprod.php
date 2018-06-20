<?
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_products_inc.php');

Yii::$app->params['page_icon'] = 'map-o';

$this->params['breadcrumb'][] = [$ctTypeList[$theProduct['offer_type']], 'products?type='.$theProduct['offer_type']];
$this->params['breadcrumb'][] = ['By '.$theProduct['createdBy']['name'], 'products?ub='.$theProduct['created_by']];
$this->params['breadcrumb'][] = ['View', 'products/r/'.$theProduct['id']];

$dayIdList = explode(',', $theProduct['day_ids']);
if (!$dayIdList) {
    $dayIdList = [];
}

if ($theProduct['image'] == '') {
    $theProduct['image'] = '/upload/devis-banners/halong2.jpg';
} else {
    $theProduct['image'] = '/upload/devis-banners/'.$theProduct['image'];
}

$productPdfFiles = [];
$productImageFiles = [];
$productExcelFiles = [];
$productUploadPath = Yii::getAlias('@webroot').'/upload/products/'.$theProduct['id'];
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
<div class="col col-1">
    <div class="row">
        <div class="col col-1-1">
            <p>
                <img class="img-circle" src="/timthumb.php?w=100&h=100&src=<?= $theProduct['updatedBy']['image'] ?>" style="border:1px solid #fff; width:64px; height:64px; position:absolute; margin:20px 0 0 20px;">
                <img class="img-responsive img-thumbnail" src="<?= $theProduct['image'] ?>">
            </p>
            <p>
                <?php if ($theProduct['owner'] == 'si') { ?>
                    <span class="label label-info">Secret Indochina</span>
                    <?php if ($theProduct['offer_type'] == 'b2b-prod') { ?>
                        <span class="label label-warning">PROD</span>
                    <?php } ?>
                <?php } ?>
                <?php if ($theTour) { ?><span class="label op">OPERATING</span><?php } ?>
            </p>
            <table class="table table-condensed table-summary">
                <tbody>
                    <tr>
                        <td><strong>Price:</strong></td><td><?= number_format($theProduct['price'], 0) ?> <?= $theProduct['price_unit'] ?> / <?= $theProduct['price_for'] ?>
                        <br><span class="text-muted">Valid until <?= date('d-m-Y', strtotime($theProduct['price_until'])) ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>About:</strong></td><td><?= $theProduct['about'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td><td><?= $theProduct['updatedBy']['name'] ?> <span class="text-muted"><?= Yii::$app->formatter->asRelativeTime($theProduct['updated_at']) ?></span></td>
                    </tr>
                </tbody>
            </table>

            <div class="mb-20">
                <p class="text-bold text-uppercase text-warning">Attachments</p>
            <?
            if (!empty($productImageFiles)) {
                foreach ($productImageFiles as $file) {
                    $fileName = substr(strrchr($file, "/"), 1);
                    echo Html::a(Html::img('/timthumb.php?h=100&src=/upload/products/'.$theProduct['id'].'/image/'.$fileName, ['style'=>'margin:0 2px 2px 0']), '/products/download/'.$theProduct['id'].'?action=download&type=image&file='.$fileName, ['title'=>$fileName]);
                }
                echo '<div style="height:10px;"></div>';
            }
            if (file_exists(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$theProduct['id'].'.pdf')) {
                $fileName = 'PDF itinerary';
                echo '<i class="fa fa-file-pdf-o" style="color:#FC6249"></i> ', Html::a($fileName, '/products/download/'.$theProduct['id'].'?action=download&type=oldpdf&file='.$fileName), ' &nbsp; ';
            }

            if (!empty($productPdfFiles)) {
                foreach ($productPdfFiles as $file) {
                    $fileName = substr(strrchr($file, "/"), 1);
                    echo '<i class="fa fa-file-pdf-o" style="color:#FC6249"></i> ', Html::a($fileName, '/products/download/'.$theProduct['id'].'?action=download&type=pdf&file='.$fileName), ' &nbsp; ';
                }
            }
            if (!empty($productExcelFiles)) {
                foreach ($productExcelFiles as $file) {
                    $fileName = substr(strrchr($file, "/"), 1);
                    echo '<i class="fa fa-file-excel-o" style="color:#207245"></i> ', Html::a($fileName, '/products/download/'.$theProduct['id'].'?action=download&type=excel&file='.$fileName), ' &nbsp; ';
                }
            }
            ?>
            </div>

            <p><strong>PRICE TABLE</strong></p>
            <table class="table table-bordered table-condensed">
<? // Gia va cac options
$ctpx = $theProduct['prices'];
$ctpx = explode(chr(10), $ctpx);
$unitp = '';
$minp = 99999;
$maxp = 0;
$optcnt = 0;
foreach ($ctpx as $ctp) {
if (substr($ctp, 0, 7) == 'OPTION:') {
$optcnt ++;
echo '<tr class="b-ffc"><th colspan="4">Option '.$optcnt.' : '.trim(substr($ctp, 7)).'</th></tr>';
echo '<tr><th>Ville</th><th>Hotel</th><th>Categorie chambre</th><th>Ref</th></tr>';
}
if (substr($ctp, 0, 2) == '+ ') {
$line = trim(substr($ctp, 2));
$line = explode(':', $line);
for ($i = 0; $i < 4; $i ++) if (!isset($line[$i])) $line[$i] = '';
echo '<tr><td style="white-space:nowrap;">'.$line[0].'</td><td>'.$line[1].'</td><td>'.$line[2].'</td><td>'.Html::a('<i class="text-muted fa fa-external-link"></i>', 'http://'.str_replace('http://', '', trim($line[3])), ['rel'=>'external']).'</td></tr>';
}
if (substr($ctp, 0, 2) == '- ') {
$line = trim(substr($ctp, 2));
$line = explode(':', $line);
for ($i = 0; $i < 3; $i ++) if (!isset($line[$i])) $line[$i] = '';
$line[1] = (int)trim($line[1]);
if ($minp > $line[1]) $minp = $line[1];
if ($maxp < $line[1]) $maxp = $line[1];
$unitp = $line[2];
echo '<tr><td colspan="4" class="text-right">'.$line[0].' <strong>'.number_format($line[1]).' '.$theProduct['price_unit'].'</strong></td></tr>';
}
}
if (empty($ctpx)) $minp = 0;
if ($minp > $maxp) $minp = 0;
?>
            </table>
        </div>
    </div><!-- col col-1 -->
</div>
<div class="col col-2">
        <p>
            <strong>FULL ITINERARY</strong>
            Days: <? $cnt = 0; foreach ($dayIdList as $id) { $cnt ++; if ($cnt != 1) {echo ' &middot; ';} echo Html::a($cnt, '#ngay-'.$id);} ?>
            &middot; <a href="#" class="text-danger" onclick="$('.day-content').toggle(); return false;">Toggle details</a>
        </p>

        <? if (in_array(MY_ID, [$theProduct['created_by'], $theProduct['updated_by']])) { ?>
        <div class="text-right text-muted" style="font-size:11px;">
            <?= Html::a('+Day after', DIR.'ct/rr/'.$theProduct['id'].'?action=day-add-nm-prepare&ct='.$theProduct['id'].'&id=0')?>&nbsp;
            <?= Html::a('+Blank day after', DIR.'ct/rr/'.$theProduct['id'].'?action=day-add-blank-after&id=0') ?>&nbsp;
        </div>
        <? } ?>

    <table class="table table-bordered table-condensed">
        <tbody>
            <?
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $id) {
                foreach ($theDays as $li) {
                    if ($li['id'] == $id) {
                        if ($li['step'] == 0) {
            ?>
            <tr id="ngay-<?= $li['id'] ?>" class="day-title">
                <td>
                    <a title="Link to this day" class="pull-right text-muted" href="#ngay-<?= $li['id'] ?>">#<?= $li['id'] ?></a>
                </td>
            </tr>
            <?
                        } elseif ($li['step'] > 1) {
                            for ($i = 1; $i < $li['step']; $i ++) {
                                $cnt ++;
            ?>
            <tr id="ngay-<?= $li['id'] ?>-step-<?= $i ?>" class="day-title bg-warning">
                <td>
                    <span class="badge"><?= $cnt ?></span>
                    &middot;
                    <?= date('d-m-Y D', strtotime('+'.($cnt - 1).' days', strtotime($theProduct['day_from']))) ?>
                    &middot;
                    <strong>Free day - no services</strong> (---)
                </td>
            </tr>
            <?
                                

                            }
                        } else {
                            $cnt ++;
            ?>
            <tr id="ngay-<?= $li['id'] ?>" class="day-title">
                <td>
                    <span class="badge badge-default"><?= $cnt ?></span>
                    &middot;
                    <?= date('d-m-Y D', strtotime('+'.($cnt - 1).' days', strtotime($theProduct['day_from']))) ?>
                    &middot;
                    <strong><?=$li['name']?></strong> (<?= $li['meals'] ?>) <a title="Link to this day" class="pull-right text-muted" href="#ngay-<?= $li['id'] ?>">#<?= $li['id'] ?></a>
                </td>
            </tr>
            <?
                        }

                        if ($li['step'] <= 1) {
            ?>
            <tr class="day-content">
                <td style="vertical-align:top;">
                    <div class="row">
                        <div class="col-lg-10 col-lg-push-2 col-md-8 col-md-push-4 mb-1em">
                            <?= Markdown::process($li['body']) ?>
                        </div>
                        <div class="col-lg-2 col-lg-pull-10 col-md-4 col-md-pull-8 mb-1em">
                            <div class="text-warning"><?= $li['guides'] ?></div>
                            <div class="text-warning"><?= $li['transport'] ?></div>
                        </div>
                    </div>
                    <? if (in_array(MY_ID, [1, $theProduct['created_by'], $theProduct['updated_by']])) { ?>
                    <div class="text-right text-muted" style="font-size:11px;">
                        <?= Html::a('+Day after', DIR.'ct/rr/'.$theProduct['id'].'?action=day-add-nm-prepare&ct='.$theProduct['id'].'&id='.$li['id'])?>&nbsp;
                        <?= Html::a('+Blank day after', DIR.'ct/rr/'.$theProduct['id'].'?action=day-add-blank-after&id='.$li['id']) ?>&nbsp;
                        <?= Html::a('Copy down', DIR.'ct/rr/'.$theProduct['id'].'?action=day-copy-down&id='.$li['id']) ?>&nbsp;
                        <?= $cnt != 1 ? Html::a('Move up', DIR.'ct/rr/'.$theProduct['id'].'?action=day-move-up&id='.$li['id']).'&nbsp;' : '' ?>
                        <?= $cnt != count($theDays) ? Html::a('Move down', DIR.'ct/rr/'.$theProduct['id'].'?action=day-move-down&id='.$li['id']).'&nbsp;' : '' ?>
                        <?= Html::a('Edit', DIR.'days/u/'.$li['id']) ?>&nbsp;
                        <?= Html::a('Delete', DIR.'ct/rr/'.$theProduct['id'].'?action=day-delete&return='.$lastId.'&id='.$li['id'], ['class'=>'day-delete', 'rel'=>$li['id']]) ?>&nbsp;
                    </div>
                    <? } ?>
                </td>
            </tr>
            <?
                            $lastId = $li['id'];
                        }
                    }
                }   
            }
            ?>
        </tbody>
    </table>

    <?php if ($theProduct['owner'] == 'at' && $theProduct['language'] == 'fr') { ?>
    <hr>
    <p><strong>TABLEAU DEVIS</strong> <?= Html::a('Edit', '/products/td/'.$theProduct['id']) ?></p>
    <?php if (!empty($metaData)) { ?>
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
            <?php foreach ($metaData as $line) { ?>
            <tr>
                <td><?= $line[0] ?></td>
                <td><?= $line[1] ?></td>
                <td><?= $line[2] ?></td>
                <td><?= $line[3] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
    <?php } ?>

    <hr>
        <p><strong>TAGS</strong>: <?= $theProduct['tags'] ?></p>
        <p><strong>NOTE</strong></p>
        <div class="mb-1em"><?= Markdown::process($theProduct['summary']) ?></div>

        <p><strong>INTRO</strong></p>
        <div class="mb-1em"><?= Markdown::process($theProduct['intro']) ?></div>
        <p><strong>KEY POINTS</strong></p>
        <div class="mb-1em"><?= Markdown::process($theProduct['points']) ?></div>
        <p><strong>TERMS AND CONDITIONS</strong></p>
        <div class="mb-1em"><?= Markdown::process($theProduct['conditions']) ?></div>
        <p><strong>MORE INFORMATION</strong></p>
        <div class="mb-1em"><?= Markdown::process($theProduct['others']) ?></div>
    </div>
</div><!-- col col-2 -->
<?
$js = <<<TXT
$('a.day-delete').on('click', function(){
    if (!confirm('Are you sure you want to delete this?')) {
        return false;
    }
});
TXT;
$this->registerJs($js);