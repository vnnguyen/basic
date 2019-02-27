<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;


?>

<form class="well well-sm form-inline">
	<?= Html::dropDownList('stype_zone', $stype_zone, $data_zones, ['class'=>'form-control select_c', 'placeholder' => 'Hotel', 'prompt' => 'Select zone']) ?>
	<?= Html::dropDownList('venue_id', $venue_id, $data_venue, ['class'=>'form-control select_c', 'placeholder' => 'Hotel', 'prompt' => 'Select hotel']) ?>
	<?= Html::textInput('tour_code', $tour_code, ['class'=>'form-control', 'placeholder' => 'Tour_code']) ?>
	<?= Html::textInput('date_range', $date_range, ['class'=>'form-control', 'placeholder' => 'Date', 'id' => 'dt_range']) ?>
	<?= Html::dropDownList('stype_count', $stype_count, ['all' => 'All', 1 => 'Ngủ đêm', 2 => 'Ăn', 3 => 'Tham quan'], ['class'=>'form-control']) ?>
	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	<?= Html::a('Reset', '@web/demo/report_hotel') ?>
</form>

<div class="col-md-6 panel">
	<div class="panel-body">
		<div class="table-responsive">
		<table class="table table-hover table-striped table-condensed table-bordered">
			<thead>
				<tr><th width="5"></th>
					<th width="10">Tour code</th>
					<th width="40">Ngày/đêm</th>
					<th width="40">Khách</th>
					<th width="40">Tiền</th>
				</tr>
			</thead>
			<tbody>
				<?
				$i = 0;
				$sum_room = 0;
				$sum_pax = 0;
				$sum_currency = [];
				foreach ($arr_data as $code => $cnt) {
					$i++;
					$sum_room += $cnt['room'];
					$sum_pax += $cnt['pax'];
					foreach ($cnt['currency'] as $unitc => $v) {
						if (!isset($sum_currency[$unitc])) {
							$sum_currency[$unitc] = 0;
						}
						$sum_currency[$unitc] += $v;
					}
				?>
				<tr>
					<td class="text-center"><?= $i?></td>
					<td class="text-left"><?= $code?></td>
					<td class="text-right"><?= $cnt['room']?></td>
					<td class="text-right"><?= $cnt['pax']?></td>
					<td class="text-right">
						
					<?
					foreach ($cnt['currency'] as $unitc => $v) {
						echo '<div> '.number_format($v).' <span class="small text-muted"> '.$unitc.'</span> </div>';
					}
					?>
					</td>
				</tr>

				<?
				}
				?>
				<tr>
					<td></td>
					<td class="text-left"></td>
					<td class="text-right"><?= $sum_room?></td>
					<td class="text-right"><?= $sum_pax?></td>
					<td class="text-right">
					<?
					foreach ($sum_currency as $uc => $sum_c) {
						echo '<div> '.number_format($sum_c).' <span class="small text-muted"> '.$uc.'</span></div>';
					}
					?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	</div>
</div>
<?
$js = <<<'TXT'
$('.select_c').select2();
$('#dt_range').datepicker({
    firstDay: 1,
    todayButton: true,
    clearButton: true,
    autoClose: true,
    range: true,
    multipleDatesSeparator: ' - ',
    language: 'en',
    dateFormat: 'yyyy/mm/dd'
});


TXT;
$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
?>