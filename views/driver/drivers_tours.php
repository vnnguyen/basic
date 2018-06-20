<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_drivers_inc.php');

$this->title = 'Drivers for tours';

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<th>Status</th>
				<th>Tour</th>
				<th>Vehicle</th>
				<th>Company</th>
				<th>Driver</th>
				<th>Service time</th>
				<th>Pts</th>
				<th>Note</th>
			</thead>
			<tbody>
				<? foreach ($theTours as $tour) { ?>
				<tr>
					<td><?= ucwords($tour['booking_status']) ?></td>
					<td class="text-nowrap"><?= Html::a($tour['op_code'], '@web/tours/drivers/'.$tour['tour_id'], ['title'=>$tour['op_name']]) ?></td>
					<td class="text-nowrap"><?= $tour['vehicle_type'] ?> <?= $tour['vehicle_number'] ?></td>
					<td class="text-nowrap"><?= $tour['driver_company'] ?></td>
					<td><?= $tour['driver_user_id'] == 0 ? $tour['driver_name'] : Html::a($tour['driver_name'], '@web/drivers/r/'.$tour['driver_user_id']) ?></td>
					<td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($tour['use_from_dt'])) ?> - <?= date('j/n/Y', strtotime($tour['use_until_dt'])) ?></td>
					<td><?= $tour['points'] ?></td>
					<td><?= $tour['note'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>