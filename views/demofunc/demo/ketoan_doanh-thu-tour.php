<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Doanh thu tour theo tháng (theo hoá đơn)';

Yii::$app->params['page_breadcrumbs'] = [
	['Kế toán', 'ketoan'],
	['Doanh thu'],
];

Yii::$app->params['page_icon'] = 'calculator';

$outputList = ['view'=>'Xem', 'download'=>'Download'];

?>
<div class="col-md-12">
	<form class="form-inline panel-search">
		Kết thúc <?= Html::textInput('month', $month, ['class'=>'form-control', 'placeholder'=>'yyyy-mm']) ?>
		Tỉ giá EUR <?= Html::textInput('eur', $eur, ['class'=>'form-control']) ?>
		Tỉ giá USD <?= Html::textInput('usd', $usd, ['class'=>'form-control']) ?>
		Xuất ra <?= Html::dropdownList('output', 'view', $outputList, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '/ketoan/doanh-thu-tour') ?>
	</form>
	<div class="panel panel-default">
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Code</th>
						<th>Tên</th>
						<th>Hãng</th>
						<th>Bắt đầu</th>
						<th>Kết thúc</th>
						<th>Ngày</th>
						<th>Pax</th>
						<th>Bán hàng</th>
						<th>KX</th>
						<th>TX</th>
						<th>Hoá đơn</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($theTours as $tour) { ?>
					<tr>
						<td><?= Html::a($tour['op_code'], '/products/op/'.$tour['id'], ['target'=>'_blank']) ?></td>
						<td><?= $tour['op_name'] ?>
						<? if ($tour['op_finish'] == 'canceled') { ?>
						<span class="text-danger">(CXL)</span>
						<? } ?>
						</td>
						<td><?
						foreach ($tour['bookings'] as $booking) {
							if ($booking['case']['company_id'] != 0) {
								echo $booking['case']['company']['name'];
							}
						}
						?></td>
						<td><?= date('j/n', strtotime($tour['day_in'])) ?></td>
						<td><?= date('j/n', strtotime($tour['day_out'])) ?></td>
						<td class="text-center"><?= $tour['days'] ?></td>
						<td class="text-center"><?= $tour['pax'] ?></td>
						<td><?
						foreach ($tour['bookings'] as $booking) {
							echo $booking['case']['owner']['nickname'];
						}
						?></td>
						<td><?
						foreach ($tour['bookings'] as $booking) {
							// Mong yeu cau
		                    $case = $booking['case'];
							// Kenh tiep can
							if ($case['stats']['kx'] != '') {
								echo $case['stats']['kx'];
							} else {
								$kx = 'k8';
								// K1
								if ($case['how_contacted'] == 'web/adwords/google') {
									$kx = 'k1';
								}

								// K2
								if ($case['how_contacted'] == 'web/adwords/bing') {
									$kx = 'k2';
								}

								// K3
								if (strpos($case['how_contacted'], 'web/search') !== false) {
									$kx = 'k3';
								}

								// K4
								if (strpos($case['how_contacted'], 'web/link') !== false || strpos($case['how_contacted'], 'web/adonline') !== false || $case['how_contacted'] == 'web') {
									$kx = 'k4';
								}
								// K5
								if ($case['how_contacted'] == 'web/direct') {
									$kx = 'k5';
								}
								// K6
								if ($case['how_contacted'] == 'web/email') {
									$kx = 'k6';
								}
								// K5
								if (strpos($case['how_contacted'], 'nweb') !== false) {
									$kx = 'k7';
								}
								echo $kx;
							}
						}
						?></td>
						<td><?
						foreach ($tour['bookings'] as $booking) {
							// Mong yeu cau
		                    $case = $booking['case'];
		                    // Loai Khach
		                    if ($case['how_found'] == 'new' || strpos($case['how_found'], 'new/nref') !== false) {
		                    	echo 'New';
							}
							if (strpos($case['how_found'], 'new/ref') !== false) {
								echo 'Referred';
							}
							if (strpos($case['how_found'], 'returning') !== false) {
								echo 'Returning';
							}
						}
						?></td>
						<?
							$cnt = 0;
							foreach ($tour['bookings'] as $booking) {
								if (empty($booking['invoices'])) {
						?>
						<td></td>
						<?
								}
								foreach ($booking['invoices'] as $invoice) {
									$cnt ++;
									if ($cnt != 1) {
						?>
					</tr>
					<tr>
						<td colspan="10"></td>
						<?
									}
									?>
						<td class="text-right"><?= Html::a(($invoice['stype'] == 'credit' ? '-' : '').number_format($invoice['amount'], 2), '/invoices/r/'.$invoice['id'], ['target'=>'_blank', 'class'=>$invoice['stype'] == 'credit' ? 'text-danger' : '']) ?> <?= $invoice['currency'] ?></td>
									<?
								}
							}
						?>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>