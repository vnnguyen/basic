<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$xrateUSD = 1.25;

$this->title = 'Tour bán được theo tháng ('.count($theBookings).')';

$kq = [];

?>

<div class="col-md-12">
	<form class="well well-sm form-inline mb15">
		<?= Html::dropdownList('khoihanh', $getKhoihanh, ArrayHelper::map($listKhoiHanh, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Khởi hành']) ?>
		<?= Html::dropdownList('bantour', $getBantour, ArrayHelper::map($listBanTour, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Bán tour']) ?>
		<?= Html::dropdownList('seller', $getSeller, ArrayHelper::map($sellerList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Người bán']) ?>
		<?= Html::dropdownList('currency', $getCurrency, ['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND'], ['class'=>'form-control', 'prompt'=>'Loại tiền']) ?>
		<!-- <?= Html::dropdownList('b2b', $getB2b, ['all'=>'B2B/B2C', 'b2b'=>'B2B only', 'b2c'=>'B2C only'], ['class'=>'form-control']) ?> -->
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/bookings/reports') ?>
	</form>
	<!-- <div class="alert alert-info">
		<strong>CHÚ Ý</strong>
		Tour bán bằng USD sẽ được quy đổi sang EUR theo tỉ giá 1 EUR = <?= $xrateUSD ?> USD và có ghi chú giá gốc bên cạnh.
		Các booking chưa có báo cáo tuy vẫn hiện ra nhưng không được tính vào tổng.
	</div> -->

	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">title</h6>
		</div>
		<div class="panel-body">
			<div id="chart1" style="width:100%; height:400px;"></div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Bảng thống kê doanh thu</h6>
		</div>
		<div class="table-responsive">
		<table class="table table-hover table-striped table-xxs table-bordered">
			<thead>
				<tr>
					<th width="10"></th>
					<th width="40">Report</th>
					<th width="40">Ngày cfm</th>
					<th>Tour và ngày khởi hành</th>
					<th width="40" class="text-center">Ngày</th>
					<th width="40" class="text-center">Pax</th>
					<th>N/pax</th>
					<th>Tiền</th>
					<th>D/thu</th>
					<th>C/phí</th>
					<th>Dt/np</th>
					<th>Cp/np</th>
					<th>L/gộp</th>
					<th>DTQĐ</th>
					<th>P%LG</th>
					<th>HSBH</th>
					<th>DT / CP USD</th>
				</tr>
			</thead>
			<tbody>
				<?
				$sumNgay = 0;
				$sumPax = 0;
				$sumNgayPax = 0;
				$sumDoanhThu = 0;
				$sumChiPhi = 0;
				$avgDoanhThuNgayPax = 0;
				$avgChiPhiNgayPax = 0;
				$sumLaiGop = 0;
				$sumDoanhThuQuyDoi = 0;
				$avgPhanTramLaiGop = 0;

				$cnt = 0;
				
				foreach ($theBookings as $booking) {
					if ($getB2b == 'all' || ($getB2b == 'b2b' && $booking['case']['is_b2b'] == 'yes') || ($getB2b == 'b2c' && $booking['case']['is_b2b'] == 'no')) {
						$doanhThuUSD = 0;
						$chiPhiUSD = 0;
						if ($booking['report']) {
							$ngay = $booking['report']['day_count'];
							$pax = $booking['report']['pax_count'];
							$ngayPax = $ngay * $pax;
							if ($booking['report']['price_unit'] == 'USD') {
								$doanhThu = $booking['report']['price'] / $xrateUSD;
								$chiPhi = $booking['report']['cost'] / $xrateUSD;
								$doanhThuUSD = $booking['report']['price'];
								$chiPhiUSD = $booking['report']['cost'];
							} else {
								$doanhThu = $booking['report']['price'];
								$chiPhi = $booking['report']['cost'];
							}
							$loaiTien = $booking['report']['price_unit'];
						} else {
							$ngay = $booking['product']['day_count'];
							$pax = $booking['pax'];
							$ngayPax = $ngay * $pax;
							$doanhThu = 10;//$booking['price'];
							$chiPhi = 10;//rand(890, 1280);
							$loaiTien = $booking['currency'];
						}

						$doanhThuNgayPax = $ngayPax == 0 ? 0 : $doanhThu / $ngayPax;
						$chiPhiNgayPax = $ngayPax == 0 ? 0 : $chiPhi / $ngayPax;
						$laiGop = $doanhThu - $chiPhi;
						$doanhThuQuyDoi = $laiGop * 5;
						$phanTramLaiGop = $doanhThu == 0 ? 0 : 100 * $laiGop / $doanhThu;

						if ($booking['report']) {
							$sumNgay += $ngay;
							$sumPax += $pax;
							$sumNgayPax += $ngayPax;
							$sumDoanhThu += $doanhThu;
							$sumChiPhi += $chiPhi;
						}
						$cnt ++;
				?>
				<tr>
					<td class="text-muted text-center"><?= $cnt ?></td>
					<td class="text-center" style="white-space:nowrap">
						<? if (!$booking['report']) { ?>
						<?= Html::a('+Report', '@web/bookings/report/'.$booking['id'], ['style'=>'color:red']) ?>
						<? } else { ?>
						<?= Html::a('Report', '@web/bookings/report/'.$booking['id']) ?>
						<? } ?>
					</td>
					<td class="text-nowrap"><?= substr($booking['status_dt'], 0, 10) ?></td>
					<td class="text-nowrap">
						<? if ($booking['note'] != '') { ?>
							<i class="fa fa-file-text-o pull-left text-muted popovers"
								data-toggle="popover"
								data-trigger="hover"
								data-title="<?= $booking['product']['title'] ?>"
								data-html="true"
								data-content="<?= Html::encode($booking['note']) ?>"></i>
						<? } ?>
						<?= Html::a($booking['product']['tour']['code'], '@web/tours/r/'.$booking['product']['tour']['id'], ['style'=>'background-color:#ffc; color:#060; padding:0 5px;', 'title'=>$booking['product']['title']]) ?>
						<? if ($booking['finish'] == 'canceled') { ?>
						<span class="label label-warning">CXL</span>
						<? } ?>					
						<?= $booking['product']['day_from'] ?>
					</td>
					<td class="text-center"><?= $ngay ?></td>
					<td class="text-center"><?= $pax ?></td>
					<td class="text-center"><?= $ngayPax ?></td>
					<td class="text-center">EUR<?//= $loaiTien ?></td>
					<td class="text-right"><?= number_format($doanhThu, 0) ?></td>
					<td class="text-right"><?= number_format($chiPhi, 0) ?></td>
					<td class="text-right"><?= number_format($doanhThuNgayPax, 2) ?></td>
					<td class="text-right"><?= number_format($chiPhiNgayPax, 2) ?></td>
					<td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($laiGop, 0) ?></td>
					<td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($doanhThuQuyDoi, 0) ?></td>
					<td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($phanTramLaiGop, 2) ?>%</td>
					<td>
						<?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?>
						<?= $booking['case']['owner']['name'] ?>
					</td>
					<td class="text-right"><?
					if ($doanhThuUSD != 0) {
						echo number_format($doanhThuUSD), ' / ', number_format($chiPhiUSD), ' USD'; 
					}
					?>
					</td>
				</tr>
				<?
						if (isset($kq[$booking['case']['owner']['name']])) {
							$kq[$booking['case']['owner']['name']]['count'] ++;
							$kq[$booking['case']['owner']['name']]['pct'] += $phanTramLaiGop;
							$kq[$booking['case']['owner']['name']]['avg'] = $kq[$booking['case']['owner']['name']]['pct'] / $kq[$booking['case']['owner']['name']]['count'];
						} else {
							$kq[$booking['case']['owner']['name']] = [
								'avg'=>0,
								'count'=>1,
								'pct'=>$phanTramLaiGop,
							];
						}
					} // is b2b
				} // for each bookings
				?>
				<tr>
					<th colspan="4">Total</th>
					<th class="text-center"><?= $sumNgay ?></th>
					<th class="text-center"><?= $sumPax ?></th>
					<th class="text-center"><?= $sumNgayPax ?></th>
					<th class="text-center">EUR</th>
					<th class="text-right"><?= number_format($sumDoanhThu, 0) ?></th>
					<th class="text-right"><?= number_format($sumChiPhi, 0) ?></th>
					<th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumDoanhThu / $sumNgayPax, 2) ?></th>
					<th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumChiPhi / $sumNgayPax, 2) ?></th>
					<th class="text-right"><?= number_format($sumDoanhThu - $sumChiPhi, 0) ?></th>
					<th class="text-right"><?= number_format(5 * ($sumDoanhThu - $sumChiPhi), 0) ?></th>
					<th class="text-right"><?= $sumDoanhThu == 0 ? 0 : number_format(100 * ($sumDoanhThu - $sumChiPhi) / $sumDoanhThu, 2) ?>%</th>
					<th></th>
					<th></th>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart1);
		function drawChart1() {
			var data = google.visualization.arrayToDataTable([
				['Bán hàng', 'Tỉ lệ lãi gộp'],
				<?
				arsort($kq);
				$cnt = 0;
				foreach ($kq as $k=>$v) {
					$cnt ++;
					if ($cnt != 1) {
						echo ', ';
					}
				?>
				['<?= $k ?>', <?= number_format($v['pct'] / $v['count'], 2) ?>]
				<?
				}
				?>
			]);

			var options = {
				hAxis: {title: 'Bán hàng'},
				chartArea:{left:0,top:0,width:"100%",height:"80%"},
				//legend:{position: 'none'},
				//hAxis:{textPosition: 'none'},
				colors:['green'],
			};

			var chart = new google.visualization.ColumnChart(document.getElementById('chart1'));
			chart.draw(data, options);
		}
	</script>
</div>
<style type="text/css">
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>
<?
$js = <<<TXT
$('.popovers').popover();
TXT;
$this->registerJs($js);
