<?
use yii\helpers\Html;

$this->title = 'Lịch khách trả tiền tour';
$this->params['breadcrumb'] = [['Tools', '@web/tools']];

$dow = ['Hai', 'Ba', 'Tư', 'Năm', 'Sáu', 'Bảy', 'CN'];

$getYear = Yii::$app->request->get('year', 2014);

?>
<div class="col-md-12">
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
						foreach ($theInvoices as $invoice) {
							if (substr($invoice['due_dt'], 0, 10) == $theDay) {
								$stay .= '<div>';
								$stay .= Html::a($invoice['ref'], '@web/invoices/r/'.$invoice['id'], ['rel'=>'external']);
								$stay .= '<br><span class="';
								if ($invoice['status'] == 'paid') {
									$stay .= 'text-success';
								} else {
									if (strtotime('now') > strtotime($invoice['due_dt'])) {
										$stay .= 'text-danger';
									}
								}
								$stay .= '">'.number_format($invoice['amount'], 2).'</span> '.$invoice['currency'].'</div>';
							}
						}
?>								
<strong style="color:#ccc" class="pull-right"><?= $currentDay ?></strong>
<?
						if ($stay != '') {
							echo $stay;
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