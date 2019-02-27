<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Thư cảm ơn và quà cho khách Club Ami Amica';

include('_report_qhkh_inc.php');

$indexList[0] = [
    'sent'=>['label'=>'Số thư cảm ơn', 'color'=>'#2196f3'],
    'replied'=>['label'=>'Số thư trả lời', 'color'=>'#4caf50'],
    'pct'=>['label'=>'Tỉ lệ hồi âm (%)', 'color'=>'#00bcd4'],
    'avg'=>['label'=>'Số ngày chờ trung bình', 'color'=>'#f44336'],
];

$indexList[1] = [
    ''=>['label'=>'Non', 'color'=>'#2196f3'],
    'ch'=>['label'=>'CH (stock épuisé)', 'color'=>'#219293'],
    'dh'=>['label'=>'Projet humantitaire', 'color'=>'#4caf50'],
    'dt'=>['label'=>'Don Tourisme Responsable', 'color'=>'#4ca980'],
    'ba'=>['label'=>'BA Maison Vietnam', 'color'=>'#6ca660'],
    'le'=>['label'=>'Livre Ethnies', 'color'=>'#444f50'],
    'ca'=>['label'=>'Cheque cadeau Amazon', 'color'=>'#082cd4'],
    'no'=>['label'=>'Note', 'color'=>'#f44336'],
    'li'=>['label'=>'Livre C. Vérot', 'color'=>'#c95618'],
    'dv'=>['label'=>'DVD Vietnam C. Vérot', 'color'=>'#c91118'],
];

Yii::$app->params['body_class'] = 'sidebar-xs';
?>

<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-2">
        Xem báo cáo năm
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '') ?>
    </form>

    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><i class="fa fa-arrow-circle-o-right"></i> Tỉ lệ hồi âm thư cảm ơn / hỏi quà (tính theo ngày gửi thư ban đầu)</h6>
        </div>
        <div class="card-body">
            <div id="chart1" style="height:400px;"></div>
        </div>
        <div class="table-responsive">
            <table id="tbl01" class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th>Chỉ số \ Tháng (<?= $year ?>)</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center" width="6%"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center" width="6%"><?= $year ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indexList[0] as $index=>$item) { ?>
                    <tr>
                        <th>
                            <i class="fa fa-square" style="color:<?= $item['color'] ?>"></i>
                            <?= $item['label'] ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center">
                            <?php if ($result[$year][$m][$index] == 0) { ?>
                                <? if ($index != 'pct') { ?>
                            <span class="text-muted">0</span>
                                <?php } ?>
                            <?php } else { ?>
                            <?= number_format($result[$year][$m][$index]) ?><?= $index == 'pct' ? '%' : '' ?>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <th class="text-center alpha-info">
                            <?= number_format($result[$year][0][$index]) ?><?= $index == 'pct' ? '%' : '' ?>
                        </th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><i class="fa fa-arrow-circle-o-right"></i> Số lượng quà đã gửi cho khách (tính theo ngày gửi)</h6>
        </div>
        <div class="card-body">
            <div id="chart2" style="height:400px;"></div>
        </div>
        <div class="table-responsive">
            <table id="tbl02" class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th>Chỉ số \ Tháng (<?= $year ?>)</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center" width="6%"><?= Html::a($m, '/referrals?giftsent='.$year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT)) ?></th>
                        <?php } ?>
                        <th class="text-center alpha-info" width="6%"><?= $year ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indexList[1] as $index=>$item) { ?>
                    <tr>
                        <th>
                            <i class="fa fa-square" style="color:<?= $item['color'] ?>"></i>
                            <?= $item['label'] ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center"><?= Html::a($result[$year][$m]['gift'][$index] ?? '<span class="text-muted">0</span>', '/referrals?gift='.$index.'&giftsent='.$year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT)) ?></td>
                        <?php } ?>
                        <th class="text-center alpha-info"><?= Html::a($result[$year][0]['gift'][$index] ?? '<span class="text-muted">0</span>', '/referrals?gift='.$index.'&giftsent='.$year) ?></th>
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
            ['Tháng', 'Số thư cảm ơn', 'Số thư trả lời', 'Số ngày chờ trung bình'],
            <?php for ($m = 1; $m <= 12; $m ++) { ?>
            ['<?= $m ?>', <?= $result[$year][$m]['sent'] ?>, <?= $result[$year][$m]['replied'] ?>, <?= number_format($result[$year][$m]['avg'], 1) ?>]<?php if ($m < 12) { ?>,<?php } ?>
            <? } ?>
      
        ]);
    
        var options = {
            title : 'Tỉ lệ hồi âm thư cảm ơn',
            seriesType: 'bars',
            series: {
                0: {targetAxisIndex: 0},
                1: {targetAxisIndex: 0},
                2: {targetAxisIndex: 1, type: 'line'}
            },
            vAxes: {
                0: {title: 'Số thư', format: '#'},
                1: {title: 'Số ngày'},
            },
            hAxis: {title: 'Tháng (<?= $year ?>)'},
            colors: [<?php foreach ($indexList[0] as $index=>$item) { if ($index != 'pct') { echo '\'', $item['color'], '\'', ($index == 'total' ? '' : ', '); } } ?>],
            // isStacked: true
        };
        
        var chart = new google.visualization.ComboChart(document.getElementById('chart1'));
        chart.draw(data, options);

        var data = google.visualization.arrayToDataTable([
            ['Tháng', <?php foreach ($indexList[1] as $index=>$item) { if ($index != 'avg') { echo '\'', $item['label'], '\', ';} } ?>],
            <?php
            // foreach ($result as $y=>$r) {
                for ($m = 1; $m <= 12; $m ++) { ?>
            ['<?= $m ?>' <? foreach ($indexList[1] as $index=>$item) { ?>, <?= $result[$year][$m]['gift'][$index] ?? 0 ?><? }?>],
            <? } ?>
      
        ]);
    
        var options = {
            title : 'Lựa chọn quà',
            vAxis: {title: 'Quà', format: '#'},
            hAxis: {title: 'Tháng (<?= $year ?>)'},
            seriesType: 'bars',
            // series: {3: {type: 'line'}},
            colors: [<?php foreach ($indexList[1] as $index=>$item) { echo '\'', $item['color'], '\'', ($index == 'total' ? '' : ', '); } ?>],
            isStacked: true
        };
        
        var chart = new google.visualization.ComboChart(document.getElementById('chart2'));
        chart.draw(data, options);
    }
</script>