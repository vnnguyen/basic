<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Nha cung cap (list tu ke toan) - '.number_format($pagination->totalCount);
$this->params['icon'] = 'home';
$this->params['breadcrumb'] = [
    ['NCC', 'ncc'],
];

$statusList = [
    ''=>'Chưa sửa',
    'ok'=>'OK - đã sửa',
    'nok'=>'NOK - không dùng',
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Danh sách nhà cung cấp</h6>
        </div>
        <div class="panel-body">
            <form method="get" action="" class="form-inline">
                <?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control']) ?>
                <?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Tìm tên / mã NCC']) ?>
                <?= Html::submitButton('Lọc', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Đặt lại', '/ncc') ?>
            </form>
        </div>
        <? if (empty($theNccs)) { ?>
        <div class="panel-body text-danger">Không có thông tin.</p>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Code KT</th>
                        <th>Tên (click để sửa)</th>
                        <th>Link đến</th>
                        <th>Còn thiếu</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theNccs as $ncc) { ?>
                    <tr>
                        <td>
                            <? if ($ncc['status'] == '') { ?><span class="label label-default">X</span><? } ?>
                            <? if ($ncc['status'] == 'ok') { ?><span class="label label-success">K</span><? } ?>
                            <? if ($ncc['status'] == 'nok') { ?><span class="label label-danger">N</span><? } ?>
                        </td>
                        <td class="text-nowrap"><?= $ncc['ma'] ?></td>
                        <td class="text-nowrap"><?= Html::a($ncc['ten'], '/ncc/u?id='.$ncc['id'], ['title'=>$ncc['updated_dt']. ' / '.$ncc['updated_by']]) ?></td>
                        <td><?= Html::a($ncc['venue']['name'], '/venues/r/'.$ncc['venue']['id'], ['target'=>'_blank']) ?></td>
                        <td class="text-danger">
                            <? if ($ncc['ten'] == $ncc['ten_cty']) { ?>[TG]<? } ?>
                            <? if ($ncc['mst'] == '') { ?>[MS]<? } ?>
                            <? if ($ncc['diachi'] == '') { ?>[DC]<? } ?>
                            <? if ($ncc['so_tk'] == '') { ?>[NH]<? } ?>
                            <? if ($ncc['nganhang'] == '') { ?>[TK]<? } ?>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>

        <? if ($pagination->totalCount > $pagination->pageSize) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]) ?>
        </div>
        <? } ?>

        <? } ?>
    </div>
</div>