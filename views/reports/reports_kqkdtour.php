<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Kết quả kinh doanh các tour kết thúc năm '.$year.' ('.count($theBookings).' booking)';

$this->params['icon'] = 'area-chart';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports', '@web/reports'],
];

?>
<div class="col-lg-12">
	<p>
		<i class="fa fa-circle text-success"></i> PAID IN FULL
		<i class="fa fa-circle text-warning"></i> NO INVOICE
		<i class="fa fa-circle text-danger"></i> CANCELED
		<br>(1) Phần "Còn thu" tính theo tỉ giá EUR = <?= $eur ?>, USD = <?= $usd ?> (thay đổi bằng cách điền bên dưới đây)
		<br>(2) Phần "Đã thu" tính theo tỉ giá kế toán tại thời điểm thu
		<br>(3) Phần "Cần thu" = (1) + (2)
	</p>

	<form class="form-inline well well-sm">
		<?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
		<?= Html::dropdownList('seller', $seller, ArrayHelper::map($sellerList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'All sellers']) ?>
		<?= Html::dropdownList('code', $code, [''=>'B2B & B2C', 'F'=>'B2C only', 'G'=>'B2B only'], ['class'=>'form-control']) ?>
		<?= Html::dropdownList('orderby', $orderby, ['date'=>'Order by Start date', 'code'=>'Order by Tour code'], ['class'=>'form-control']) ?>
		<?= Html::textInput('eur', $eur, ['class'=>'form-control', 'placeholder'=>'EUR']) ?>
		<?= Html::textInput('usd', $usd, ['class'=>'form-control', 'placeholder'=>'USD']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/reports/kqkdtour') ?>
	</form>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th class="">Tháng</th>
					<? for ($mm = 1; $mm <= 12; $mm ++) { ?>
					<th class="text-center"><?= $mm ?></th>
					<? } ?>
					<th class="text-right">Tổng <?= $year ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Cần thu (VND)</td>
					<? for ($mm = 1; $mm <= 12; $mm ++) { ?>
					<td class="text-right text-nowrap"><?= number_format($result['due'][$mm], 0) ?></td>
					<? } ?>
					<td class="text-right text-nowrap"><strong><?= number_format($result['due'][0], 0) ?></strong></td>
				</tr>
				<tr>
					<td>Đã thu (VND)</td>
					<? for ($mm = 1; $mm <= 12; $mm ++) { ?>
					<td class="text-right text-nowrap"><?= number_format($result['paid'][$mm], 0) ?></td>
					<? } ?>
					<td class="text-right text-nowrap"><strong><?= number_format($result['paid'][0], 0) ?></strong></td>
				</tr>
				<tr>
					<td>Đã thu (%)</td>
					<? for ($mm = 1; $mm <= 12; $mm ++) { ?>
					<td class="text-right text-nowrap"><?= $result['due'][$mm] == 0 ? '0' : number_format(100 * $result['paid'][$mm] / $result['due'][$mm], 0) ?> %</td>
					<? } ?>
					<td class="text-right text-nowrap"><strong><?= $result['due'][0] == 0 ? '0' : number_format(100 * $result['paid'][0] / $result['due'][0], 2) ?> %</strong></td>
				</tr>
				<tr>
					<td>Còn thu (VND)</td>
					<? for ($mm = 1; $mm <= 12; $mm ++) { ?>
					<td class="text-right text-nowrap"><?= number_format($result['bal'][$mm], 0) ?></td>
					<? } ?>
					<td class="text-right text-nowrap"><strong><?= number_format($result['bal'][0], 0) ?></strong></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div role="tabpanel">

		<!-- Nav tabs -->
		<ul class="nav nav-tabs mb-1em" role="tablist">
			<? for ($mm = 1; $mm <= 12; $mm ++) { ?>
			<li role="presentation" class="<?= $mm == date('n') ? 'active' : '' ?>"><a href="#mm-<?= $mm ?>" aria-controls="home" role="tab" data-toggle="tab">Tháng <?= $mm ?></a></li>
			<? } ?>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<? for ($mm = 1; $mm <= 12; $mm ++) { ?>
			<div role="tabpanel" class="tab-pane <?= $mm == date('n') ? 'active' : '' ?>" id="mm-<?= $mm ?>">
				<div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th width="40">TT</th>
								<th width="150">Tour code - name</th>
								<th width="100">End date</th>
								<th width="100">Bán hàng</th>
								<th width="100">Giá gốc</th>
								<th width="100">Phải thu (VND)</th>
								<th width="100">Đã thu (VND)</th>
								<th width="50">=%</th>
								<th width="100">Còn thu (VND)</th>
								<th>Ghi chú</th>
							</tr>
						</thead>
						<tbody>
<?

$cnt = 0;
foreach ($theBookings as $booking) {
	if ((int)substr($booking['product']['day_until'], 5, 2) == $mm) {
		$cnt ++;
		$invoiceTotal = [];
		$bgClass = '';
		foreach ($booking['invoices'] as $invoice) {
			if (!isset($invoiceTotal[$invoice['currency']])) {
				$invoiceTotal[$invoice['currency']] = 0;
			}
			if ($invoice['stype'] == 'credit') {
				$invoice['amount'] = -$invoice['amount'];
			}
			$invoiceTotal[$invoice['currency']] += $invoice['amount'];
		}

		if ($booking['finish'] == 'canceled') {
			$bgClass = 'bg-danger';
		} else {
			if ($result['due']['bkg'.$booking['id']] == 0) {
				$bgClass = 'bg-warning';
			} else {
				if ($result['bal']['bkg'.$booking['id']] == 0) {
					$bgClass = 'bg-success';
				}
			}
		}
?>
							<tr class="">
								<td class="<?= $bgClass ?> text-muted text-center"><?= $cnt ?></td>
								<td class="text-nowrap"><?= Html::a($booking['product']['op_code'], '@web/products/op/'.$booking['product']['id']) ?> - <?= $booking['product']['op_name'] ?></td>
								<td class="text-nowrap"><?= $booking['product']['day_until'] ?></td>
								<td class="text-nowrap"><?= Html::a($booking['createdBy']['name'], '@web/reports/kqkdtour?year='.$year.'&seller='.$booking['created_by']) ?></td>
								<td class="text-right text-nowrap">
<?
foreach ($invoiceTotal as $currency=>$total) {
	echo '<div>', Html::a(number_format($total, 2), Url::to(['booking/r', 'id'=>$booking['id']])), ' ', $currency, '</div>';
}

?>
								</td>
								<td class="text-right text-nowrap"><?= number_format($result['due']['bkg'.$booking['id']]) ?> VND</td>
								<td class="text-right text-nowrap"><?= number_format($result['paid']['bkg'.$booking['id']]) ?> VND</td>
								<td class="text-right text-nowrap"><?= $result['due']['bkg'.$booking['id']] == 0 ? '0' : number_format(100 * $result['paid']['bkg'.$booking['id']] / $result['due']['bkg'.$booking['id']], 2) ?> %</td>
								<td class="text-right text-nowrap"><?= number_format($result['bal']['bkg'.$booking['id']]) ?> VND</td>
								<td><?= $booking['finish'] ?></td>
							</tr>
<?
	}
}
?>
							<tr>
								<th colspan="5"></th>
								<th class="text-right text-nowrap"><?= number_format($result['due'][$mm]) ?> VND</th>
								<th class="text-right text-nowrap"><?= number_format($result['paid'][$mm]) ?> VND</th>
								<th class="text-right text-nowrap"><?= $result['due'][$mm] == 0 ? '0' : number_format(100 * $result['paid'][$mm] / $result['due'][$mm], 2) ?> %</th>
								<th class="text-right text-nowrap"><?= number_format($result['bal'][$mm]) ?> VND</th>
								<th></th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<? } ?>
		</div>

	</div>

</div>