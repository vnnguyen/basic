<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

$this->title = 'Drivers and vehicles - '.$theTour['op_code'];

$this->params['breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
	[substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
	[$theTour['op_code'], 'products/op/'.$theTour['id']],
	['Drivers', 'tours/drivers/'.$theTour['id']],
];

$tourdayIds = explode(',', $theTour['day_ids']);

$form = ActiveForm::begin();
?>
<div class="col-md-12">
	<p><strong>CURRENT ASSIGNED DRIVERS</strong> | <?= Html::a('+New', DIR.URI.'?action=add') ?></p>
	<? if (empty($tourDrivers)) { ?>
	<p>No assigned drivers.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Status</th>
					<th>Vehicle</th>
					<th>Company</th>
					<th>Driver</th>
					<th>Service time</th>
					<th>Points</th>
					<th>Note</th>
					<th width="20"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($tourDrivers as $driver) { if ($driver['parent_id'] == 0) { ?>
				<tr>
					<td><?= ucwords($driver['booking_status']) ?></td>
					<td class="text-nowrap"><?= $driver['vehicle_type'] ?> <?= $driver['vehicle_number'] ?></td>
					<td class="text-nowrap"><?= $driver['driver_company'] ?></td>
					<td class="text-nowrap"><?
					if ($driver['driver_user_id'] != 0 && $driver['namephone'] != '') {
						echo '<i class="fa fa-user text-muted"></i> ', Html::a($driver['namephone'], '@web/drivers/r/'.$driver['driver_user_id'], ['rel'=>'external']);
					} else {
						echo $driver['driver_name'];
					}
					echo ' - ', Html::a('Edit', DIR.URI.'?action=edit&item_id='.$driver['id']);
					?>
					</td>
					<td class="text-nowrap text-center">
						<div>
						<?= date('j/n/Y', strtotime($driver['use_from_dt'])) ?> - <?= date('j/n/Y', strtotime($driver['use_until_dt'])) ?>
						<?= Html::a('+', DIR.URI.'?action=addtime&item_id='.$driver['id'], ['title'=>'+Service time']) ?>
						</div>
						<? foreach ($tourDrivers as $item2) {
							if ($item2['parent_id'] == $driver['id']) {
								echo '<div>', date('j/n/Y', strtotime($item2['use_from_dt'])), ' - ', date('j/n/Y', strtotime($item2['use_until_dt']));
								echo ' ', Html::a('<i class="fa fa-trash-o"></i>', DIR.URI.'?action=delete&item_id='.$item2['id'], ['title'=>'Delete', 'class'=>'text-muted']), '</div>';
							}
						}
						?>
					</td>
					<td class="text-center"><?= $driver['points'] ?></td>
					<td><?= $driver['note'] ?></td>
					<td class="text-muted">
						<?= Html::a('<i class="fa fa-trash-o"></i>', DIR.URI.'?action=delete&item_id='.$driver['id'], ['class'=>'text-danger', 'title'=>'Delete']) ?>
					</td>
				</tr>
				<? } } ?>
			</tbody>
		</table>
	</div>
	<? } // if empty ?>
	<hr>
</div>

<div class="col-md-6">
	<? if ($action == 'add') { ?>
	<p><strong>NEW DRIVER/VEHICLE INFO</strong></p>
	<? } ?>

	<? if ($action == 'addtime') { ?>
	<p><strong>NEW SERVICE TIME FOR <?= $theDriver['driver_name'] ?></strong></p>
	<? } ?>

	<? if ($action == 'edit') { ?>
	<p><strong>EDIT INFO</strong>
		<? if ($action == 'edit' && $theDriver['driver_user_id']) { ?>
		<em class="text-danger">NOTE: You cannot edit driver's name. To replace, delete and add new.</em>
		<? } ?>
	</p>
	<? } ?>

	<? if ($action == 'delete') { ?>
	<p><strong>CONFIRM DELETION</strong></p>
	<? } ?>

	<? if (in_array($action, ['add', 'addtime', 'edit'])) { ?>
	<? if (in_array($action, ['add', 'edit'])) { ?>
	<div class="row">
		<div class="col-xs-6"><?= $form->field($theForm, 'vehicleType') ?></div>
		<div class="col-xs-6"><?= $form->field($theForm, 'vehicleNumber') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'driverCompany') ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'driverName', ['inputOptions'=>['class'=>'form-control', 'autocomplete'=>'off', ($action == 'edit' && $theDriver['driver_user_id'] != 0 ? 'disabled' : 'meh')=>'disabled']]) ?></div>
	</div>
	<? } ?>
	<div class="row">
		<div class="col-xs-6"><?= $form->field($theForm, 'useFromDt') ?></div>
		<div class="col-xs-6"><?= $form->field($theForm, 'useUntilDt') ?></div>
	</div>
	<? if (in_array($action, ['add', 'edit'])) { ?>
	<div class="row">
		<div class="col-xs-6"><?= $form->field($theForm, 'bookingStatus')->dropdownList($theForm::$bookingStatusList) ?></div>
		<div class="col-xs-6"><?= $form->field($theForm, 'points') ?></div>
	</div>
	<?= $form->field($theForm, 'note')->textArea(['rows'=>5]) ?>
	<? } ?>
	<div class="text-right"><?= Html::submitButton('Save', ['class'=>'btn btn-primary']) ?></div>
	<? } ?>
