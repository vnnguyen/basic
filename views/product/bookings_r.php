<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_products_inc.php');

$this->params['breadcrumb'][] = ['View', '@web/bookings/r/'.$theBooking['id']];

?>
<div class="col-md-12">
	<ul class="nav nav-tabs mb-1em">
		<li class=""><a href="/products/r/<?= $theProduct['id'] ?>">Product overview</a></li>
		<li class="active"><a href="/products/sb/<?= $theProduct['id'] ?>">Sales &amp; Bookings</a></li>
		<li class=""><a href="/products/op/<?= $theProduct['id'] ?>">Operation</a></li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Testing menu <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="xxx">
				<li role="presentation" class="dropdown-header">PRODUCT</li>
				<li class=""><a role="menuitem" href="">Product Overview</a></li>
				<li class=""><a role="menuitem" href="">Itinerary</a></li>
				<li class=""><a role="menuitem" href="">Prices</a></li>
				<li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">SALES</li>
				<li><a href="/products/sb/<?= $theProduct['id'] ?>">Sales Overview</a></li>
				<li><a href="/bookings?product_id=<?= $theProduct['id'] ?>">Bookings</a></li>
				<li class=""><a role="menuitem" href="">People</a></li>
				<li class=""><a role="menuitem" href="">Payments</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">OPERATIONS</li>
				<li><a href="/products/op/30357">Operation Overview</a></li>
				<li class=""><a role="menuitem" href="">Service costs</a></li>
				<li class=""><a role="menuitem" href="">Customers</a></li>
				<li class=""><a role="menuitem" href="">Feedback</a></li>
				<li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
			</ul>
		</li>
	</ul>
</div>
<div class="col-md-3">
	<p><strong>THE CASE</strong></p>
	<p>
		<i class="fa fa-briefcase"></i>
		<?= Html::a($theBooking['case']['name'], '@web/cases/r/'.$theBooking['case']['id']) ?>
		by <?= Html::a($theBooking['case']['owner']['name'], '@web/users/r/'.$theBooking['case']['owner']['id']) ?>
	</p>
