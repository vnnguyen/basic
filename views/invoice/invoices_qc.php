<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_invoice_inc.php');

$this->title = 'Quick invoice creation';
$this->params['breadcrumb'][] = ['New', '@web/invoices/qc?booking_id='.$theBooking['id']];

$form = ActiveForm::begin();
?>
<div class="col-md-8">
	<div class="well well-sm">Booking <?= Html::a($theBooking['id'], '@web/bookings/r/'.$theBooking['id']) ?> | Tour <?= Html::a($theBooking['product']['tour']['code'], '@web/tours/r/'.$theBooking['product']['tour']['id']) ?> | Product <?= Html::a($theBooking['product']['title'], '@web/products/r/'.$theBooking['product']['id']) ?> by <?= $theBooking['createdBy']['name'] ?></div>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theForm, 'opCode') ?></div>
		<div class="col-md-9"><?= $form->field($theForm, 'opName') ?></div>
	</div>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theForm, 'paxName') ?></div>
		<div class="col-md-9"><?= $form->field($theForm, 'paxAddr')->textArea(['rows'=>5]) ?></div>
	</div>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theForm, 'lang')->dropdownList($languageList) ?></div>
		<div class="col-md-3"><?= $form->field($theForm, 'currency')->dropdownList($currencyList) ?></div>
		<div class="col-md-3"><?= $form->field($theForm, 'cost') ?></div>
		<div class="col-md-3"><?= $form->field($theForm, 'deposit') ?></div>
	</div>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theForm, 'xrate') ?></div>
		<div class="col-md-9"><?= $form->field($theForm, 'link') ?></div>
	</div>

	<div class="text-right"><?= Html::submitButton('Create invoices', ['class'=>'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
	<p><strong>Tham khảo: ghi phần dịch vụ</strong></p>
	<pre>Acompte de 10% du prix total du voyage "<?= $theBooking['product']['title'] ?>" | <?= number_format(0.1 * $theBooking['price'], 2) ?> | 1</pre>
	<pre>Organisation du voyage "<?= $theBooking['product']['title'] ?>" | <?= number_format($theBooking['price']) ?> | 1</pre>
	<p><strong>Link tỉ giá</strong></p>
	<pre>
Taux de change EUR/USD: http://www.ecb.europa.eu/stats/exchange/eurofxref/html/index.en.html
</pre>
	<pre>
Taux de change VND/USD: http://www.vietcombank.com.vn/ExchangeRates
</pre>
	<p><strong>Tai khoan Amica (EUR)</strong></p>
	<pre>
Bénéficiaire : AMICA., JSC
Adresse: 3rd Floor, Nikko building, 27 Nguyen Truong To, Ba Dinh, Ha Noi, Vietnam
Numéro de compte (EUR) : 8 8 8 1 9 3 7 9
Banque : ASIA COMMERCIAL BANK
Adresse : 40 Hang Giay, Dong Xuan, Hoan Kiem, Hanoi
Swift code : ASCB VNVX
Website: http://www.acb.com.vn</pre>
	<p><strong>Tai khoan Amica (USD)</strong></p>
	<pre>
Bénéficiaire : AMICA., JSC
Adresse: 3rd Floor, Nikko building, 27 Nguyen Truong To, Ba Dinh, Ha Noi, Vietnam
Numéro de compte (USD) : 8 8 8 1 9 2 4 9
Banque : ASIA COMMERCIAL BANK
Adresse : 40 Hang Giay, Dong Xuan, Hoan Kiem, Hanoi
Swift code : ASCB VNVX
Website: http://www.acb.com.vn</pre>
</div>
<?
ActiveForm::end();


