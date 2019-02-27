<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

for ($y = date('Y') + 2; $y >= 2007; $y --) {
    $yearList[$y] = $y;
}

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Kết quả bán hàng B2C năm '.$year;
if ($year2 != $year && $year2 != 0) {
    Yii::$app->params['page_title'] .= ' so với năm '.$year2;
}

$viewList = [
    'khoihanh'=>'Theo tháng khởi hành',
    'ketthuc'=>'Theo tháng kết thúc',
];
$indexList = [
    0=>['label'=>'Tổng số tour', 'real'=>true, 'link'=>'{yyyymm}'],
    1=>['label'=>'Tổng số khách', 'real'=>true, 'link'=>''],
    2=>['label'=>'Tổng số ngày', 'real'=>true, 'link'=>''],
    3=>['label'=>'Số khách BQ /tour', 'real'=>true, 'round'=>1, 'avg'=>true],
    4=>['label'=>'Số ngày BQ /tour', 'real'=>true, 'round'=>1, 'avg'=>true],
    5=>['label'=>'Doanh thu', 'sub'=>'EUR', 'link'=>''],
    6=>['label'=>'Giá vốn', 'sub'=>'EUR', 'link'=>''],
    7=>['label'=>'Lợi nhuận', 'sub'=>'EUR', 'link'=>''],

    17=>['label'=>'Tỉ lệ lãi', 'sub'=>'%', 'round'=>2, 'avg'=>true],
    18=>['label'=>'Tỉ lệ markup', 'sub'=>'%', 'round'=>2, 'avg'=>true],

    8=>['label'=>'Doanh thu BQ /tour', 'sub'=>'EUR', 'avg'=>true],
    9=>['label'=>'Giá vốn BQ /tour', 'sub'=>'EUR', 'avg'=>true],
    10=>['label'=>'Lợi nhuận BQ /tour', 'sub'=>'EUR', 'avg'=>true],
    11=>['label'=>'Doanh thu BQ /khách', 'sub'=>'EUR', 'avg'=>true],
    12=>['label'=>'Giá vốn BQ /khách', 'sub'=>'EUR', 'avg'=>true],
    13=>['label'=>'Lợi nhuận BQ /khách', 'sub'=>'EUR', 'avg'=>true],
    14=>['label'=>'Doanh thu BQ /khách/ngày', 'sub'=>'EUR', 'round'=>2, 'avg'=>true],
    15=>['label'=>'Giá vốn BQ /khách/ngày', 'sub'=>'EUR', 'round'=>2, 'avg'=>true],
    16=>['label'=>'Lợi nhuận BQ /khách/ngày', 'sub'=>'EUR', 'round'=>2, 'avg'=>true],
];

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
.table-narrow tr>td {padding:8px 4px!important;}
.table-narrow tr>td:first-child {padding:8px 4px!important;}
.text-underline {text-decoration:underline;}
.index-caret {position:absolute; margin:-6px 0 0 0}
</style>
<div class="col-md-12">
    <form class="form-inline mb-20">
        Xem
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control']) ?>
        ss vs
        <?= Html::dropdownList('year2', $year2, $yearList, ['class'=>'form-control', 'prompt'=>'-']) ?>
        xem
        <?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control']) ?>
        quy đổi ra
        <?= Html::dropdownList('currency', $currency, $currencyList, ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
    </form>
<style>
.select2-container {width:100%!important;}
.select2-selection {-height:36px!important;}
.select2-selection--multiple .select2-search--inline .select2-search__field {padding:5px 0;}
.select2-selection--multiple .select2-selection__choice {padding:5px 12px;}
</style>
    <div class="panel panel-default">
        <div class="panel-body">
            <div id="div-toggle-filters">
                <?php if ($sopax != '') { ?><strong>Số khách:</strong> <?= $sopax ?>; <?php } ?>
                <?php if ($songay != '') { ?><strong>Số ngày:</strong> <?= $songay ?>; <?php } ?>
                <?php if ($doanhthu != '') { ?><strong>Doanh thu:</strong> <?= $doanhthu ?> <span class="text-muted"><?= $currency ?></span>; <?php } ?>
                <?php if ($loinhuan != '') { ?><strong>Lợi nhuận:</strong> <?= $loinhuan ?> <span class="text-muted"><?= $currency ?></span>; <?php } ?>
                <?php if (!empty($diemden)) { ?><strong>Điểm đến:</strong> <?= implode(', ', $diemden) ?> (<?= $dkdiemden ?>); <?php } ?>
                &nbsp;
                <a href="#" class="pull-right action-show-filters">Thay đổi điều kiện tìm kiếm</a>
            </div>
            <div id="div-filters" style="display:none">
                <p class="text-warning">CHÚ Ý: Trang này đang được xây dựng. Các trường có ký hiệu (X) là chưa hoạt động. <a class="pull-right action-cancel-filters" href="#">Thôi</a></p>
                <form class="form-horizontal">
                    <div class="row">
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
                                <label class="col-sm-3 control-label">(X) Doanh thu:</label>
                                <div class="col-sm-9"><?= Html::textInput('doanhthu', $doanhthu, ['class'=>'form-control', 'placeholder'=>'VD. 1000-2000']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) Lợi nhuận:</label>
                                <div class="col-sm-9"><?= Html::textInput('loinhuan', $loinhuan, ['class'=>'form-control', 'placeholder'=>'VD. 1000-2000']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) Điểm đến:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('diemden', '', ArrayHelper::map($destList, 'code', 'name_en'), ['class'=>'form-control', 'multiple'=>'multiple']) ?></div>
                            </div>                            
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) Điều kiện điểm đến:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('dkdiemden', '', $dkdiemdenList, ['class'=>'form-control', 'placeholder'=>'VD. 8-10']) ?></div>
                            </div>                            
                        </div>
                    </div>
                    <div class="-text-right">
                        <button class="btn btn-primary" type="submit"><?= Yii::t('app', 'Go') ?></button>
                        <button class="btn btn-danger" type="reset"><?= Yii::t('app', 'Reset') ?></button>
                        <a class="action-cancel-filters"><?= Yii::t('app', 'Cancel') ?></a>
                    </div>
                </form>
            </div>
            <hr>
            <div>
                *BQ = bình quân *Không tính tour cancel
                <i class="fa fa-square"></i> <strong>Thực tế</strong>
                <i class="fa fa-square"></i> Dự kiến
                <span class="text-muted"><i class="fa fa-square"></i> So sánh</span>
                <span class="text-underline"><i class="fa fa-square"></i> Sẽ thay đổi</span>
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
                    <tr>
                        <th>Số tour tìm kiếm + tỉ lệ</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="info text-center">
                            <div class="text-bold"><?= number_format($result[$year][$m]['tk']) ?></div>
                            <div><?= number_format($result[$year][$m]['pc'], 2) ?>%</div>
                        </td>
                        <?php } ?>
                        <td class="warning">-</td>
                    </tr>
                    <?php foreach ($indexList as $i=>$index) { ?>

                    <?php if (!isset($index['real'])) { // Không có real tức là không có so sánh thực tế ?>
                    <tr>
                        <th rowspan="2">
                            <?= $index['label'] ?>
                            <?php if (isset($index['sub'])) { ?><div class="text-muted text-italic"><?= $index['sub'] ?><?php } ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <!-- TITLE HOA DON / DOANH THU NGUYEN TE -->
                        <?php
                        $titleDT = '';
                        $contentDT = '';
                        $titleTT = '';
                        $contentTT = '';
                        // index 5 la doanh thu
                        if ($i == 5) {
                            $titleDT = 'Dự tính thu:';
                            foreach ($hoadonNguyente[$year][$m] as $cur=>$amt) {
                                $contentDT .= '<div class="text-right">'.number_format($amt).' '.$cur.'</div>';
                            }
                            $titleTT = 'Thực tế thu:';
                            foreach ($thuNguyente[$year][$m] as $cur=>$amt) {
                                $contentTT .= '<div class="text-right">'.number_format($amt).' '.$cur.'</div>';
                            }
                        }
                        ?>
                        <td>
                            <!-- SO SANH NAM -->
                            <?php if ($year2 != 0 && $year2 != $year && $result[$year][$m][$i.'tt'] > $result[$year2][$m][$i.'tt'] && $result[$year2][$m][$i.'tt'] != 0) { ?>
                            <?php $pct = number_format(100 * ($result[$year][$m][$i.'tt'] - $result[$year2][$m][$i.'tt']) / $result[$year2][$m][$i.'tt'], 2).'%'; ?>
                            <i class="index-caret fa fa-caret-up text-success" title="<?= $pct ?>"></i>
                            <?php } ?>
                            <?php if ($year2 != 0 && $year2 != $year && $result[$year][$m][$i.'tt'] < $result[$year2][$m][$i.'tt'] && $result[$year2][$m][$i.'tt'] != 0) { ?>
                            <?php $pct = number_format(100 * ($result[$year][$m][$i.'tt'] - $result[$year2][$m][$i.'tt']) / $result[$year2][$m][$i.'tt'], 2).'%'; ?>
                            <i class="index-caret fa fa-caret-down text-danger" title="<?= $pct ?>"></i>
                            <?php } ?>

                            <!-- KHONG CO SO SANH NAM, LUC DO SE SO SANH THUC TE VA DU TINH -->
                            <?php if (($year2 == 0 || $year2 == $year) && $result[$year][$m][$i.'tt'] > $result[$year][$m][$i]) { ?>
                            <?php $pct = $result[$year][$m][$i] == 0 ? 0 : number_format(100 * ($result[$year][$m][$i.'tt'] - $result[$year][$m][$i]) / $result[$year][$m][$i], 2).'%'; ?>
                            <i class="index-caret fa fa-caret-up text-success" title="<?= $pct ?>"></i>
                            <?php } ?>
                            <?php if (($year2 == 0 || $year2 == $year) && $result[$year][$m][$i.'tt'] < $result[$year][$m][$i]) { ?>
                            <?php $pct = $result[$year][$m][$i] == 0 ? 0 : number_format(100 * ($result[$year][$m][$i.'tt'] - $result[$year][$m][$i]) / $result[$year][$m][$i], 2).'%'; ?>
                            <i class="index-caret fa fa-caret-down text-danger" title="<?= $pct ?>"></i>
                            <?php } ?>

                            <div <?php if ($titleTT != '') { ?>
                                data-toggle="popover" data-html="true" data-trigger="hover" title="<?= $titleTT ?>" data-content="<?= Html::encode($contentTT) ?>"
                                <? } ?> class="text-center text-bold <?= strtotime($year.'-'.$m.'-'.cal_days_in_month(CAL_GREGORIAN, $m, $year)) > strtotime(NOW) ? 'text-underline' : '' ?>"><?= number_format($result[$year][$m][$i.'tt'], $index['round'] ?? 0) ?></div>
                            <?php if ($year2 != 0 && $year2 != $year && $result[$year2][$m][$i.'tt'] != 0) { ?>
                            <div class="text-center text-muted" style="font-size:95%"><?= number_format($result[$year2][$m][$i.'tt'], $index['round'] ?? 0) ?></div>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="warning">
                            <?
                            if (isset($index['avg'])) {
                                $result[$year][0][$i.'tt'] = $result[$year][0][$i.'tt'] / 12;
                                if ($year2 != 0) {
                                    $result[$year2][0][$i.'tt'] = $result[$year2][0][$i.'tt'] / 12;
                                }
                            }
                            ?>
                            <div class="text-center text-warning text-bold <?= $year == date('Y') ? 'text-underline' : '' ?>"><?= number_format($result[$year][0][$i.'tt'], $index['round'] ?? 0) ?></div>
                            <?php if ($year2 != 0 && $year2 != $year && $result[$year2][0][$i.'tt'] != 0) { ?>
                            <div class="text-center text-muted" style="font-size:95%"><?= number_format($result[$year2][0][$i.'tt'], $index['round'] ?? 0) ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <?php if (isset($index['real'])) { ?>
                        <th>
                            <?= $index['label'] ?>
                            <?php if (isset($index['sub'])) { ?><div class="text-muted text-italic"><?= $index['sub'] ?><?php } ?>
                        </th>
                        <?php } // if isset index[real] ?>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <!-- TITLE HOA DON / DOANH THU NGUYEN TE -->
                        <?php
                        $titleDT = '';
                        $contentDT = '';
                        $titleTT = '';
                        $contentTT = '';
                        // index 5 la doanh thu
                        if ($i == 5) {
                            $titleDT = 'Dự tính thu nguyên tệ:';
                            foreach ($hoadonNguyente[$year][$m] as $cur=>$amt) {
                                $contentDT .= '<div class="text-right">'.number_format($amt).' '.$cur.'</div>';
                            }
                            $titleTT = 'Thực thu nguyên tệ:';
                            foreach ($thuNguyente[$year][$m] as $cur=>$amt) {
                                $contentTT .= '<div class="text-right">'.number_format($amt).' '.$cur.'</div>';
                            }
                        }
                        ?>

                        <td>
                            <?php if ($year2 != 0 && $year2 != $year && $result[$year][$m][$i] > $result[$year2][$m][$i] && $result[$year2][$m][$i] != 0) { ?>
                            <?php $pct = number_format(100 * ($result[$year][$m][$i] - $result[$year2][$m][$i]) / $result[$year2][$m][$i], 2).'%'; ?>
                            <i class="index-caret fa fa-caret-up text-success" title="<?= $pct ?>"></i>
                            <?php } ?>
                            <?php if ($year2 != 0 && $year2 != $year && $result[$year][$m][$i] < $result[$year2][$m][$i] && $result[$year2][$m][$i] != 0) { ?>
                            <?php $pct = number_format(100 * ($result[$year][$m][$i] - $result[$year2][$m][$i]) / $result[$year2][$m][$i], 2).'%'; ?>
                            <i class="index-caret fa fa-caret-down text-danger" title="<?= $pct ?>"></i>
                            <?php } ?>

                            <div <?php if ($titleDT != '') { ?>
                                data-toggle="popover" data-html="true" data-trigger="hover" title="<?= $titleDT ?>" data-content="<?= Html::encode($contentDT) ?>"
                                <? } ?> class="text-center <?= isset($index['real']) ? 'text-bold' : '' ?> <?= strtotime($year.'-'.$m.'-'.cal_days_in_month(CAL_GREGORIAN, $m, $year)) > strtotime(NOW) ? 'text-underline' : '' ?>"><?= number_format($result[$year][$m][$i], $index['round'] ?? 0) ?></div>
                            <?php if ($year2 != 0 && $year2 != $year) { ?>
                            <div class="text-center text-muted" style="font-size:90%"><?= number_format($result[$year2][$m][$i], $index['round'] ?? 0) ?></div>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="warning">
                            <?
                            if (isset($index['avg'])) {
                                // $result[$year][0][$i] = $result[$year][0][$i] / 12;
                                if ($year2 != 0) {
                                    // $result[$year2][0][$i] = $result[$year2][0][$i] / 12;
                                }
                            }
                            ?>
                            <div class="text-center text-warning <?= isset($index['real']) ? 'text-bold' : '' ?> <?= $year == date('Y') ? 'text-underline' : '' ?>"><?= number_format($result[$year][0][$i], $index['round'] ?? 0) ?></div>
                            <?php if ($year2 != 0 && $year2 != $year) { ?>
                            <div class="text-center text-muted" style="font-size:90%"><?= number_format($result[$year2][0][$i], $index['round'] ?? 0) ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th>Chỉ số / Tháng</th>
                        <?php for ($m = 1; $m <=   12; $m ++) { ?>
                        <th class="text-center"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center">Cả năm</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading no-border-bottom">
            <h6 class="panel-title"><?= Yii::t('x', 'Tỉ giá VND qua các tháng') ?></h6>
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
                <li><strong>Doanh thu dự tính:</strong> Lấy tổng tiền các hoá đơn do bán hàng làm khi bán tour; tỉ giá tính tại thời điểm phải thu tiền</li>
                <li><strong>Doanh thu thực tế:</strong> Lấy tổng tiền các lần thanh toán hoá đơn; tỉ giá tính tại thời điểm thu tiền thực tế</li>
                <li><strong>Giá vốn dự tính:</strong> Lấy giá vốn dự tính do bán hàng nhập khi bán tour; tỉ giá tính tại thời điểm bán</li>
                <li><strong>Giá vốn thực tế:</strong> Lấy tổng tiền chi phí tour thực tế do điều hành nhập; tỉ giá tính tại thời điểm thanh toán thực tế *</li>
                <li><strong>Tỉ giá:</strong> Tỉ giá trong quá khứ lấy từ bảng tỉ giá bình quân VCB do Phòng Tài chính kế toán cung cấp, tỉ giá tương lai chưa có thì lấy tỉ giá ngày gần nhất</li>
            </ul>
        </div>
    </div>
</div>
<?php

$js = <<<'JS'
$('[data-toggle="popover"]').popover()
$('.action-show-filters').on('click', function(e){
    e.preventDefault()
    $('#div-toggle-filters').hide()
    $('#div-filters').show()
})
$('.action-cancel-filters').on('click', function(e){
    e.preventDefault()
    $('#div-filters').hide()
    $('#div-toggle-filters').show()
})
$('.has-select2 select').select2()
JS;
$this->registerJs($js);