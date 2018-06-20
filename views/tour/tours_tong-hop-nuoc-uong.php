<?
use yii\helpers\Html;

$this->title = 'Tổng hợp nước uống trên xe, tháng '.$getMonth;
$this->params['breadcrumb'] = [
  ['Tours', 'tours'],
  [$getMonth, 'tours?month='.$getMonth],
];

Yii::$app->params['body_class'] = 'sidebar-xs';

?>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm hidden-print">
		<select class="form-control" style="width:auto" name="month">
			<? foreach ($monthList as $li) { ?>
			<option value="<?= $li['ym'] ?>" <?= $li['ym'] == $getMonth ? 'selected="selected"' : ''?>>Tháng <?= $li['ym'] ?></option>
			<? } ?>
		</select>
		<?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Tên chi phí']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', DIR.URI) ?>
	</form>
	<table class="table table-xxs table-striped table-bordered">
		<thead>
			<tr>
				<th width="20">TT</th>
				<th width="80" class="text-center">Ngày</th>
				<th width="12%">Chi phí</th>
				<th>Tour code</th>
				<th width="10%">Điều hành</th>
				<th width="100">Giá</th>
				<th width="30">SL</th>
				<th width="120">Thành $</th>
				<th>Ghi chú / guide</th>
			</tr>
		</thead>
		<tbody>
			<?
			$totalBottles = 0;
			$total = 0;
			$cnt = 0;
			$currentDay = 0;
			foreach ($theCptx as $cpt) {
				if (trim($name) == '' || strpos(strtoupper($cpt['dvtour_name']), strtoupper($name)) !== false) {
					$cnt ++;
					if ($cpt['plusminus'] == 'minus') {
						$x = -1;
						$spanClass = 'text-danger text-bold text-line';
					} else {
						$x = 1;
						$spanClass = '';
					}

					$total += $x * $cpt['qty'] * $cpt['price'];
					$totalBottles += $x * $cpt['qty'];
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
				<td><?= Html::a($cpt['dvtour_name'], '@web/cpt/r/'.$cpt['dvtour_id']) ?></td>
				<td><?= Html::a($cpt['tour']['code'].' - '.$cpt['tour']['name'], '@web/tours/r/'.$cpt['tour']['id']) ?></td>
				<td class="text-nowrap"><?
				$names = [];
				foreach ($cpt['tour']['operators'] as $operator) {
					if (in_array($operator['id'], [8162, 24820, 15081, 29212, 118, 7, 5270])) {
						$names[] = $operator['name'];
					}
				}
				echo implode(', ', $names);
				?></td>
				<td class="text-right"><span class="<?= $spanClass ?>"><?= number_format($cpt['price']) ?></span> <small class="text-muted">VND</small></td>
				<td class="text-center"><?= number_format($cpt['qty']) ?></td>
				<td class="text-right"><?= number_format($x * $cpt['qty'] * $cpt['price']) ?> <small class="text-muted">VND</small></td>
				<td><?
                $names = [];
                foreach ($cpt['tour']['product']['guides'] as $tourguide) {
                    if (strtotime($tourguide['use_from_dt']) <= strtotime($cpt['dvtour_day']) && strtotime($tourguide['use_until_dt']) >= strtotime($cpt['dvtour_day'])) {
                        $names[] =  $tourguide['guide_name'];
                    }
                }
                echo implode(', ', $names);
                ?></td>
			</tr>
			<?
				} // if name
			} // foreach cpt
			?>
			<tr>
				<td colspan="6" class="text-right">Tổng</td>
				<td class="text-right"><strong><?= number_format($totalBottles) ?></strong></td>
				<td class="text-right"><strong><?= number_format($total) ?></strong> <small class="text-muted">VND</small></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
