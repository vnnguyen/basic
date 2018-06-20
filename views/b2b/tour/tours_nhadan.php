<?
use yii\helpers\Html;
Yii::$app->params['page_title'] = 'Lịch: '.Html::a($theVenue['name'], '@web/venues/r/'.$theVenue['id']).' năm '.$getYear;
Yii::$app->params['page_meta_title'] = strip_tags(Yii::$app->params['page_title']);
Yii::$app->params['page_icon'] = 'calendar';
Yii::$app->params['body_class'] = 'bg-white';
Yii::$app->params['page_breadcrumbs'] = [
	['Tour operation', '@web/tours'],
	['Local homes calendar', '@web/tours/nhadan'],
];
$dow = ['Hai', 'Ba', 'Tư', 'Năm', 'Sáu', 'Bảy', 'CN'];

?>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<form class="form-inline well well-sm">
				<?= Html::dropdownList('venue', $getVenue, $venueList, ['class'=>'form-control']) ?>
				<?= Html::dropdownList('year', $getYear, $yearList, ['class'=>'form-control']) ?>
				<?= Html::dropdownList('view', $getView, ['year'=>'Xem cả năm', 'month'=>'Xem từng tháng'], ['class'=>'form-control']) ?>
				<?= Html::submitButton('Xem lịch', ['class'=>'btn btn-primary']) ?>
			</form>
		</div>
		<div class="col-md-6">
			<form method="post" action="" class="form-inline well well-sm">
				<?= Html::hiddenInput('action', 'add-avail') ?>
				<?= Html::hiddenInput('venue_id', $theVenue['id']) ?>
				<?= Html::textInput('day', '', ['id'=>'input-day', 'class'=>'form-control', 'placeholder'=>'Ngày']) ?>
				<?= Html::textInput('pax', '', ['id'=>'input-pax', 'class'=>'form-control', 'placeholder'=>'Pax', 'style'=>'width:50px;']) ?>
				<?= Html::textInput('note', '', ['id'=>'input-note', 'class'=>'form-control', 'placeholder'=>'Ghi chú']) ?>
				<?= Html::submitButton('Giữ chỗ', ['class'=>'btn btn-danger']) ?>
			</form>
		</div>
	</div>
	<? if ($getView == 'year') { ?>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center"><?= $getYear ?></th>
<?
				for ($mo = 1; $mo <= 12; $mo ++) {
					$firstDayOfWeek[$mo] = date('N', strtotime($getYear.'-'.$mo.'-1'));
					$monthDayCount[$mo] = date('t', strtotime($getYear.'-'.$mo));

?>
				<th class="text-center text-nowrap">Tháng <?= $mo ?></th>
<?
				}
?>
			</tr>
		</thead>
		<tbody>
<?
			$wd = 0;
			for ($cnt = 1; $cnt <= 37; $cnt ++) {
?>
			<tr>
				<th class="text-center text-muted <?= $wd == 6 ? 'bg-danger' : '' ?>"><?= $dow[$wd] ?></th>
<?
				for ($mo = 1; $mo <= 12; $mo ++) {
?>
				<td>
<?
					$currentDay = 1 + $cnt - $firstDayOfWeek[$mo];
					if ($currentDay > 0 && $currentDay <= $monthDayCount[$mo]) {
						$theDay = date('Y-m-d', strtotime($getYear.'-'.$mo.'-'.$currentDay));
						$stay = '';
						$activities = '';
						foreach ($theCptx as $cpt) {
							if (
								$cpt['dvtour_day'] == $theDay &&
								(
									strpos($cpt['dvtour_name'], 'stay') !== false ||
									strpos($cpt['dvtour_name'], 'modation') !== false ||
									strpos($cpt['dvtour_name'], 'hà dân') !== false ||
									strpos($cpt['dvtour_name'], 'hách s') !== false ||
									strpos($cpt['dvtour_name'], 'otel') !== false ||
									strpos($cpt['dvtour_name'], 'gủ') !== false
								) &&
								strpos($cpt['dvtour_name'], 'service') === false &&
								strpos($cpt['dvtour_name'], 'phục vụ') === false &&
								strpos($cpt['dvtour_name'], 'hoa quả') === false &&
								strpos($cpt['dvtour_name'], 'fruit') === false
							) {
								$stay .= '<div>('
									.round($cpt['qty'], 0).') '
									.Html::a($cpt['tour']['code'], '@web/tours/services/'.$cpt['tour']['id'].'#dvtour-'.$cpt['dvtour_id'], ['rel'=>'external', 'title'=>$cpt['dvtour_name'].' / '.$cpt['updatedBy']['name']])
									.'</div>';
							}
							// All activities
							if ($cpt['dvtour_day'] == $theDay) {
								$activities .= '<div>'
									.$cpt['tour']['code']
									.' / '
									.round($cpt['qty'], 0)
									.' '
									.$cpt['unit']
									.' / '
									.$cpt['dvtour_name']
									.'</div>';
							}
						}
						if ($activities != '') {
?>
<span data-fill="<?= $theDay ?>" style="background-color:#ccc; cursor:pointer;" class="click-fill label label-default popovers pull-right text-muted" data-trigger="hover" data-title="<?= date('D d M Y', strtotime($theDay)) ?>" data-placement="left" data-html="true" data-content="<?= Html::encode($activities) ?>" data-original-title="" title=""><?= $currentDay ?></span>
<?								
						} else {
?>
<strong data-fill="<?= $theDay ?>" style="color:#ccc; cursor:pointer;" class="click-fill pull-right"><?= $currentDay ?></strong>
<?
						}
						if ($stay != '') {
							echo $stay;
						}

						foreach ($theWaits as $wait) {
							if (substr($wait['from_dt'], 0, 10) == $theDay) {
								echo '<div title="'.$wait['username'].'">';
								if ($wait['created_by'] == MY_ID) {
									echo Html::a('<i class="fa fa-trash-o"></i>', '@web/tours/nhadan?venue_id='.$getVenue.'&year='.$getYear.'&action=remove-avail&id='.$wait['id'], ['class'=>'text-danger', 'title'=>'Xoá']), ' ';
								}
								echo $wait['note'], '</div>';
							}
						}
					}
?>
				</td>
<?
				}
?>
			</tr>
<?
				if ($wd == 6) {
					$wd = 0;
				} else {
					$wd ++;
				}
			}
?>
			<tr>
				<th class="text-center"><?= $getYear ?></th>
<?
				for ($mo = 1; $mo <= 12; $mo ++) {
?>
				<th class="text-center text-nowrap">Tháng <?= $mo ?></th>
<?
				}
?>
			</tr>
		</tbody>
	</table>
	<? } else { ?>
	<ul class="nav nav-tabs mb-1em" data-tabs="tabs">
		<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
		<li class="<?= $mo == date('m') ? 'active' : ''?>"><a data-toggle="tab" href="#month<?= $mo ?>">Tháng <?= $mo ?></a></li>
		<? } ?>
	</ul>
	<div id="tab-content" class="tab-content">
		<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
		<div id="month<?= $mo ?>" class="<?= $mo == date('m') ? 'active' : '' ?> tab-pane">
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th width="20"></th>
						<th width="20">Th</th>
						<th width="50">Ngày</th>
						<th>Tour</th>
						<th>Nội dung</th>
						<th width="50">Pax</th>
						<th>Guide</th>
					</tr>
				</thead>
				<tbody>
