<?
use yii\helpers\Html;

$this->title = 'Tour calendar';

$this->params['icon'] = 'calendar';
$this->params['breadcrumb'] = [
	['Tours', '@web/tours'],
	['Calendar', '@web/tours/calendar'],
	['Vertical view', '@web/tours/calendar?view=v'],
];

?>
<div class="col-md-12">
	<form class="form-inline well well-sm">
		<?= Html::a('Previous week', '@web/tours/calendar?date='.$prevWeek) ?>
		<input type="text" id="date" style="width:100px; display:inline-block;" class="form-control" name="date" value="<?= $thisWeek ?>">
		<button type="submit" class="btn btn-primary">Go</button>
		<?= Html::a('Next week', '@web/tours/calendar?date='.$nextWeek) ?>
		|
		<?= Html::a('Back to this week', '/tours/calendar') ?>
		<!-- // TODO 
		|
		<?= Html::a('Today only', '#', ['id'=>'today-only']) ?>
		|
		<?= Html::a('Me only', '#', ['id'=>'me-only']) ?>
		-->
	</form>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th width="80">Day</th>
					<th width="200">Tour</th>
					<th>Activities</th>
				</tr>
			</thead>
			<tbody>
<?
for ($i = 0; $i < 7; $i ++) {
	$thisDay = date('Y-m-d', strtotime('+ '.$i.' days', strtotime($thisWeek)));
	foreach ($theTours as $tour) {
		$diff = date_diff(date_create($tour['day_from']), date_create($thisWeek))->format('%R%a');
		$dayIdList = explode(',', $tour['day_ids']); 
		$index = (int)$diff + $i;
		foreach ($tour['days'] as $day) {
			if (isset($dayIdList[$index]) && $day['id'] == $dayIdList[$index]) {
				$trClass = '';
				$names = [];
				foreach ($tour['bookings'] as $booking) {
					$names[] = Html::a($booking['createdBy']['name'], '#', ['class'=>'tour-user text-muted', 'data-id'=>$booking['createdBy']['id']]);
					$trClass .= ' tour-user-'.$booking['createdBy']['id'];
				}
				if ($tour['tour']['operators']) {
					foreach ($tour['tour']['operators'] as $user) {
						$names[] = Html::a($user['name'], '#', ['class'=>'tour-user text-muted', 'data-id'=>$user['id']]);
						$trClass .= ' tour-user-'.$user['id'];
					}
				}

?>
				<tr>
					<td class="text-center text-nowrap"><strong><?= date('j/n (D)', strtotime($thisDay)) ?></strong></td>
					<td class="text-nowrap">
						<div><strong><?= $tour['op_code'] ?></strong> <?= $tour['op_name'] ?> <?= $tour['day_count'] ?>d <?= $tour['pax'] ?>p  <?= date('j/n', strtotime($tour['day_from'])) ?></div>
						<div><?= implode(', ', $names) ?></div>
					</td>
					<td>
						<div><?= $day['name'] ?></div>
<?
				if (isset($tour['tour']['cpt'])) {
					foreach ($tour['tour']['cpt'] as $cpt) {
						if ($cpt['dvtour_day'] == $thisDay) {
?>
						<div style="height:20px; overflow:hidden;" title="<?= $cpt['venue']['name'] ?>; <?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>">
							<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id'], ['rel'=>'external', 'class'=>'text-danger']) ?>
							<?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>
						</div>
<?
						break;
						}
					}
				}
?>
					</td>
				</tr>
<?
			} // if day id == index
		} // foreach tour days
	} // foreach theTours
} // for each day of week
$cnt = 0;

