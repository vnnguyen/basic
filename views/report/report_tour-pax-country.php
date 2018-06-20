<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = '(B2C only) Số lượng tour và khách theo các nước đến thăm';

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
?>
<div class="col-md-12">
    <div class="panel panel-white">
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
    </div>

    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Number of tours by visiting country</h6>
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
                        <td class="text-center"><?= number_format($r[$cc][0]) ?></td>
                        <? } ?>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <div id="r1chart" style="height:400px;"></div>
        </div>
    </div>
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
</div>
<?
$total = [
    'tours'=>[],
    'pax'=>[],
];

foreach ($result as $y=>$r) {
    foreach ($r as $c=>$n) {
        if (isset($total['tours'][$c])) {
            $total['tours'][$c] += $n[0];
        } else {
            $total['tours'][$c] = $n[0];
        }
        if (isset($total['pax'][$c])) {
            $total['pax'][$c] += $n[1];
        } else {
            $total['pax'][$c] = $n[1];
        }
    }
}

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var r1data = google.visualization.arrayToDataTable([
        ['Country', 'Vietnam', 'Laos', 'Cambodia', 'Myanmar', 'Thailand', 'Indonesia', 'Malaysia', 'China', { role: 'annotation' } ]
        <? foreach ($result as $y=>$r) { ?>
        , ['<?= $y ?>'<? foreach ($r as $c=>$n) { ?>, <?= $n[0]?><? } ?>, '']
        <? } ?>
    ]);

    var r1options = {
        title: 'Tours by visiting country',
        vAxis : {title: 'Tours'},
        hAxis : {title: 'Year'},
        width: '100%',
        height: 400,
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
    };

    var r1chart = new google.visualization.ColumnChart(document.getElementById('r1chart'));
    r1chart.draw(r1data, r1options);

    var r2data = google.visualization.arrayToDataTable([
        ['Country', 'Vietnam', 'Laos', 'Cambodia', 'Myanmar', 'Thailand', 'Indonesia', 'Malaysia', 'China', { role: 'annotation' } ]
        <? foreach ($result as $y=>$r) { ?>
        , ['<?= $y ?>'<? foreach ($r as $c=>$n) { ?>, <?= $n[1]?><? } ?>, '']
        <? } ?>
    ]);

    var r2options = {
        title: 'Tour pax by visiting country',
        vAxis : {title: 'Pax'},
        hAxis : {title: 'Year'},
        width: '100%',
        height: 400,
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
    };

    var r2chart = new google.visualization.ColumnChart(document.getElementById('r2chart'));
    r2chart.draw(r2data, r2options);

    var r3data = google.visualization.arrayToDataTable([
        ['Country', 'Tours']
        <? foreach ($countries as $cc=>$cn) { ?>
        , ['<?= $cn ?>', <?= $total['tours'][$cc] ?>]
        <? } ?>
    ]);

    var r3options = {
        title: 'Tours by visiting country',
        pieHole: 0.4,
    };

    var r3chart = new google.visualization.PieChart(document.getElementById('r3chart'));
    r3chart.draw(r3data, r3options);

    var r4data = google.visualization.arrayToDataTable([
        ['Country', 'Pax']
        <? foreach ($countries as $cc=>$cn) { ?>
        , ['<?= $cn ?>', <?= $total['pax'][$cc] ?>]
        <? } ?>
    ]);

    var r4options = {
        title: 'Pax by visiting country',
        pieHole: 0.4,
    };

    var r4chart = new google.visualization.PieChart(document.getElementById('r4chart'));
    r4chart.draw(r4data, r4options);
}
</script>