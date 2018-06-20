<?
use yii\helpers\Html;

include('_tours_inc.php');

/*
	$db->query('UPDATE at_tours SET status="deleted" WHERE id=%i LIMIT 1', $theTour['id']);
	$db->query('UPDATE at_ct SET op_finish="canceled", op_finish_dt=%s WHERE id=%i LIMIT 1', NOW, $theCt['id']);
*/

$this->title = 'Pax list: '.$theTour['op_code'];

$this->params['breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
	[substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
	[$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
	['Pax list', URI],
];

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="20"></th>
					<th colspan="2">Names</th>
					<th>Gender</th>
					<th>Birthdate</th>
					<th>Nationality</th>
					<th>Passport</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Note</th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0;
				foreach ($theTour['bookings'] as $booking) {
					foreach ($booking['people'] as $user) {
				?>
				<tr>
					<td><?= ++$cnt ?></td>
					<td><?= $user['fname'] ?></td>
					<td><?= $user['lname'] ?></td>
					<td><?= $user['gender'] ?></td>
					<td><?= $user['bday'] ?> / <?= $user['bmonth'] ?> / <?= $user['byear'] ?></td>
					<td><?= $user['country']['name_en'] ?></td>
					<td></td>
					<td><?= $user['email'] ?></td>
					<td><?= $user['phone'] ?></td>
					<td><?= $user['info'] ?></td>
				</tr>
				<?
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>