</div>
<div class="col-md-6">
	<p><strong>THE BOOKING</strong></p>
	<? if ($theBooking['finish'] == 'canceled') { ?>
	<div class="alert alert-danger">
		<i class="fa fa-fw fa-info-circle"></i>
		This booking has been canceled at <?= $theBooking['finish_dt'] ?> (UTC)
	</div>
	<? } ?>
	<table class="table table-condensed table-bordered">
		<tbody>
			<tr>
				<td width="20%">Booking ID</td>
				<td width="30%"><?= $theBooking['id'] ?></td>
				<td width="20%">Status</td>
				<td><?= strtoupper($theBooking['status']) ?></td>
			</tr>
			<tr>
				<td>Updated:</td><td colspan="3"><?= $theBooking['updatedBy']['name'] ?>, <?= $theBooking['updated_at'] ?></td>
			</tr>
			<tr>
				<td>Pax:</td><td><?= $theBooking['pax'] ?></td>
				<td>Price:</td><td><?= number_format($theBooking['price']) ?> <?= $theBooking['currency'] ?></td>
			</tr>
			<tr>
				<td>Start date:</td><td colspan="3"><?= $theBooking['product']['day_from'] ?></td>
			</tr>
			<tr>
				<td>Note:</td><td colspan="3"><?= nl2br($theBooking['note']) ?></td>
			</tr>
		</tbody>
	</table>

	<?= Html::a('+ Invoice', '@web/invoices/c?booking_id='.$theBooking['id'], ['class'=>'pull-right']) ?>
	<p><strong>INVOICES</strong></p>

	<? if ($theBooking['invoices']) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th width="50">Status</th>
				<th width="70">Due date</th>
				<th width="70">Account</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
		<?
		$total = 0;
		foreach ($theBooking['invoices'] as $invoice) {
			$invoice['xrate'] = 1;
		?>
			<tr>
				<td class="text-nowrap">
					<? if ($invoice['status'] == 'paid') { ?>
					<span class="label label-success">PAID</span>
					<? } else { ?>
						<? if (strtotime($invoice['due_dt']) < strtotime('now')) { ?>
						<span class="label label-danger">OVERDUE</span>
						<? } else { ?>
						<span class="label label-default">NOT PAID</span>
						<? } ?>
					<? } ?>
				</td>
				<td class="text-nowrap">
					<?= substr($invoice['due_dt'], 0, 10) ?>
				</td>
				<td class="text-nowrap">
					<?= $invoice['method'] ?>
					<? if ($invoice['link'] != '') { ?>
					- <?= Html::a('Payment link', $invoice['link'], ['rel'=>'external']) ?>
					<? } ?>
				</td>
				<td class="text-right">
					<?= Html::a(number_format($invoice['amount'], 2), '@web/invoices/r/'.$invoice['id']) ?>
					<small class="text-muted"><?= $invoice['currency'] ?></small>
					<?= Html::a('<i class="fa fa-edit"></i>', '@web/invoices/u/'.$invoice['id']) ?>
				</td>
			</tr>
				<?
				}
				?>
		</tbody>
	</table>
	<? } else { ?>
	<p>No invoices found.</p>
	<? } ?>

	<?= Html::a('+ Payment', '@web/payments/c?booking_id='.$theBooking['id'], ['class'=>'pull-right', 'id'=>'a-payments-c']) ?>
	<p><strong>PAYMENTS</strong></p>

	<div id="div-payments-c" style="display:none; padding:8px; border:1px solid #ccc; background-color:#f6f6f6; margin-bottom:16px;">
		<p><strong>NEW PAYMENT</strong></p>
		<? $form = ActiveForm::begin(); ?>
		<div class="row">
			<div class="col-md-6"><?= $form->field($thePayment, 'payment_dt') ?></div>
			<div class="col-md-6"><?= $form->field($thePayment, 'ref') ?></div>
		</div>
		<div class="row">
			<div class="col-md-6"><?= $form->field($thePayment, 'payer') ?></div>
			<div class="col-md-6"><?= $form->field($thePayment, 'payee') ?></div>
		</div>
		<div class="row">
			<div class="col-md-4"><?= $form->field($thePayment, 'method') ?></div>
			<div class="col-md-3"><?= $form->field($thePayment, 'amount') ?></div>
			<div class="col-md-2"><?= $form->field($thePayment, 'currency')->dropdownList(['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND'], ['prompt'=>'- Select -']) ?></div>
			<div class="col-md-3"><?= $form->field($thePayment, 'xrate') ?></div>
		</div>
		<?= $form->field($thePayment, 'note')->textArea(['rows'=>5]) ?>
		<?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?> or <?= Html::a('Cancel', '#', ['class'=>'a-payments-c-cancel']) ?>
		<? ActiveForm::end(); ?>
	</div>

	<? if ($theBooking['payments']) { ?>
	<table class="table table-bordered table-condensed">
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
		foreach ($theBooking['payments'] as $payment) {
		?>
			<tr>
				<td><?= Html::a(substr($payment['payment_dt'], 0, 16), '@web/payments/r/'.$payment['id'], ['title'=>$payment['note']]) ?></td>
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
	<? } else { ?>
	<p>No payments found.</p>
	<? } ?>
	<hr>

	<?= Html::a('+ New pax', '#', ['class'=>'pull-right', 'id'=>'a-user-c']) ?>
	<p><strong>PEOPLE</strong></p>
	<?
	$searchUsers = Yii::$app->session->get('searchUsers', []);
	if (!empty($searchUsers)) {
		echo '<div class="alert alert-info"><strong>The following users were found with same name / email</strong>';
		foreach ($searchUsers as $user) {
			echo '<br>ID: ', Html::a('@web/users/r/'.$user['id'], $user['id']), ' | Name: ', $user['fname'], ' / ', $user['fname'], ' (', $user['name'], ')';
		}
		echo '</div>';
	}
	?>
	<div id="div-user-c" style="display:none;">
		<form class="form-inline mb-1em" method="post" action="">
			<input type="hidden" name="action" value="add-pax">
			<input type="text" class="form-control" style="width:60%" name="name" value="" autocomplete="off" placeholder="Enter user ID, email, or full name">
			<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		</form>
		<div class="alert alert-info">
			- Để thêm pax, hãy nhập ID, email hoặc tên đầy đủ. Tên đầy đủ phải có dạng <kbd>First Last</kbd> (có dấu cách ở giữa, dài ít nhất 7 ký tự)<br>
			- Nếu tìm được một người có ID, email, hoặc tên như vậy đã tồn tại: sẽ thêm luôn người đó vào booking<br>
			- Nếu tìm thấy nhiều người có cùng email, tên: sẽ báo lỗi và yêu cầu điền bằng ID<br>
			- Nếu nhập tên và không có người nào tồn tại với tên như vậy: sẽ tạo người mới và thêm luôn vào booking<br>
			- Trường hợp muốn nhập một người mới nhưng có tên trùng với người đã có trong CSDL: <a href="/users/c">vào trang này để thêm</a> và quay lại đây nhập ID
		</div>
	</div>
	<?
	$cnt = 0;
	foreach ($thePeople as $person) {
		$cnt ++;
	?>
	<div style="background-color:#f6f6f6; padding:4px; margin-bottom:4px;" class="clearfix">
		<?= Html::img('https://secure.gravatar.com/avatar/'.md5($person['email']).'?s=40&d=wavatar', ['style'=>'float:left; margin-right:8px;']) ?>
		<i class="fa fa-<?= $person['gender'] ?>"></i>
		<img src="<?= DIR ?>assets/img/flags/16x11/<?= $person['country_code'] ?>.png" alt="<?= $person['country_code'] ?>">
		<?= Html::a($person['name'], '@web/users/r/'.$person['id']) ?>
		<? if ($person['byear'] != '0000') { ?>
		<em><?= date('Y') - $person['byear'] ?></em>
		<? } ?>
		<? if ($person['status'] == 'canceled') { ?><span class="label label-danger">CANCELED</span><? } ?>
		<br>
		<?= Html::a('Edit info', '@web/users/u/'.$person['id'], ['class'=>'text-muted']) ?>
		-
		<?= Html::a('Cancel bkg', '@web/bookings/r/'.$theBooking['id'].'?action=cancel-user-booking&user_id='.$person['id'], ['class'=>'text-muted']) ?>
		-
		<?= Html::a('Remove from bkg', '@web/bookings/r/'.$theBooking['id'].'?action=delete-user-booking&user_id='.$person['id'], ['class'=>'text-muted']) ?>
	</div>
	<?
	} // foreach people

	if ($cnt < $theBooking['pax']) {
		for ($i = $cnt + 1; $i <= $theBooking['pax']; $i ++) {
	?>
	<div style="background-color:#f6f6f6; padding:4px; margin-bottom:4px;" class="clearfix">
		<?= Html::img('https://secure.gravatar.com/avatar/'.md5('000').'?s=40&d=mm', ['style'=>'float:left; margin-right:8px;']) ?>
		<span class="text-danger">PAX #<?= $i ?></span>
		<br>
		<span class="text-danger">This pax info is missing.</span>
	</div>
	<?
		} // for i
	} // if cnt
	?>

</div>
<div class="col-md-3">
	<p><strong>THE PRODUCT</strong></p>
	<p>
		<? if ($theBooking['product']['tour']) { $tour = $theBooking['product']['tour']; ?>
		<?= Html::a($tour['code'], '@web/tours/r/'.$tour['id'], ['style'=>'color:#148040; padding:0 3px; background-color:#ffc']) ?>
		<? } ?>
		<?= Html::a($theBooking['product']['title'], '@web/products/r/'.$theBooking['product']['id']) ?>
	</p>
	<p>
		(<?= Html::a('Show all', '#', ['id'=>'a-show-all-days']) ?> - <?= Html::a('Hide all', '#', ['id'=>'a-hide-all-days']) ?> or click each day to toggle)
	</p>
	<?
	$cnt = 0;
	$dayIdList = explode(',', $theBooking['product']['day_ids']);
	foreach ($dayIdList as $id) {
		foreach ($theBooking['product']['days'] as $day) {
			if ($id == $day['id']) {
				$cnt ++;
	?>
	<div>
		<strong><?= str_pad($cnt, 2, '0', STR_PAD_LEFT) ?></strong>.
		<span data-body-id="<?= $day['id'] ?>" class="product-day-name"><?= $day['name'] ?></span>
		(<?= $day['meals'] ?>)
	</div>
	<div id="product-day-body-<?= $day['id'] ?>" class="product-day-body" style="display:none; border-left:4px solid #eee; padding-left:8px; margin-left:8px; margin-top:4px;">
		<?= Markdown::process($day['body']) ?>
	</div>
	<?
			}
		} // foreach days
	} // foreach ids
	?>
</div>
<?
$js = <<<TXT
$('#a-user-c').click(function(){
	$('#div-user-c').toggle();
	return false;
});
$('#a-invoices-c').click(function(){
	$('#div-invoices-c').toggle();
	$('a.a-payments-c-cancel').click();
	return false;
});
$('a.a-invoices-c-cancel').click(function(){
	$('#div-invoices-c').hide();
	return false;
});
$('#a-payments-c').click(function(){
	$('#div-payments-c').toggle();
	$('a.a-invoices-c-cancel').click();
	return false;
});
$('a.a-payments-c-cancel').click(function(){
	$('#div-payments-c').hide();
	return false;
});
$('#a-show-all-days').click(function(){
	$('.product-day-body').show();
	return false;
});
$('#a-hide-all-days').click(function(){
	$('.product-day-body').hide();
	return false;
});
$('.product-day-name').click(function(){
	var bodyId = $(this).data('body-id');
	$('.product-day-body').hide();
	$('#product-day-body-'+bodyId).show();
});
$('#payment-payment_dt, #invoice-due_dt').daterangepicker({
	minDate:'2007-01-01',
	maxDate:'2050-01-01',
	startDate:moment(),
	format:'YYYY-MM-DD HH:mm',
	showDropdowns:true,
	singleDatePicker:true,
	timePicker:true,
	timePicker12Hour:false,
	timePickerIncrement:5
});

TXT;
$this->registerCssFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);