<?
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_products_inc.php');

$this->title = $theProduct['title'];

if ($theTour) {
	$this->title = Html::a($theTour['code'], '@web/tours/r/'.$theTour['id'], ['style'=>'background-color:#ffc; padding:0 3px; color:#148040;']). ' '.$this->title;
}

$this->params['breadcrumb'][] = ['View', '@web/products/r/'.$theProduct['id']];
$this->params['breadcrumb'][] = ['Sales & Bookings', '@web/products/sb/'.$theProduct['id']];

$dayIdList = explode(',', $theProduct['day_ids']);
if (!$dayIdList) {
	$dayIdList = [];
}
?>
<style type="text/css">
	.table.table-summary td {background-color:#f0f0f0; border:1px solid #fff;}
</style>
<div class="col-md-12">
	<ul class="nav nav-tabs mb-1em">
		<? foreach ($productViewTabs as $tab) { ?>
		<li class="<?= URI == $tab['link'] ? 'active' : '' ?>"><?= Html::a($tab['label'], DIR.$tab['link']) ?></li>
		<? } ?>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Testing menu <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="xxx">
				<li role="presentation" class="dropdown-header">PRODUCT</li>
				<li class=""><a role="menuitem" href="">Product Overview</a></li>
				<li class=""><a role="menuitem" href="">Itinerary</a></li>
				<li class=""><a role="menuitem" href="">Prices</a></li>
				<li class=""><a role="menuitem" href="">Files & Notes</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">SALES</li>
				<li><?= Html::a('Sales Overview', '@web/products/sb/'.$theProduct['id']) ?></li>
				<li><?= Html::a('Bookings', '@web/bookings?product_id='.$theProduct['id']) ?></li>
				<li class=""><a role="menuitem" href="">People</a></li>
				<li class=""><a role="menuitem" href="">Payments</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">OPERATIONS</li>
				<li><?= Html::a('Operation Overview', '@web/products/op/'.$theProduct['id']) ?></li>
				<li class=""><a role="menuitem" href="">Service costs</a></li>
				<li class=""><a role="menuitem" href="">Customers</a></li>
				<li class=""><a role="menuitem" href="">Feedback</a></li>
				<li class=""><a role="menuitem" href="">Files & Notes</a></li>
			</ul>
		</li>
	</ul>
</div>

<div class="col-md-3">
	<p><strong>CASES</strong> Click to select one</p>
	<? if ($theProduct['bookings']) { ?>
	<div class="list-group">
		<? foreach ($theProduct['bookings'] as $booking) { ?>
		<a href="<?= DIR ?>cases/r/<?= $booking['case']['id'] ?>" class="list-group-item xactive">
			<h4 class="list-group-item-heading"><i class="fa fa-briefcase"></i> <?= $booking['case']['name'] ?></h4>
			<p class="list-group-item-text"><?= $booking['case']['deal_status'] ?></p>
		</a>
		<? } ?>
	</div>
	<? } ?>
</div>
<div class="col-md-6">
	<p><strong>BOOKING DETAILS</strong></p>
	<? if (empty($theBookings)) { ?>
	<div class="alert alert-warning">
		No bookings found. <?= Html::a('Add new booking now', '@web/bookings/c?product_id='.$theProduct['id'], ['class'=>'alert-link']) ?>
	</div>
	<? } else { ?>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th width="40">ID</th>
				<th>Case name</th>
				<th>Customers</th>
				<th>Price</th>
				<th>Status</th>
				<th width="40"></th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theBookings as $booking) { ?>
			<tr>
				<td class="text-muted text-center"><?= Html::a($booking['id'], '@web/bookings/r/'.$booking['id']) ?></td>
				<td><?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?></td>
				<td><?= $booking['pax'] ?></td>
				<td class="text-right"><?= $booking['price'] ?> <?= $booking['currency'] ?></td>
				<td><?= $booking['status'] ?>, <?= $booking['updated_at'] ?></td>
				<td>
					<?= Html::a('<i class="fa fa-edit"></i>', '@web/bookings/u/'.$booking['id'], ['class'=>'text-muted']) ?>
					<?= Html::a('<i class="fa fa-trash-o"></i>', '@web/bookings/d/'.$booking['id'], ['class'=>'text-muted']) ?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
	<p><?= Html::a('+ New booking', '@web/bookings/c?product_id='.$theProduct['id']) ?></p>
	<? } ?>
	<p><strong>PAYMENTS</strong></p>
	<? if ($theProduct['bookings']) { ?>
		<? foreach ($theProduct['bookings'] as $booking) { ?>
			<? if ($booking['payments']) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>Payment date</th>
				<th>Account</th>
				<th>Amount</th>
				<th>In VND</th>
			</tr>
		</thead>
		<tbody>
		<?
		$total = 0;
		foreach ($booking['payments'] as $payment) {
		?>
			<tr>
				<td><?= Html::a(substr($payment['payment_dt'], 0, 16), 'payments/r/'.$payment['id'], ['title'=>$payment['note']]) ?></td>
				<td><?= $payment['method'] ?></td>
				<td class="text-right"><? if ($payment['currency'] != 'VND') { ?><?= number_format($payment['amount'], 2) ?> <span class="text-muted"><?= $payment['currency'] ?></span><? } ?></td>
				<td class="text-right">
					<? if ($payment['currency'] == 'VND') { $total += $payment['amount']; ?>
					<?= number_format($payment['amount'], 0) ?> <span class="text-muted">VND</span>
					<? } else { $total += $payment['amount'] * $payment['xrate']; ?>
					<?= number_format($payment['amount'] * $payment['xrate'], 0) ?> <span class="text-muted">VND</span>
					<? } ?>
				</td>
			</tr>
				<?
				}
				?>
			<tr>
				<td colspan="3">Total paid</td>
				<td class="text-right"><?= number_format($total, 0) ?> <span class="text-muted">VND</span></td>
			</tr>
		</tbody>
	</table>
			<? } ?>
		<? } ?>
	<? } ?>
</div>
<div class="col-md-3">
	<p><strong>ITINERARY</strong> <?= $theProduct['day_count'] ?> days, from <?= $theProduct['day_from'] ?></p>
	<table class="table table-condensed table-striped table-bordered">
		<tbody>
			<?
			$cnt = 0;
			foreach ($dayIdList as $id) {
				foreach ($theProduct['days'] as $day) {				
					if ($day['id'] == $id) {
						$cnt ++;
?>
			<tr>
				<td><?= $cnt ?></td>
				<td>
					<?= $day['name'] ?>
					(<?= $day['meals'] ?>)
				</td>
			</tr>
<?
					}
				}
			}
			?>
		</tbody>
	</table>
</div>
