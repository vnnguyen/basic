<?
use yii\helpers\Html;
$this->title = 'Sales results: '.$theSeller['name'];
$this->params['breadcrumb'] = [
	['Manager', 'manager'],
	['Sales results', 'manager/sales-results'],
	[$theSeller['name'], 'manager/sales-results-seller?seller='.$theSeller['id']],
];

$results = [];

for ($mo = 1; $mo <= 12; $mo ++) {
	$results[$mo]['all'] = 0;
	$results[$mo]['won'] = 0;
	$results[$mo]['lost'] = 0;
}

$sourceList = [
	'all'=>'All sources',
	'direct'=>'Direct web access',
	'search'=>'Web search',
	'adwords'=>'Google Adwords',
	'adwords-amica'=>'- Google Adwords Amica',
	'returning'=>'Returning customers',
	'referred'=>'Referred customers',
	'b2b'=>'B2B customers',
];

?>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm">
		<select class="form-control" name="seller">
			<? foreach ($sellerList as $li) { ?>
			<option value="<?= $li['id'] ?>" <?= $li['id'] == $getSeller ? 'selected="selected"' : ''?>><?= $li['lname'] ?> (<?= $li['fname'] ?> <?= $li['lname'] ?>)</option>
			<? } ?>
		</select>
		<select class="form-control" name="year">
			<? foreach ($yearList as $yr) { ?>
			<option value="<?= $yr ?>" <?= $yr == $getYear ? 'selected="selected"' : ''?>><?= $yr ?></option>
			<? } ?>
		</select>
		<?= Html::dropdownList('source', $getSource, $sourceList, ['class'=>'form-control']) ?>
		<button type="submit" class="btn btn-primary">Go</button>
	</form>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<tr>
					<th>Tháng</th>
					<th width="25%">HS nhận mới</th>
					<th width="15%">Tỉ lệ</th>
					<th width="25%">HS thành công mới</th>
					<th width="25%">HS thất bại mới</th>
				</tr>
			</thead>
			<tbody>
				<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
				<tr>
					<td class="text-center">Th. <?= $mo ?></td>
					<td>
						<ol>
						<? foreach ($assignedCases as $li) { ?>
							<? if (date('n', strtotime($li['ao'])) == $mo) {
								$results[$mo]['all'] ++;
								if ($li['deal_status'] == 'won') {
									$saleStatus = 'sale-status-won';
									$results[$mo]['won'] ++;
								} else {
									if ($li['status'] == 'closed') {
										$saleStatus = 'sale-status-lost';
										$results[$mo]['lost'] ++;
									} else {
										$saleStatus = 'sale-status-pending';
									}
								}
								?>
							<li><?= Html::a($li['name'], '@web/cases/r/'.$li['id'], ['rel'=>'external', 'class'=>$saleStatus]) ?></li>
							<? } ?>
						<? } ?>
						</ol>
					</td>
					<td class="text-center">
						<? if ($results[$mo]['all'] != 0) { ?>
						<div style="font-size:30px"><?= $results[$mo]['all'] ?></div>
						<div>nhận</div>
						<div style="font-size:30px;">
							<span class="text-success"><?= $results[$mo]['won'] ?></span>
							<? if ($results[$mo]['all'] != 0) { ?>
							<small class="text-muted">=</small>
							<span class="text-success"><?= number_format(100 * $results[$mo]['won'] / $results[$mo]['all'], 2) ?></span>
							<small class="text-muted">%</small>
							<? } ?>
						</div>
						<div>bán đc</div>
						<div style="font-size:30px;">
							<span class="text-danger"><?= $results[$mo]['lost'] ?></span>
							<? if ($results[$mo]['all'] != 0) { ?>
							<small class="text-muted">=</small>
							<span class="text-danger"><?= number_format(100 * $results[$mo]['lost'] / $results[$mo]['all'], 2) ?></span>
							<small class="text-muted">%</small>
							<? } ?>
						</div>
						<div>k bán đc</div>
						<? if ($results[$mo]['won'] + $results[$mo]['lost'] != 0) { ?>
						<div style="font-size:30px;">
							<span class="text-warning"><?= number_format(100 * $results[$mo]['won'] / ($results[$mo]['won'] + $results[$mo]['lost']), 2) ?></span>
							<small class="text-muted">%</small>
						</div>
						<div>tỉ lệ bán đc tuyệt đối</div>
						<? } ?>
						<? } ?>
					</td>
					<td>
						<ol>
							<? $idList = []; ?>
							<? foreach ($wonCases as $li) { ?>
							<? if (date('n', strtotime($li['deal_status_date'])) == $mo) { $idList[] = $li['id']; ?>
							<li><?= Html::a($li['name'], '@web/cases/r/'.$li['id'], ['rel'=>'external', 'class'=>'sale-status-won']) ?></li>
							<? } ?>
							<? } ?>
							<?
							if (MY_ID == 1) {
								echo '<p>', implode(', ', $idList), '</p>';
							}
							?>
						</ol>
					</td>
					<td>
						<ol>
							<? foreach ($lostCases as $li) { ?>
							<? if (date('n', strtotime($li['closed'])) == $mo) { ?>
							<li><?= Html::a($li['name'], '@web/cases/r/'.$li['id'], ['rel'=>'external', 'class'=>'sale-status-lost']) ?></li>
							<? } ?>
							<? } ?>
						</ol>
					</td>
				</tr>
				<? } ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<style>
a.sale-status-won {color:#090;}
a.sale-status-lost {color:#c00;}
a.sale-status-pending {color:#333;}
</style>