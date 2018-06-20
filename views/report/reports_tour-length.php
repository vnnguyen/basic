<?
use yii\helpers\Html;

$this->title = 'Tỉ trọng ngày tour';
$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports', '@web/manager/reports'],
	['Tour length', '@web/manager/reports/tour-length'],
];

for ($yr = $minYear; $yr <= $maxYear; $yr ++) {
	for ($mo = 1; $mo <= 12; $mo ++) {
		$num['month'][$yr][$mo]['total'] = 0;
		foreach ($theGroups as $group) {
			$num['month'][$yr][$mo][$group[0]] = 0;
		}
	}
}

for ($yr = $minYear; $yr <= $maxYear; $yr ++) {
	$num['year'][$yr]['total'] = 0;
	foreach ($theGroups as $group) {
		$num['year'][$yr][$group[0]] = 0;
	}
}

foreach ($theProducts as $product) {
	$y = (int)substr($product['day_from'], 0, 4);
	if ($y >= $minYear && $y <= $maxYear) {
		// Thang tour
		$m = (int)substr($product['day_from'], 5, 2);
		// So ngay tour
		$d = (int)$product['day_count'];

		$num['year'][$y]['total'] ++;
		$num['month'][$y][$m]['total'] ++;
		
		foreach ($theGroups as $group) {
			if ($d >= $group[1] && $d <= $group[2]) {
				$num['year'][$y][$group[0]] ++;
				$num['month'][$y][$m][$group[0]] ++;
			}
		}
	}
}

?>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm">
		<?= Html::textInput('grouping', $getGrouping, ['class'=>'form-control', 'placeholder'=>'1-7,8-14,15-']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>
	<p><strong>YEAR VIEW</strong></p>
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th class="text-center"></th>
				<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
				<th class="text-center"><?= $yr ?></th>
				<? } ?>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theGroups as $group) { ?>
			<tr>
				<th class="text-center"><?= $group[0] ?></th>
				<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
				<td class="text-center">
					<div style="font-size:200%; color:brown;">
						<? if ($num['year'][$yr]['total'] == 0) { ?>
						-
						<? } else { ?>
						<?= number_format(100 * $num['year'][$yr][$group[0]] / $num['year'][$yr]['total'], 2) ?> %
						<? } ?>
					</div>
					<div><?= number_format($num['year'][$yr][$group[0]], 0) ?> / <?= number_format($num['year'][$yr]['total'], 0) ?></div>
				</td>
				<? } ?>
			</tr>
			<? } ?>
		</tbody>
	</table>
	<p><strong>MONTH VIEW</strong></p>
	<ul class="nav nav-tabs mb-1em" data-tabs="tabs">
		<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
		<li class="<?= $yr == date('Y') ? 'active' : ''?>"><a data-toggle="tab" href="#year<?= $yr ?>"><?= $yr ?></a></li>
		<? } ?>
	</ul>
	<div id="tab-content" class="tab-content">
		<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
		<div id="year<?= $yr ?>" class="<?= $yr == date('Y') ? 'active' : '' ?> tab-pane">
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th class="text-center"></th>
						<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
						<th class="text-center"><?= $mo ?></th>
						<? } ?>
					</tr>
				</thead>
				<tbody>
					<? foreach ($theGroups as $group) { ?>
					<tr>
						<th class="text-center"><?= $group[0] ?></th>
						<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
						<td class="text-center">
							<div style="color:brown; font-weight:bold; font-size:120%;">
								<? if ($num['month'][$yr][$mo]['total'] == 0) { ?>
								-
								<? } else { ?>
								<?= number_format(100 * $num['month'][$yr][$mo][$group[0]] / $num['month'][$yr][$mo]['total'], 2) ?> %
								<? } ?>
							</div>
							<div><?= $num['month'][$yr][$mo][$group[0]] ?> / <?= $num['month'][$yr][$mo]['total'] ?></div>
						</td>
						<? } ?>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
		<? } ?>
	</div>
	<p>This report's code was last updated on 2014-09-24 14:48</p>
</div>