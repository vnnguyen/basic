<?
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include ('_products_inc.php');

$this->title = 'Tour products ('.number_format($pagination->totalCount, 0).')';

?>
<!--div class="alert alert-info">CHÚ Ý: Mới tách thêm 2 loại ct mới là CT tour Hãng và ct tour TCG, mọi người chú ý khi tìm kiếm</div-->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="get" action="" class="form-inline">
                <select class="form-control w-auto" name="language">
                    <option value="all">All languages</option>
                    <? foreach ($languageList as $k => $v) { ?>
                    <option value="<?=$k?>" <?=$getLanguage == $k ? 'selected="selected"' : ''?>><?=$v?></option>
                    <? } ?>
                </select>
                <? if (SEG2 != 'b2b') { ?>
                <?= Html::dropdownList('type', $getType, [
                    'private'=>'Private tours',
                    'combined2016'=>'Combined tours (2016)',
                    'vpc'=>'VPC tours (2012)',
                    'tcg'=>'TCG tours (2013)',
                    ], ['class'=>'form-control']) ?>
                <? } else { ?>
                <?//= Html::dropdownList('customer', $customer, ArrayHelper::map($customersList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'All customers']) ?>
                <? } ?>
                <select name="ub" class="form-control w-auto">
                    <option value="all">Updated by</option>
                    <option value="<?=Yii::$app->user->id?>" <?=$getUb == Yii::$app->user->id ? 'selected="selected"' : ''?>>Tôi (<?= Yii::$app->user->identity->name ?>)</option>
                    <? foreach ($ubList as $ub) { if (Yii::$app->user->id != $ub['id']) { ?>
                    <option value="<?= $ub['id'] ?>" <?=$getUb == $ub['id'] ? 'selected="selected"' : ''?>><?=$ub['lname']?>, <?=$ub['email']?></option>
                    <? } } ?>
                </select>
                <select class="form-control w-auto" name="month">
                    <option value="all">Start date</option>
                    <? foreach ($startDateList as $product) { ?>
                    <option value="<?= $product['ym'] ?>" <?= $getMonth == $product['ym'] ? 'selected="selected"' : ''?>><?= $product['ym'] ?></option>
                    <? } ?>
                </select>
                <select class="form-control w-auto" name="proposal">
                    <option value="all">Trạng thái bán</option>
                    <option value="yes" <?=$getProposal == 'yes' ? 'selected="selected"' : ''?>>Đang bán</option>
                    <option value="no" <?=$getProposal == 'no' ? 'selected="selected"' : ''?>>Chưa bán</option>
                </select>
                <select class="form-control w-auto" name="days">
                    <option value="all">Days</option>
                    <option value="10" <?=$getDays == '10' ? 'selected="selected"' : ''?>>1-10 ngày</option>
                    <option value="20" <?=$getDays == '20' ? 'selected="selected"' : ''?>>11-20 ngày</option>
                    <option value="30" <?=$getDays == '30' ? 'selected="selected"' : ''?>>21-30 ngày</option>
                    <option value="31" <?=$getDays == '31' ? 'selected="selected"' : ''?>>Trên 30 ngày</option>
                </select>
                <input type="text" class="form-control w-auto" name="name" placeholder="Search name or tag" value="<?=fHTML::encode($getName)?>" />
                <select class="form-control w-auto" name="order">
                    <option value="updated_at">Order by: Updated</option>
                    <option value="day_from" <?=$getOrder == 'day_from' ? 'selected="selected"' : ''?>>Order by: Tour date</option>
                    <option value="day_count" <?=$getOrder == 'day_count' ? 'selected="selected"' : ''?>>Order by: Days</option>
                    <option value="pax" <?=$getOrder == 'pax' ? 'selected="selected"' : ''?>>Order by: Pax</option>
                    <option value="title" <?=$getOrder == 'title' ? 'selected="selected"' : ''?>>Order by: Name</option>
                </select>
                <select class="form-control w-auto" name="sort">
                    <option value="desc">Descending</option>
                    <option value="asc" <?=$getSort == 'asc' ? 'selected="selected"' : ''?>>Ascending</option>
                </select>
                <button type="submit" class="btn btn-primary">Go</button>
                <?= Html::a('Reset', 'products') ?>
            </form>
        <? if (empty($theProducts)) { ?>
        </div>
    </div>
    <div class="alert alert-warning">No data found</div>
        <? } else { ?>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th class="text-center">Lang</th>
                        <th>Name</th>
                        <th>Start date</th>
                        <th class="text-center">Days</th>
                        <th class="text-center">Pax</th>
                        <th>Price</th>
                        <th>Updated by</th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theProducts as $product) { ?>
                    <tr>
                        <td class="text-muted text-center text-nowrap"><?= strtoupper($product['language']) ?></td>
                        <td>
<?
                            if ($product['offer_count'] == 0) {
                                echo Html::a('<i class="fa-fw fa fa-plus text-info"></i>', '@web/bookings/c?product_id='.$product['id'], ['title'=>'+ New booking']);
                            } else {
                                if (isset($product['bookings'])) {
                                    foreach ($product['bookings'] as $booking) {
                                        if (isset($booking['case'])) {
                                            echo Html::a('<i class="fa-fw fa fa-briefcase"></i>', '@web/cases/r/'.$booking['case']['id'], ['class'=>'text-warning', 'title'=>'View case: '.$booking['case']['name']]), ' ';
                                        }
                                    }
                                }
                                if (isset($product['tour']['code'])) {
                                    echo Html::a($product['tour']['code'], '@web/tours/r/'.$product['tour']['id'], ['title'=>'View tour: '.$product['tour']['name'], 'style'=>'background-color:#ffc; color:#060; padding:0 5px;']);
                                }
                            }
                            echo Html::a($product['title'], '@web/products/r/'.$product['id']);
                            ?>
                            <i class="fa fa-file-text-o popovers pull-right text-muted"
                                data-trigger="hover"
                                data-title="<?= $product['title'] ?>"
                                data-placement="left"
                                data-html="true"
                                data-content="
                            <?
                            $dayIds = explode(',', $product['day_ids']);
                            if (count($dayIds) > 0) {
                                $cnt = 0;
                                foreach ($dayIds as $id) {
                                    foreach ($product['days'] as $day) {
                                        if ($day['id'] == $id) {
                                            $cnt ++;
                                            echo '<strong>', $cnt, ':</strong> ', str_replace(['"'], ['\''], $day['name']), ' (', $day['meals'], ')<br>';
                                        }
                                    }
                                }
                            }
                            ?>
                            "></i>


                            <?


$productAttachments = [];
if (file_exists(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$product['id'].'.pdf')) {
    $productAttachments[] = [
        'type'=>'oldpdf',
        'file'=>'Itinerary.pdf',
    ];
}
$productUploadPath = Yii::getAlias('@webroot').'/upload/products/'.$product['id'];
if (file_exists($productUploadPath.'/pdf')) {
    foreach (FileHelper::findFiles($productUploadPath.'/pdf') as $file) {
        $productAttachments[] = [
            'type'=>'pdf',
            'file'=>substr(strrchr($file, '/'), 1),
        ];
    }
}
if (file_exists($productUploadPath.'/image')) {
    foreach (FileHelper::findFiles($productUploadPath.'/image') as $file) {
        $productAttachments[] = [
            'type'=>'image',
            'file'=>substr(strrchr($file, '/'), 1),
        ];
    }
}
if (file_exists($productUploadPath.'/excel')) {
    foreach (FileHelper::findFiles($productUploadPath.'/excel') as $file) {
        $productAttachments[] = [
            'type'=>'excel',
            'file'=>substr(strrchr($file, '/'), 1),
        ];
    }
}

foreach($productAttachments as $attachment) {
    $fileExt = strtolower(substr(strrchr($attachment['file'], '.'), 1));
    if (in_array($fileExt, ['pdf'])) {
        $fileIcon = '<i class="fa fa-file-pdf-o" style="color:#FC6249"></i>';
    } elseif (in_array($fileExt, ['jpg', 'png', 'jpeg'])) {
        $fileIcon = '<i class="fa fa-file-image-o" style="color:#FFB515"></i>';
    } elseif (in_array($fileExt, ['doc', 'docx', 'docm'])) {
        $fileIcon = '<i class="fa fa-file-word-o" style="color:#2A5699"></i>';
    } elseif (in_array($fileExt, ['xls', 'xlsx', 'xlsm', 'xlxb'])) {
        $fileIcon = '<i class="fa fa-file-excel-o" style="color:#207245"></i>';
    } else {
        $fileIcon = '<i class="fa fa-file-text-o"></i>';
    }
    echo ' ', Html::a($fileIcon, '/products/download/'.$product['id'].'?type='.$attachment['type'].'&file='.urlencode($attachment['file']), ['title'=>$attachment['file'], 'class'=>'text-muted']);
}

    ?>
                            <span class="text-muted"><?= $product['about'] ?></span>
                        </td>
                        <td class="text-center"><?= date('j/n/Y', strtotime($product['day_from'])) ?></td>
                        <td class="text-center"><?= count($product['days']) ?></td>
                        <td class="text-center"><?= $product['pax'] ?></td>
                        <td class="text-right"><?= number_format($product['price'], 0) ?> <span class="text-muted"><?= $product['price_unit'] ?></span></td>
                        <td>
                            <?= Html::a($product['updatedBy']['name'], '@web/users/r/'.$product['updatedBy']['id']) ?>
                            <span class="text-muted"><?= Yii::$app->formatter->asRelativeTime($product['updated_at']) ?></span>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-edit"></i>', '@web/products/u/'.$product['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>

    <? if ($pagination->totalCount > $pagination->limit) { ?>
    <div class="text-center">
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]) ?>
    </div>
    <? } // if pagination ?>

    <? } // if empty products ?>
</div>
<style type="text/css">
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>
<?
$js = <<<TXT
$('.popovers').popover();
TXT;
$this->registerJs($js);