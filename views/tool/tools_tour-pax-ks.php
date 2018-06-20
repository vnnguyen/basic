<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

Yii::$app->params['page_title'] = 'Các pax dùng dịch vụ của: '.$theVenue['name'];
Yii::$app->params['page_icon'] = 'car';
Yii::$app->params['page_breadcrumbs'] = [
	['Dịch vụ', 'venues'],
	[$theVenue['name'], 'venues/r/'.$theVenue['id']],
	['Danh sách khách tour']
];
?>
<div class="col-md-12">
	<form class="form-inline panel-search">
		<?= Html::hiddenInput('ks', $theVenue['id']) ?>
		<?= Html::textInput('month', $month, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-default']) ?>
		<?= Html::a('Reset', DIR.URI) ?>
		|
		Hoặc xem: <?= Html::a('Xem ds tour', '/tools/tour-ks?ks='.$theVenue['id']) ?>
	</form>

	<? if (empty($results)) { ?>
	<div class="alert alert-danger">No data found.</div>
	<? } else { ?>
	<div class="panel panel-default">
		<table class="table table-bordered table-condensed" style="width:auto;">
			<thead>
				<tr>
					<th>Tour code, tên, trạng thái</th>
					<th>Tên khách</th>
					<th>Email</th>
					<th class="text-center">Quốc gia</th>
				</tr>
			</thead>
			<tbody>
				<?
				foreach ($results as $cpt) {
					$paxCount = 0;
					foreach ($cpt['tour']['product']['bookings'] as $booking) {
						$paxCount += $booking['paxcount'];
					}
				?>
				<tr>
					<td class="text-nowrap">
						<?= $cpt['tour']['product']['op_finish'] == 'canceled' ? '<span class="text-danger">(CXL)</span>' : '' ?>
						<?= Html::a($cpt['tour']['product']['op_code'], '@web/tours/r/'.$cpt['tour']['id']) ?> 
						<span class="text-muted"><?= $cpt['tour']['product']['op_name'] ?></span> 
						<?= $cpt['tour']['product']['day_count'] ?>d <?= $paxCount ?>p <?= date('j/n', strtotime($cpt['tour']['product']['day_from'])) ?>
					</td>
					<?
					$cnt = 0;
					foreach ($cpt['tour']['product']['bookings'] as $booking) {
						foreach ($booking['pax'] as $pax) {
							$cnt ++;
							if ($cnt > 1) {
					?>
				</tr>
				<tr>
					<td></td>
					<?
							}
					?>
					<td><?= $pax['name'] ?></td>
					<td><?= $pax['email'] ?></td>
					<td><?= strtoupper($pax['country_code']) ?></td>
					<?
						}
					}
					?>
					</td>
				</tr>
				<?
				}
				?>
			</tbody>
		</table>
	</div>
	<? } // if empty ?>
</div>