<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'B2B client account: '.$theAccount['name'];

$this->params['breadcrumb'] = [
	['B2B', '@web/b2b'],
	['Clients', '@web/b2b/clients'],
	['View', '@web/b2b/client/'.$theAccount['id']],
];

// Sort tours
$tourList = [];
foreach ($theAccount['cases'] as $case) {
	foreach ($case['bookings'] as $booking) {
		$tour = $booking['product'];
		$tourList[$tour['day_from']] = $tour;
	}
}
krsort($tourList);

?>
<div class="col-md-8">
	<? if ($view == 'cases') { ?>
	<p><strong>CLIENT CASES | <?= Html::a('TOURS', '@web/b2b/client/'.$theAccount['id'].'?view=tours') ?></strong></p>
	<div class="table-responsive">
		<table class="table table-responsive table-bordered">
			<thead>
				<tr>
					<th>Created</th>
					<th>Case name</th>
					<th>Status</th>
					<th>Owner</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theAccount['cases'] as $case) { ?>
				<tr>
					<td><?= date('j/n/Y', strtotime($case['created_at'])) ?></td>
					<td><?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?></td>
					<td><?= $case['deal_status'] ?> / <?= $case['status'] ?></td>
					<td><?= $case['owner']['name'] ?></td>
				</tr>
				<?
				if (!empty($case['bookings'])) {
					echo '<tr><td><td colspan="3" style="background-color:#f3f3f3">TOURS: ';
					foreach ($case['bookings'] as $booking) {
						echo Html::a($booking['product']['op_code'], '@web/products/op/'.$booking['product']['id']);
						echo ' - ', $booking['product']['pax'], 'p ', $booking['product']['day_count'], 'd ', date('j/n', strtotime($booking['product']['day_from']));
						if ($booking['product']['op_finish'] == 'canceled') {
							echo ' <small class="text-danger">(CXL)</small>';
						}
						echo ', ';
					}
					echo '</td></tr>';
				}
				?>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } else { ?>
	<p><strong><?= Html::a('CASES', '@web/b2b/client/'.$theAccount['id']) ?> | CLIENT TOURS</strong>
	<div class="table-responsive">
		<table class="table table-responsive table-bordered">
			<thead>
				<tr>
					<th>Year</th>
					<th>Mo</th>
					<th>Arrival</th>
					<th>Client code</th>
					<th>Amica code</th>
					<th>Days</th>
					<th>Pax</th>
				</tr>
			</thead>
			<tbody>
				<?
				$year = 0;
				$month = 0;
				foreach ($tourList as $date=>$tour) {
				?>
				<tr>
					<td><?= substr($date, 0, 4) ?></td>
					<td><?= substr($date, 5, 2) ?></td>
					<td><?= date('j/n/Y', strtotime($date)) ?></td>
					<td><?= Html::a($tour['client_ref'] == '' ? '(None)' : $tour['client_ref'], '@web/products/ref/'.$tour['id']) ?></td>
					<td><?= Html::a($tour['op_code'], '@web/products/op/'.$tour['id']) ?> <?= $tour['op_name'] ?></td>
					<td><?= $tour['day_count'] ?></td>
					<td><?= $tour['pax'] ?></td>
					<td><?= $tour['op_finish'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>
</div>
<div class="col-md-4">
	<? if ($theAccount['image'] != '') { ?>
	<p><strong>COMPANY LOGO</strong></p>
	<p><img src="<?= $theAccount['image'] ?>" class="img-responsive"></p>
	<? } ?>
</div>