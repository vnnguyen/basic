<?
use yii\helpers\Html;
$this->title = 'Sales results';

if (SEG1 == 'me') {
	$this->params['breadcrumb'] = [
		['Me', 'me'],
		['Sales results', 'me/sales-results'],
	];
} else {
	$this->params['breadcrumb'] = [
		['Manager', 'manager'],
		['Sales results', 'manager/sales-results'],
	];
}


$yearList = [
	'2018'=>'2018',
	'2017'=>'2017',
	'2016'=>'2016',
	'2015'=>'2015',
	'2014'=>'2014',
	'2013'=>'2013',
	'2012'=>'2012',
	'2011'=>'2011',
	'2010'=>'2010',
	'2009'=>'2009',
	'2008'=>'2008',
	'2007'=>'2007',
];

$sourceList = [
	'all'=>'All sources',
	'direct'=>'Direct web access',
	'search'=>'Web search',
	'search-amica'=>'Web search Amica',
	'adwords'=>'Google Adwords',
	'adwords-amica'=>'- Google Adwords Amica',
	'returning'=>'Returning customers',
	'referred'=>'Referred customers',
	'b2b'=>'B2B customers',
];

$assignList = [
	'all'=>'All cases',
	'assigned'=>'Assigned cases only',
	'unassigned'=>'Unassigned cases only',
];

?>
<div class="col-md-12">
	<form class="form-inline well well-sm" method="get" action="">
		<?= Html::dropdownList('year', $getYear, $yearList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('source', $getSource, $sourceList, ['class'=>'form-control']) ?>
		<?//= Html::dropdownList('assign', $getAssign, $assignList, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', DIR.URI) ?>
		|
		<a id="view-toggle" href="#">Toggle number/percentage</a>
	</form>
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
</div>
<?
$jsCode = <<<TXT
$('a#view-toggle, table div.text-success, table div.text-warning, table div.text-danger').click(function(){
	$('.view-number, .view-percentage').toggle();
	return false;
});
TXT;
$this->registerJs($jsCode);