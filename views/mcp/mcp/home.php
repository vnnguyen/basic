<?
use yii\helpers\Html;

yap('page_title', 'Master control panel');
yap('page_icon', 'home');

Yii::$app->params['page_breadcrumbs'] = [];
Yii::$app->params['page_layout'] = '-h';

?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 data-step="5" data-intro="Latest jobs added" class="panel-title">Recent jobs</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Date in</th>
                        <th>Type (Status)</th>
                        <th>Client</th>
                        <th>In charge</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($recentJobs as $job) { ?>
                    <tr>
                        <td><?= date('j M', strtotime($job['in_date'])) ?></td>
                        <td><?= Html::a($job['stype'], '@web/jobs/r/'.$job['id']) ?> (<?= substr($job['status'], 0, 1) ?>)</td>
                        <td><?= $job['client']['name'] ?></td>
                        <td><?= $job['owner']['name'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 data-step="5" data-intro="Latest jobs added" class="panel-title">Recent projects</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Project image</th>
                        <th>Name & Address</th>
                        <th>Added by</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($recentProjects as $project) { ?>
                    <? if (!$project['featureImage']) {
                        $fileName = '/assets/img/no-photo-available.jpg?v2';
                        } else {
                        $fileName = '/timthumb.php?w=200&h=150&src='.$project['featureImage']['name'];
                        }
                    ?>
                    <tr>
                        <td><?= Html::img($fileName, ['style'=>'width:80px; height:60px']) ?></td>
                        <td><?= Html::a($project['name'], '/p/r/'.$project['id']) ?>
                            <br><?= $project['addr_district'] ?>, <?= $project['addr_city'] ?></td>
                        <td><?= $project['updatedBy']['name'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Recently created accounts</h6>
        </div>
        <div class="panel-body">
            <? foreach ($recentlyCreatedAccounts as $account) { ?>
            <div><?= Html::a($account['name'], '/mcp/accounts/r/'.$account['id']) ?> (<?= strtoupper($account['subscriptions']) ?>) <?= $account['createdBy']['name'] ?>, <?= Yii::$app->formatter->asRelativetime($account['created_dt']) ?></div>
            <? } ?>
        </div>
    </div>
</div>

<?
$totalJobCnt = Yii::$app->db->createCommand('SELECT COUNT(*) FROM at_jobs')->queryScalar();
$totalPropertyCnt = Yii::$app->db->createCommand('SELECT COUNT(*) FROM at_properties')->queryScalar();
$totalProjectCnt = Yii::$app->db->createCommand('SELECT COUNT(*) FROM at_properties WHERE is_project="yes"')->queryScalar();
?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Stats</h6>
        </div>
        <div class="panel-body">
            <p><strong>Jobs by month</strong> (total <?= number_format($totalJobCnt) ?>)</p>
            <div id="chart1" style="width:100%; height:200px;"></div>
            <br>
            <p><strong>Properties records by month</strong> (total <?= number_format($totalPropertyCnt) ?>)</p>
            <div id="chart2" style="width:100%; height:200px;"></div>
            <br>
            <p><strong>Projects by month</strong> (total <?= number_format($totalProjectCnt) ?>)</p>
            <div id="chart3" style="width:100%; height:200px;"></div>
        </div>
    </div>
</div>
<?
$ch1data = Yii::$app->db->createCommand('SELECT SUBSTRING(in_date,1,7) AS ym, COUNT(*) AS t FROM at_jobs GROUP BY ym ORDER BY ym')->queryAll();
$ch2data = Yii::$app->db->createCommand('SELECT SUBSTRING(created_dt,1,7) AS ym, COUNT(*) AS t FROM at_properties GROUP BY ym ORDER BY ym')->queryAll();
$ch3data = Yii::$app->db->createCommand('SELECT SUBSTRING(created_dt,1,7) AS ym, COUNT(*) AS t FROM at_properties WHERE is_project="yes" GROUP BY ym ORDER BY ym')->queryAll();
?>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart1);
    google.setOnLoadCallback(drawChart2);
    google.setOnLoadCallback(drawChart3);
    function drawChart1() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Jobs', { role: 'annotation' }]
<? foreach ($ch1data as $d) { ?>
            , ['<?= date('M Y', strtotime($d['ym'])) ?>', <?= $d['t'] ?>, <?= $d['t'] ?>]
<? } ?>
        ]);

        var options = {
            hAxis:{textPosition: 'bottom'},
            vAxis: {title: 'Jobs'},
            chartArea:{left:100,top:20,width:"80%",height:"80%"},
            legend:{position:"left"},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart1'));
        chart.draw(data, options);
    }

    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Property records']
<? foreach ($ch2data as $d) { ?>
            , ['<?= date('M Y', strtotime($d['ym'])) ?>', <?= $d['t'] ?>]
<? } ?>
        ]);

        var options = {
            hAxis:{textPosition: 'bottom'},
            vAxis: {title: 'Records'},
            chartArea:{left:100,top:20,width:"80%",height:"80%"},
            legend:{position:"left"},
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart2'));
        chart.draw(data, options);
    }

    function drawChart3() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Projects', { role: 'annotation' }]
<? foreach ($ch3data as $d) { ?>
            , ['<?= date('M Y', strtotime($d['ym'])) ?>', <?= $d['t'] ?>, <?= $d['t'] ?>]
<? } ?>
        ]);

        var options = {
            vAxis: {title: 'Projects'},
            chartArea:{left:100,top:20,width:"80%",height:"80%"},
            legend:{position: 'left'},
            hAxis:{textPosition: 'bottom'},
            colors:['purple'],
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart3'));
        chart.draw(data, options);
    }
</script>
