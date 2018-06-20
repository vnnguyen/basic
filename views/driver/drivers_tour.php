<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_drivers_inc.php');

$this->title = 'Drivers for tour: '.$theTour['code'].' - '.$theTour['name'];

?>
<div class="col-md-6">
	<form method="post" action="">
	<p><strong>THE DRIVER</strong></p>
	<P>
		<select class="form-control" name="driver">
			<option value="0">- Select a driver -</option>
			<? foreach ($theDrivers as $driver) { ?>
			<option value="<?= $driver['id'] ?>"><?= $driver['lname'] ?>, <?= $driver['fname'] ?> - <?= $driver['phone'] ?></option>
			<? } ?>
		</select>
	</p>
	<p><strong>THE DAYS</strong></p>
	<?
	$cnt = 0;
	$dayIdList = explode(',', $theProduct['day_ids']);
	foreach ($dayIdList as $id) {
		foreach ($theProduct['days'] as $day) {
			if ($id == $day['id']) {
				$cnt ++;
	?>
	<div class="checkbox">
		<label for="day-<?= $day['id'] ?>" style="display:inline; font-weight:normal">
			<input type="checkbox" id="day-<?= $day['id'] ?>" name="days[]" value="<?=$day['id'] ?>" class="">
			<strong><?= str_pad($cnt, 2, '0', STR_PAD_LEFT) ?></strong>.
			<?= $day['name'] ?>
			(<?= $day['meals'] ?>)
		</label>
	</div>
	<?
			}
		} // foreach days
	} // foreach ids
	?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	</form>
</div>