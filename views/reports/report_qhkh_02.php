<?php
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Báo cáo về HSBH hàng được giới thiệu theo tháng mở HS';

include('_report_qhkh_inc.php');

$viewList = [
    'casestart'=>'Mở HSBH',
    'tourstart'=>'Khởi hành tour',
    'tourend'=>'Kết thúc tour',
];

$indexList = [
    'pending'=>['label'=>'HS đang bán', 'color'=>'#00bcd4', 'bg'=>'info'],
    'won'=>['label'=>'HS thành công', 'color'=>'#4caf50', 'bg'=>'success'],
    'lost'=>['label'=>'HS không thành công', 'color'=>'#f44336', 'bg'=>'danger'],
    'created'=>['label'=>'HS được mở', 'color'=>'#666', 'bg'=>'white'],
];

Yii::$app->params['body_class'] = 'sidebar-xs';

function rtrim0($text) {
    return rtrim(rtrim($text, '0'), '.');
}

$refBy = [
    'referred/customer'=>'Bởi khách cũ',
    'referred/amica'=>'Bởi người Amica',
    'referred/org'=>'Bởi tổ chức liên quan',
    'referred/other'=>'Bởi nguồn khác',
    'referred/expat'=>'Bởi expat ở VN',
];
?>
<style>
.index-caret {position: absolute; margin: -6px 0 0 0}
</style>
<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-2">
        <?= Html::dropdownList('view', 'casestart', $viewList, ['class'=>'form-control', 'readonly'=>'readonly']) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::dropdownList('year2', $year2, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Comp.')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>

    <div class="card">
        <div class="card-body">
            <div id="chart1" style="height:400px;"></div>
        </div>
        <div class="tabel-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th>Chỉ số \ Tháng (<?= $year ?><?php if ($year2 != '' && $year2 != $year) { ?> vs <?= $year2 ?><?php } ?>)</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center" width="6%"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center" width="7%">Cả năm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indexList as $index=>$listItem) { $key = md5($listItem['label']); ?>
                    <tr class="<?= $listItem['bg'] ?>">
                        <th><i class="fa fa-square position-left" style="color:<?= $listItem['color'] ?>"></i><?= $listItem['label'] ?> <i data-key="<?= $key ?>" class="toggle-key fa fa-chevron-down cursor-pointer text-muted"></i></th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center">
                            <?php if ($year2 != $year && $year2 != '') { ?>
                            <?php if ($result[$year][$m][$index] > $result[$year2][$m][$index]) { ?><i class="fa fa-caret-up text-success index-caret"></i><?php } ?>
                            <?php if ($result[$year][$m][$index] < $result[$year2][$m][$index]) { ?><i class="fa fa-caret-down text-danger index-caret"></i><?php } ?>
                            <?php } ?>
                            <div>
                                <span style="font-size:150%"><?= Html::a($result[$year][$m][$index], '/cases?view=created&deal_status='.($index == 'created' ? '' : $index).'&year='.$year.'&month='.str_pad($m, 2, '0', STR_PAD_LEFT).'&how_found=referred', ['target'=>'_blank']) ?></span>
                                <?php if ($year2 != $year && $year2 != '') { ?>
                                / <span class="text-purple" title="<?= $year2 ?>"><?= $result[$year2][$m][$index] ?? '' ?></span>
                                <?php } else { ?>
                                <div class="text-muted"><?= rtrim0(number_format($result[$year][$m]['created'] == 0 ? 0 : 100 * $result[$year][$m][$index] / $result[$year][$m]['created'], 2)) ?>%</div>
                                <?php } ?>
                            </div>
                        </td>
                        <?php } ?>
                        <td class="text-center">
                            <?php if ($year2 != $year && $year2 != '') { ?>
                            <?php if ($result[$year][0][$index] > $result[$year2][0][$index]) { ?><i class="fa fa-caret-up text-success index-caret"></i><?php } ?>
                            <?php if ($result[$year][0][$index] < $result[$year2][0][$index]) { ?><i class="fa fa-caret-down text-danger index-caret"></i><?php } ?>
                            <?php } ?>
                            <div>
                                <span style="font-size:150%"><?= Html::a($result[$year][0][$index], '/cases?view=created&deal_status='.($index == 'created' ? '' : $index).'&year='.$year.'&how_found=referred', ['target'=>'_blank']) ?></span>
                                <?php if ($year2 != $year && $year2 != '') { ?>
                                <span class="text-purple" title="<?= $year2 ?>"><?= $result[$year2][0][$index] ?? '' ?></span>
                                <?php } else { ?>
                                <div class="text-muted"><?= rtrim0(number_format($result[$year][0]['created'] == 0 ? 0 : 100 * $result[$year][0][$index] / $result[$year][0]['created'], 2)) ?>%</div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php foreach ($result[$year][0]['hf'][$index] as $hf=>$num) { ?>
                    <tr class="tr-key-<?= $key ?> <?= $listItem['bg'] ?>">
                        <th> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $refBy[$hf] ?? $hf ?></th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center"><?= $result[$year][$m]['hf'][$index][$hf] ?? 0 ?></td>
                        <?php } ?>
                        <td class="text-center"><?= $result[$year][0]['hf'][$index][$hf] ?? 0 ?></td>
                    </tr>
                    <?php } ?>

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
            ['Tháng', 'Tổng', 'HS đang bán', 'Hs thành công', 'Hs không thành công'],
            <?php for ($m = 1; $m <= 12; $m ++) { $r = $result[$year][$m]; ?>
            ['<?= $m ?>', <?= $r['created'] ?>, <?= $r['pending'] ?>, <?= $r['won'] ?>, <?= $r['lost'] ?>],
            <?php } ?>
        ]);
    
        var options = {
            title : 'Báo cáo về HS được giới thiệu',
            // isStacked: 'true',
            vAxis: {title: 'Hồ sơ'},
            hAxis: {title: 'Tháng (<?= $year ?>)'},
            seriesType: 'bars',
            series: {0: {type: 'line'}},
            colors: ['#c99', '#2196f3', '#4caf50', '#f44336'],
        };
        
        var chart = new google.visualization.ComboChart(document.getElementById('chart1'));
        chart.draw(data, options);
    }
</script>
  
<?php
$js = <<<'TXT'
$('.toggle-key').on('click', function(){
    var key = $(this).data('key')
    $(this).toggleClass('fa-chevron-down fa-chevron-right')
    $('.tr-key-' + key).toggle()
})
TXT;

$this->registerJs($js);  