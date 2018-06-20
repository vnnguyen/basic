<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;


$xrateUSD = 1.25;

$this->title = 'Tour bán được theo tháng ('.count($theBookings).')';

$kq = [];

?>

<div class="col-md-12">
	<form class="well well-sm form-inline mb15">
		<?= Html::dropdownList('type_date', $getTypeDate, ['m' => 'month', 'y' => 'Year'], ['class'=>'form-control', 'id' => 'type_date', 'prompt' => 'view']) ?>
		<input name="date" value="<?= isset($getDate)? $getDate: ''?>" type='text' class="form-control datepicker-here" id="selme" data-min-view="<?= ($getTypeDate == 'm')? 'months': 'years'?>" data-view="<?= ($getTypeDate == 'm')? 'months': 'years'?>"
		data-date-format="<?= ($getTypeDate == 'm')? 'yyyy-mm': 'yyyy'?>" data-language="en" data-position="bottom left"  placeholder="Date select"readonly/>
		<?= Html::dropdownList('destination', '', ['' => 'Dest', 'vi'=>'VietNam', 'lao'=>'Lao', 'cam'=>'Campodia'], ['class'=>'form-control', 'id' => 'destination', 'multiple' => true]) ?>
		<?= Html::dropdownList('sale_status', $getStatus, ['op'=>'Active', 'nop'=>'Draft'], ['class'=>'form-control', 'id' => 'sale_status', 'prompt' => 'status']) ?>
		<?= Html::dropdownList('seller', $getSeller, ArrayHelper::map($sellerList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Người bán']) ?>
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
			<h6 class="panel-title">Bảng thống kê Tour</h6>
		</div>
		<div class="table-responsive">
		<table class="table table-hover table-striped table-xxs table-bordered">
			<thead>
				<tr>
					<th></th>
					<th>Request</th>
					<th>Series</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?
				$sumPax = 0;
				$sumPax_request = 0;
				$sumPax_series = 0;

				$sum_b2b_series = 0;
				$sum_b2b_request = 0;
				$sumTour = 0;

				$sum_tour_in_VN = 0;
				$sum_tour_request_VN = 0;
				$sum_tour_series_VN = 0;

				$sum_tour_in_LAO = 0;
				$sum_tour_request_LAO = 0;
				$sum_tour_series_LAO = 0;

				$sum_tour_in_CAM = 0;
				$sum_tour_request_CAM = 0;
				$sum_tour_series_CAM = 0;

				$sum_tour_in_ASC = 0;
				$sum_tour_request_ASC = 0;
				$sum_tour_series_ASC = 0;
				$data_table = [];
				foreach ($theBookings as $booking) {
					// var_dump($booking['case']['stats']['pa_destinations']);
					$sumTour ++;
					$pax = 0;
					$stype = '';
					if ($booking['report']) {
						$pax = $booking['report']['pax_count'];
					} else {
						$pax = $booking['pax'];
					}
					$sumPax += $pax; // is b2b
					if ($booking['case']['stype'] == 'b2b-series') {

						$stype = 'series';
						$sum_b2b_series++;
						$sumPax_series += $pax;
					}
					if ($booking['case']['stype'] == 'b2b') {

						$stype = 'request';
						$sum_b2b_request++;
						$sumPax_request += $pax;
					}
					// var_dump($booking);
					if ($booking['case']['stats']['pa_destinations'] != '') {
						$arr_destination = explode(',', $booking['case']['stats']['pa_destinations']);
						if (count($arr_destination) > 0) {
							if (in_array('vn', $arr_destination)) {
								$sum_tour_in_VN ++;
								if ($stype == 'series')	$sum_tour_series_VN ++;
								if ($stype == 'request') $sum_tour_request_VN ++;
							}
							if (in_array('kh', $arr_destination)) {
								$sum_tour_in_CAM ++;
								if ($stype == 'series') $sum_tour_series_CAM ++;
								if ($stype == 'request') $sum_tour_request_CAM ++;
							}
							if (in_array('la', $arr_destination)) {
								$sum_tour_in_LAO ++;
								if ($stype == 'series') $sum_tour_series_LAO ++;
								if ($stype == 'request') $sum_tour_request_LAO ++;
							}
							if (count($arr_destination) > 1) {
								$sum_tour_in_ASC ++;
								if ($stype == 'series') $sum_tour_series_ASC ++;
								if ($stype == 'request') $sum_tour_request_ASC ++;
							}
						}
					}
					// sl tour
					$data_table['sl_tour']['series'] = $sum_b2b_series;
					$data_table['sl_tour']['request'] = $sum_b2b_request;
					$data_table['sl_tour']['total'] = $sumTour;
					// sl pax
					$data_table['sl_pax']['series'] = $sumPax_series;
					$data_table['sl_pax']['request'] = $sumPax_request;
					$data_table['sl_pax']['total'] = $sumPax;
					// vn
					$data_table['VN']['series'] = $sum_tour_series_VN;
					$data_table['VN']['request'] = $sum_tour_request_VN;
					$data_table['VN']['total'] = $sum_tour_in_VN;
					// lao
					$data_table['LAO']['series'] = $sum_tour_series_LAO;
					$data_table['LAO']['request'] = $sum_tour_request_LAO;
					$data_table['LAO']['total'] = $sum_tour_in_LAO;
					//cam
					$data_table['CAM']['series'] = $sum_tour_series_CAM;
					$data_table['CAM']['request'] = $sum_tour_request_CAM;
					$data_table['CAM']['total'] = $sum_tour_in_CAM;
					//asc tour
				};
				
				?>
				<tr>
					<td>SL tour</td>
					<td><?= $sum_b2b_request?></td>
					<td><?= $sum_b2b_series?></td>
					<td><?= $sumTour?></td>
				</tr>
				<tr>
					<td>SL pax</td>
					<td><?= $sumPax_request?></td>
					<td><?= $sumPax_series?></td>
					<td><?= $sumPax?></td>
				</tr>
				<tr>
					<td>VN</td>
					<td><?= $sum_tour_request_VN?></td>
					<td><?= $sum_tour_series_VN?></td>
					<td><?= $sum_tour_in_VN?></td>
				</tr>
				<tr>
					<td>LAO</td>
					<td><?= $sum_tour_request_LAO?></td>
					<td><?= $sum_tour_series_LAO?></td>
					<td><?= $sum_tour_in_LAO?></td>
				</tr>
				<tr>
					<td>CAM</td>
					<td><?= $sum_tour_request_CAM?></td>
					<td><?= $sum_tour_series_CAM?></td>
					<td><?= $sum_tour_in_CAM?></td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
	<div id="export">
		<a href="/b2b/report/export_data?data=<?= urlencode(serialize($data_table))?>" title="Export data" class="btn btn-primary" id="exportData">Export to excel</a>
	</div>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart1);
		function drawChart1() {
			var data = google.visualization.arrayToDataTable([
				['Bán hàng', 'Tỉ lệ lãi gộp'],
				['abxc', 24],
				['abxc', 24],
				['abxc', 24],
				['abxc', 24],
				['abxc', 24],
				['abxc', 24],
				['abxc', 24],
				['abxc', 24],
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

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);

$js = <<<TXT
$('.popovers').popover();
$('#destination').select2();
var date_fill = $('#selme').val();
var datepicker1 = $('#selme').datepicker({
	onRenderCell: function (date, cellType) {
		if (this.view == 'months') {
			var currentMonth = date.getMonth();
	        return {
	                html: currentMonth+1
	            }
		}
    },
}).data('datepicker');

	datepicker1.update('altField', $('#selme').val());
	$('#type_date').change(function(){
		if ($(this).val() == 'm') {
			datepicker1.view = 'months';
			datepicker1.update('minView', 'months');
			datepicker1.update({
				dateFormat: 'yyyy-mm',
				onRenderCell: function (date, cellType) {
					var currentMonth = date.getMonth();
			        return {
			                html: currentMonth+1
			            }
		    	},
			});
		} else {
			datepicker1.view= 'years';
			datepicker1.update('minView', 'years');
			datepicker1.update({
				dateFormat: 'yyyy',
				onRenderCell: function (date, cellType) {
					var currentYear = date.getFullYear();
			        return {
			                html: currentYear
			            }
		    	},
			});
		}
	});
	$(document).ready(function(){
		if (date_fill != '') {
			$('#selme').val(date_fill);
			// datepicker1.date = new Date(date_fill);
			datepicker1.selectDate(new Date(date_fill));
		}
	});
	// $('#exportData').click(function(){
	// 	$.ajax({
 //            method: 'GET',
 //            url: '/b2b/report/export_data',
 //            // data: {ct_id: ex_id},
 //            dataType: 'json'
 //        }).done(function(response){
 //            console.log(response);
 //            var last_item = $('#wrap_extensions').find('.item:last');
 //            $(last_item).append('<div class="ext_content"></div>');
 //            jQuery.each(response, function(index, item){
 //                var day = index + 1;
 //                var span = '<span>Day '+day+': '+item.name+' ('+item.meals+')</span><br/>';
 //                $(last_item).find('.ext_content').append(span);
 //            });
 //        });
	// });

TXT;

$this->registerJs($js);
