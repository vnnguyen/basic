<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
$this->title = 'Monthly case assignments by seller';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Sales results by assignments', '@web/manager/sales-results-assignments'],
];

?>
<div class="col-md-12">
	<form class="form-inline well well-sm" method="get" action="">
		<select class="form-control" name="seller">
			<option value="0">- Select a seller -</option>
			<optgroup label="Active">
				<? foreach ($sellerList as $seller) { if ($seller['status'] == 'on') { ?>
				<option value="<?= $seller['id'] ?>" <?= $getSeller == $seller['id'] ? 'selected="selected"' : '' ?>><?= $seller['fname'] ?> <?= $seller['lname'] ?> (<?= $seller['email'] ?>)</option>
				<? } } ?>
			</optgroup>
			<optgroup label="Inactive (old)">
				<? foreach ($sellerList as $seller) { if ($seller['status'] == 'off') { ?>
				<option value="<?= $seller['id'] ?>" <?= $getSeller == $seller['id'] ? 'selected="selected"' : '' ?>><?= $seller['fname'] ?> <?= $seller['lname'] ?> (<?= $seller['email'] ?>)</option>
				<? } } ?>
			</optgroup>
		</select>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', URI) ?>
	</form>
	<p><strong>Chú ý:</strong> Các con số là số HS nhận, số HS thành công, và tỉ lệ thành công. Một số năm trước có tháng đạt tỉ lệ 100% vì khi đó chưa có thống kê tất cả HS mà chỉ có thống kê HS thành công</p>
	<div class="table-responsive">
		<!-- YEARLY -->
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="50">YM</th>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<th class="text-center" colspan="3"><?= $mo ?></th>
					<? } ?>
					<th colspan="3">Total</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($yearList as $yr) { if ($results['all'][$yr][0] != 0) { ?>
				<tr>
					<td class="text-center"><strong><?= $yr ?></strong></td>
					<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
					<? if ($results['all'][$yr][$mo] != 0) { $ym = $yr.'-'.substr('0'.$mo, -2); ?>
					<td class="text-center">
						<?= Html::a($results['all'][$yr][$mo], '@web/manager/cases?ca=assigned&month='.$ym.'&owner_id='.$getSeller, ['rel'=>'external']) ?>
					</td>
					<td class="text-center text-success">
						<?= $results['won'][$yr][$mo] ?>
					</td>
					<td class="text-center text-success">
						<?= number_format(100 * $results['won'][$yr][$mo] / $results['all'][$yr][$mo], 1) ?>%
					</td>
					<? } else { ?>
					<td></td>
					<td></td>
					<td></td>
					<? } ?>
					<? } ?>
					<? if ($results['all'][$yr][0] != 0) { ?>
					<td class="text-center"><?= $results['all'][$yr][0] ?></td>
					<td class="text-success text-center"><?= $results['won'][$yr][0] ?></td>
					<td class="text-success text-center"><?= number_format(100 * $results['won'][$yr][0] / $results['all'][$yr][0], 1) ?>%</td>
					<? } else { ?>
					<td>0</td>
					<td></td>
					<td></td>
					<? } ?>
				</tr>
				<? } } ?>
			</tbody>
		</table>
	</div>
</div>
