<?php
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use app\widgets\LinkPager;

include ('_program_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'B2B tour programs ('.number_format($pagination->totalCount, 0).')';

?>
<!--div class="alert alert-info">CHÚ Ý: Mới tách thêm 2 loại ct mới là CT tour Hãng và ct tour TCG, mọi người chú ý khi tìm kiếm</div-->
<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-2">
        <?= Html::dropdownList('client', $client, ArrayHelper::map($clientList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Client')]) ?>
        <?= Html::dropdownList('language', $language, $languageList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Language')]) ?>
        <?= Html::dropdownList('type', $type, [
            'normal'=>'Normal programs',
            'sample'=>'Sample programs',
            'b2b-prod'=>'Product tours',
            ], ['class'=>'form-control']) ?>
        <select name="ub" class="form-control w-auto">
            <option value="">Updated by</option>
            <option value="<?= USER_ID ?>" <?= $ub == USER_ID ? 'selected="selected"' : ''?>>Tôi (<?= Yii::$app->user->identity->name ?>)</option>
            <?php foreach ($ubList as $ub) { if (USER_ID != $ub['id']) { ?>
            <option value="<?= $ub['id'] ?>" <?=$ub == $ub['id'] ? 'selected="selected"' : ''?>><?=$ub['lname']?>, <?=$ub['email']?></option>
            <?php } } ?>
        </select>
        <select class="form-control w-auto" name="month">
            <option value=""><?= Yii::t('x', 'Start date') ?></option>
            <?php foreach ($startDateList as $program) { ?>
            <option value="<?= $program['ym'] ?>" <?= $month == $program['ym'] ? 'selected="selected"' : ''?>><?= $program['ym'] ?></option>
            <?php } ?>
        </select>
        <select class="form-control w-auto" name="proposal">
            <option value="">Trạng thái bán</option>
            <option value="yes" <?=$proposal == 'yes' ? 'selected="selected"' : ''?>>Đang bán</option>
            <option value="no" <?=$proposal == 'no' ? 'selected="selected"' : ''?>>Chưa bán</option>
        </select>
        <select class="form-control w-auto" name="days">
            <option value=""><?= Yii::t('x', 'Days') ?></option>
            <option value="10" <?=$days == '10' ? 'selected="selected"' : ''?>>1-10 ngày</option>
            <option value="20" <?=$days == '20' ? 'selected="selected"' : ''?>>11-20 ngày</option>
            <option value="30" <?=$days == '30' ? 'selected="selected"' : ''?>>21-30 ngày</option>
            <option value="31" <?=$days == '31' ? 'selected="selected"' : ''?>>Trên 30 ngày</option>
        </select>
        <?= Html::textInput('name', $name, ['class'=>'form-control w-auto', 'placeholder'=>'Search name or tag']) ?>
        <select class="form-control w-auto" name="order">
            <option value="updated_at">Order by: Updated</option>
            <option value="day_from" <?= $order == 'day_from' ? 'selected="selected"' : ''?>>Order by: Tour date</option>
            <option value="day_count" <?= $order == 'day_count' ? 'selected="selected"' : ''?>>Order by: Days</option>
            <option value="pax" <?= $order == 'pax' ? 'selected="selected"' : ''?>>Order by: Pax</option>
            <option value="title" <?= $order == 'title' ? 'selected="selected"' : ''?>>Order by: Name</option>
        </select>
        <select class="form-control w-auto" name="sort">
            <option value="desc">Descending</option>
            <option value="asc" <?= $sort == 'asc' ? 'selected="selected"' : ''?>>Ascending</option>
        </select>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <?php if (empty($thePrograms)) { ?>
    <div class="text-warning"><?= Yii::t('x', 'No data found.') ?></div>
    <?php } else { ?>
    <div class="card mb-2">
        <div class="table-responsive">
            <table class="table card-table table-narrow">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th class="text-center"><?= Yii::t('x', 'Lang') ?></th>
                        <th><?= Yii::t('x', 'For client') ?></th>
                        <th><?= Yii::t('x', 'Name of program') ?></th>
                        <th class="text-center"><?= Yii::t('x', 'Pax') ?></th>
                        <th class="text-center"><?= Yii::t('x', 'Days') ?></th>
                        <?php if ($type != 'b2b-prod') { ?>
                        <th class="text-center"><?= Yii::t('x', 'Start date') ?></th>
                        <?php } ?>
                        <th class="text-right"><?= Yii::t('x', 'Price') ?></th>
                        <th><?= Yii::t('x', 'Updated by') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($thePrograms as $program) { ?>
                    <tr>
                        <td>
                            <?= Html::a('<i class="fa fa-edit"></i>', '@web/b2b/programs/u/'.$program['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
                        </td>
                        <td class="text-muted text-center text-nowrap"><?= strtoupper($program['language']) ?></td>
                        <td><?= $program['client_id'] == 0 ? '' : Html::a($program['client']['name'], '/b2b/clients/r/'.$program['client_id']) ?></td>
                        <td>
<?
                            if ($program['offer_count'] == 0) {
                                echo Html::a('<i class="fa-fw fa fa-plus text-info"></i>', '@web/bookings/c?product_id='.$program['id'], ['title'=>'+ New booking']);
                            } else {
                                if (isset($program['bookings'])) {
                                    foreach ($program['bookings'] as $booking) {
                                        if (isset($booking['case'])) {
                                            echo Html::a('<i class="fa-fw fa fa-briefcase"></i>', '@web/cases/r/'.$booking['case']['id'], ['class'=>'text-warning', 'title'=>'View case: '.$booking['case']['name']]), ' ';
                                        }
                                    }
                                }
                                if (isset($program['tour']['code'])) {
                                    echo Html::a($program['tour']['code'], '@web/tours/r/'.$program['tour']['id'], ['title'=>'View tour: '.$program['tour']['name'], 'style'=>'background-color:#ffc; color:#060; padding:0 5px;']);
                                }
                            }
                            echo Html::a($program['title'], '@web/b2b/programs/r/'.$program['id']);

if (is_dir(Yii::getAlias('@webroot').'/upload/products/'.$program['id'])) {
    $programFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@webroot').'/upload/products/'.$program['id']);
// if (file_exists(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$program['id'].'.pdf')) {
//     $programAttachments[] = [
//         'type'=>'oldpdf',
//         'file'=>'Itinerary.pdf',
//     ];
// }

    foreach($programFiles as $file) {
        $fileExt = strtolower(substr(strrchr($file, '.'), 1));
        if (in_array($fileExt, ['pdf'])) {
            $fileIcon = '<i class="fa fa-file-pdf-o" style="color:#FC6249"></i>';
        } elseif (in_array($fileExt, ['jpg', 'png', 'jpeg']) && strpos($file, '/map/') !== false) {
            $fileIcon = '<i class="fa fa-map-o" style="color:#FFB515"></i>';
        } elseif (in_array($fileExt, ['jpg', 'png', 'jpeg'])) {
            $fileIcon = '<i class="fa fa-file-image-o" style="color:#FFB515"></i>';
        } elseif (in_array($fileExt, ['doc', 'docx', 'docm'])) {
            $fileIcon = '<i class="fa fa-file-word-o" style="color:#2A5699"></i>';
        } elseif (in_array($fileExt, ['xls', 'xlsx', 'xlsm', 'xlxb'])) {
            $fileIcon = '<i class="fa fa-file-excel-o" style="color:#207245"></i>';
        } else {
            $fileIcon = '<i class="fa fa-file-text-o"></i>';
        }
        echo ' ', Html::a($fileIcon, str_replace(Yii::getAlias('@webroot'), '', $file));
    }
}
    ?>
                            <span class="text-muted"><?= $program['about'] ?></span>
                            <div class="small">
                                <?php if ($program['client_series'] != '') { ?>
                                <span class="text-info"><?= $program['client_series'] ?></span> &nbsp; 
                                <?php } ?>
                            </div>
                        </td>
                        <td class="text-center"><?= $program['pax'] ?></td>
                        <td class="text-center">
                            <a href="#" class="popovers"
                                data-trigger="hover"
                                data-title="<?= $program['title'] ?>"
                                data-placement="left"
                                data-html="true"
                                data-content="
                            <?
                            $dayIds = explode(',', $program['day_ids']);
                            if (count($dayIds) > 0) {
                                $cnt = 0;
                                foreach ($dayIds as $id) {
                                    foreach ($program['days'] as $day) {
                                        if ($day['id'] == $id) {
                                            $cnt ++;
                                            echo '<strong>', $cnt, ':</strong> ', str_replace(['"'], ['\''], $day['name']), ' (', $day['meals'], ')<br>';
                                        }
                                    }
                                }
                            }
                            ?>
                            "><?= count($program['days']) ?></a>
                        </td>
                        <?php if ($type != 'b2b-prod') { ?>
                        <td class="text-center">
                            <?php if ($program['offer_type'] != 'b2b-prod') { ?>
                            <?= date('j/n/Y', strtotime($program['day_from'])) ?>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="text-right text-nowrap"><?= number_format($program['price'], 0) ?> <span class="text-muted"><?= $program['price_unit'] ?></span></td>
                        <td>
                            <?= Html::a($program['updatedBy']['name'], '@web/users/r/'.$program['updatedBy']['id']) ?>
                            <span class="text-muted"><?= Yii::$app->formatter->asRelativeTime($program['updated_at']) ?></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } // if empty products ?>
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]) ?>


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