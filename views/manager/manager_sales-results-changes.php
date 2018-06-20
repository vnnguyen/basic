<?
use yii\helpers\Html;
$this->title = 'Kết quả bán hàng thêm';
$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Sales results', '@web/manager/sales-results-changes'],
];

?>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-4">
			<div class="well well-sm">
				<h4>HS mới được giao</h4>
				<ol>
					<? foreach ($last10Assigned as $li) { ?>
					<li><?= Html::a($li['name'], '@web/cases/r/'.$li['id'], ['rel'=>'external']) ?> <span class="text-muted"><?= $li['owner_name'] ?>, <?= $li['at'] ?></span></li>
					<? } ?>
				</ol>

			</div>
		</div>
		<div class="col-md-4">
			<div class="well well-sm">
				<h4>HS thành công mới</h4>
				<ol>
					<? foreach ($last10Won as $li) { ?>
					<li><?= Html::a($li['name'], '@web/cases/r/'.$li['id'], ['rel'=>'external', 'style'=>'color:#090']) ?> <span class="text-muted"><?= $li['owner_name'] ?>, <?= $li['at'] ?></span></li>
					<? } ?>
				</ol>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well well-sm">
				<h4>HS thất bại mới</h4>
				<ol>
					<? foreach ($last10Lost as $li) { ?>
					<li><?= Html::a($li['name'], '@web/cases/r/'.$li['id'], ['rel'=>'external', 'style'=>'color:#c00']) ?> <span class="text-muted"><?= $li['owner_name'] ?>, <?= $li['at'] ?></span></li>
					<? } ?>
				</ol>
			</div>
		</div>
	</div>

	<p>Xem bảng tổng hợp: <span class="sparkline" values="14,18,20,13,16"></span>
		<?
		foreach ($yearList as $yr) {
			$btnClass = 'default';
			if ($getYear == $yr)
				$btnClass = 'primary';
		?>
		<?= Html::a($yr, '@web/manager/sales-results-changes?year='.$yr, ['class'=>'btn btn-'.$btnClass]) ?>
		<? } ?>
	</p>
	<div class="table-responsive">
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="30"></th>
					<th>Seller \ Month</th>
					<th class="text-center" width="6%" colspan="2">30 ngày qua</th>
					<? for ($mo = 1; $mo <=12; $mo ++) { ?>
					<th class="text-center" width="6%" colspan="2">Th. <?= $mo ?></th>
					<? } ?>
					<th class="text-center" width="6%" colspan="2">Total</th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($sellerList as $li) { $cnt ++; ?>
				<tr>
					<td class="text-center text-muted"><?= $cnt ?></td>
					<td><?= Html::a($li['rname'], '@web/manager/sales-results-seller?year='.$getYear.'&seller='.$li['id'], ['rel'=>'external']) ?></td>
					<td class="text-center">
						<span class="text-success"><?= $results[$li['id']][30]['won'] == 0 ? '' : $results[$li['id']][30]['won'] ?></span>
					</td>
					<td class="text-center">
						<span class="text-danger"><?= $results[$li['id']][30]['lost'] == 0 ? '' : $results[$li['id']][30]['lost'] ?></span>
					</td>
					<? for ($mo = 1; $mo <=12; $mo ++) { ?>
					<td class="text-center">
						<span class="text-success"><?= $results[$li['id']][$mo]['won'] == 0 ? '' : $results[$li['id']][$mo]['won'] ?></span>
					</td>
					<td class="text-center">
						<span class="text-danger"><?= $results[$li['id']][$mo]['lost'] == 0 ? '' : $results[$li['id']][$mo]['lost'] ?></span>
					</td>
					<? } ?>
					<td class="text-center">
						<span class="text-success"><strong><?= $results[$li['id']][0]['won'] ?></strong></span>
					</td>
					<td class="text-center">
						<span class="text-danger"><strong><?= $results[$li['id']][0]['lost'] ?></strong></span>
					</td>
				</tr>
				<? } ?>
				<tr>
					<td class="text-center text-muted"></td>
					<td>All sellers</td>
					<td class="text-center">
						<span class="text-success"><strong><?= $results[0][30]['won'] ?></strong></span>
					</td>
					<td class="text-center">
						<span class="text-danger"><strong><?= $results[0][30]['lost'] ?></strong></span>
					</td>
					<? for ($mo = 1; $mo <=12; $mo ++) { ?>
					<td class="text-center">
						<span class="text-success"><strong><?= $results[0][$mo]['won'] ?></strong></span>
					</td>
					<td class="text-center">
						<span class="text-danger"><strong><?= $results[0][$mo]['lost'] ?></strong></span>
					</td>
					<? } ?>
					<td class="text-center">
						<span class="text-success"><strong><?= $results[0][0]['won'] ?></strong></span>
					</td>
					<td class="text-center">
						<span class="text-danger"><strong><?= $results[0][0]['lost'] ?></strong></span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<p>
		<i class="fa fa-circle text-success"></i> Số HS bán thêm được
		<i class="fa fa-circle text-danger"></i> Số HS đóng mà không bán được tour
	</p>
	<p>Chú ý:</p>
	<ul>
		<li>Thống kê theo tháng bán được / không bán được, không kể HS được mở và giao cho người bán vào tháng nào</li>
		<li>Số lượng là số HS, mặc dù đôi khi một HS bán được nhiều hơn 1 tour</li>
		<li>Tính cả các HS bán được mà sau đó bị tour huỷ trước ngày khởi hành</li>
		<li>Một hồ sơ bán được nhiều tour (vd HS tour series hãng, HS tour TCG) thì ngày thống kê là ngày bán được tour mới nhất</li>
	</ul>
</div>

<?
$js = <<<TXT
$('.sparkline').sparkline('html', {type: 'bar', barColor: '#148040'});
TXT;

$this->registerJsFile(DIR.'assets/jquery.sparkline_2.1.2/jquery.sparkline.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);
