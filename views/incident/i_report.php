<?
use yii\helpers\Html;

Yii::$app->params['body_class'] = 'sidebar-xs';

Yii::$app->params['page_title'] = Yii::t('app', 'Incident report');
$data = '';
if ($result != null) {
	$data = json_encode($result);
}
$incidentTypeList = [
    6=>Yii::t('incident', 'Visa/Travel documents'),
    7=>Yii::t('incident', 'Payment'),
    8=>Yii::t('incident', 'Transportation'),
    9=>Yii::t('incident', 'Air travel'),
    10=>Yii::t('incident', 'Accommodation'),
    11=>Yii::t('incident', 'Meal/Restaurant'),
    12=>Yii::t('incident', 'Guide'),
    3=>Yii::t('incident', 'Security'),
    2=>Yii::t('incident', 'Health'),
    1=>Yii::t('incident', 'Service'),
    4=>Yii::t('incident', 'Internal'),
    5=>Yii::t('incident', 'Other'),
];
$data_text = json_encode($incidentTypeList);
$dt = Yii::$app->request->get('range', '');
?>
<style>
	.daterange-buttons { min-width: 15%; }
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
	        <form class="form-inline">
	            <?= Html::textInput('range', $dt, ['class'=>'form-control daterange-buttons', 'placeholder'=>Yii::t('complaint', 'range')]) ?>
	            <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
	            <?= Html::a(Yii::t('app', 'Reset'), '') ?>
	        </form>
        </div>
    </div>
    <div class="clearfix">	</div>
    <?php if ($data != ''){ ?>
	<div class="report_content">
		<div class="col-md-8">
			<div id="chart" data-source='<?=$data;?>' data-text='<?=$data_text;?>'></div>
		</div>
		<div class="col-md-4">
			<div id="piechart" style="height: 400px"></div>
		</div>
		<div class="clearfix"></div>
		<p><strong><?= Yii::t('app', 'Result');?></strong></p>
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<?php foreach ($incidentTypeList as $title): ?>
					<th class="text-center"><?= $title ?></th>
					<?php endforeach ?>
					<th class="text-center">Total</th>
				</tr>
			</thead>
			<tbody>
			<?php $total = 0;?>
				<tr>
					<?php foreach ($incidentTypeList as $key => $n): ?>
					<td class="text-center">
					<?php foreach ($result as $inci): ?>
					<?php
						if ($inci['stype'] == $key) {
							$total += $inci['cnt'];
							echo $inci['cnt'];
							break;
						}
					?>
					<?php endforeach ?>
					</td>
					<?php endforeach ?>
					<td class="text-center"><?= $total;?></td>
				</tr>
			</tbody>
		</table>
	</div>
    <?php } ?>
</div>
<?php
$js = <<<'TXT'
	var wrap_grap = document.getElementById('chart');
	var D_SOURCE = $(wrap_grap).data('source');
	var D_text = $(wrap_grap).data('text');
	var data_map = [];
	if (D_SOURCE != '') {
		data_map.push(['stype', 'Incidents']);
		$(D_SOURCE).each(function(index, item){
			var arr_item = [];
			if (D_text[item.stype] != undefined) {
				arr_item.push(D_text[item.stype]);
				arr_item.push(parseInt(item.cnt));
				data_map.push(arr_item);
			}
		});
	}
	google.charts.load('current', {'packages':['corechart', 'bar']});
  	google.charts.setOnLoadCallback(drawChart);


	function drawChart() {
		if (data_map.length == 0) {
			return false;
		}
		var data = google.visualization.arrayToDataTable(data_map);
		DATA_CHART1 = data;
        var options = {
			bars: 'vertical',
			legend: { position: 'top'},
			bar: { groupWidth: '65%' },
			chartArea:{left: 80, width:'85%',height:'65%'},
			vAxis: {format: 'decimal'},
			height: 400,
			colors: ['#1b9e77'],

        };
		var CHART1 = new google.visualization.ColumnChart(wrap_grap);
		CHART1.draw(data, options);
	}
	// Button class options
    $('.daterange-buttons').daterangepicker({
        applyClass: 'btn-success',
        cancelClass: 'btn-danger',
        locale: {
            format: 'YYYY/MM/DD'
        },
        showDropdowns: true,
        startDate: moment().subtract('days', 365 * 5),
        endDate: moment(),
        ranges: {
                'Today': [moment(), moment()],
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
    });
TXT;
$this->registerJsFile('https://www.gstatic.com/charts/loader.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/plugins/ui/moment/moment.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/plugins/pickers/daterangepicker.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs($js);
?>
