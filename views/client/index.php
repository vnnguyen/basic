<?
use yii\helpers\Html;

$this->title = 'Welcome to your client space';
$this->params['icon'] = 'home';

?>
<!--img src="https://ppcdn.500px.org/93836759/cacaadd4bc6634d7cdc91c2426be77d033a9367f/2048.jpg" class="img-responsive"-->
<div class="col-md-12">
	<h3>Upcoming tours</h3>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Start date</th>
				<th>Tour code and name</th>
				<th>Pax</th>
				<th>Note</th>
			</tr>
		</thead>
		<tbody>
	<? foreach ($theBookings as $booking) { ?>
			<tr>
				<td><?= $booking['day_from'] ?></td>
				<td><?= Html::a($booking['op_code'], '@web/client/product?id='.$booking['id']) ?> <?= $booking['title'] ?></td>
				<td><?= $booking['pax'] ?></td>
				<td><?= $booking['id'] ?></td>
			</tr>
	<? } ?>
		</tbody>
	</table>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>Pax name</th>
				<th>Deposit</th>
				<th>Personal info</th>
				<th>Note</th>
			</tr>
		</thead>
		<tbody>
	<? $cnt = 0; foreach ($thePax as $user) { ?>
			<tr>
				<td class="text-center"><?= ++ $cnt ?></td>
				<td><?= Html::a($user['name'], '@web/client/info?id='.$user['id']) ?></td>
				<td class="text-center">YES</td>
				<td class="text-center">NO</td>
				<td></td>
			</tr>
	<? } ?>
		</tbody>
	</table>

	<h3>Latest news</h3>
	<h3>Proposals</h3>
	<h3>Bookings</h3>
	<h3>My info</h3>
</div>
