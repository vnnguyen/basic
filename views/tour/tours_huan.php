<?
use yii\helpers\Html;

$this->title = 'Tour tháng '.$month.' ('.number_format(count($theTours)).' tour)';
$this->params['breadcrumb'] = [
	['Tours', '@web/tours'],
	[$month, '@web/tours?month='.$month],
];

?>
<div class="col-md-12">
	<form class="well well-sm form-inline">
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/tours') ?>
	</form>
	<div class="table-responsive">
		<table id="tourlist" class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th width="40">#</th>
					<th width="40">Vào</th>
					<th width="40">Ra</th>
					<th width="">Code - Tên tour - <!--a class="fw-n" href="#" onclick="$('tr.paxLine').toggleClass('hide'); return false;">Ẩn / hiện danh sách khách</a--></th>
					<th width="70">Ngày</th>
					<th width="70">Pax</th>
					<th>Bán hàng</th>
					<th>Điều hành</th>
					<th>CSKH</th>
					<th>Guide MB</th>
				</tr>
			</thead>
			<tbody>
<?
$dayIn = '';
$cnt = 0;
$getStatus = 'all';
$getLanguage = 'all';
foreach ($theTours as $tour) {
	$tour['cs'] = 0;
	$tour['op'] = 0;
	if (1 == 1
	//&& ($getStatus == 'all' || ($getStatus != 'all' && $tour['status'] == $getStatus))
	//&& ($getLanguage == 'all' || ($getLanguage != 'all' && $tour['language'] == $getLanguage))
	//&& ($getSe == 0 || ($getSe != 0 && $tour['se'] == $getSe))
	//&& ($getOp == 0 || ($getOp != 0 && isset($monthTourOpList[$tour['id']]) && in_array($getOp, $monthTourOpList[$tour['id']])))
	//&& ($getCs == 0 || ($getCs != 0 && isset($monthTourCsList[$tour['id']]) && in_array($getCs, $monthTourCsList[$tour['id']])))
	) {}
?>
				<tr>
					<td class="text-center text-muted"><?= ++ $cnt ?></td>
					<td class="text-center"><strong><?
	if ($dayIn != $tour['day_from']) {
		$dayIn = $tour['day_from'];
		echo substr($dayIn, -2);
	}
?></strong>
					</td>
					<td class="text-center"><?= date('d', strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days')) ?></td>
					<td>
						<i class="fa fa-file-text-o popovers pull-right text-muted"
							data-trigger="hover"
							data-title="<?= $tour['title'] ?>"
							data-placement="left"
							data-html="true"
							data-content="
<?
			$dayIds = explode(',', $tour['day_ids']);
			if (count($dayIds) > 0) {
				$cnt2 = 0;
				foreach ($dayIds as $id) {
					foreach ($tour['days'] as $day) {
						if ($day['id'] == $id) {
							$cnt2 ++;
							echo '<strong>', $cnt2, ':</strong> ', str_replace(['"'], ['\''], $day['name']), ' <em>', $day['meals'], '</em><br>';
						}
					}
				}
			}
?>
						"></i>
<?
						$flag = $tour['language'];
						if ($tour['language'] == 'en') $flag = 'us';
						if ($tour['language'] == 'vi') $flag = 'vn';
						echo '<img src="/images/flags/16x11/', $flag,'.png">';
?>
						<?= $tour['status'] == 'deleted' ? '<strong style="color:#c00;">(CXL)</strong> ' : ''?>
						<?= Html::a($tour['tour']['code'].' - '.$tour['tour']['name'], '@web/tours/r/'.$tour['id']) ?>
					</td>
					<td class="text-nowrap text-center"><?= Html::a($tour['day_count'].' ngày', '@web/tours/services/'.$tour['id']) ?></td>
					<td class="text-nowrap text-center"><?= Html::a($tour['pax'].' pax', '@web/tours/pax/'.$tour['id']) ?></td>
					<td>
<?
	$nameList = [];
	foreach ($tour['bookings'] as $booking) {
		$nameList[] = $booking['createdBy']['name'];
	}
	echo implode(', ', $nameList);
?>
					</td>
					<td>
					<?/*
					if (isset($monthTourOpList[$tour['id']])) {
						foreach ($tourOps as $liTO) {
							if (in_array($liTO['id'], $monthTourOpList[$tour['id']])) {
								echo anchor('tours?month='.$getMonth.'&op='.$liTO['id'], $liTO['name']);
								break;
							}
						}
					}*/
?>
					</td>
					<td>
<?/*
					if (isset($monthTourCsList[$tour['id']])) {
						foreach ($tourCss as $liTO) {
							if (in_array($liTO['id'], $monthTourCsList[$tour['id']])) {
								echo anchor('tours?month='.$getMonth.'&cs='.$liTO['id'], $liTO['name']);
								break;
							}
						}
					}*/
?>
					</td>
					<td></td>
				</tr>
<?
}
?>
			</tbody>
		</table>
	</div>
</div>
<style>
.fa-male {color:blue;}
.fa-female {color:purple;}
.form-control.w-auto {width:auto; display:inline;}
</style>
