<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_invoice_inc.php');

Yii::$app->params['page_title'] = 'Invoices ('.number_format($pagination->totalCount, 0).')';
Yii::$app->params['page_icon'] = 'money';

$paymentList = [
	'all'=>'All',
	'paid'=>'Paid',
	'unpaid'=>'Unpaid',
];

$nhothuList = array_merge(['all'=>'Collected by'], $nhothuList);

?>
<div class="col-md-12">
	<!--p><?= date('Y-m-d', strtotime('Sunday this week', strtotime('2014-05-04'))) ?></p-->
	<form class="form-inline panel-search" method="get" action="">
	<?= Html::dropdownList('brand', $brand, ['at'=>'Amica Travel', 'si'=>'Secret Indochina'], ['class'=>'form-control', 'prompt'=>'Issued by']) ?>
	<?= Html::dropdownList('month', $getMonth, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Month']) ?>
	<?= Html::dropdownList('status', $getStatus, $statusList, ['class'=>'form-control', 'prompt'=>'Status']) ?>
	<?= Html::dropdownList('payment', $getPayment, $paymentList, ['class'=>'form-control', 'prompt'=>'Payment']) ?>
	<?= Html::dropdownList('method', $getMethod, $methodList, ['class'=>'form-control', 'prompt'=>'Method']) ?>
	<?= Html::dropdownList('currency', $getCurrency, $currencyList, ['class'=>'form-control', 'prompt'=>'Currency']) ?>
	<?= Html::dropdownList('by', $getBy, $nhothuList, ['class'=>'form-control']) ?>
	<?= Html::textInput('gateway', $getGateway, ['class'=>'form-control', 'placeholder'=>'Payment gateway']) ?>
	<?= Html::textInput('billto', $getBillTo, ['class'=>'form-control', 'placeholder'=>'Bill to name']) ?>
	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	<?= Html::a('Reset', DIR.URI) ?>
	</form>
	<? if (empty($theInvoices)) { ?>
	<div class="alert alert-danger">
		No data found.
	</div>
	<? } else { ?>
	<div class="panel panel-default">
	<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th width="30"></th>
					<th width="50">ID</th>
					<th width="50">Status</th>
					<th width="80">Due date</th>
					<th width="100">Tour / Booking</th>
					<th width="100">Seller</th>
					<th>Bill to</th>
					<th width="70">Amount</th>
					<th width="30">Pay</th>
					<th>Method</th>
					<th>Note</th>
					<th>Updated by</th>
					<th width="60"></th>
				</tr>
			</thead>
			<tbody>
<?
				$cnt = 0;
				foreach ($theInvoices as $invoice) {
					$cnt ++;
?>
				<tr>
					<td class="text-muted text-center"><?= $cnt ?></td>
					<td class="text-nowrap"><?= strtoupper($invoice['ref']) ?></td>
					<td class="text-center text-nowrap">
<?
					if ($invoice['status'] == 'draft') {
						echo '<span class="label label-default">DRAFT</span>';
					} elseif ($invoice['status'] == 'canceled') {
						echo '<span class="label" style="background-color:#333; color:#fff; text-decoration:line-through;">CANCELED</span>';
					} else {
						if ($invoice['payment_status'] == 'unpaid') {
							if (strtotime($invoice['due_dt']) < strtotime('now')) {
								echo '<span class="label label-danger">OVERDUE</span>';
							} else {
								echo '<span class="label label-warning">UNPAID</span>';
							}
						} else {
							echo '<span class="label label-success">PAID</span>';
						}
					}
?>
					</td>
					<td class="text-nowrap">
						<?= substr($invoice['due_dt'], 0, 10) ?>
					</td>
					<td class="text-nowrap">
						<?= Html::a($invoice['booking']['product']['tour']['code'], '@web/tours/r/'.$invoice['booking']['product']['tour']['id']) ?>
						(<?= Html::a('View booking', '@web/bookings/r/'.$invoice['booking']['id'], ['class'=>'text-muted']) ?>)
					</td>
					<td><?= $invoice['booking']['createdBy']['name'] ?></td>
					<td><?= $invoice['bill_to_name'] ?></td>
					<td class="text-right text-nowrap">
						<?= Html::a(number_format($invoice['amount'], 2), 'invoices/r/'.$invoice['id']) ?>
						<?= $invoice['currency'] ?>
					</td>
					<td>
						<?= $invoice['gw_currency'] ?>
					</td>
					<td>
						<? if ($invoice['nho_thu'] != '') { ?><i class="fa fa-hand-o-right text-danger" title="Nhờ thu: <?= $invoice['nho_thu'] ?>"></i><? } ?>
						<?= $methodList[$invoice['method']] ?>
						<?= $invoice['gw_name'] == '' ? '' : ' / '.$invoice['gw_name'] ?>
						<?= $invoice['link'] == '' ? '' : ' / '.Html::a('Link', $invoice['link'], ['rel'=>'external']) ?>
					</td>
					<td>
						<? if ($invoice['note'] != '') { ?>
						<i class="fa fa-file-text-o popovers text-muted"
							data-trigger="hover"
							data-title="Note"
							data-placement="left"
							data-html="true"
							data-content="<?= nl2br(Html::encode($invoice['note'])) ?>"></i>
						<? } ?>
					</td>
					<td><?= $invoice['createdBy']['name'] ?>, <?= substr($invoice['created_at'], 0, 10) ?></td>
					<td class="text-nowrap">
						<?= Html::a('<i class="fa fa-print"></i>', 'invoices/p/'.$invoice['id'], ['class'=>'text-muted', 'title'=>'Print', 'rel'=>'external']) ?>
						<?= Html::a('<i class="fa fa-edit"></i>', 'invoices/u/'.$invoice['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', 'invoices/d/'.$invoice['id'], ['class'=>'text-muted', 'title'=>'Delete']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
		</div>
	</div>

	<? if ($pagination->totalCount > $pagination->limit) { ?>
	<div class="text-center">
	<?=LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>
	<? } // if empty invoices ?>

</div>
<?
$js = <<<TXT
$('.popovers').popover();
TXT;
$this->registerJs($js);