foreach ($theTours as $tour) {
	break;
	$trClass = 'tour-user';
	$names = [];
	foreach ($tour['bookings'] as $booking) {
		$names[] = Html::a($booking['createdBy']['name'], '#', ['class'=>'tour-user text-muted', 'data-id'=>$booking['createdBy']['id']]);
		$trClass .= ' tour-user-'.$booking['createdBy']['id'];
	}
	if ($tour['tour']['operators']) {
		foreach ($tour['tour']['operators'] as $user) {
			$names[] = Html::a($user['name'], '#', ['class'=>'tour-user text-muted', 'data-id'=>$user['id']]);
			$trClass .= ' tour-user-'.$user['id'];
		}
	}
?>
				<tr class="<?= $trClass ?>">
<?
	// Sai lệch so với ngày tour khởi hành
	$diff = date_diff(date_create($tour['day_from']), date_create($thisWeek))->format('%R%a');
	$dayIdList = explode(',', $tour['day_ids']); 
	for ($i = 0; $i < 7; $i ++) {
		$thisDay = date('Y-m-d', strtotime('+ '.$i.' days', strtotime($thisWeek)));
		$tdClass = '';
		if ($thisDay == date('Y-m-d')) {
			$tdClass = 'bg-success';
		}
?>
					<td class="<?= $tdClass ?>"><?= $thisDay ?></td>
					<td>
<?
		$index = (int)$diff + $i;
		foreach ($tour['days'] as $day) {
			if (isset($dayIdList[$index]) && $day['id'] == $dayIdList[$index]) {
?>
						<div><?= $tour['op_code'] ?></div>
						<div style="height:20px; overflow:hidden;" title="<?= $day['name'] ?> <?= $day['meals'] ?>"><?= $day['name'] ?> <em><?= $day['meals'] ?></em></div>
<?
				if (isset($tour['tour']['cpt'])) {
					foreach ($tour['tour']['cpt'] as $cpt) {
						if ($cpt['dvtour_day'] == $thisDay) {
?>
						<div style="height:20px; overflow:hidden;" title="<?= $cpt['venue']['name'] ?>; <?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>">
							<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id'], ['rel'=>'external', 'class'=>'text-danger']) ?>
							<?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>
						</div>
<?
						break;
						}
					}
				}
			}
		}
		foreach ($theGuides as $guide) {
			if ($guide['day'] == $thisDay && $guide['tour_id'] == $tour['tour']['id']) {
				echo '<div class="text-info">', $guide['name'], ' ', $guide['phone'], '</div>';
				break;
			}
		}

		// Tour notes by TOp

		if (isset($tour['tournotes'])) {
			foreach ($tour['tournotes'] as $note) {
				$lines = explode(PHP_EOL, $note['body']);
				foreach ($lines as $line) {
					$parts = explode('>>>', $line);
					if (isset($parts[1])) {
						$parts[0] = trim($parts[0]);
						$parts[1] = trim($parts[1]);
						if ($parts[0] == date('j/n', strtotime($thisDay))) {
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
							if (strpos($parts[1], '(phone)') !== false) {
								$icon = 'phone';
							}
							if (strpos($parts[1], '(train)') !== false) {
								$icon = 'train';
							}
							if (strpos($parts[1], '(time)') !== false) {
								$icon = 'clock-o';
							}
							$parts[1] = str_replace(['(red)', '(green)', '(blue)', '(purple)'], ['', '', '', ''], $parts[1]);
							$parts[1] = str_replace(['(car)', '(train)', '(phone)', '(time)', '(plane)'], ['', '', '', '', ''], $parts[1]);

?>
						<div title="<?= $note['updatedBy']['name'] ?>; <?= substr($note['updated_at'], 0, 16) ?>" style="color:<?= $color ?>;">
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
					</td>
				</tr>
<?
	}
}
?>

			</tbody>
		</table>

	</div>
</div>
<style type="text/css">
.text-bold {font-weight:bold;}
</style>
<?
$js = <<<'TXT'
$('h3.page-title').append(' (<span id="tour-count">TOUR_COUNT</span>)');
$('a.tour-user').click(function(){
	var id = $(this).data('id');
	if ($(this).hasClass('text-bold')) {
		$('a.tour-user[data-id='+id+']').removeClass('text-bold');
		$('tr.tour-user').show();
	} else {
		$('a.tour-user').removeClass('text-bold');
		$('a.tour-user[data-id='+id+']').addClass('text-bold');
		$('tr.tour-user').hide();
		$('tr.tour-user-'+id).show();
	}
	cnt = 0;
	$('#tour-count').html($('tr.tour-user:visible').length);
	$('td.cnt:visible').each(function(index){
		$(this).html(1 + index);
	});
	return false;
});
TXT;
$this->registerJs(str_replace(['TOUR_COUNT'], [count($theTours)], $js));
$js = <<<TXT
$('#date').datepicker({
	format: "yyyy-mm-dd",
    weekStart: 1,
    todayBtn: "linked",
    clearBtn: true,
    language: "vi",
    autoclose: true
});

TXT;
$this->registerCssFile(DIR.'assets/bootstrap-datepicker_1.3.1/css/datepicker3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-datepicker_1.3.1/js/bootstrap-datepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-datepicker_1.3.1/js/locales/bootstrap-datepicker.vi.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs(str_replace(['{dt}'], [$thisWeek], $js));