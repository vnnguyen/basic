<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

for ($y = date('Y') + 2; $y >= 2007; $y --) {
    $yearList[$y] = $y;
}

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = 'Kết quả bán hàng B2C năm '.$year;
if ($year2 != $year && $year2 != 0) {
    Yii::$app->params['page_title'] .= ' so với năm '.$year2;
}

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'Reports'), 'reports'],
    ['B2C'],
];


$viewList = [
    'tourstart'=>'Theo tháng khởi hành',
    'tourend'=>'Theo tháng kết thúc',
];

$viewMode = 'est';
if ($year2 != 0 && $year2 != $year) {
    $viewMode = 'comp';
}

$dkdiemdenList = [
    'all'=>'Gồm tất cả các điểm được chọn, bất kể thứ tự',
    'any'=>'Gồm ít nhất một trong các điểm được chọn',
    'not'=>'Không có điểm nào trong các điểm được chọn',
    'only'=>'Tất cả và chỉ gồm các điểm được chọn',
    'exact'=>'Tất cả và theo đúng thứ tự được chọn',
];

$currencyList = [
    'EUR'=>'EUR',
    'USD'=>'USD',
    'VND'=>'VND',
];

$destList = \common\models\Country::find()
    ->select(['code', 'name_en'])
    ->where(['code'=>['vn', 'la', 'kh', 'mm', 'th', 'my', 'id', 'cn']])
    ->orderBy('name_en')
    ->asArray()
    ->all();

