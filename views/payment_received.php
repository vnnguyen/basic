<div style="width:600px;">
	<p style="font-size:32px; font-weight:bold; color:#777">
		<?= number_format($thePayment['amount'], 0)?> <span style="color:#ccc"><?= $thePayment['currency'] ?></span>
	</p>
	<p><img alt="MONEY RECEIVED" src="http://www.metro1.com/wp-content/uploads/2014/01/Money-100s.jpg"></p>
	<p>--- DON'T REPLY TO THIS EMAIL | ĐỪNG VIẾT TRẢ LỜI EMAIL NÀY ---</p>
	<p>Link to details: https://my.amicatravel.com/bookings/r/<?= $theBooking['id'] ?></p>
	<p>Payment date: <?= $thePayment['payment_dt'] ?></p>
	<p>Ref ID: <?= $theInvoice['ref'] ?></p>
	<p>Payment by: <?= $thePayment['payer'] ?></p>
	<p>Payment to: <?= $thePayment['payee'] ?></p>
	<p>Account: <?= $thePayment['method'] ?></p>
	<p>Amount: <?= number_format($thePayment['amount'], 0)?> <?= $thePayment['currency'] ?></p>
	<p>Note: <?= nl2br($thePayment['note']) ?></p>
	<p>&mdash;<br />Updated by <?= Yii::$app->user->identity->fname ?> <?= Yii::$app->user->identity->lname ?></p>
</div>
