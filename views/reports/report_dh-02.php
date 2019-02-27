<?php
use yii\helpers\Html;

$regionList = [
    'vn-n'=>[
        'name'=>'Vietnam - North',
        'ops'=>[8162, 24820, 42901, 46803, 29212, 15018, 118, 15081],
    ],
    'vn-c'=>[
        'name'=>'Vietnam - Central',
        'ops'=>[7915],
    ],
    'vn-s'=>[
        'name'=>'Vietnam - South',
        'ops'=>[37675, 27726, 46046],
    ],
    'la'=>[
        'name'=>'Laos',
        'ops'=>[30554, 9146, 34596, 25727],
    ],
    'kh'=>[
        'name'=>'Cambodia',
        'ops'=>[31399, 19371, 1906],
    ],
];

$yesNoList = [
    'no'=>Yii::t('x', 'Exclude canceled tours'),
    'yes'=>Yii::t('x', 'Include canceled tours'),
];

Yii::$app->params['page_title'] = 'Số lượng tour phân công cho điều hành';

Yii::$app->params['page_icon'] = 'list';

$this->params['breadcrumb'] = [
    ['Reports', '@web/reports'],
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

$this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom border-0 mb-0">
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'dh-02' ? ' active' : '' ?>" href="/reports/dh-02">Số lượng tour</a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'dh-01' ? ' active' : '' ?>" href="/reports/dh-01">Số ngày tour</a></li>
</ul><?php
$this->endBlock();
?>

<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::dropdownList('inccxl', $inccxl, $yesNoList, ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <div class="card bg-white">
        <div class="card-header">
            <h6 class="card-title">
                <?= Yii::t('x', 'Number of tours allocated to operators per month in {year}', ['year'=>$year]) ?>
            </h6>
        </div>
        <div class="table-responsive">
            <table id="tbltours" class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th><?= Yii::t('x', 'Operator \ Mo.') ?></th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center" style="width:6%"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center" style="width:8%"><?= $year ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regionList as $region) { ?>
                    <tr class="alpha-primary">
                        <th colspan="14"><?= $region['name'] ?></th>
                    </tr>
                        <?php foreach ($region['ops'] as $op) { ?>
                            <?php foreach ($operators as $opK=>$opN) { ?>
                                <?php if ($opK == $op) { ?>
                    <tr>
                        <th><?= Html::a($opN, '#') ?></th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center"><?= Html::a($result[$year][$m][$opK] ?? 0, '/tours?orderby='.$view.($inccxl == 'no' ? '&status=active' : '').'&time='.$year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT).'&operator='.$opK, ['target'=>'_blank']) ?></td>
                        <?php } ?>
                        <th class="text-center"><?= Html::a($result[$year]['all'][$opK] ?? 0, '/tours?orderby='.$view.($inccxl == 'no' ? '&status=active' : '').'&time='.$year.'&operator='.$opK, ['target'=>'_blank']) ?></th>
                    </tr>

                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- div class="card-body">
            <div id="chart1" style="height:400px;"></div>
        </div -->
    </div>
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
        , ['<?= $y ?>'<?php foreach ($ml[$m] as $u=>$n) { ?>, <?= $n ?><?php } ?>, '']
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