?>
<style>
th {background-color:#f3f3f3;}
.text-actual {font-weight:bold;}
.text-estimated {font-weight:normal; color:#00bcd4;}
.text-comparing {font-weight:normal; color:#ff5722;}
.text-changing {border-bottom:1px solid #00bcd4!important;}

.index-caret {position:absolute; margin:-6px 0 0 0}
.select2-container {width:100%!important;}
.select2-selection {-height:36px!important;}
.select2-selection--multiple .select2-search--inline .select2-search__field {padding:5px 0;}
.select2-selection--multiple .select2-selection__choice {padding:5px 12px;}
</style>

<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-white">
            <div class="pull-right">
                Chú thích:
                <span class="text-actual"><i class="fa fa-square"></i> Thực tế</span>
                <span class="text-estimated"><i class="fa fa-square"></i> Dự kiến</span>
                <span class="text-comparing"><i class="fa fa-square"></i> So sánh</span>
                <span class="text-changing"><i class="fa fa-square"></i> Sẽ thay đổi</span>
            </div>
            <h6 class="card-title"><i class="fa fa-circle-o mr-2"></i><?= Yii::$app->params['page_title'] ?></h6>
        </div>
        <div class="card-body">
            <div id="div-current-filters">
                Đang xem kết quả của <strong><?= number_format($tourCount) ?></strong> tour B2C <strong><?= $view == 'tourend' ? 'kết thúc' : 'khởi hành' ?></strong> năm <strong><?= $year ?></strong><?php if ($year2 != 0) { ?> (so sánh với năm <strong><?= $year2 ?></strong>)<?php } ?> (không tính tour huỷ) quy ra tiền <strong><?= $currency ?></strong>;
                <?php if ($sopax != '') { ?><strong>Số khách:</strong> <?= $sopax ?>; <?php } ?>
                <?php if ($songay != '') { ?><strong>Số ngày:</strong> <?= $songay ?>; <?php } ?>
                <?php if ($doanhthu != '') { ?><strong>Doanh thu:</strong> <?= $doanhthu ?> <span class="text-muted"><?= $currency ?></span>; <?php } ?>
                <?php if ($loinhuan != '') { ?><strong>Lợi nhuận:</strong> <?= $loinhuan ?> <span class="text-muted"><?= $currency ?></span>; <?php } ?>
                <?php if (!empty($diemden)) { ?><strong>Điểm đến:</strong> <?= implode(', ', $diemden) ?> (<?= $dkdiemden ?>); <?php } ?>
                <?php if (!empty($kx_source) || !empty($tx_source)) { ?><strong>Nguồn:</strong> <?= implode(', ', $kx_source) ?> | <?= implode(', ', $tx_source) ?>; <?php } ?>
                &nbsp;
                &middot;
                <a href="#" class="action-cancel-filters" style="display:none;"><?= Yii::t('x', 'Cancel') ?></a>
                <a href="#" class="action-show-filters"><?= Yii::t('x', 'Thay đổi điều kiện tìm kiếm') ?></a>
                &middot;
                <a href="?" class="action-reset-filters"><?= Yii::t('x', 'Reset') ?></a>
            </div>
            <div id="div-filters" class="mt-2" style="display:none">
                <!-- <p class="text-warning">CHÚ Ý: Trang này đang được xây dựng. Các trường có ký hiệu (X) là chưa hoạt động.</p> -->
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">Xem tour theo:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">Năm / So sánh:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-6"><?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control']) ?></div>
                                        <div class="col-sm-6"><?= Html::dropdownList('year2', $year2, $yearList, ['class'=>'form-control', 'prompt'=>'-']) ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">Loại tiền quy đổi:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('currency', $currency, $currencyList, ['class'=>'form-control']) ?></div>
                            </div>
                        <!--
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) Doanh thu:</label>
                                <div class="col-sm-9"><?= Html::textInput('doanhthu', $doanhthu, ['class'=>'form-control', 'placeholder'=>'VD. 1000-2000']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) Lợi nhuận:</label>
                                <div class="col-sm-9"><?= Html::textInput('loinhuan', $loinhuan, ['class'=>'form-control', 'placeholder'=>'VD. 1000-2000']) ?></div>
                            </div>
                        -->
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">Số khách:</label>
                                <div class="col-sm-9"><?= Html::textInput('sopax', $sopax, ['class'=>'form-control', 'placeholder'=>'VD. 8-10']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">Số ngày tour:</label>
                                <div class="col-sm-9"><?= Html::textInput('songay', $songay, ['class'=>'form-control', 'placeholder'=>'VD. 8-10']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">Nguồn:</label>
                                <div class="col-sm-4 has-select2"><?= Html::dropdownList('kx_source', $kx_source, ArrayHelper::map($channelList, 'id', 'name'), ['class'=>'form-control', 'multiple'=>'multiple']) ?></div>
                                <div class="col-sm-5 has-select2"><?= Html::dropdownList('tx_source', $tx_source, ArrayHelper::map($typeList, 'id', 'name'), ['class'=>'form-control', 'multiple'=>'multiple']) ?></div>
                            </div>


                            <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) Điểm đến:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('diemden', $diemden, ArrayHelper::map($destList, 'code', 'name_en'), ['class'=>'form-control', 'multiple'=>'multiple']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) Điều kiện điểm đến:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('dkdiemden', $dkdiemden, $dkdiemdenList, ['class'=>'form-control', 'placeholder'=>'VD. 8-10']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="-text-right">
                        <button class="btn btn-primary" type="submit"><?= Yii::t('app', 'Go') ?></button>
                        <a class="action-cancel-filters"><?= Yii::t('x', 'Cancel') ?></a>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th>Chỉ số \ Tháng</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center" width="6%"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center" width="8%">Cả năm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indexList as $i=>$index) { ?>
                    <tr>
                        <th>
                            <?= $i ?>. <?= $index['label'] ?>
                            <?php if (isset($index['hint'])) { ?><sup title="<?= $index['hint'] ?>" style="cursor:help"><i class="fa fa-question-circle"></i></sup><?php } ?>
                            <?php if (isset($index['sub'])) { ?><div class="text-muted text-italic"><?= $index['sub'] ?></div><?php } ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <?php
                        $titleDT = '';
                        $contentDT = '';
                        $titleTT = '';
                        $contentTT = '';
                        // index 5 la doanh thu
                        if ($i == 5) {
                            $titleDT = 'Dự tính:';
                            foreach ($hoadonNguyente[$year][$m] as $cur=>$amt) {
                                $contentDT .= '<div class="text-right">'.number_format($amt).' '.$cur.'</div>';
                            }
                            $titleTT = 'Thực tế:';
                            foreach ($thuNguyente[$year][$m] as $cur=>$amt) {
                                $contentTT .= '<div class="text-right">'.number_format($amt).' '.$cur.'</div>';
                            }
                        }
                        ?>

                        <td class="<?= strtotime($year.'-'.$m.'-'.cal_days_in_month(CAL_GREGORIAN, $m, $year)) > strtotime(NOW) ? 'text-changing' : '' ?>">
                            <?php
                            // Caret info
                            $caret = false;
                            if (isset($index['est']) && $viewMode == 'est') {
                                $pct = $result[$year][$m][$i]['estimated'] == 0 ? 0 : number_format(100 * ($result[$year][$m][$i]['actual'] - $result[$year][$m][$i]['estimated']) / $result[$year][$m][$i]['estimated'], 2).'%';
                                if ($result[$year][$m][$i]['actual'] > $result[$year][$m][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'up',
                                        'color'=>'text-success',
                                        'text'=>'Thực tế cao hơn '.$pct.' so với dự tính',
                                    ];
                                } elseif ($result[$year][$m][$i]['actual'] < $result[$year][$m][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'down',
                                        'color'=>'text-danger',
                                        'text'=>'Thực tế thấp hơn '.$pct.' so với dự tính',
                                    ];
                                } else {
                                    $caret = [
                                        'dir'=>'right',
                                        'color'=>'text-info',
                                        'text'=>'Thực tế đúng bằng dự tính',
                                    ];
                                }
                            } elseif ($viewMode == 'comp') {
                                $pct = $result[$year][$m][$i]['comp'] == 0 ? 0 : number_format(100 * ($result[$year][$m][$i]['actual'] - $result[$year][$m][$i]['comp']) / $result[$year][$m][$i]['comp'], 2).'%';
                                if ($result[$year][$m][$i]['actual'] > $result[$year][$m][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'up',
                                        'color'=>'text-success',
                                        'text'=>'Năm '.$year.' cao hơn '.$pct.' so với '.$year2,
                                    ];
                                } elseif ($result[$year][$m][$i]['actual'] < $result[$year][$m][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'down',
                                        'color'=>'text-danger',
                                        'text'=>'Năm '.$year.' thấp hơn '.$pct.' so với '.$year2,
                                    ];
                                } else {
                                    $caret = [
                                        'dir'=>'right',
                                        'color'=>'text-info',
                                        'text'=>'Năm '.$year.' đúng bằng '.$year2,
                                    ];
                                }
                            }
                            ?>
                            <?php if ($caret) { ?>
                            <i class="index-caret fa fa-caret-<?= $caret['dir'] ?> <?= $caret['color'] ?>" title="<?= $caret['text'] ?>"></i>
                            <?php } ?>
                            <div class="text-center text-actual" <?php if ($titleTT != '') { ?>data-toggle="popover" data-html="true" data-trigger="hover" title="<?= $titleTT ?>" data-content="<?= Html::encode($contentTT) ?>"<? } ?>>
                                <?= number_format($result[$year][$m][$i]['actual'], $index['round'] ?? 0) ?>
                            </div>
                            <?php if (isset($index['est']) && $viewMode == 'est') { ?>
                            <div class="text-center text-estimated" <?php if ($titleDT != '') { ?>data-toggle="popover" data-html="true" data-trigger="hover" title="<?= $titleDT ?>" data-content="<?= Html::encode($contentDT) ?>"<? } ?>>
                                <?= number_format($result[$year][$m][$i]['estimated'], $index['round'] ?? 0) ?>
                            </div>
                            <?php } ?>
                            <?php if ($viewMode == 'comp') { ?>
                            <div class="text-center text-comparing">
                                <?= number_format($result[$year][$m][$i]['comp'], $index['round'] ?? 0) ?>
                            </div>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="warning <?= strtotime($year.'-12-31') > strtotime(NOW) ? 'text-changing' : '' ?>">
                            <?php
                            // Caret info
                            $caret = false;
                            if (isset($index['est']) && $viewMode == 'est') {
                                $pct = $result[$year][0][$i]['estimated'] == 0 ? 0 : number_format(100 * ($result[$year][0][$i]['actual'] - $result[$year][0][$i]['estimated']) / $result[$year][0][$i]['estimated'], 2).'%';
                                if ($result[$year][0][$i]['actual'] > $result[$year][0][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'up',
                                        'color'=>'text-success',
                                        'text'=>'Thực tế cao hơn '.$pct.' so với dự tính',
                                    ];
                                } elseif ($result[$year][0][$i]['actual'] < $result[$year][0][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'down',
                                        'color'=>'text-danger',
                                        'text'=>'Thực tế thấp hơn '.$pct.' so với dự tính',
                                    ];
                                } else {
                                    $caret = [
                                        'dir'=>'right',
                                        'color'=>'text-info',
                                        'text'=>'Thực tế đúng bằng dự tính',
                                    ];
                                }
                            } elseif ($viewMode == 'comp') {
                                $pct = $result[$year][0][$i]['comp'] == 0 ? 0 : number_format(100 * ($result[$year][0][$i]['actual'] - $result[$year][0][$i]['comp']) / $result[$year][0][$i]['comp'], 2).'%';
                                if ($result[$year][0][$i]['actual'] > $result[$year][0][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'up',
                                        'color'=>'text-success',
                                        'text'=>'Năm '.$year.' cao hơn '.$pct.' so với '.$year2,
                                    ];
                                } elseif ($result[$year][0][$i]['actual'] < $result[$year][0][$i]['estimated']) {
                                    $caret = [
                                        'dir'=>'down',
                                        'color'=>'text-danger',
                                        'text'=>'Năm '.$year.' thấp hơn '.$pct.' so với '.$year2,
                                    ];
                                } else {
                                    $caret = [
                                        'dir'=>'right',
                                        'color'=>'text-info',
                                        'text'=>'Năm '.$year.' đúng bằng '.$year2,
                                    ];
                                }
                            }
                            ?>
                            <?php if ($caret) { ?>
                            <i class="index-caret fa fa-caret-<?= $caret['dir'] ?> <?= $caret['color'] ?>" title="<?= $caret['text'] ?>"></i>
                            <?php } ?>

                            <div class="text-center text-actual">
                                <?= number_format($result[$year][0][$i]['actual'], $index['round'] ?? 0) ?>
                            </div>
                            <?php if (isset($index['est']) && $viewMode == 'est') { ?>
                            <div class="text-center text-estimated">
                                <?= number_format($result[$year][0][$i]['estimated'], $index['round'] ?? 0) ?>
                            </div>
                            <?php } ?>
                            <?php if ($viewMode == 'comp') { ?>
                            <div class="text-center text-comparing">
                                <?= number_format($result[$year][0][$i]['comp'], $index['round'] ?? 0) ?>
                            </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <thead>
                    <tr>
                        <th>Chỉ số / Tháng</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center">Cả năm</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="actions-fillter mb-2 text-right d-none">
        <form id="form_fillter">
            <button name="export-data" class="btn btn-info export-data" type="submit">Export to excel</button>
        </form>
    </div>
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><i class="fa fa-circle-o mr-2"></i><?= Yii::t('x', 'Chi tiết thực tế các tour trong từng tháng') ?></h6>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-narrow table-striped">
            <?php for ($m = 1; $m <= 12; $m ++) { ?>
                <tr class="warning">
                    <th width="15"></th>
                    <th>Tour tháng <?= $m ?>/<?= $year ?></th>
                    <th class="text-center" width="60">Pax</th>
                    <th class="text-center" width="60">Ngày</th>
                    <th class="text-right" width="120">Doanh thu</th>
                    <th class="text-right" width="120">Giá vốn</th>
                    <th class="text-right" width="120">Lơi nhuận</th>
                    <th class="text-right" width="80">% lãi</th>
                    <th class="text-center" width="60">Kx</th>
                    <th class="text-center" width="60">Tx</th>
                    <th>Note</th>
                </tr>

                <?php if (empty($detail[$m])) { ?>
                <tr><td class="text-danger" colspan="10">Không có thông tin.</td></tr>

                <?php } else { ?>
                <?php foreach ($detail[$m] as $i=>$detailItem) { ?>
                <tr>
                    <td class="text-muted text-center"><?= 1 + $i ?></td>
                    <td><?= Html::a($detailItem['1'].' - '.$detailItem[2], '/products/op/'.$detailItem[0]) ?></td>
                    <td class="text-center"><?= number_format($detailItem[6]) ?></td>
                    <td class="text-center"><?= number_format($detailItem[5]) ?></td>
                    <td class="text-right"><strong><?= number_format($detailItem[3]) ?></strong> <span class="text-muted"><?= $currency ?></span></td>
                    <td class="text-right text-danger"><?= number_format($detailItem[4]) ?> <span class="text-muted"><?= $currency ?></span></td>
                    <td class="text-right text-success"><?= number_format($detailItem[3] - $detailItem[4]) ?> <span class="text-muted"><?= $currency ?></span></td>
                    <td class="text-right"><?= number_format($detailItem[3] == 0 ? 0 : 100 * ($detailItem[3] - $detailItem[4]) / $detailItem[3], 2) ?> <span class="text-muted">%</span></td>
                    <td class="text-center"><?= $detailItem[7] ?></td>
                    <td class="text-center"><?= $detailItem[8] ?></td>
                    <td></td>
                </tr>
                <?php } ?>
                <tr>
                    <th></th>
                    <th>Tổng số</th>
                    <th class="text-center"><?= number_format($result[$year][$m][1]['actual']) ?></th>
                    <th class="text-center"><?= number_format($result[$year][$m][2]['actual']) ?></th>
                    <th class="text-right"><strong><?= number_format($result[$year][$m][5]['actual']) ?></strong> <span class="text-muted"><?= $currency ?></span></th>
                    <th class="text-right text-danger"><?= number_format($result[$year][$m][6]['actual']) ?> <span class="text-muted"><?= $currency ?></span></th>
                    <th class="text-right text-success"><?= number_format($result[$year][$m][7]['actual']) ?> <span class="text-muted"><?= $currency ?></span></th>
                    <th class="text-right"><?= number_format($result[$year][$m][17]['actual'], 2) ?> <span class="text-muted">%</span></th>
                    <th class="text-center"><?= 0 ?></th>
                    <th class="text-center"><?= 0 ?></th>
                    <th></th>
                </tr>
                <tr><td colspan="9"></td></tr>
                <?php } // ?>
            <?php } ?>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading no-border-bottom">
            <h6 class="panel-title"><i class="fa fa-circle-o position-left"></i><?= Yii::t('x', 'Tỉ giá qua các tháng & năm') ?></h6>
        </div>
        <div class="panel-body no-padding table-responsive">
            <table class="table table-narrow">
                <?php foreach ($xrateTable as $xy=>$xm) { ?>
                <tr>
                    <th class="text-center"><?= $xy ?></th>
                    <?php foreach ($xm as $xmi) { ?>
                    <td>
                        <?php foreach ($xmi as $cu=>$xr) { ?>
                        <div class="text-right">[<?= $cu ?>] <?= number_format($xr) ?></div>
                        <?php } ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <!--
        <div><strong>Tỉ giá (fix, 9/8/2017):</strong></div>
        <?php foreach ($xrate as $currency=>$rate) { ?>
        <div>1 <?= $currency ?> = <?= $rate ?> EUR</div>
        <?php } ?>
        <hr>
    -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Cách tính doanh thu, giá vốn. tỉ giá</h6>
        </div>
        <div class="panel-body">
            <ul>
                <li><strong>Tỉ giá:</strong> Tỉ giá trong quá khứ lấy từ bảng tỉ giá bình quân VCB do Phòng Tài chính kế toán cung cấp, tỉ giá tương lai chưa có thì lấy tỉ giá ngày gần nhất</li>
            </ul>
        </div>
    </div>
</div>
<?php

$js = <<<'JS'
// $('.export-data').on('click', function(){
//     // console.log();
//     var url = $('#form_fillter').prop('action');
//     $.ajax({
//         url: url,
//         method: 'GET'
//     })
//     .done(function(data) {
//         console.log(data);

//     }, 'json')
//     .fail(function(data) {
//         if (data['message']) {
//             alert(data['message']);
//         } else {
//             alert('Error export!');
//         }
//     });
//     return false;
// });
$('[data-toggle="popover"]').popover()
$('.action-show-filters').on('click', function(e){
    e.preventDefault()
    $('#div-filters').show()
    $('.action-show-filters').hide()
    $('.action-cancel-filters').show()
})
$('.action-cancel-filters').on('click', function(e){
    e.preventDefault()
    $('#div-filters').hide()
    $('.action-cancel-filters').hide()
    $('.action-show-filters').show()
})
$('.has-select2 select').select2()
JS;
$this->registerJs($js);