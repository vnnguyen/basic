<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;
use Mailgun\Mailgun;

\app\assets\BootstrapDaterangePickerAsset::register($this);

$this->title = 'Test tour : '.$theTour['op_code'].' - '.$theTour['op_name'];

$this->params['breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
	[substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
	[$theTour['op_code'], 'products/op/'.$theTour['id']],
	['Test', 'tours/test/'.$theTour['id']],
];

$tourdayIds = explode(',', $theTour['day_ids']);

# Instantiate the client.
$mgClient = new Mailgun(MAILGUN_API_KEY);
//$mgClient = new Mailgun('key-41qs3pbnff7i2k42jmsh9v6ch059jf76');
$domain = 'amicatravel.com';

# Issue the call to the client.
//$result = $mgClient->get("routes", ['skip' => 0, 'limit' => 200]);

//\fCore::expose($result);
//exit;


?>

<style type="text/css">
.day-list-item { margin-bottom:1px;}
.day-list-item-title {padding:4px; background-color:#f6f6f6;}
.day-list-item-body {border-top:1px solid #999; padding:8px;}
.day-number {font-size:90%;}
.day-date {padding:0 8px; background-color:#eee; padding:0 4px; margin:0 4px;}
.day-name {}
.day-meals {padding:0 8px; color:#666;}
</style>
<div class="col-md-5">
	<p><strong>SERVICES & COSTS</strong></p>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Category</th>
					<th>Name of service</th>
					<th>Day</th>
					<th>Cost</th>
					<th>Booking</th>
					<th>Payment</th>
					<th>Use</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ([] as $item) { ?>
				<tr>
					<td></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($theForm, 'service_time') ?>
	<?= $form->field($theForm, 'note')->textArea(['rows'=>5]) ?>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-7">
	<p>
		<strong>ITINERARY</strong> Last update <?= date('j/n/Y H:i', strtotime($theTour['updated_at'])) ?>
		- <a href="#" class="a-show-all">Show all</a>
		- <a href="#" class="a-hide-all">Hide all</a>
	</p>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>BOOK</th>
					<th>PAY</th>
					<th>USE</th>
					<th>Name of svc</th>
					<th>Supplier</th>
					<th>Cost</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
<?
$cnt = 0;
foreach ($tourdayIds as $id) {
	foreach ($theTour['days'] as $day) {
		if ($day['id'] == $id) {
			$date = strtotime('+ '.$cnt.' days', strtotime($theTour['day_from']));
			$dmY = date('j/n/Y', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
			$dm = date('j/n', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
?>
				<tr>
					<td colspan="10">
						<div class="xday-list-item">
							<div class="xday-list-item-title clearfix">
								<span class="text-muted day-number"><?= ++$cnt ?></span>
								<span class="day-date"><?= $dm ?></span>
								<span class="xday-name"><?= $day['name'] ?></span>
								<em class="day-meals"><?= $day['meals'] ?></em>
							</div>
							<div class="xday-list-item-body">
							</div>
						</div>
					</td>
				</tr>
<?
			foreach ($theTourDrivers as $driver) {
				if (strtotime(substr($driver['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($driver['use_until_dt'], 0, 10))) {
?>
				<tr>
					<td><i class="fa fa-car"></i></td>
					<td style="color:#00897b">
<?
					if ($driver['booking_status'] == 'confirmed') {
						echo '[CFM]';
					} else {
						echo '['.strtoupper($driver['booking_status']).']';
					}
?>
					</td>
					<td>-</td>
					<td>-</td>
					<td><?= $driver['vehicle_type'] ?> <?= $driver['namephone'] ?></td>
					<td><?= $driver['driver_company'] ?></td>
					<td>-</td>
					<td>-</td>
				</tr>
<?
				} // if same tour and day
			} // forerach drivers

			foreach ($theTourguides as $guide) {
				if (strtotime(substr($guide['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($guide['use_until_dt'], 0, 10))) {
?>
				<tr>
					<td><i class="fa fa-user"></i></td>
					<td style="color:#fb8c00">
<?
					if ($guide['booking_status'] == 'confirmed') {
						echo '[CFM]';
					} else {
						echo '['.strtoupper($guide['booking_status']).']';
					}
?>
					</td>
					<td>-</td>
					<td>-</td>
					<td><?= $guide['namephone'] ?></td>
					<td><?= $guide['guide_company'] ?></td>
					<td>-</td>
					<td>-</td>
				</tr>
<?
				} // if same tour and day
			} // forerach drivers
		} // if same day
	} // foreach days
} // foreach day ids
?>

			</tbody>
		</table>
	</div>
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
		<div class="day-list-item-title clearfix">
			<span class="day-number"><?= ++$cnt ?></span>
			<span class="day-date"><?= $dmY ?></span>
			<span class="day-name"><?= $day['name'] ?></span>
			<em class="day-meals"><?= $day['meals'] ?></em>
		</div>
		<div class="day-list-item-body">
<?
foreach ($theTourDrivers as $driver) {
	if (strtotime(substr($driver['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($driver['use_until_dt'], 0, 10))) {
		echo '<div style="font-size:90%; color:#00897b"><i class="fa fa-car"></i> ';
		if ($driver['booking_status'] == 'confirmed') {
			echo '<span class="label label-success">CFM</span> ';
		} else {
			echo '['.strtoupper($driver['booking_status']).'] ';
		}
		echo $driver['vehicle_type'].', '.$driver['driver_company'].', '.$driver['driver_name'];
		echo '</div>';
	}
}



	if (!empty($theTour['tournotes'])) {
		foreach ($theTour['tournotes'] as $note) {
			$lines = explode(PHP_EOL, $note['body']);
			foreach ($lines as $line) {
				$parts = explode('>>>', $line);
				if (isset($parts[1])) {
					$parts[0] = trim($parts[0]);
					$parts[1] = trim($parts[1]);
					if ($parts[0] == $dm) {

						$color = 'blue';
						$icon = '';
						if (strpos($parts[1], '(red)') !== false) {
							$color = 'red';
						}
						if (strpos($parts[1], '(green)') !== false) {
							$color = 'green';
						}
						if (strpos($parts[1], '(purple)') !== false) {
							$color = 'purple';
						}
						if (strpos($parts[1], '(car)') !== false) {
							$icon = 'car';
						}
						if (strpos($parts[1], '(plane)') !== false) {
							$icon = 'plane';
						}
						if (strpos($parts[1], '(air)') !== false) {
							$icon = 'plane';
						}
						if (strpos($parts[1], '(flight)') !== false) {
							$icon = 'plane';
						}
						if (strpos($parts[1], '(phone)') !== false) {
							$icon = 'phone';
						}
						if (strpos($parts[1], '(tel)') !== false) {
							$icon = 'phone';
						}
						if (strpos($parts[1], '(train)') !== false) {
							$icon = 'train';
						}
						if (strpos($parts[1], '(guide)') !== false) {
							$icon = 'user';
						}
						if (strpos($parts[1], '(hdv)') !== false) {
							$icon = 'user';
						}
						if (strpos($parts[1], '(time)') !== false) {
							$icon = 'clock-o';
						}
						$parts[1] = str_replace(['(red)', '(green)', '(blue)', '(purple)'], ['', '', '', ''], $parts[1]);
						$parts[1] = str_replace(['(car)', '(train)', '(phone)', '(tel)', '(time)', '(plane)', '(flight)', '(air)', '(guide)', '(hdv)'], ['', '', '', '', '', '', '', '', '', ''], $parts[1]);

?>
						<div style="font-size:90%; color:<?= $color ?>; background-color:#ffe; padding:4px;" title="<?= $note['updatedBy']['name'] ?>; <?= substr($note['updated_at'], 0, 16) ?>">
						<? if (in_array(MY_ID, [$note['created_by'], $note['updated_by']])) { ?><a title="Edit" class="text-muted" href="/tours/ctn/<?= $note['product_id'] ?>"><i class="fa fa-edit"></i></a><? } ?>
						<? if ($icon != '') { ?><i class="fa fa-<?= $icon ?>"></i><? } ?>
						<?= trim($parts[1]) ?>
						</div>
<?
					}
				}
			}
		}
	}

?>
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

$('#xtourtestform-service_time').daterangepicker({
    "locale": {
        "format": "DD/MM/YYYY HH:mm",
        "separator": " - ",
        "applyLabel": "Chọn",
        "cancelLabel": "Huỷ",
        "fromLabel": "Từ",
        "toLabel": "Đến",
        "customRangeLabel": "Tuỳ chọn",
        "daysOfWeek": [
            "CN",
            "T2",
            "T3",
            "T4",
            "T5",
            "T6",
            "T7"
        ],
        "monthNames": [
            "Tháng 1",
            "Tháng 2",
            "Tháng 3",
            "Tháng 4",
            "Tháng 5",
            "Tháng 6",
            "Tháng 7",
            "Tháng 8",
            "Tháng 9",
            "Tháng 10",
            "Tháng 11",
            "Tháng 12"
        ],
        "firstDay": 1
    },
	"showDropdowns": true,
	"showWeekNumbers": true,
	"timePicker": true,
	"timePicker24Hour": true,
	"timePickerIncrement": 1,
	"opens": "left",
	"drops": "down",
	"buttonClasses": "btn btn-sm",
	"applyClass": "btn-success",
	"cancelClass": "btn-default"
}, function(start, end, label) {
	//console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
});

$('#xtourtestform-service_time').on('cancel.daterangepicker', function(ev, picker) {
	//do something, like clearing an input
	$('#tourtestform-service_time').val('');
});
TXT;
$this->registerJs($js);