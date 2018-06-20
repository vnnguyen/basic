<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Tour tháng '.$month.' ('.number_format(count($theTours)).' tour)';
$this->params['breadcrumb'] = [
	['Tours', '@web/tours'],
	[$month, '@web/tours?month='.$month],
];

$newMonthList = [];
foreach ($monthList as $mo) {
	$newMonthList[$mo['ym']] = $mo['ym'].' ('.$mo['total'].')';
}

$statusList = [
	'active'=>'Active',
	'canceled'=>'Canceled',
];

?>
<div class="col-md-12">
	<form class="well well-sm form-inline">
		<?= Html::dropdownList('month', $month, $newMonthList, ['class'=>'form-control', 'prompt'=>'Start date']) ?>
		<?= Html::dropdownList('fg', $fg, ['f'=>'F tours', 'g'=>'G tours'], ['class'=>'form-control', 'prompt'=>'F/G tours']) ?>
		<?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control', 'prompt'=>'Status']) ?>
		<?= Html::dropdownList('seller', $seller, $sellerList, ['class'=>'form-control', 'prompt'=>'Sellers']) ?>
		<?= Html::dropdownList('operator', $operator, $operatorList, ['class'=>'form-control', 'prompt'=>'Operators']) ?>
		<?= Html::dropdownList('cservice', $cservice, $cserviceList, ['class'=>'form-control', 'prompt'=>'Customer care']) ?>
		<?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search in name']) ?>
		<?= Html::textInput('dayname', $dayname, ['class'=>'form-control', 'placeholder'=>'Search in days']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/tours') ?>
	</form>
	<div class="table-responsive">
		<table id="tourlist" class="table table-condensed table-bordered table-striped">
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
foreach ($theTours as $tour) {
	$sellerOK = true;
	if ($seller != 0) {
		$sellerOK = false;
		foreach ($tour['bookings'] as $booking) {
			if ($booking['createdBy']['id'] == $seller) {
				$sellerOK = true;
			}
		}
	}

	$operatorOK = true;
	if ($operator != 0) {
		$operatorOK = false;
		if ($tour['tour']['operators']) {
			foreach ($tour['tour']['operators'] as $user) {
				if (!in_array($user['id'], [1351, 7756, 9881, 29296, 30554]) && $user['id'] == $operator) {
					$operatorOK = true;
				}
			}
		}
	}

	$cserviceOK = true;
	if ($cservice != 0) {
		$cserviceOK = false;
		if ($tour['tour']['operators']) {
			foreach ($tour['tour']['operators'] as $user) {
				if (in_array($user['id'], [1351, 7756, 9881, 29296, 30554]) && $user['id'] == $cservice) {
					$cserviceOK = true;
				}
			}
		}
	}

	$dayOK = true;
	if (strlen(trim($dayname)) > 2) {
		$dayOK = false;
		foreach ($tour['days'] as $day) {
			if (strpos(\fURL::makeFriendly($day['name'], '-'), \fURL::makeFriendly($dayname, '-')) !== false) {
				$dayOK = true;
				break;
			}
		}
	}

	// FG
	$fgOK = true;
	if (in_array($fg, ['f', 'g']) && substr($tour['tour']['code'], 0, 1) != strtoupper($fg)) {
		$fgOK = false;
	}

	// Status
	$statusOK = true;
	if (($status == 'active' && $tour['tour']['status'] == 'deleted') || ($status == 'canceled' && $tour['tour']['status'] != 'deleted')) {
		$statusOK = false;
	}

	if ($sellerOK && $operatorOK && $cserviceOK && $dayOK && $fgOK && $statusOK) {
?>
				<tr class="tour">
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
							$dd = date('d', strtotime('+ '.$cnt2.' days', strtotime($tour['day_from'])));
							$cnt2 ++;
							echo '<strong>', $dd, ':</strong> ', Html::encode($day['name']), ' <em>', $day['meals'], '</em><br>';
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
						<?= $tour['tour']['status'] == 'deleted' ? '<strong style="color:#c00;">(CXL)</strong> ' : ''?>
						<?= Html::a($tour['tour']['code'].' - '.$tour['tour']['name'], '@web/tours/r/'.$tour['tour']['id']) ?>
					</td>
					<td class="text-nowrap text-center"><?= Html::a($tour['day_count'].' ngày', '@web/tours/services/'.$tour['tour']['id']) ?></td>
					<td class="text-nowrap text-center"><?= Html::a($tour['pax'].' pax', '@web/tours/pax/'.$tour['id']) ?></td>
					<td class="text-nowrap">
<?
	$nameList = [];
	foreach ($tour['bookings'] as $booking) {
		$nameList[] = $booking['createdBy']['name'];
	}
	echo implode(', ', $nameList);
?>
					</td>
					<td>
<?
	$nameList = [];
	if ($tour['tour']['operators']) {
		foreach ($tour['tour']['operators'] as $user) {
			if (!in_array($user['id'], [1351, 7756, 9881, 29296, 30554])) {
				$nameList[] = $user['name'];
			}
		}
	}
	echo implode(', ', $nameList);
?>
					</td>
					<td class="text-nowrap">
<?
	$nameList = [];
	if ($tour['tour']['operators']) {
		foreach ($tour['tour']['operators'] as $user) {
			if (in_array($user['id'], [1351, 7756, 9881, 29296, 30554])) {
				$nameList[] = $user['name'];
			}
		}
	}
	echo implode(', ', $nameList);
?>
					</td>
					<td>
<?
	$nameList = [];
	if (!empty($tourGuides)) {
		foreach ($tourGuides as $guide) {
			if ($guide['tour_id'] == $tour['id']) {
				$nameList[] = $guide['namephone'];
			}
		}
	}
	echo implode(', ', $nameList);
?>
					</td>
				</tr>
<?
	} // if hidden
} // foreach
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
