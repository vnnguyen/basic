<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Số lượng và tỉ lệ phân công điều hành tour';

Yii::$app->params['page_icon'] = 'list';

$this->params['breadcrumb'] = [
    ['Manager', '@web/manager'],
    ['Reports', '@web/manager/reports'],
];

$countries = [
    'vn'=>'Vietnam',
    'la'=>'Laos',
    'kh'=>'Cambodia',
    'mm'=>'Myanmar',
    'th'=>'Thailand',
    'id'=>'Indonesia',
    'my'=>'Malaysia',
    'cn'=>'China',
];

$opList = [];
foreach ($result as $y=>$ml) {
    foreach ($ml as $m=>$ul) {
        foreach ($ul as $u=>$n) {
            if (!in_array($u, $opList)) {
                $opList[] = $u;
            }
        }
    }
}

Yii::$app->params['body_class'] = 'sidebar-xs';
?>
<style type="text/css">
    #tbltours td {vertical-align:top!important;}
</style>
<div class="col-md-12">
<!--     <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Number of tours & pax by visiting country, all time</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div id="r3chart" style="height:400px;"></div>
                </div>
                <div class="col-md-6">
                    <div id="r4chart" style="height:400px;"></div>
                </div>
            </div>            
        </div>
    </div> -->

    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Number of tours by visiting country</h6>
        </div>
        <div class="table-responsive">
            <table id="tbltours" class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>Year \ Month</th>
                        <? for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center"><?= $m ?></th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($result as $y=>$ml) { ?>
                    <tr>
                        <th><?= $y ?></th>
                        <? for ($m = 1; $m <= 12; $m ++) { ?>
                        <td>
                            <? if (isset($result[$y][$m])) { ?>
                            <? foreach ($result[$y][$m] as $u=>$n) { ?>
                            <div class="pull-right"><?= $n ?></div>
                            <div><?= $u ?></div>
                            <? } ?>
                            <? } ?>
                        </td>
                        <? } ?>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <div id="chart1" style="height:400px;"></div>
        </div>
    </div>
    <? if (0): ?>
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Number of pax by visiting country</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>Country\Year</th>
                        <? foreach ($result as $y=>$r) { ?>
                        <th class="text-center"><?= $y ?></th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($countries as $cc=>$cn) { ?>
                    <tr>
                        <th><?= $cn ?></th>
                        <? foreach ($result as $y=>$r) { ?>
                        <td class="text-center"><?= number_format($r[$cc][1]) ?></td>
                        <? } ?>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <div id="r2chart" style="height:400px;"></div>
        </div>
    </div>
    <? endif; ?>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data1 = google.visualization.arrayToDataTable([
        ['Month', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', { role: 'annotation' } ]
        <?
        foreach ($result as $y=>$ml) { 
            if ($y == 2017) {
                for ($m = 1; $m <= 12; $m ++) {
                    if (!isset($ml[$m])) {
                        echo '0';
                    } else {
                ?>
        , ['<?= $y ?>'<? foreach ($ml[$m] as $u=>$n) { ?>, <?= $n ?><? } ?>, '']
        <?
                    }
                }
            }
        }
        ?>
    ]);

    var options1 = {
        title: 'Tours by operators',
        vAxis : {title: 'Tours'},
        hAxis : {title: 'Month'},
        width: '100%',
        height: 400,
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
    };

    var chart1 = new google.visualization.ColumnChart(document.getElementById('chart1'));
    chart1.draw(data1, options1);

}
</script>
