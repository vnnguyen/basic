<?
use yii\helpers\Html;
$this->title = 'Manager dashboard';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
];

$totalPaymentInVnd = 0;
foreach ($monthPayments as $payment) {
	$totalPaymentInVnd += $payment['xrate'] * $payment['amount'];
}

$this->params['icon'] = 'area-chart';

?>
<div class="col-lg-12">
	<p><strong>TRONG THÁNG NÀY</strong></p>
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="list-group">
				<a href="<? DIR ?>manager/cases?month=<?= date('Y-m') ?>" class="list-group-item list-group-item-warning">
					<i class="fa fa-briefcase fa-3x pull-right text-warning"></i>
					<h3 class="list-group-item-heading">+ <?= $monthCaseCount ?></h3>
					<p class="list-group-item-text">Hồ sơ bán hàng</p>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="list-group">
				<a href="<? DIR ?>manager/sales-results-changes" class="list-group-item list-group-item-success">
					<i class="fa fa-smile-o fa-3x pull-right text-success"></i>
					<h3 class="list-group-item-heading">+ <?= $monthNewTourCount ?></h3>
					<p class="list-group-item-text">Hồ sơ thành công</p>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="list-group">
				<a href="<?= DIR ?>payments?limit=500&month=<?= date('Y-m') ?>" class="list-group-item list-group-item-info">
					<i class="fa fa-euro fa-3x pull-right text-info"></i>
					<h3 class="list-group-item-heading">+ <?= number_format($totalPaymentInVnd) ?> VND</h3>
					<p class="list-group-item-text">Tiền tour khách thanh toán</p>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="list-group">
				<a href="<?= DIR ?>manager/tours-departures" class="list-group-item list-group-item-danger">
					<i class="fa fa-flag fa-3x pull-right text-danger"></i>
					<h3 class="list-group-item-heading">+ <?= $monthTourCount ?></h3>
					<p class="list-group-item-text">Tour khởi hành</p>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-6">
			<p><strong>KẾT QUẢ BÁN HÀNG (SỐ HS BÁN THÊM) TRONG THÁNG</strong></p>
			<div id="piechart" style="width:100%; height: 500px;"></div>
		</div>
		<div class="col-md-6">
			<p><strong>SỐ HS BÁN THÊM THEO THÁNG</strong></p>
			<div id="chart2" style="width:100%; height:200px;"></div>
			<br>
			<p><strong>SỐ TOUR KHỞI HÀNH THEO THÁNG</strong></p>
			<div id="chart3" style="width:100%; height:200px;"></div>
			<br>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart1);
	google.setOnLoadCallback(drawChart2);
	google.setOnLoadCallback(drawChart3);
	function drawChart1() {
		var data = google.visualization.arrayToDataTable([
		['Seller', 'Won'],
		<? $cnt = 0; foreach ($wonCasesBySeller as $li) { $cnt ++; ?>
		['<?= $li['name']?>', <?= $li['total'] ?>]<?= $cnt != count($wonCasesBySeller) ? ',' : '' ?>
		<? } ?>
		]);

		var options = {
			pieHole: 0.4,
			chartArea:{left:0,top:0,width:"100%",height:"100%"},
			legend:{position:"left"},
		};

		var chart = new google.visualization.PieChart(document.getElementById('piechart'));
		chart.draw(data, options);
	}

	function drawChart2() {
		var data = google.visualization.arrayToDataTable([
			['Month', 'Won cases'],
			<?
			$t24 = strtotime('-24 month');
			$t1 = strtotime('+1 month');
			$cnt = 0;
			foreach ($last12moWonCases as $li) {
				$t = strtotime($li['ym'].'-01');
				if ($t24 < $t && $t1 > $t) {
					$cnt ++;
					if ($cnt != 1) echo ', ';
			?>
			['<?= $li['ym'] ?>', <?= $li['total'] ?>]
			<?
				}
			}
			?>
		]);

		var options = {
			// hAxis: {title: 'Tháng'},
			chartArea:{left:0,top:0,width:"100%",height:"100%"},
			legend:{position: 'none'},
			hAxis:{textPosition: 'none'}
		};

		var chart = new google.visualization.LineChart(document.getElementById('chart2'));
		chart.draw(data, options);
	}

	function drawChart3() {
		var data = google.visualization.arrayToDataTable([
			['Month', 'Tours'],
			<?
			$t24 = strtotime('-24 month');
			$t1 = strtotime('+1 month');
			$cnt = 0;
			foreach ($last12moTours as $li) {
				$t = strtotime($li['ym'].'-01');
				if ($t24 < $t && $t1 > $t) {
					$cnt ++;
					if ($cnt != 1) echo ', ';
			?>
			['<?= $li['ym'] ?>', <?= $li['total'] ?>]
			<?
				}
			}
			?>
		]);

		var options = {
			// hAxis: {title: 'Tháng'},
			chartArea:{left:0,top:0,width:"100%",height:"80%"},
			legend:{position: 'none'},
			hAxis:{textPosition: 'none'},
			colors:['purple'],
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('chart3'));
		chart.draw(data, options);
	}
</script>