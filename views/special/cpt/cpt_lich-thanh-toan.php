<?
use yii\helpers\Html;

app\assets\DatetimePickerAsset::register($this);

$theTotal = 0;
foreach ($total as $payable=>$amount) {
	$theTotal += $amount;
}

$chitiet = \Yii::$app->request->get('chitiet');
Yii::$app->params['body_class'] = 'bg-white';
Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Lịch thanh toán chi phí tour '.date('j/n', strtotime($day1)).' - '.date('j/n', strtotime($day2)).' | '.number_format($theTotal).' VND';
Yii::$app->params['page_breadcrumbs'] = [
	['Chi phí tour', 'cpt'],
	['Lịch thanh toán', 'cpt/lich-thanh-toan'],
];
?>

<div class="col-md-12">
	<form class="form-inline mb-20">
		Rate 1 USD = <?= number_format($xRates['USD'], 0) ?> VND | Chọn ngày
		<?= Html::textInput('day1', $day1, ['class'=>'form-control day1']) ?>
		<?= Html::textInput('day2', $day2, ['class'=>'form-control day2']) ?>
		<?= Html::dropdownList('chitiet', $chitiet, ['yes'=>'Diễn giải từng mục', 'no'=>'Chỉ hiện tổng tiền'], ['class'=>'form-control']) ?>
		<?= Html::dropdownList('c3', $c3, ['off'=>'Chưa TT', 'on'=>'Đã TT'], ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', DIR.URI) ?>
	</form>

	<p><strong>XEM THEO NHÀ CUNG CẤP</strong></p>
	<div class="table-responsive">
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="100">Thanh toán cho</th>
					<th width="100">Tổng tiền</th>
					<? if ($chitiet != 'no') { ?>
					<th width="50">Tour</th>
					<th width="100">Dịch vụ</th>
					<th width="100">Đơn giá</th>
					<th width="50">SL</th>
					<th width="150">Tổng</th>
					<? } ?>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<? foreach ($result as $payableto=>$items) { ?>
				<? $cnt = 0 ?>
				<? foreach ($items as $item) { ?>
					<? $cnt ++ ?>
					<? if ($chitiet != 'no' || $cnt == 1) { ?>
				<tr>
					<td class="text-nowrap"><?= $cnt == 1 ? $payableto : '' ?></td>
					<td class="text-right text-nowrap"><?= $cnt == 1 ? number_format($total[$payableto], 0).' VND' : '' ?></td>
					<? if ($chitiet != 'no') { ?>
					<td><?= Html::a($item['tour_code'], '@web/cpt?view=tour-code&tour='.$item['tour_code']) ?></td>
					<td class="text-nowrap"><?= Html::a($item['name'], '@web/cpt/r/'.$item['id']) ?></td>
					<td class="text-right text-nowrap"><?= number_format($item['quantity'] * $item['price'], 2) ?> <span class="text-muted"><?= $item['currency'] ?></span></td>
					<td class="text-nowrap">&times; <?= $item['quantity'] ?> <?= $item['unit'] ?></td>
					<td class="text-right text-nowrap"><?= number_format($item['total'], 0) ?> <span class="text-muted">VND</span></td>
					<? } ?>
					<td></td>
				</tr>
					<? } ?>
				<? } ?>
			<? } ?>
			</tbody>
		</table>
	</div>

	<hr>
	<p><strong>XEM THEO NGÀY</strong></p>
	<div class="table-responsive">
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
				<th width="10%">Thời hạn</th>
				<th width="10%">Tour code</th>
				<th width="35%">Nội dung</th>
				<th width="10%">Ngày dịch vụ</th>
				<th width="15%">Ai trả</th>
				<th width="10%">Điều hành</th>
				<th width="10%">Thanh toán?</th>
				</tr>
			</thead>
			<tbody>
			<?
			$dow = '';
			$due = '';
			$name = '';
			$venue = '';
			foreach ($theCptx as $cpt) {
				if ($due != $cpt['due'] || $name != $cpt['dvtour_name'] || $venue != $cpt['venue_id']) {
			?>
				<tr>
					<td class="text-nowrap"><?= $dow != $cpt['due'] ? Yii::$app->formatter->asDate($cpt['due'], 'php:l, j/n') : '' ?></td>
					<td><?=$cpt['tour_status'] == 'deleted' || $cpt['tour_status'] == 'canceled' ? '<span style="background:red; color:#ffc;">CXL</span> ' : ''?><?= Html::a($cpt['code'], '@web/tours/services/'.$cpt['tour_id'] )?></td>
					<td><?= Html::a($cpt['dvtour_name'], '@web/cpt/r/'.$cpt['dvtour_id']) ?> (<?= $cpt['venue_name'] ?>)</td>
					<td><?= date('j/n/Y', strtotime($cpt['dvtour_day'])) ?></td>
					<td><?=$cpt['payer']?></td>
					<td><?=$cpt['op_name']?></td>
					<td>
					<?=
					$text = '';
					if (substr($cpt['c5'], 0, 2) == 'on') {
						$text = 'một phần';
					}
					if (substr($cpt['c7'], 0, 2) == 'on') {
						$text = 'toàn bộ';
					}
					echo $text;
					?></td>
				</tr>
			<?
				if ($dow != $cpt['due']) {
					$dow = $cpt['due'];
				}
				$due = $cpt['due'];
				$name = $cpt['dvtour_name'];
				$venue = $cpt['venue_id'];
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<?
$js = <<<TXT
$('.day1, .day2').datetimepicker({
	format:'YYYY-MM-DD'
});
TXT;
$this->registerJs($js);