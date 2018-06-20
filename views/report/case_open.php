<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
$arr_title = [
	'month_open' => 'Hồ Sơ mở theo tháng',
	'month_end' => 'Hồ Sơ theo tháng tours kết thúc',
	'month_start' => 'Hồ Sơ theo tháng tours đi',
];
$this->title = $arr_title[$case_type];
$this->params['breadcrumb'] = [];
$data = json_encode($result);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css');
$this->registerCss('
	.text_search::after { content: " , "; }
	.text_search:last-child::after { content: ""; }
	.text-center { text-align: center !important}
	.table th{ width: 5%}
	.won_case, .lost_case, .pending_case { font-size: 12px}
	table td .won_case {color: #1B9E77;}
	table td .lost_case {color: #D95F02;}
	table td .pending_case {color: #53A8FB;}
');
include('_kase_inc.php');
$info = $_GET;
?>
<div class="col-md-12">
	<div class ="search">
		<div id="wrap_search" class="hidden">
			<form method="get" action="" class="form-horizontal panel-search">
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label col-md-1 text-center">Report type</label>
						<div class="col-md-2">
							<?= Html::dropdownList('case_type', $case_type, [
					            'month_open'=> 'Month cases open',
					            'month_end'=> 'Month tours finished',
					            'month_start'=>'Month tours start',
					        ], ['class'=>'form-control']) ?>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Prospect</label>
						<div class="col-md-10">
							<?= Html::dropdownList('prospect', $getProspect, [
					            'all'=>'Prospect',
					            '1'=>'1 star',
					            '2'=>'2 stars',
					            '3'=>'3 stars',
					            '4'=>'4 stars',
					            '5'=>'5 stars',
					        ], ['class'=>'form-control']) ?>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label text-center col-md-2">Owner</label>
						<div class="col-md-10">
							<select class="form-control" name="owner_id">
					            <option value="all">All owners</option>
					            <optgroup label="Sellers in Vietnam">
					                <? foreach ($ownerList as $case) { ?>
					                <option value="<?= $case['id'] ?>" <?php if($case['id'] == $getOwnerId) {
					                	echo 'selected="selected"';
					                	$info['lname'] = $case['lname'];
					                	$info['email'] = $case['email'];
					                } ?>><?= $case['lname'] ?>, <?= $case['email'] ?></option>
					                <? } ?>
					            </optgroup>
					            <optgroup label="Sellers in France">
					                <option value="cofr-13" <?= 'cofr-13' == $getOwnerId ? 'selected="selected"' : '' ?>>Hoa (Hoa Bearez)</option>
					                <option value="cofr-5246" <?= 'cofr-5246' == $getOwnerId ? 'selected="selected"' : '' ?>>Arnaud (Arnaud Levallet)</option>
					                <option value="cofr-1769" <?= 'cofr-1769' == $getOwnerId ? 'selected="selected"' : '' ?>>Trân (Cao Lê Trân)</option>
					                <option value="cofr-767" <?= 'cofr-767' == $getOwnerId ? 'selected="selected"' : '' ?>>Cô Xuân (Vương Thị Xuân)</option>
					                <option value="cofr-688" <?= 'cofr-688' == $getOwnerId ? 'selected="selected"' : '' ?>>Frédéric (Frédéric Hoeckel)</option>
					            </optgroup>
					        </select>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Device</label>
						<div class="col-md-10">
							<?= Html::dropdownList('device', $getDevice, [
					            'all'=>'Device',
					            'desktop'=>'desktop',
					            'tablet'=>'tablet',
					            'mobile'=>'mobile',
					            'none'=>'none',
					        ], ['class'=>'form-control']) ?>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Site</label>
						<div class="col-md-10">
							<?= Html::dropdownList('site', $getSite, [
					            'all'=>'Contact via site',
					            'fr'=>'FR',
					            'vac'=>'VAC',
					            'val'=>'VAL',
					            'vpc'=>'VPC',
					            'ami'=>'AMI',
					            'en'=>'EN',
					        ], ['class'=>'form-control']) ?>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">How contacted</label>
						<div class="col-md-10">
							<?= Html::dropdownList('contacted', $contacted, $caseHowContactedListFormatted, ['class'=>'form-control', 'prompt'=>Yii::t('k', 'How customer contacted us')]) ?>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">How found</label>
						<div class="col-md-10">
							<?= Html::dropdownList('found', $found, $caseHowFoundListFormatted, ['class'=>'form-control', 'prompt'=>Yii::t('k', 'How customer found us')]) ?>
					    </div>
					</div>
				</div>
        		<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Language</label>
						<div class="col-md-10">
							<select class="form-control" name="language">
					            <option value="all">All languages</option>
					            <option value="en" <?= $getLanguage == 'en' ? 'selected="selected"' : ''?>>English</option>
					            <option value="fr" <?= $getLanguage == 'fr' ? 'selected="selected"' : ''?>>Francais</option>
					            <option value="vi" <?= $getLanguage == 'vi' ? 'selected="selected"' : ''?>>Tiếng Việt</option>
					        </select>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Campaigns</label>
						<div class="col-md-10">
							<select class="form-control" name="campaign_id">
					            <option value="all">Campaigns</option>
					            <option value="0"  <?= $getCampaignId == '0' ? 'selected="selected"' : '' ?>>No campaign</option>
					            <option value="yes"  <?= $getCampaignId == 'yes' ? 'selected="selected"' : '' ?>>Any campaign</option>
					            <? foreach ($campaignList as $case) { ?>
					            <option value="<?= $case['id'] ?>" <?= $case['id'] == $getCampaignId ? 'selected="selected"' : '' ?>><?= date('d/m/Y', strtotime($case['start_dt'])) ?>: <?= $case['name'] ?></option>
					            <? } ?>
					        </select>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Number Days</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="number_day" value="<?= $getNumberDay ?>" placeholder="Search number days" autocomplete="off">
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Number paxs</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="number_pax" value="<?= $getNumberPax ?>" placeholder="Search number paxs" autocomplete="off">
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Destinations</label>
						<div class="col-md-10">
							<?= Html::dropDownList('destination', $getDestinations, ArrayHelper::map($tourCountryList, 'code', 'name_en'), ['class' => 'selectpicker form-control', 'multiple' => true]) ?>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-2 text-center">Destination option</label>
						<div class="col-md-10">
							<select class="form-control" name="destselect">
								<option value="all" selected="">All selected countries</option>
								<option value="any">Any selected countries</option>
								<option value="only">Only selected countries</option>
							</select>
					    </div>
					</div>
				</div>

		        <div class=" text-right">
		        	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		        		<?= Html::a('Reset', '@web/report/case_open',['class' => 'btn btn-default']) ?>
		        </div>
		    </form>
		</div>
		<a id="search_btn" title="" class="pull-right">Search</a>
		<p class="wrap_text_search">
			<?= ($getProspect != '' && $getProspect != 'all')? '<span class="text_search">Prospect: <strong>'.$getProspect.'</strong></span>' : '' ?>
			<?= (isset($getDestinations) && !empty($getDestinations))? '<span class="text_search">Destinations: <strong>'.implode(',', $getDestinations).'</strong></span>' : '' ?>
			<?= ($getDevice != 'all')? '<span class="text_search">Device: <strong>'.$getDevice.'</strong></span>' : '' ?>
			<?= ($getSite != 'all')? '<span class="text_search">Site: <strong>'.$getSite.'</strong></span>' : '' ?>
			<?= ($getNumberDay != '')? '<span class="text_search">Number days tour: <strong>'.$getNumberDay.'</strong></span>' : '' ?>
			<?= ($getOwnerId != '' && $getOwnerId != 'all')? '<span class="text_search">Owner: <strong>'.$info['lname'].'</strong></span>' : '' ?>
			<?= ($found != '')? '<span class="text_search">How found: <strong>'.$found.'</strong></span>' : '' ?>
			<?= ($getNumberPax != '')? '<span class="text_search">Number paxs: <strong>'.$getNumberPax.'</strong></span>' : '' ?>
			<?= ($getLanguage != 'all')? '<span class="text_search">Language: <strong>'.$getLanguage.'</strong></span>' : '' ?>
		</p>
	</div>
	<div class="clearfix">	</div>
	<div class="report_content">
		<div class="col-md-8">
			<div id="chart" data-source='<?=$data?>'></div>
		</div>
		<div class="col-md-4">
			<div id="piechart" style="height: 400px"></div>
		</div>
		<div class="clearfix"></div>
		<p><strong>CASE IN MONTH VIEW</strong></p>
		<ul class="nav nav-tabs mb-1em click_tab" data-tabs="tabs" id="btn-group">
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
								$totals = 0;
								$totalsWon = 0;
								$totalsFail = 0;
								$totalsPending = 0;

							?>
							<? for ($mo = 1; $mo <= 12; $mo ++) {
								$totalCase = isset($result[$yr][$mo]['c_total']) ? $result[$yr][$mo]['c_total']: 0;
								$c_won = isset($result[$yr][$mo]['c_won']) ? $result[$yr][$mo]['c_won'] : 0;
								$c_fail = $result[$yr][$mo]['c_fail'];
								$c_pending = $result[$yr][$mo]['c_pending'];
								$totals += $totalCase;
								$totalsWon += $c_won;
								$totalsFail += $result[$yr][$mo]['c_fail'];
								$totalsPending += $result[$yr][$mo]['c_pending'];
								$w_text = '<span class="won_case">W-</span>';
								$l_text = '<span class="lost_case">L-</span>';
								$p_text = '<span class="pending_case">P-</span>';
							?>
							<td class="text-center">
								<div style="color:brown; font-weight:bold; font-size:120%;">
								<?//= ($totalCase > 0) ? number_format(100 * $c_won / $totalCase, 2) : '-' ?> <!-- % -->
								</div>
								<div><?= ($totalCase > 0) ? 'T-':''?><?= $totalCase;?>  <br/>
									<?= $w_text;?><?= $c_won;?> <br/>
									<?= $l_text;?><?= $c_fail;?> <br/>
									<?= $p_text;?><?= $c_pending;?>
								</div>
							</td>
							<? } ?>
							<td class="text-center">
								<div>T-<?= $totals;?> <br/>
									<?= $w_text;?><?= $totalsWon;?> <br/>
									<?= $l_text;?><?= $totalsFail;?> <br/>
									<?= $p_text;?><?= $totalsPending;?>
								</div>
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
	var wrap_grap = document.getElementById('chart');
	var D_SOURCE = $(wrap_grap).data('source');
	var CHART1, CHART3;
	var DATA_CHART1, DATA_CHART3;
	var OPTION_CHART1, OPTION_CHART3;
	var data_grap = {};
	var data_grap2 = {};
  	var years = [];
  	$.each(D_SOURCE, function(index, item) {
		var obj = [];
		var obj2 = [];
		var c_won = 0, c_fail = 0,  c_pending = 0;
		years.push(index);
		obj.push(['Month', 'Won', 'Lost', 'Pending']);
		obj2.push(['task', 'Total cases']);
		for (var mo = 1; mo <= 12; mo++) {
      		var totalCase = (item[mo] != undefined && item[mo]['c_total'] != undefined)? parseInt(item[mo]['c_total']): 0;
      		var caseWon = (item[mo] != undefined && item[mo]['c_won'] != undefined)? parseInt(item[mo]['c_won']): 0;
      		var caseFail = (item[mo] != undefined && item[mo]['c_fail'] != undefined)? parseInt(item[mo]['c_fail']): 0;
      		var casePending = (item[mo] != undefined && item[mo]['c_pending'] != undefined)? parseInt(item[mo]['c_pending']): 0;
      		c_won += caseWon;
      		c_fail += caseFail;
      		c_pending += casePending;
      		obj.push([(mo).toString(), caseWon, caseFail, casePending]);
		}
		obj2.push(['Won', c_won]);
		obj2.push(['Lost', c_fail]);
		obj2.push(['Pending', c_pending]);
		data_grap[parseInt(index)] = obj;
		data_grap2[parseInt(index)] = obj2;
	});
	google.charts.load('current', {'packages':['corechart', 'bar']});
  	google.charts.setOnLoadCallback(drawChart);
  	google.charts.setOnLoadCallback(drawChart2);


	function drawChart() {
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
		DATA_CHART1 = data;
        var options = {
			chart: {
			title: 'Case opened, Case success : '+ min_y + ' - ' + max_y,
			// subtitle: 'Case opened, Case success : '+ min_y + ' - ' + max_y,
			},
			bars: 'vertical',
			legend: { position: 'top'},
			bar: { groupWidth: '65%' },
			isStacked: true,
			chartArea:{left: 80, width:'85%',height:'65%'},
			vAxis: {format: 'decimal'},
			height: 400,
			colors: ['#1b9e77', '#d95f02', '#53A8FB'],

        };
		OPTION_CHART1 = options;
		CHART1 = new google.visualization.ColumnChart(wrap_grap);
		CHART1.draw(data[y], options);
		var btns = $('.click_tab');
		$(document).on('click', $(btns), function (e) {
          if (e.target.tagName === 'A') {
          	var tab = e.target;
          	var y = parseInt($(tab).text());
            CHART1.draw(data[y], options);
          }
        });
	}

	function drawChart2() {
		var y = $year;
		var data = {};
		var min_y = years[0];
		var max_y = 0;
		for(var i = 0; i < years.length; i ++){
			data[years[i]] = google.visualization.arrayToDataTable(data_grap2[years[i]]);
			if (i == years.length - 1) {
				max_y = years[i];
			}
		}
		DATA_CHART3 = data;
		var options = {
		  title: 'Total this year ' + y,
		  colors: ['#1b9e77', '#d95f02', '#53A8FB'],
		  chartArea:{ width:'85%',height:'65%'},
		  legend: {position: 'none'} 
		  // is3D: true
		};
		OPTION_CHART3 = options;
		CHART3 = new google.visualization.PieChart(document.getElementById('piechart'));

		CHART3.draw(data[y], options);
		var btnss = $('.click_tab');
		$(document).on('click', $(btnss), function (e) {
          if (e.target.tagName === 'A') {
          	var tab = e.target;
          	var y = parseInt($(tab).text());
          	var options = {
			  title: 'Total this year ' + $(tab).text(),
			  colors: ['#1b9e77', '#d95f02', '#53A8FB'],
			  chartArea:{ width:'85%',height:'65%'},
			  legend: {position: 'none'} 
			};
            CHART3.draw(data[y], options);
          }
        });
	}

	$('#search_btn').click(function(){
		if ($('#wrap_search').hasClass('hidden')) {
			$('#google-visualization-errors-all-1, #google-visualization-errors-all-3, #google-visualization-errors-all-5').empty();
			$('#wrap_search').fadeIn(100, function(){
				$('#wrap_search').removeClass('hidden');
			});
			$('.text_search, .report_content').fadeOut(400,function(){
				$('.text_search, .report_content').hide()
			});
			$(this).text('Hide Search Form');
		} else {
			$('#wrap_search').slideUp(300, function(){
				$('#wrap_search').addClass('hidden');
			});
			$(this).text('Search');
			$('.text_search, .report_content').fadeIn(400,function(){
				$('#google-visualization-errors-all-1, #google-visualization-errors-all-3, #google-visualization-errors-all-5').empty();
				$('.text_search, .report_content').show()
			});
		}
	});
	// $('.click_tab').click(function(e){
	// 	var clicked = $(this);
	// 	var li_clicked = $(e.target).closest('li');
	// 	var li_index = li_clicked.index();
	// 	var other_tabs = $('.click_tab').not($(clicked));
	// 	$.each(other_tabs, function(ind, item){
	// 		var old_a = $(item).find('li.active a');
	// 		var div_id = $(old_a).attr('href');
	// 		$(div_id).removeClass('active');
	// 		$(item).find('li.active').removeClass('active');
	// 		var c_li = $(item).find('li:eq('+li_index+')').addClass('active');
	// 		var new_a = $(c_li).find('a');
	// 		var div_id = $(new_a).attr('href');
	// 		$(div_id).addClass('active');
	// 	});
	// });
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
$this->registerJs(str_replace('$year', date('Y'), $js));
?>