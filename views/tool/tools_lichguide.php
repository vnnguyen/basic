<?
use yii\helpers\Html;
$this->title = 'Lịch tour của guide: '.Html::a($theTourguide['name'], '@web/tourguides/r/'.$theTourguide['id']).' năm '.$getYear;
$this->params['breadcrumb'] = [
	['Tools', '@web/tools'],
	['Tour guide', '@web/tools/lichguide'],
];
$dow = ['Hai', 'Ba', 'Tư', 'Năm', 'Sáu', 'Bảy', 'CN'];
?>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<form class="form-inline well well-sm">
				<?= Html::dropdownList('year', $getYear, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
				<?= Html::submitButton('Xem lịch', ['class'=>'btn btn-primary']) ?>
			</form>
		</div>
	</div>
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
?>
					<strong style="color:#ccc" class="pull-right"><?= $currentDay ?></strong>
<?
						$theDay = date('Y-m-d', strtotime($getYear.'-'.$mo.'-'.$currentDay));						
						foreach ($theDays as $day) {
							if ($day['day'] == $theDay) {
								echo Html::a($day['code'], '@web/tours/r/'.$day['id']);
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
</div>
