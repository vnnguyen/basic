<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
Yii::$app->params['body_class'] = 'sidebar-xs';
$this->title = 'Tour thành công kết thúc';
$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports', '@web/manager/reports'],
	['Case open', '@web/manager/reports/case_open'],
];
$data = json_encode($result);
$info = [];
if (isset($_GET)) {
	$info = $_GET;
}
$param_url = $_SERVER['QUERY_STRING'];
$xRates = [
	'USD' => 1,
	'VND' => 22725,
	'EUR' => 1.15,
	'LAK' => 0.00012,
	'KHR' => 0.00024
];
?>
<div class="col-md-12">
	<div class ="search">
		<div id="wrap_search" class="hidden">
			<form method="get" action="" class="form-horizontal panel-search">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center"><?= Yii::t('app', 'Destinations');?></label>
						<div class="col-md-10">
							<?= Html::dropDownList('destination', $getDestinations, ArrayHelper::map($tourCountryList, 'code', 'name_en'), ['class' => 'selectpicker form-control', 'multiple' => true]) ?>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center"><?= Yii::t('app', 'Destination option');?></label>
						<div class="col-md-10">
							<select class="form-control" name="destselect">
								<option value="all" selected=""><?= Yii::t('app', 'All selected countries');?></option>
								<option value="any"><?= Yii::t('app', 'Any selected countries');?></option>
								<option value="only"><?= Yii::t('app', 'Only selected countries');?></option>
							</select>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center"><?= Yii::t('app', 'Number Days');?></label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="number_day" value="<?= $getNumberDay ?>" placeholder="Search number days" autocomplete="off">
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center"><?= Yii::t('app', 'Ages');?></label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="age" value="<?= $getAge ?>" placeholder="Search Age" autocomplete="off">
					    </div>
					</div>
				</div>
				<!-- <div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Prospect</label>
						<div class="col-md-10">

					    </div>
					</div>
				</div> -->

		        <div class=" text-right">
		        	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		        	<?= Html::a('Reset', '@web/report/tour_end',['class' => 'btn btn-default']) ?>
		        </div>
		    </form>
		</div>
		<a id="search_btn" title="" class="pull-right">Search</a>
		<p class="wrap_text_search">
			<?//= ($found != '')? '<span class="text_search">How found: <strong>'.$found.'</strong></span>' : '' ?>
		</p>
	</div>
	<div class="report_content">
		<div class="col-md-12">
			<div id="chart1" data-source='<?=$data?>'></div>
		</div>
		<p><strong>MONTH VIEW</strong></p>
		<ul class="nav nav-tabs mb-1em" data-tabs="tabs" id="btn-group">
			<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
			<li class="<?= $yr == date('Y') ? 'active' : ''?>"><a data-toggle="tab" href="#year<?= $yr ?>"><?= $yr ?></a></li>
			<? } ?>
		</ul>
		<div id="tab-content" class="tab-content">
			<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
			<div id="year<?= $yr ?>" class="<?= $yr == date('Y') ? 'active' : '' ?> tab-pane">
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
							<th class="text-center"><?= $mo ?></th>
							<? } ?>
							<th class="text-center">Total</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php
								$t_totals = 0;
								$dt_totals = 0;
								$cp_totals = 0;
								$ln_totals = 0;
							?>
							<? for ($mo = 1; $mo <= 12; $mo ++) {
								$totalTour = isset($result[$yr][$mo]['t_total']) ? $result[$yr][$mo]['t_total']: 0;
								if ($totalTour > 0) {
									$day_count_total = isset($result[$yr][$mo]['day_count']) ? $result[$yr][$mo]['day_count']: 0;
									$pax_count_total = isset($result[$yr][$mo]['pax_count']) ? $result[$yr][$mo]['pax_count']: 0;
									$tour_month = $result[$yr][$mo];
									$t_totals += $totalTour;
									if (isset($tour_month['t_dt'], $tour_month['t_cost']) && $tour_month['t_dt'] != 0 && $tour_month['t_cost'] != 0) 
									{
										$xrateToUSD = 0;
										$arr_dtUSD = [];
										$arr_cpUSD = [];
										$ln = 0;
										$Average_dt_per_tour = 0;
										$Average_cost_per_tour = 0;
										$Average_ln_per_tour = 0;
										if (is_array($tour_month['t_dt']) && !empty($tour_month['t_dt'])) {
											foreach ($tour_month['t_dt'] as $key => $v) {
												if (isset($xRates[$key])) {
													$arr_dtUSD[] = $v / $xRates[$key];
												} else {
													echo 'Not Found xRates'.$key;
												}
											}
										}
										if (is_array($tour_month['t_cost']) && !empty($tour_month['t_cost'])) {
											foreach ($tour_month['t_cost'] as $k => $num) {
												if (isset($xRates[$k])) {
													$arr_cpUSD[] = $num / $xRates[$k];
												} else {
													echo 'Not Found xRates'.$k;
												}
											}
										}
										if (empty($arr_dtUSD)) {
											$arr_dtUSD[] = 0;
										}
										if (empty($arr_cpUSD)) {
											$arr_cpUSD[] = 0;
										}
										$arr_dtUSD = array_sum($arr_dtUSD);
										$arr_cpUSD = array_sum($arr_cpUSD);
										$ln = $arr_dtUSD - $arr_cpUSD;
										$dt_totals += $arr_dtUSD;
										$cp_totals += $arr_cpUSD;
										$ln_totals += $ln;
										$Average_dt_per_tour = $arr_dtUSD / $totalTour;
										$Average_cost_per_tour = $arr_cpUSD / $totalTour;
										$Average_ln_per_tour = $ln / $totalTour;
									}
									$Average_day_count_per_tour = $day_count_total / $totalTour;
									$Average_pax_count_per_tour = $pax_count_total / $totalTour;
									if (isset($Average_dt_per_tour, $Average_pax_count_per_tour) && $Average_pax_count_per_tour > 0) {
										$dt_pax_per_tour = $Average_dt_per_tour / $Average_pax_count_per_tour;
										if ($Average_pax_count_per_tour > 0 && $Average_day_count_per_tour) {
											$dt_pax_per_tour_per_day = ($Average_dt_per_tour / $Average_pax_count_per_tour) / $Average_day_count_per_tour;
										}
									}
								} else {
									$xrateToUSD = 0;
									$arr_dtUSD = 0;
									$arr_cpUSD = 0;
									$ln = 0;
									$Average_dt_per_tour = 0;
									$Average_cost_per_tour = 0;
									$Average_ln_per_tour = 0;
									$Average_pax_count_per_tour = 0;
									$Average_day_count_per_tour = 0;
									$dt_pax_per_tour = 0;
									$dt_pax_per_tour_per_day = 0;
								}
								// if ($yr == 2017 && $mo == 6) {
								// 		var_dump($result[$yr][$mo]);
								// 	}
								if ($mo < 10) {
									$mo = '0'.$mo;
								}

								$param = (trim($param_url) != '') ? '&'.$param_url: '';
							?>
							<td class="text-center">
								<div>T <br><?= Html::a($totalTour, '/tours/index?month='.$yr.'-'.$mo.$param) ?></div>
								<div>
								DT <br>
								<?= (isset($arr_dtUSD)) ? Html::a(number_format($arr_dtUSD, 2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': 0?>
								</div>
								<div>
								COST <br>
								<?= (isset($arr_cpUSD)) ? Html::a(number_format($arr_cpUSD, 2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': 0?>
								</div>
								<div>
								LN <br>
								<?= (isset($ln)) ? Html::a(number_format($ln, 2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': 0?>
								<div>
								DT/T <br>
								<?= (isset($Average_dt_per_tour)) ? Html::a(number_format($Average_dt_per_tour, 2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': 0?>
								</div>
								<div>
								COST/T <br>
								<?= (isset($Average_cost_per_tour)) ? Html::a(number_format($Average_cost_per_tour, 2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': 0?>
								</div>
								<div>
								LN/T <br>
								<?= (isset($Average_ln_per_tour)) ? Html::a(number_format($Average_ln_per_tour, 2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': 0?>
								</div>
								<div>
								DC/T <br>
								<?= (isset($Average_day_count_per_tour)) ? Html::a(round($Average_day_count_per_tour), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">day</span><br>': ''?>
								</div>
								<div>
								PAX/T <br>
								<?= (isset($Average_pax_count_per_tour)) ? Html::a(number_format($Average_pax_count_per_tour, 2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">pax</span><br>': ''?>
								</div>
								<div>
								DT/T/PAX <br>
								<?= (isset($dt_pax_per_tour)) ? Html::a(number_format($dt_pax_per_tour,2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': ''?>
								</div>
								<div>
								DT/T/PAX/D <br>
								<?= (isset($dt_pax_per_tour_per_day)) ? Html::a(number_format($dt_pax_per_tour_per_day,2), '/tours/index?month='.$yr.'-'.$mo.$param). ' <span class="text-muted small">USD</span><br>': ''?>
								</div>
							</td>
							<? } ?>
							<td class="text-center">
								<div>T <br><?= Html::a($t_totals, '/tours/index?'.$param_url);?></div>
								<div>DT <br><?= Html::a(number_format($dt_totals, 2), '/tours/index?'.$param_url);?></div>
								<div>COST <br><?= Html::a(number_format($cp_totals, 2), '/tours/index?'.$param_url);?></div>
								<div>LN <br><?= Html::a(number_format($ln_totals, 2), '/tours/index?'.$param_url);?></div>
								<div>DT/T <br><?= ($t_totals > 0) ? Html::a(number_format(($dt_totals / $t_totals) / 12, 2), '/tours/index?'.$param_url): 0;?></div>
								<div>COST/T <br><?= ($t_totals > 0) ? Html::a(number_format(($cp_totals / $t_totals) / 12, 2), '/tours/index?'.$param_url): 0;?></div>
								<div>LN/T <br><?= ($t_totals > 0) ? Html::a(number_format(($ln_totals / $t_totals) / 12, 2), '/tours/index?'.$param_url): 0;?></div>
							</td>
						</tr>
						<?// } ?>
					</tbody>
				</table>
			</div>
			<? } ?>
		</div>
	</div>
</div>
<?php
$js = <<<'TXT'
		var wrap_grap = document.getElementById('chart1');
		var D_SOURCE = $(wrap_grap).data('source');
		var data_grap = {};
      	var years = [];
      	$.each(D_SOURCE, function(index, item) {
			var obj = [];
			years.push(index);
			obj.push(['Month', 'Cases won', 'Cases fail', 'Cases Pending']);
			for (var mo = 1; mo <= 12; mo++) {
          		var totalCase = (item[mo] != undefined && item[mo]['c_total'] != undefined)? parseInt(item[mo]['c_total']): 0;
          		var caseWon = (item[mo] != undefined && item[mo]['c_won'] != undefined)? parseInt(item[mo]['c_won']): 0;
          		var caseFail = (item[mo] != undefined && item[mo]['c_fail'] != undefined)? parseInt(item[mo]['c_fail']): 0;
          		var casePending = (item[mo] != undefined && item[mo]['c_pending'] != undefined)? parseInt(item[mo]['c_pending']): 0;
          		obj.push([(mo).toString(), caseWon, caseFail, casePending]);
			}
			data_grap[parseInt(index)] = obj;
		});
		google.charts.load('current', {'packages':['corechart', 'bar']});
      	google.charts.setOnLoadCallback(drawChart1);

		function drawChart1() {
			var y = $year;
			var data = {};
			var min_y = years[0];
			var max_y = 0;
			for(var i = 0; i < years.length; i ++){
				data[years[i]] = google.visualization.arrayToDataTable(data_grap[years[i]]);
				if (i == years.length - 1) {
					max_y = years[i];
				}
			}
	        var options = {
	            // title: 'Case opened, Case success : '+ min_y + ' - ' + max_y,
	          bars: 'vertical',
	          vAxis: {format: 'decimal'},
	          height: 400,
	          colors: [ '#1b9e77', '#d95f02', '#53A8FB'],
	          isStacked: true,
	        };

			var chart = new google.visualization.ColumnChart(wrap_grap);
			chart.draw(data[y], options);
			var btns = document.getElementById('btn-group');
			btns.onclick = function (e) {//console.log(e.target.tagName);
	          if (e.target.tagName === 'A') {
	          	var tab = e.target;
	          	var y = parseInt($(tab).text());
	            chart.draw(data[y], options);
	          }
	        }
		}
		$('#search_btn').click(function(){
			if ($('#wrap_search').hasClass('hidden')) {
				$('#wrap_search').fadeIn(100, function(){
					$('#wrap_search').removeClass('hidden');
				});
				$('.text_search, .report_content').fadeOut(400,function(){
					$(this).hide()
				});
				$(this).text('Hide Search Form');
			} else {
				$('#wrap_search').slideUp(300, function(){
					$('#wrap_search').addClass('hidden');
				});
				$('.text_search, .report_content').fadeIn(400,function(){
					$(this).show()
				});
				$(this).text('Search');
			}
		});
		$('.selectpicker').selectpicker({
			// style: 'btn-default',
			title: 'countries',
			tickIcon: '',
			showIcon: false,
			showTick: false,
			size: 4
		});
TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('https://www.gstatic.com/charts/loader.js', ['depends' => 'yii\web\JqueryAsset']);
// echo str_replace('$result', json_encode($result), $js);
$this->registerJs(str_replace('$year', date('Y'), $js));
?>