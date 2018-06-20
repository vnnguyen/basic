<?
use yii\helpers\Html;
$this->title = 'Tours by departure date';
$this->params['breadcrumb'] = [
	['Manager', 'manager'],
	['Tours by departure date', 'manager/tours-departures'],
];

?>
<div class="col-md-12">
	<div class="alert alert-info">Sắp xếp theo ngày tour chạy chứ không phải ngày bán được tour. Tính cả các tour bị huỷ</div>
	<form method="get" action="" class="form-inline well well-sm">
		<select class="form-control" name="year">
			<option value="all">All years</option>
			<? foreach ($yearList as $li) { ?>
			<option value="<?= $li['y'] ?>" <?= $getYear == $li['y'] ? 'selected="selected"' : ''?>><?= $li['y'] ?> (<?= $li['total'] ?>)</option>
			<? } ?>
		</select>
		<button type="submit" class="btn btn-primary">Go</button>
		<?= Html::a('Reset', DIR.URI) ?>
	</form>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-condensed">
			<thead>
			<tr>
				<th>Theo người bán</th>
				<? for ($mo = 1; $mo <=12; $mo ++) { ?>
				<th width="6%" class="text-center">Th <?=$mo?></th>
				<? } ?>
				<th class="ta-c">Tổng số</th>
			</tr>
			</thead>
			<tbody>
			<?
			foreach ($sellerList as $u) {
				echo '<tr>';
				echo '<th>'.$u['ub_name'].'</th>';
				for ($i = 1; $i <=12; $i ++) {
					echo '<td class="text-center">';
					$cnt = 0;
					foreach ($tourList as $t) {
						if ($t['mo'] == $i && $t['se'] == $u['se']) $cnt ++;
					}
					if ($cnt != 0) echo $cnt;
					echo '</td>';
				}
				echo '<th class="text-center">'.$u['total'].'</th>';
				echo '</tr>';
			}
			?>
			<tr>
				<th>Tổng</th>
				<?
				for ($i = 1; $i <=12; $i ++) {
					echo '<th class="text-center text-bold">';
					$cnt = 0;
					foreach ($tourList as $t) {
						if ($t['mo'] == $i) $cnt ++;
					}
					echo $cnt;
					echo '</th>';
				}
				?>
				<th class="text-center"><?= count($tourList) ?></th>
			</tr>
			</tbody>
		</table>
	</div>
</div>