<?
$cnt = 0;
foreach ($theCptx as $cpt) {
	if (		
		substr($cpt['dvtour_day'], 5, 2) == substr('0'.$mo, -2) &&
		(
			strpos($cpt['dvtour_name'], 'stay') !== false ||
			strpos($cpt['dvtour_name'], 'modation') !== false ||
			strpos($cpt['dvtour_name'], 'hà dân') !== false ||
			strpos($cpt['dvtour_name'], 'gủ') !== false
		) &&
		strpos($cpt['dvtour_name'], 'service') === false &&
		strpos($cpt['dvtour_name'], 'phục vụ') === false &&
		strpos($cpt['dvtour_name'], 'hoa quả') === false &&
		strpos($cpt['dvtour_name'], 'fruit') === false
	) {

		$cnt ++;
?>
					<tr>
						<td class="text-center"><?= $cnt ?></td>
						<td class="text-nowrap text-center"><?= $dow[date('N', strtotime($cpt['dvtour_day'])) - 1] ?></td>
						<td class="text-nowrap"><?= date('d-m-Y', strtotime($cpt['dvtour_day'])) ?></td>
						<td class="text-nowrap"><?= Html::a($cpt['tour']['code'].' - '.$cpt['tour']['name'], '@web/tours/r/'.$cpt['tour']['id']) ?></td>
						<td class="text-nowrap"><?= $cpt['dvtour_name'] ?></td>
						<td class="text-center text-nowrap"><?= round($cpt['qty'], 0) ?> <?= $cpt['unit'] ?></td>
						<td>
						<?
						foreach ($guideList as $guide) {
							if ($guide['tour_id'] == $cpt['tour']['ct_id'] && strtotime(substr($guide['use_from_dt'], 0, 10)) <= strtotime($cpt['dvtour_day']) && strtotime(substr($guide['use_until_dt'], 0, 10)) >= strtotime($cpt['dvtour_day'])) {
								echo $guide['guide_name'];
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
		<? } ?>
	</div>
	<? } // if view ?>
</div>
<style type="text/css">
.table-bordered>tbody>tr>td {border-color:#999;}
</style>
<?
$js = <<<TXT
$('#input-day').daterangepicker({
	minDate:'2007-01-01',
	maxDate:'2027-01-01',
	startDate:$(this).val() == '' ? '{dt}' : $(this).val(),
	format:'YYYY-MM-DD',
	showDropdowns:true,
	singleDatePicker:true
});
$('.click-fill').click(function(){
	fill = $(this).data('fill');
	$('#input-day').val(fill);
	$('#input-day').data('daterangepicker').setStartDate(fill);
	$('#input-day').focus();
});

TXT;
$this->registerCssFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker-bs3.css', ['depends'=>'app\assets\BootstrapAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'app\assets\BootstrapAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker.js', ['depends'=>'app\assets\BootstrapAsset']);
$this->registerJs(str_replace(['{dt}'], [date('Y-m-d')], $js));