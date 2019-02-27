<?
use yii\helpers\Html;

$this->title = 'Số tour và khách tour các năm (không tính các tour bị huỷ)';

$this->params['icon'] = 'area-chart';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports', '@web/reports'],
];
?>
<div class="col-md-12">
	<div id="chart_01" style="min-width: 310px; height: 400px; margin:0 auto 16px;"></div>
<?
$this->registerJsFile(DIR.'assets/highcharts_4.1.5/js/highcharts.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/highcharts_4.1.5/js/modules/exporting.js', ['depends'=>'yii\web\JqueryAsset']);
$yearList = [];
$tourTotalList = [];
$paxTotalList = [];
foreach ($resultTours as $year=>$yearResult) {
	$yearList[] = $year;
	$tourTotalList[] = $resultTours[$year][0];
	$paxTotalList[] = $result[$year][0];
}
$js = <<<'TXT'

$('#chart_01').highcharts({
    chart: {
    	borderColor: '#d8d8d8',
    	borderWidth: 1,
        zoomType: 'xy'
    },
    title: {
        text: 'Số tour và khách tour các năm'
    },
    subtitle: {
        text: '(không tính các tour huỷ)'
    },
    xAxis: [{
        categories: [$yearList],
        crosshair: true
    }],
    yAxis: [{ // Primary yAxis
    	min: 0,
        labels: {
            format: '{value}',
            style: {
                color: '#8085E9'
            }
        },
        title: {
            text: 'Số tour',
            style: {
                color: '#8085E9'
            }
        }
    }, { // Secondary yAxis
    	min: 0,
        title: {
            text: 'Số khách',
            style: {
                color: '#FF7474'
            }
        },
        labels: {
            format: '{value}',
            style: {
                color: '#FF7474'
            }
        },
        opposite: true
    }],
    tooltip: {
        shared: true
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        x: 120,
        verticalAlign: 'top',
        y: 100,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
    },
    series: [{
        name: 'Số khách',
        type: 'column',
        color: '#FF7474',
        yAxis: 1,
        data: [$paxTotalList],
        tooltip: {
            valueSuffix: ' khách'
        }

    }, {
        name: 'Số tour',
        type: 'spline',
        color: '#8085E9',
        data: [$tourTotalList],
        tooltip: {
            valueSuffix: ' tour'
        }
    }]
});
TXT;

$js = str_replace([
	'$yearList',
	'$tourTotalList',
	'$paxTotalList',
	], [
	implode(',', $yearList),
	implode(',', $tourTotalList),
	implode(',', $paxTotalList),
	], $js);

$this->registerJs($js);

?>

	<p><strong style="color:#8085E9">Số tour</strong> /  <strong style="color:#FF7474">Số khách</strong>. Click để xem danh sách tour chi tiết</p>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th width="80" class="text-nowrap">Năm / Tháng</th>
					<? for ($month = 1; $month <= 12; $month ++) { ?>
					<th class="text-center"><?= $month ?></th>
					<? } ?>
					<th class="text-center">Tổng</th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($resultTours as $year=>$yearResult) { ?>
				<tr>
					<td class="text-center"><?= $year ?></td>
					<? for ($month = 1; $month <= 12; $month ++) { 
						$totalTours = isset($yearResult[$month]) ? $yearResult[$month] : 0;
						$totalPax = isset($result[$year][$month]) ? $result[$year][$month] : 0;
						?>
					<td class="text-center">
						<?= Html::a(number_format($totalTours, 0), '@web/tours?month='.date('Y-m', strtotime($year.'-'.$month)), ['rel'=>'external', 'style'=>'color:#8085E9']) ?>
						/
						<?= Html::a(number_format($totalPax, 0), '@web/tours?month='.date('Y-m', strtotime($year.'-'.$month)), ['rel'=>'external', 'style'=>'color:#FF7474']) ?>
					</td>
					<? } ?>
					<td class="text-center">
						<strong style="color:#8085E9"><?= number_format($resultTours[$year][0], 0) ?></strong>
						/
						<strong style="color:#FF7474"><?= number_format($result[$year][0], 0) ?></strong>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>

</div>