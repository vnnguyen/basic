<?
use yii\helpers\Html;

$this->title = 'Lịch xem rối nước tháng '.date('n/Y', strtotime($month));
$this->params['breadcrumb'] = [
  ['Tours', 'tours'],
  [$month, 'tours?month='.$month],
];
?>
<style type="text/css">
.text-bold {font-weight:bold;}
.text-line {text-decoration:line-through;}
</style>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm hidden-print">
		<select class="form-control" style="width:auto" name="month">
			<? foreach ($monthList as $li) { ?>
			<option value="<?= $li['ym'] ?>" <?= $li['ym'] == $month ? 'selected="selected"' : ''?>>Tháng <?= $li['ym'] ?></option>
			<? } ?>
		</select>
		<button type="submit" class="btn btn-primary">Go</button>
	</form>
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<tr>
				<th width="20">TT</th>
				<th width="80" class="text-center">Ngày</th>
				<th width="12%">Giờ xem</th>
				<th>Tour code</th>
				<th width="10%">Điều hành</th>
				<th width="30">+/-</th>
				<th width="100">Loại vé</th>
				<th width="30">SL</th>
				<th width="120">Thành $</th>
				<th>Ghi chú / guide</th>
			</tr>
		</thead>
		<tbody>
			<?
			$total = 0;
			$cnt = 0;
			$currentDay = 0;
			foreach ($theCptx as $cpt) {
				$cnt ++;
				if ($cpt['plusminus'] == 'minus') {
					$x = -1;
					$spanClass = 'text-danger text-bold text-line';
				} else {
					$x = 1;
					$spanClass = '';
				}

				$total += $x * $cpt['qty'] * $cpt['price'];
			?>
			<tr>
				<td class="text-center text-muted"><?= $cnt ?></td>
				<td class="text-center text-nowrap">
					<?
					if ($cpt['dvtour_day'] != $currentDay) {
						$currentDay = $cpt['dvtour_day'];
						echo date('d-m-Y', strtotime($currentDay));
					} else {
						// echo '-';
						echo date('d-m-Y', strtotime($currentDay));
					}
					?>
				</td>
				<td class="text-center"><?= Html::a($cpt['dvtour_name'], '@web/cpt/r/'.$cpt['dvtour_id']) ?></td>
				<td><?= Html::a($cpt['tour']['code'].' - '.$cpt['tour']['name'], '@web/cpt/r/'.$cpt['tour']['id']) ?></td>
				<td class="text-nowrap"><?
				$names = [];
				foreach ($tourOperators as $operator) {
					if ($operator['tour_id'] == $cpt['tour']['id']) {
						$names[] = $operator['name'];
					}
				}
				echo implode(', ', $names);
				?></td>
				<td class="text-right"><?= $cpt['plusminus'] == 'plus' ? '' : '&mdash;' ?></td>
				<td class="text-right"><span class="<?= $spanClass ?>"><?= number_format($cpt['price']) ?></span> <small class="text-muted">VND</small></td>
				<td class="text-center"><?= number_format($cpt['qty']) ?></td>
				<td class="text-right"><?= number_format($x * $cpt['qty'] * $cpt['price']) ?> <small class="text-muted">VND</small></td>
				<td><?
				foreach ($cpt['tour']['guides'] as $tourguide) {
					echo $tourguide['name'], ' - ', $tourguide['phone'], ' &nbsp; ';
				}
				?></td>
			</tr>
			<? } ?>
			<tr>
				<td colspan="8" class="text-right">Tổng tiền</td>
				<td class="text-right"><strong><?= number_format($total) ?></strong> <small class="text-muted">VND</small></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
