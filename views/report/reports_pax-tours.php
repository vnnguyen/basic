<?
use yii\helpers\Html;
$this->title = 'Khách đi từ '.$from.' tour ('.count($thePax).')';

$this->params['icon'] = 'area-chart';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports', '@web/reports'],
];
?>
<div class="col-md-12">
	<p><?= Html::a('Đi 2 tour trở lên', '?from=2') ?> | <?= Html::a('Đi 3 tour trở lên', '?from=3') ?> | <?= Html::a('Đi 4 tour trở lên', '?from=4') ?></p>
	<div class="alert alert-info">CHÚ Ý: Có một số trường hợp 2 tour rất sát nhau (thực ra là cùng một dợt đến VN), lý do vì có sự tách/ghép trong đoàn. Có tour đã bị huỷ.</div>
	<div class="table-responsive">
		<table class="table table-condensed table-striped">
			<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>MF, CO, AGE</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Addr</th>
				<th>Tours</th>			
			</tr>
			</thead>
			<? $cnt = 0; foreach ($thePax as $pax) { $cnt ++; ?>
			<tr>
				<td class="text-muted text-center"><?= $cnt ?></td>
				<td class="text-nowrap"><?= Html::a($pax['name'], '@web/users/r/'.$pax['user_id'])?></td>
				<td class="text-nowrap"><?= $pax['gender'] ?>, <?= $pax['country_code'] ?>, <?= $pax['byear'] == 0 ? '' : date('Y') - $pax['byear'] ?></td>
				<td><?= $pax['email'] ?></td>
				<td class="text-nowrap"><?= $pax['phone'] ?></td>
				<td><?
				foreach ($paxAddrs as $paxa) {
					if ($paxa['rid'] == $pax['user_id']) echo $paxa['v'], ' &nbsp; ';
				}
				?></td>
				<td><?
				foreach ($theTours as $t) {
					if ($t['user_id'] == $pax['user_id']) {
						if ($t['finish'] == 'canceled') echo '<span style="color:#c00;">(CXL)</span> ';
						echo Html::a($t['op_code'], '@web/products/r/'.$t['id']), ' - ';
					}
				}
				?></td>
			</tr>
			<? } ?>
		</table>
	</div>
</div>