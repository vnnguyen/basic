<?php
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Báo cáo về nguồn khách (tour code F, không huỷ)';
if ($view == 'casestart') {
    Yii::$app->params['page_title'] = 'Báo cáo về nguồn khách (HSBH B2C)';
}

include('_report_qhkh_inc.php');

$indexList = [
    'new'=>['label'=>'Đoàn khách mới', 'color'=>'#2196f3'],
    'referred'=>['label'=>'Đoàn được giới thiệu', 'color'=>'#4caf50'],
    'returning'=>['label'=>'Đoàn khách quay lại', 'color'=>'#00bcd4'],
    'total'=>['label'=>'Tổng số đoàn', 'color'=>'#f44336'],
];

$viewList = [
    'casestart'=>'Mở HSBH',
    'tourstart'=>'Khởi hành tour',
    'tourend'=>'Kết thúc tour',
];

Yii::$app->params['body_class'] = 'sidebar-xs';

$refBy = [
    'referred/customer'=>'Bởi khách cũ',
    'referred/amica'=>'Bởi người Amica',
    'referred/org'=>'Bởi tổ chức liên quan',
    'referred/other'=>'Bởi nguồn khác',
    'referred/expat'=>'Bởi expat ở VN',
];

function rtrim0($text) {
    return rtrim(rtrim($text, '0'), '.');
}
?>
<style type="text/css">
.index-caret {position: absolute; margin: -6px 0 0 0}
</style>
<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-2">
        <?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::dropdownList('year2', $year2, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Comp.')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>

    <div class="card">
        <div class="card-body">
            <div id="chart1" style="height:400px;"></div>
        </div>
        <div class="card-body">
            <div id="chart2" style="height:400px;"></div>
        </div>
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th>Chỉ số \ Tháng (năm <?= $year ?><?php if ($year2 != $year && $year2 != '') { ?> so với <?= $year2 ?><?php } ?>)</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center" width="6%"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center" width="8%">Cả năm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indexList as $index=>$item) { ?>
                        <?php if ($index == 'returning') { ?>
                            <?php foreach ($result[$year][0]['ref_how_found'] as $hf=>$num) { ?>
                    <tr class="toggle-referred-list-item">
                        <th>
                            <i class="fa fa-square position-left" style="color:#fff"></i>
                            <?= $refBy[$hf] ?? $hf ?>
                        </th>
                                <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center">
                            <?php $num = $result[$year][$m]['ref_how_found'][$hf] ?? 0 ?>
                            <div>
                                <span style="font-size:120%"><?= $num ?></span>
                                <?php if ($year2 != $year && $year2 != '') { ?>
                                <?php $num2 = $result[$year2][$m]['ref_how_found'][$hf] ?? 0 ?>
                                / <span class="text-purple" title="<?= $year2 ?>"><?= $num2 ?></span>
                                <?php } else { ?>
                                <div class="text-muted" style="font-size:90%"><?= $result[$year][$m]['referred'] == 0 ? '0' : rtrim0(number_format(100 * $num / $result[$year][$m]['referred'], 2)) ?>%</div>
                                <?php } ?>
                            </div>
                        </td>
                                <?php } ?>
                        <td class="text-center">
                            <?php $num = $result[$year][0]['ref_how_found'][$hf] ?? 0 ?>
                            <div>
                                <span style="font-size:120%"><?= $num ?></span>
                                <?php if ($year2 != $year && $year2 != '') { ?>
                                <?php $num2 = $result[$year2][0]['ref_how_found'][$hf] ?? 0 ?>
                                / <span class="text-purple" title="<?= $year2 ?>"><?= $num2 ?></span>
                                <?php } else { ?>
                                <div class="text-muted" style="font-size:90%"><?= $result[$year][0]['referred'] == 0 ? '0' : rtrim0(number_format(100 * $num / $result[$year][0]['referred'], 2)) ?>%</div>
                                <?php } ?>
                            </div>
                            
                        </td>
                    </tr>
                            <?php } ?>
                        <?php } ?>
                    <tr>
                        <th>
                            <i class="fa fa-square position-left" style="color:<?= $item['color'] ?>"></i>
                            <?= $item['label'] ?>
                            <?php if ($index == 'referred') { ?>
                            <i class="toggle-referred-list fa fa-chevron-down cursor-pointer text-muted"></i>
                            <?php } ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
                            <?php if ($year2 != $year && $year2 != '') { ?>
                                <?php if ($result[$year][$m][$index] > $result[$year2][$m][$index]) { ?><i class="index-caret fa fa-caret-up text-success"></i><?php } ?>
                                <?php if ($result[$year][$m][$index] < $result[$year2][$m][$index]) { ?><i class="index-caret fa fa-caret-down text-danger"></i><?php } ?>
                            <?php } ?>
                            <div>
                                <span style="font-size:150%"><?= $result[$year][$m][$index] ?></span>
                                <?php if ($year2 != $year && $year2 != '') { ?>
                                / <span class="text-purple" title="<?= Yii::t('x', 'Year') ?> <?= $year2 ?>: <?= $result[$year2][$m][$index] ?>"><?= $result[$year2][$m][$index] ?></span>
                                <?php } else { ?>
                                <div class="text-muted"><?= $result[$year][$m]['total'] == 0 ? '0' : rtrim0(number_format(100 * $result[$year][$m][$index] / $result[$year][$m]['total'], 2)) ?>%</div>
                                <?php } ?>
                            </div>
                            
                        </td>
                        <?php } ?>
                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
                            <?php if ($year2 != $year && $year2 != '') { ?>
                                <?php if ($result[$year][0][$index] > $result[$year2][0][$index]) { ?><i class="index-caret fa fa-caret-up text-success"></i><?php } ?>
                                <?php if ($result[$year][0][$index] < $result[$year2][0][$index]) { ?><i class="index-caret fa fa-caret-down text-danger"></i><?php } ?>
                            <?php } ?>
                            <div>
                                <span style="font-size:150%"><?= $result[$year][0][$index] ?></span>
                                <?php if ($year2 != $year && $year2 != '') { ?>
                                / <span class="text-purple" title="<?= Yii::t('x', 'Year') ?> <?= $year2 ?>: <?= $result[$year2][0][$index] ?>"><?= $result[$year2][0][$index] ?></span>
                                <?php } else { ?>
                                <div class="text-muted"><?= $result[$year][0]['total'] == 0 ? '0' : rtrim0(number_format(100 * $result[$year][0][$index] / $result[$year][0]['total'], 2)) ?>%</div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawVisualization);

    function drawVisualization() {
        var data = google.visualization.arrayToDataTable([
            ['Tháng', 'Mới' , 'Được GT' , 'Quay lại', 'Tổng <?= $year ?>'],
            <?php
            for ($m = 1; $m <= 12; $m ++) { ?>
            ['<?= $m ?>'<?php foreach ($indexList as $index=>$item) { ?> ,<?= $result[$year][$m][$index]?><?php }?>],
            <?php } ?>
      
        ]);

        var chart2Data = google.visualization.arrayToDataTable([
            ['Tháng', 'Mới' , 'Được GT' , 'Quay lại'],
            <?php
                for ($m = 1; $m <= 12; $m ++) { ?>
            ['<?= $m ?>'<?php foreach ($indexList as $index=>$item) { if ($index != 'total') { ?> ,<?= $result[$year][$m][$index]?><?php } } ?>],
            <?php } ?>
      
        ]);
    
        var options = {
            title : '<?= $view == 'casestart' ? 'Số HSBH theo nguồn khách' :  'Số tour theo nguồn khách' ?>',
            vAxis: {title: 'Số tour'},
            hAxis: {title: 'Tháng (<?= $year ?>)'},
            seriesType: 'bars',
            series: {3: {type: 'line'}},
            colors: [<?php foreach ($indexList as $index=>$item) { echo '\'', $item['color'], '\'', ($index == 'total' ? '' : ', '); } ?>],
            // isStacked: true
        };

        var chart2Options = {
            title: 'Tỉ lệ % các nguồn khách',
            isStacked: 'percent',
            // legend: {position: 'right', maxLines: 3},
            hAxis: {title: 'Tháng (<?= $year ?>)',  titleTextStyle: {color: '#333'}},
            vAxis: {
                title: 'Phần trăm',
                minValue: 0,
                ticks: [0, .2, .4, .6, .8, 1]
            },
            colors: [<?php foreach ($indexList as $index=>$item) { echo '\'', $item['color'], '\'', ($index == 'total' ? '' : ', '); } ?>],
        };

        var chart1 = new google.visualization.ComboChart(document.getElementById('chart1'));
        var chart2 = new google.visualization.AreaChart(document.getElementById('chart2'));
        chart1.draw(data, options);
        chart2.draw(chart2Data, chart2Options);
    }
</script>
<?php
$js = <<<'TXT'
$('.toggle-referred-list').on('click', function(){
    $(this).toggleClass('fa-chevron-down fa-chevron-right')
    $('.toggle-referred-list-item').toggle()
})
TXT;

$this->registerJs($js);
  