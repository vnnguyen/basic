<?
use yii\helpers\Html;
$this->title = 'Monthly assignments by seller';
$this->params['breadcrumb'] = [
	['Manager', 'manager'],
	['Sellers assignments', 'manager/sellers-cases'],
];
?>
<div class="col-lg-12">
	<form class="well well-sm form-inline" method="get" action="">
		Xem các tháng:
		<select name="month" class="form-control">
			<? foreach ($ymx as $ym) { ?><option value="<?=$ym['ym']?>" <?=$ym['ym'] == $getMonth ? 'selected="selected"' : ''?>><?=Html::a($ym['ym'].' ('.$ym['total'].')', DIR.URI.'?month='.$ym['ym'])?></option><? } ?>
		</select>
		<button type="submit" class="btn btn-primary">Go</button>
	</form>
	<div id="chart_div" style="width:100%; height:300px;"></div>
	<br>
	<div class="table-responsive">
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th>Sellers</th>
					<? for ($i = 1; $i <= 31; $i ++) { ?>
					<th class="text-center"><?=$i?></th>
					<? } ?>
					<th class="text-center">TS</th>
				</tr>
			</thead>
			<tbody>
			<? foreach ($sellers as $p) { ?>
			<tr>
				<td><?= Html::a($p['owner_name'], '@web/manager/sales-results-seller?year='.substr($getMonth, 0, 4).'&seller='.$p['owner_id'], ['title'=>$p['owner_name']])?></td>
				<?
				$total = 0;
				$totalis_priority = 0;
				for ($i = 1; $i <= 31; $i ++) { ?>
				<td class="text-center">
				<?
				$cnt = 0;
				$cntis_priority = 0;
				foreach ($ex as $e) {
					if ($e['owner_id'] != 0 && ($e['owner_id'] == $p['owner_id']) && ($e['ao'] == $getMonth.'-'.substr('0'.$i, -2))) {
						$cnt ++;
						$total ++;
						if ($e['is_priority'] == 'yes') {
							$cntis_priority ++;
							$totalis_priority ++;
						}
					}
				}
				if ($cnt != 0) echo $cnt;//, '<br />', '<col-lg- style="color:#148040; background:#ffc;">'.$cntis_priority.'</col-lg->';
				?>
				</td>
				<? } ?>
				<th class="text-center"><?=$p['total']?>
					<!--br /><col-lg- style="color:#148040; background:#ffc;"><?=$totalis_priority?></col-lg-->
				</th>
			</tr>
			<? } ?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Người bán', 'Số HS', { role: 'annotation' }],
			<?
			$cnt = 0;
			foreach ($sellers as $p) {
				foreach ($ex as $e) {
					if ($e['owner_id'] != 0 && ($e['owner_id'] == $p['owner_id']) && ($e['ao'] == $getMonth.'-'.substr('0'.$i, -2))) {
						$cnt ++;
						$total ++;
						if ($e['is_priority'] == 'yes') {
							$cntis_priority ++;
							$totalis_priority ++;
						}
					}
				}
			?>
			['<?= $p['owner_name'] ?>', <?= $p['total'] ?>, <?= $p['total'] ?>],
			<?
			}
			?>
			['', 0, 0]
		]);

		var options = {
			title: 'Số lượng HS được giao',
			chartArea:{left:60,top:30,width:"100%",height:"60%"},
			legend:{position:"none"},
			//hAxis:{textPosition:'in'},
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
	</script>
</div>
