<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Markdown;

include('_cp_inc.php');

$this->title = $theCp['name'];
if ($theCp['venue_id'] != 0) {
	$this->title .= ' @'.Html::a($theCp['venue']['name'], '@web/venues/r/'.$theCp['venue_id']);
}

?>
<div class="col-md-4">
	<p><strong>CHI PHÍ</strong></p>
	<table class="table table-condensed table-striped table-bordered">
		<thead></thead>
		<tbody>
			<? foreach ($theCp as $k=>$v) { ?>
			<tr>
				<td><?= $k ?></td>
				<td><?
				if ($k == 'company_id') {
					if ($v == 0) {
						echo $v;
					} else {
						echo Html::a($theCp['company']['name'], '@web/companies/r/'.$v, ['rel'=>'external']);
					}
				} elseif ($k == 'venue_id') {
					if ($v == 0) {
						echo $v;
					} else {
						echo Html::a($theCp['venue']['name'], '@web/venues/r/'.$v, ['rel'=>'external']);
					}
				} else {
					echo nl2br($v);
				}
				 ?></td>				
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
<div class="col-md-4">
	<p><strong>BẢNG GIÁ</strong></p>
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<tr>
				<th>Thời hạn áp dụng</th>
				<th>Tên</th>
				<th>Giá tiền</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theCp['cpg'] as $cpg) { ?>
			<tr>
				<td><?= $cpg['from_dt'] ?> to <?= $cpg['until_dt'] ?></td>
				<td><?= Html::a($cpg['name'], '@web/cpg/u/'.$cpg['id']) ?></td>
				<td class="text-right">
					<?= number_format($cpg['price'],2) ?>
					<span class="text-muted"><?= $cpg['currency'] ?></span>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
<div class="col-md-4">
	<p><strong>CÁC CHI PHÍ LIÊN QUAN</strong></p>
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<tr>
				<th>Type</th>
				<th>Name</th>
				<th>Unit</th>
				<th>Abbr</th>
			</tr>
		</thead>
		<tbody>
			<? $currentGroup = ''; foreach ($relatedCpx as $cp) { ?>
			<? if ($currentGroup != $cp['grouping']) { $currentGroup = $cp['grouping']; ?>
			<tr><th colspan="4"><?= $currentGroup ?></th></tr>
			<? } ?>
			<tr>
				<td class="text-muted"><?= $cp['stype'] ?></td>
				<td><?= Html::a($cp['name'], '@web/cp/r/'.$cp['id']) ?></td>
				<td><?= $cp['unit'] ?></td>
				<td><?= $cp['abbr'] ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