</div>
<? ActiveForm::end(); ?>
<style type="text/css">
.day-list-item { margin-bottom:8px;}
.day-list-item-title {border:1px dotted #ccc; padding:8px; background-color:#eceff1;}
.day-list-item-body {border:1px dotted #ccc; padding:8px; border-top:0;}
.day-number {padding:0 8px; border-right:1px solid #ddd; font-weight:bold; display:inline-block; float:left;}
.day-date {padding:0 8px; border-right:1px solid #ddd; display:inline-block; float:left;}
.day-name {padding:0 8px; border-right:1px solid #ddd; font-weight:bold; display:inline-block; float:left;}
.day-meals {padding:0 8px; color:#757575;}
</style>
<div class="col-md-6">
	<p>
		<strong>ITINERARY</strong> Last update <?= $theTour['updated_at'] ?>
		<a href="#" class="a-show-all">Show all</a>
		<a href="#" class="a-hide-all">Hide all</a>
	</p>
	<div class="itinerary day-list">
<?
$cnt = 0;
foreach ($tourdayIds as $id) {
	foreach ($theTour['days'] as $day) {
		if ($day['id'] == $id) {
			$date = strtotime('+ '.$cnt.' days', strtotime($theTour['day_from']));
			$dmY = date('j/n/Y', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
			$dm = date('j/n', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
?>
	<div class="day-list-item">
		<div class="day-list-item-title">
			<span class="day-number"><?= ++$cnt ?></span>
			<span class="day-date"><?= $dmY ?></span>
			<span class="day-name"><?= $day['name'] ?></span>
			<em class="day-meals"><?= $day['meals'] ?></em>
		</div>
		<div class="day-list-item-body">
			<?= Markdown::process($day['body']) ?>
		</div>
	</div>
<?
		}
	}
}
?>
	</div>
</div>
<?

$js = <<<'TXT'
$('#tourdriverform-usefromdt, #tourdriverform-useuntildt').daterangepicker({
    locale: {
        firstDay: 1,
        format: 'YYYY-MM-DD HH:mm'
    },
    timePicker: true,
    timePickerIncrement: 5,
    timePicker24Hour: true,
    singleDatePicker: true,
    showDropdowns: true
});

var drivers = [$theDrivers];

var drivers = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.whitespace,
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	local: drivers
});

$('#tourdriverform-drivername').typeahead({
	hint: true,
	highlight: true,
	minLength: 1
	},{
	name: 'drivers',
	source: drivers
});
TXT;

$driverList = [];
foreach ($theDrivers as $driver) {
	$driverList[] = "'$driver[namephone]'";
}
$js = str_replace('$theDrivers', implode(', ', $driverList), $js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.0/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJsFile(DIR.'assets/typeahead.js_0.11.1/typeahead.bundle.min.js', ['depends'=>'app\assets\MainAsset']);

$this->registerJs($js);

$js = <<<'TXT'
$('.day-list-item-body').hide();

$('.a-show-all').click(function(){
	$('.day-list-item-body').slideDown(300);
	return false;
});
$('.a-hide-all').click(function(){
	$('.day-list-item-body').slideUp(300);
	return false;
});
$('.day-list-item-title').click(function(){
	var body = $(this).parent().find('.day-list-item-body');
	body.addClass('clicked');
	if (body.is(':visible')) {
		body.slideUp(300);
		//$('.day-list-item-body:not(.clicked)').slideDown(300);
	} else {
		body.slideDown(300);
		$('.day-list-item-body:not(.clicked)').slideUp(300);
	}
	body.removeClass('clicked');
});
TXT;
$this->registerJs($js);