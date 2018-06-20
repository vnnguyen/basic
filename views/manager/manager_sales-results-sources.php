<?
use yii\helpers\Html;
$this->title = 'Sales results by source';

if (SEG1 == 'me') {
	$this->params['breadcrumb'] = [
		['Me', '@web/me'],
		['Sales results by source', '@web/me/sales-results-sources'],
	];
} else {
	$this->params['breadcrumb'] = [
		['Manager', '@web/manager'],
		['Sales results by source', '@web/manager/sales-results-sources'],
	];
}

$yearList = [
	'2014'=>'2014',
	'2013'=>'2013',
	'2012'=>'2012',
	'2011'=>'2011',
	'2010'=>'2010',
	'2009'=>'2009',
	'2008'=>'2008',
	'2007'=>'2007',
];

?>
<div class="col-md-12">
	<form class="form-inline well well-sm" method="get" action="">
		<?= Html::dropdownList('year', $getYear, $yearList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('source', $getSource, $sourceList, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', DIR.URI) ?>
	</form>
	<div class="table-responsive">
		<!-- YEARLY -->
		<p><strong>Chú ý:</strong> Tổng các nguồn riêng lẻ có thể lớn hơn 100% vì có những hồ sơ liệt kê được theo 2 tiêu chí khác nhau, vd Khách quay lại và search</p>
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="20"></th>
					<th>Seller \ Month</th>
					<th class="text-right">Sources</th>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<th class="text-center"><?= $mo ?></th>
					<? } ?>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($sellerList as $seller) { $cnt ++; ?>
				<tr>
					<td class="text-center text-muted"><?= $cnt ?></td>
					<td class="text-nowrap"><?= Html::a($seller['rname'], '@web/manager/sales-results-seller?year='.$getYear.'&seller='.$seller['id']) ?></td>
					<td>
						<?
						foreach ($sourceList as $k=>$v) {
							if ($getSource == 'all' || $getSource == $k) {
								echo '<div>', $v, '</div>';
							}
						}
						?>
					</td>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<td class="text-nowrap">
						<?
						if (isset($results[$seller['id']][$mo])) {
							foreach ($sourceList as $k=>$v) {
								if ($getSource == 'all' || $getSource == $k) {
						?>
						<div class="clearfix">
							<div style="float:left; width:16%; text-align:center;"><span class="label label-default" title="<?= $v ?>"><?= substr($k, 0, 1) ?></span></div>
							<div style="float:left; width:16%; text-align:right;"><?= $results[$seller['id']][$mo]['all'][$k] ?></div>
							<div style="float:left; width:16%; text-align:right; color:#060"><?= $results[$seller['id']][$mo]['won'][$k] ?></div>
							<div style="float:left; width:50%; text-align:right; color:#060">
								<? if ($results[$seller['id']][$mo]['all'][$k] != 0) { ?>
								<?= number_format(100 * $results[$seller['id']][$mo]['won'][$k] / $results[$seller['id']][$mo]['all'][$k], 1) ?>%
								<? } else { ?>
								&nbsp;
								<? } ?>
							</div>
						</div>
						<?
								} // if get source
							} // foreach source
						}
						?>
					</td>
					<? } ?>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if (1==2): ?>
	<div class="table-responsive">
		<!-- YEARLY -->
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="20"></th>
					<th>Seller \ Month</th>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<th width="6%" class="text-center">Th. <?= $mo ?></th>
					<? } ?>
					<th width="10%" class="text-right">Total</th>
				</tr>
			</thead>
			<tbody>
				<? /* if ($getAssign != 'assigned') { ?>
				<tr>
					<td class="text-muted text-center">0</td>
					<td>
						<img src="/timthumb.php?w=100&h=100&zc=1&src=http://my.amicatravel.com/upload/user-files/17436/avatar-secure-woman1.png" style="float:left; width:64px; margin-right:6px;">
						Unassigned cases
					</td>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<td class="text-right">
					</td>
					<? } // for mo ?>
					<td class="text-right">
					</td>
				</tr>
				<? } */ // if get assigned ?>
				<? $cnt = 0; foreach ($sellerList as $li) { ?>
				<tr>
					<td class="text-muted text-center"><?= ++$cnt ?></td>
					<td>
						<img src="/timthumb.php?zc=1&w=100&h=100&src=<?= $li['image'] ?>" style="float:left; width:64px; margin-right:6px;">
						<?= Html::a($li['rname'], '@web/manager/sales-results-seller?source='.$getSource.'&year='.$getYear.'&seller='.$li['id'], ['rel'=>'external']) ?>
					</td>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<td class="text-right">
						<?= $results[$li['id']][$mo]['all'] == 0 ? '-' : $results[$li['id']][$mo]['all'] ?>
						<div class="text-success">
							<span class="view-number"><?= $results[$li['id']][$mo]['won'] == 0 ? '-' : $results[$li['id']][$mo]['won'] ?></span>
							<span class="view-percentage" style="display:none"><?= $results[$li['id']][$mo]['all'] == 0 ? '' : number_format(100 * $results[$li['id']][$mo]['won'] / $results[$li['id']][$mo]['all'], 1) ?>%</span>
						</div>
						<div class="text-danger">
							<span class="view-number"><?= $results[$li['id']][$mo]['lost'] == 0 ? '-' : $results[$li['id']][$mo]['lost'] ?></span>
							<span class="view-percentage" style="display:none"><?= $results[$li['id']][$mo]['all'] == 0 ? '' : number_format(100 * $results[$li['id']][$mo]['lost'] / $results[$li['id']][$mo]['all'], 1) ?>%</span>
						</div>
						<div class="text-warning">
							<? if ($results[$li['id']][$mo]['won'] + $results[$li['id']][$mo]['lost'] == 0) { ?>
							-
							<? } else { ?>
							<?= number_format(100 * $results[$li['id']][$mo]['won'] / ($results[$li['id']][$mo]['won'] + $results[$li['id']][$mo]['lost']), 1) ?>%
							<? } ?>
						</div>
					</td>
					<? } ?>
					<td class="text-right">
						<?= $results[$li['id']][0]['all'] ?>
						<div class="text-success">
							<?= $results[$li['id']][0]['won'] ?>
							&nbsp;
							<?= $results[$li['id']][0]['all'] == 0 ? '' : number_format(100 * $results[$li['id']][0]['won'] / $results[$li['id']][0]['all'], 1) ?>%
						</div>
						<div class="text-danger">
							<?= $results[$li['id']][0]['lost'] ?>
							&nbsp;
							<?= $results[$li['id']][0]['all'] == 0 ? '' : number_format(100 * $results[$li['id']][0]['lost'] / $results[$li['id']][0]['all'], 1) ?>%
						</div>
						<div class="text-warning">
							<? if ($results[$li['id']][0]['won'] + $results[$li['id']][0]['lost'] == 0) { ?>
							-
							<? } else { ?>
							<?= number_format(100 * $results[$li['id']][0]['won'] / ($results[$li['id']][0]['won'] + $results[$li['id']][0]['lost']), 1) ?>%
							<? } ?>
						</div>
					</td>
				</tr>
				<? } ?>
				<tr>
					<td></td>
					<td>TOTAL</td>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<td class="text-right">
						<?= $results[0][$mo]['all'] == 0 ? '-' : $results[0][$mo]['all'] ?>
						<div class="text-success">
							<span class="view-number"><?= $results[0][$mo]['won'] == 0 ? '-' : $results[0][$mo]['won'] ?></span>
							<span class="view-percentage" style="display:none"><?= $results[0][$mo]['all'] == 0 ? '' : number_format(100 * $results[0][$mo]['won'] / $results[0][$mo]['all'], 1) ?>%</span>
						</div>
						<div class="text-danger">
							<span class="view-number"><?= $results[0][$mo]['lost'] == 0 ? '-' : $results[0][$mo]['lost'] ?></span>
							<span class="view-percentage" style="display:none"><?= $results[0][$mo]['all'] == 0 ? '' : number_format(100 * $results[0][$mo]['lost'] / $results[0][$mo]['all'], 1) ?>%</span>
						</div>
						<div class="text-warning">
							<? if ($results[0][$mo]['won'] + $results[0][$mo]['lost'] == 0) { ?>
							-
							<? } else { ?>
							<?= number_format(100 * $results[0][$mo]['won'] / ($results[0][$mo]['won'] + $results[0][$mo]['lost']), 1) ?>%
							<? } ?>
						</div>
					</td>
					<? } ?>
					<td class="text-right">
						<?= $results[0][0]['all'] ?>
						<div class="text-success">
							<?= $results[0][0]['won'] ?>
							&nbsp;
							<?= $results[0][0]['all'] == 0 ? '' : number_format(100 * $results[0][0]['won'] / $results[0][0]['all'], 1) ?>%
						</div>
						<div class="text-danger">
							<?= $results[0][0]['lost'] ?>
							&nbsp;
							<?= $results[0][0]['all'] == 0 ? '' : number_format(100 * $results[0][0]['lost'] / $results[0][0]['all'], 1) ?>%
						</div>
						<div class="text-warning">
							<? if ($results[0][0]['won'] + $results[0][0]['lost'] == 0) { ?>
							-
							<? } else { ?>
							<?= number_format(100 * $results[0][0]['won'] / ($results[0][0]['won'] + $results[0][0]['lost']), 1) ?>%
							<? } ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<ul class="list-unstyled">
		<li><i class="fa fa-circle"></i> Số HS nhận trong tháng</li>
		<li><i class="fa fa-circle text-success"></i> Số HS thành công trong số nhận & Tỉ lệ</li>
		<li><i class="fa fa-circle text-danger"></i> Số HS không thành công trong số nhận & Tỉ lệ</li>
		<li><i class="fa fa-circle text-warning"></i> Tỉ lệ thành công thực (số HS thành công / (số HS thành công + số HS không thành công))</li>
	</ul>
	<p>Chú ý:</p>
	<ul>
		<li>Click các con số để đổi giữa xem con số và phần trăm</li>
		<li>Click tên người bán hàng để xem chi tiết theo người bán</li>
	</ul>
	<? endif; ?>
</div>
