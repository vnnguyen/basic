<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = Yii::t('x', 'Phân bổ chi phí QC theo thời gian mở HS, loại khách và kênh liên hệ');
Yii::$app->params['page_breadcrumbs'] = [
    ['Reports', 'reports'],
    ['Marketing', 'reports/mkt'],
    ['#05', 'reports/mkt-05'],
];

$this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom border-0 mb-0">
    <li class="nav-item"><a class="nav-link<?= $action == 'cost' ? ' active' : '' ?>" href="?action=cost">Phân bổ chi phí</a></li>
    <li class="nav-item"><a class="nav-link<?= $action == 'excel' ? ' active' : '' ?>" href="?action=excel">Xuất Excel cho KT</a></li>
    <li class="nav-item"><a class="nav-link<?= $action == 'update' ? ' active' : '' ?>" href="?action=update">Update chi phí</a></li>
</ul><?php
$this->endBlock();

if ($action == 'cost') {

$noTypeList = [
    ''=>['id'=>'t0', 'name'=>'No source', 'description'=>'No source data'],
];

$viewList = [
    'ok'=>Yii::t('x', 'HS đã phân bổ chi phí'),
    'nok'=>Yii::t('x', 'HS chưa phân bổ chi phí'),
    'all'=>Yii::t('x', 'Tất cả HS đã và chưa phân bổ'),
];

?>
<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control']) ?>
        được tạo:
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?>
        kết thúc tour:
        <?= Html::dropdownList('date_end', $date_end, $dateEndList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?>
        của bán hàng:
        <?= Html::dropdownList('seller', $seller, $sellerList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Not selected')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title">
                <?= Yii::t('x', 'Phân bổ chi phí K các hồ sơ mở năm {year}', ['year'=>$year]) ?>.
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-narrow">
                <thead>
                    <tr>
                        <th class="text-center"><?= Yii::t('x', 'Channel') ?> \ <?= Yii::t('x', 'Source') ?></th>
                        <?php foreach (array_merge($typeList, $noTypeList) as $typeId=>$types) { ?>
                        <th class="text-center" width="15%"><?= $types['name'] ?></th>
                        <?php } ?>
                        <th class="text-center" width="15%"><?= Yii::t('x', 'All sources') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($channelList as $channelId=>$channels) {?>
                    <tr>
                        <th>
                            <?= $channels['name'] ?>
                            <span class="text-muted" style="font-weight:normal"><?= $channels['description'] ?></span>
                        </th>
                        <?php foreach (array_merge($typeList, $noTypeList) as $typeId=>$types) { ?>
                        <td class="text-center">
                            <?php
                            if (isset($data[$year][$channelId][$typeId])) {
                                $item = $data[$year][$channelId][$typeId];
                                if ($item['count'] == 0) { ?>
                            <div class="text-muted">0</div>
                            <?php
                                } elseif ($view == 'nok') { ?>
                            <div><a href="/cases?date_created=<?= $year ?>&kx=<?= $channels['id'] ?>&how_found=<?= $types['id'] ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=no" target="_blank"><?= $item['count'] ?></a></div>
                            <?php
                                } else {
                                    if ($item['count'] != 0) {
                                        $item['avg'] = $item['total'] / $item['count'];
                                    }
                                    if ($typeId == 'new') {
                                        $class = 'text-primary';
                                    } elseif ($typeId == 'referred') {
                                        $class = 'text-purple';
                                    } else {
                                        $class = 'text-pink';
                                    }
                            ?>
                            <div class="small"><?= number_format($item['count']) ?> <span class="text-muted ">cases.</span> <a href="/cases?date_created=<?= $year ?>&kx=<?= $channels['id'] ?>&how_found=<?= $types['id'] ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=yes" target="_blank"><?= Yii::t('x', 'View') ?></a></div>
                            <div class="<?= $class ?>"><?= number_format($item['total'], 1) ?> &euro;</div>
                            <div class="small text-muted">avg. <span style="color:#333"><?= number_format($item['avg'], 1) ?></span> &euro;/case</div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                        <?php } ?>
                        <th class="text-center alpha-success">
                            <?php
                            $item = $data[$year][$channelId]['alltypes'];
                            if ($item['count'] == 0) { ?>
                            <div class="text-muted">0</div>
                            <?php
                            } elseif ($view == 'nok') { ?>
                            <div><a href="/cases?date_created=<?= $year ?>&kx=<?= $channels['id'] ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=no" target="_blank"><?= $item['count'] ?></a></div>
                            <?php
                            } else {
                                if ($item['count'] != 0) {
                                    $item['avg'] = $item['total'] / $item['count'];
                                }
                                $class = 'text-slate';

                            ?>
                            <div class="small"><?= number_format($item['count']) ?> <span class="text-muted ">cases.</span> <a href="/cases?date_created=<?= $year ?>&kx=<?= $channels['id'] ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=yes" target="_blank"><?= Yii::t('x', 'View') ?></a></div>
                            <div class="<?= $class ?>"><?= number_format($item['total'], 1) ?> &euro;</div>
                            <div class="small text-muted">avg. <span style="color:#333"><?= number_format($item['avg'], 1) ?></span> &euro;/case</div>
                            <?php
                            } ?>
                        </th>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th><?= Yii::t('x', 'All channels') ?></th>
                        <?php foreach (array_merge($typeList, $noTypeList) as $typeId=>$types) { ?>
                        <th class="text-center alpha-orange">
                            <?php
                            if (isset($data[$year]['allchannels'][$typeId])) {
                                $item = $data[$year]['allchannels'][$typeId];
                                if ($item['count'] == 0) { ?>
                            <div class="text-muted">0</div>
                            <?php
                                } elseif ($view == 'nok') { ?>
                            <div><a href="/cases?date_created=<?= $year ?>&how_found=<?= $types['id'] ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=no" target="_blank"><?= $item['count'] ?></a></div>
                            <?php
                                } else {
                                    if ($item['count'] != 0) {
                                        $item['avg'] = $item['total'] / $item['count'];
                                    }
                                    if ($typeId == 'new') {
                                        $class = 'text-primary';
                                    } elseif ($typeId == 'referred') {
                                        $class = 'text-purple';
                                    } else {
                                        $class = 'text-pink';
                                    }
                            ?>
                            <div class="small"><?= number_format($item['count']) ?> <span class="text-muted ">cases.</span> <a href="/cases?date_created=<?= $year ?>&how_found=<?= $types['id'] ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=yes" target="_blank"><?= Yii::t('x', 'View') ?></a></div>
                            <div class="<?= $class ?>"><?= number_format($item['total'], 1) ?> &euro;</div>
                            <div class="small text-muted">avg. <span style="color:#333"><?= number_format($item['avg'], 1) ?></span> &euro;/case</div>
                            <?php
                                }
                            }
                            ?>
                        </th>
                        <?php } ?>
                        <th class="text-center alpha-info">
                            <?php
                            $item = $data[$year]['allchannels']['alltypes'];
                            if ($item['count'] == 0) { ?>
                            <div class="text-muted">0</div>
                            <?php
                            } elseif ($view == 'nok') { ?>
                            <div><a href="/cases?date_created=<?= $year ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=no" target="_blank"><?= number_format($item['count']) ?></a></div>
                            <?php
                            } else {
                                $item['avg'] = $item['count'] == 0 ? 0 : $item['total'] / $item['count'];
                                $class = 'text-brown';
                            ?>
                            <div class="small"><?= number_format($item['count']) ?> <span class="text-muted ">cases.</span> <a href="/cases?date_created=<?= $year ?>&date_end=<?= $date_end ?>&owner_id=<?= $seller ?>&kxcost=yes" target="_blank"><?= Yii::t('x', 'View') ?></a></div>
                            <div class="<?= $class ?>"><?= number_format($item['total'], 1) ?> &euro;</div>
                            <div class="small text-muted">avg. <span style="color:#333"><?= number_format($item['avg'], 1) ?></span> &euro;/case</div>
                            <?php
                            } ?>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php } // action cost ?>


<?php if ($action == 'excel') {

for ($m = 12; $m >= 1; $m --) {
    $monthList[$m] = $m;
}
for ($y = date('Y') + 1; $y >= 2017; $y --) {
    $yearList[$y] = $y;
}

$colorClass = [
    'new'=>'text-primary',
    'referred'=>'text-purple',
    'returning'=>'text-pink',
];
    ?>
<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::hiddenInput('action', 'excel') ?>
        HS có tour yêu cầu kết thúc vào tháng
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control']) ?>
        năm
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
    </form>
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title">For accounting department</h6>
        </div>
        <table class="table table-narrow table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('x', 'Seller') ?></th>
                    <th class="text-center"><?= Yii::t('x', 'Channel') ?></th>
                    <th colspan="3" class="text-center"><?= Yii::t('x', 'Case count by source') ?></th>
                    <th colspan="3" class="text-center"><?= Yii::t('x', 'Cost total by source') ?> (&euro;)</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th class="text-center" width="10%"><?= Yii::t('x', 'New') ?></th>
                    <th class="text-center" width="10%"><?= Yii::t('x', 'Referred') ?></th>
                    <th class="text-center" width="10%"><?= Yii::t('x', 'Returning') ?></th>
                    <th class="text-center" width="10%"><?= Yii::t('x', 'New') ?></th>
                    <th class="text-center" width="10%"><?= Yii::t('x', 'Referred') ?></th>
                    <th class="text-center" width="10%"><?= Yii::t('x', 'Returning') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $se=>$chx) { ?>
                    <?php foreach ($chx as $ch=>$tyx) { ?>
                        <?php if ($data[$se][$ch]['total'] > 0) { ?>
                <tr>
                    <th><?= $sellerList[$se]['name'] ?? Yii::t('x', '(No seller)') ?></th>
                    <th class="text-center"><?= strtoupper($ch) ?></th>
                    <?php foreach (['new', 'referred', 'returning'] as $ty) { $item = $data[$se][$ch][$ty]; ?>
                    <td class="text-center <?= $colorClass[$ty] ?> <?= $item['num'] == 0 ? 'text-muted' : '' ?>"><?= $item['num'] ?></td>
                    <?php } ?>
                    <?php foreach (['new', 'referred', 'returning'] as $ty) { $item = $data[$se][$ch][$ty]; ?>
                    <td class="text-center <?= $colorClass[$ty] ?> <?= $item['cost'] == 0 ? 'text-muted' : '' ?>"><?= $item['cost'] ?></td>
                    <?php } ?>
                </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>

<?php if ($action == 'update') { ?>
<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title">Update K cost (Note: Phuong Anh only)</h6>
        </div>
        <div class="card-body">
            <?php if (in_array(USER_ID, [1, 695])) { ?>
            <form method="post" action="">
                <div class="form-group">
                    <label>Với mọi HSBH được mở trong khoảng thời gian sau đây:</label>
                    <div>
                        <input type="text" name="dates" class="form-control has-drp" style="display:inline-block; width:250px" value="">
                        <select class="form-control" name="newonly" style="display:inline-block; width:400px" >
                            <option value="new">Chỉ lấy những HS chưa điền chi phí bao giờ</option>
                            <option value="all">Lấy tất cả HS bất kể chưa hay đã điền chi phí</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Áp dụng chi phí theo bảng sau (để trống nếu không áp dụng, điền 0 nếu chi phí là 0,. Đơn vị tính là EUR):</label>
                    <table class="table table-narrow table-framed">
                        <tr>
                            <th class="text-center text-primary">New</th>
                            <th class="text-center text-purple">Referred</th>
                            <th class="text-center text-pink">Returning</th>
                            <th class="text-center text-slate">(No data)</th>
                            <th>Type / Channels</th>
                        </tr>
                        <?php foreach ($channelList as $channelId=>$channels) { ?>
                        <tr>
                            <?php foreach (['new', 'referred', 'returning', 't0'] as $ty) { ?>
                            <td style="padding-left:0!important"><input type="number" step="any" class="form-control text-right" name="cost[<?= $channels['id'] ?>][<?= $ty ?>]" value=""></td>
                            <?php } ?>
                            <th><?= $channels['name'] ?> <span style="font-weight:normal" class="text-muted"><?= $channels['description'] ?></span></th>
                        </tr>
                        <?php } ?>
                    </table>
                </div>

                <div class="form-group">
                    <label>Loại tiền</label>
                    <div>
                        <select name="currency" class="form-control" style="display:inline-block; width:120px">
                            <option value="EUR" selected="selected">EUR</option>
<!--                             <option value="USD">USD</option>
                            <option value="VND">VND</option> -->
                        </select>
                    </div>
                </div>
                <p>* Chi phí sẽ được ghi vào từng HS thỏa điều kiện trên và là con số cố định nếu không được update lại.</p>

                <?= Html::submitButton(Yii::t('x', 'Save changes'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('x', 'Cancel'), '?') ?>
            </form>
            <?php } ?>
        </div>
    </div>
</div>

<?php
$js = <<<'TXT'
$('.has-drp').daterangepicker({
    autoUpdateInput: false,
    locale: {
        format: 'YYYY-MM-DD',
        cancelLabel: 'No date'
    },
    ranges: {
       'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
       'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
       'This month': [moment().startOf('month'), moment().endOf('month')],
       'This year': [moment().startOf('year'), moment().endOf('year')],
       'Next month': [moment().add(1, 'months').startOf('month'), moment().add(1, 'months').endOf('month')],
       'Next year': [moment().add(1, 'years').startOf('year'), moment().add(1, 'years').endOf('year')],
    },
})
$('.has-drp').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' -- ' + picker.endDate.format('YYYY-MM-DD'));
});

$('.has-drp').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});
TXT;

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($js);
} // action update



