<?
use yii\helpers\Html;
?>
<div class="panel">
	<div class="panel-heading">
		<form class="form-inline">
			<?= Html::label('Date');?>
            <?= Html::input('text', 'dt', '', ['class'=>'form-control', 'id' => 'dt']) ?>
            <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
        </form>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table-tight table table-bordered table-xxs" id="tbl_review">
				<thead>
					<tr>
						<th rowspan="2" class="text-center" width="100"></th>
						<th rowspan="2" class="text-center">Kenh</th>
						<th colspan="4" class="text-center">New</th>
						<th colspan="3" class="text-center">Referral</th>
						<th colspan="2" class="text-center">Return</th>
					</tr>
					<tr>
						<th class="text-center">2015</th>
						<th class="text-center">2016</th>
						<th class="text-center">2017</th>
						<th class="text-center">2018</th>
						<th class="text-center">2016</th>
						<th class="text-center">2017</th>
						<th class="text-center">2018</th>
						<th class="text-center">2016</th>
						<th class="text-center">2017</th>
					</tr>
				</thead>
				<tbody>
					<?php
					ksort($data_users);
					foreach ($data_users as $user_id => $d_user) {
						$name = isset($arr_user_name[$user_id])? $arr_user_name[$user_id]: $user_id;
						$x = 1;
						$rowspan = count($d_user);
						ksort($d_user);
						foreach ($d_user as $kx => $data) {
					?>
					<tr>
					<?php if ($x == 1) { ?>
						<td class="text-center" rowspan="<?= $rowspan;?>"><?= $name?></td>
					<?php } ?>
						<td class="text-center"><?= $kx?></td>
						<td class="text-center"><?= isset($data['new'][2015]) ? $data['new'][2015]: 0?></td>
						<td class="text-center"><?= isset($data['new'][2016]) ? $data['new'][2016]: 0?></td>
						<td class="text-center"><?= isset($data['new'][2017]) ? $data['new'][2017]: 0?></td>
						<td class="text-center"><?= isset($data['new'][2018]) ? $data['new'][2018]: 0?></td>
						<td class="text-center"><?= isset($data['ref'][2016]) ? $data['ref'][2016]: 0?></td>
						<td class="text-center"><?= isset($data['ref'][2017]) ? $data['ref'][2017]: 0?></td>
						<td class="text-center"><?= isset($data['ref'][2018]) ? $data['ref'][2018]: 0?></td>
						<td class="text-center"><?= isset($data['returning'][2016]) ? $data['returning'][2016]: 0?></td>
						<td class="text-center"><?= isset($data['returning'][2017]) ? $data['returning'][2017]: 0?></td>
					</tr>
					<?
							$x ++;
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?
$js = <<<'TXT'
$('#dt').datepicker({
    firstDay: 1,
    todayButton: true,
    clearButton: true,
    autoClose: true,
    range: false,
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