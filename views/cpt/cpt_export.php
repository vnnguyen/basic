<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_cpt_inc.php');
$this->title = 'Export chi phí tour '.$theTour['code'].' - '.$theTour['name'].' - '.$theTour['product']['pax'].' pax ('.count($theCptx).')';
$this->params['breadcrumb'][] = [$theTour['code'], '@web/cpt?tour='.$theTour['id']];

?>
<div class="col-lg-12">
	<? if (empty($theCptx)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="80">Ngày</th>
					<th>Người</th>
					<th>Nội dung</th>
					<th>Tour</th>
					<th>Bán hàng</th>
					<th>0x</th>
					<th>Acc1</th>
					<th>Acc2</th>
					<th>$$$</th>
					<th>=$$$</th>
				</tr>
			</thead>
			<tbody>
				<?
				$total['all'] = 0;
				$total['vnd'] = 0;
				$total['usd'] = 0;
				$total['eur'] = 0;
				$xrates['usd'] = 21250;
				$xrates['eur'] = 28250;
				$xrates['vnd'] = 1;

				if ($theTour) {
					$dayIdList = explode(',', $theTour['product']['day_ids']);
					$cnt = 0;
					$totalVND = 0;
					foreach ($dayIdList as $di) {
						foreach ($theTour['product']['days'] as $day) {
							if ($day['id'] == $di) {
								$currentDay = date('d-m-Y', strtotime('+'.$cnt.' day', strtotime($theTour['product']['day_from'])));
								$currentDOW = date('d-m-Y D', strtotime('+'.$cnt.' day', strtotime($theTour['product']['day_from'])));
								$cnt ++;
						?>

<?
								foreach ($theCptx as $cpt) {
									if ($currentDay == date('d-m-Y', strtotime($cpt['dvtour_day']))) {
										// BEGIN LINE
										$sign = $cpt['plusminus'] == 'plus' ? 1 : -1;
										$cur = strtolower($cpt['unitc']);
										$total[$cur] += $sign * $cpt['price'] * $cpt['qty'];
										$total['all'] += $xrates[$cur] * $sign * $cpt['price'] * $cpt['qty'];
?>
				<tr>
					<td class=""><?= date('d/m/Y', strtotime($theTour['product']['day_from'])) ?></td>
					<td class=""><?= $cpt['payer'] ?></td>
					<td class=""><?= $cpt['dvtour_name'] ?></td>
					<td class=""><?= $theTour['code'] ?></td>
					<td class=""><?= 'HANGDT' ?></td>
					<td class="">02</td>
					<td class="">1541</td>
					<td class="">3311</td>
					<!--
					<td>
						<? if ($cpt['mm']) { ?>
						<span class="badge popovers pull-right"
							data-trigger="hover"
							data-placement="right"
							data-html="true"
							data-title="Comments"
							data-content="
						<? foreach ($cpt['mm'] as $li2) { ?>
						<div style='margin-bottom:5px'><strong><?= $li2['updatedBy']['name'] ?></strong> <em><?= $li2['uo'] ?></em></div>
						<p><?= nl2br($li2['mm']) ?></p>
						<? } ?>
						"><?= count($cpt['mm']) ?></span>
						<? } ?>
						<? if ($cpt['cp']) { ?>
						<?= Html::a($cpt['cp']['name'], '@web/cp/r/'.$cpt['cp']['id'])?>
						<? } else { ?>
						<?= $cpt['dvtour_name'] ?>
						<? } ?>
						@<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id']) ?>
					</td>
					<!--td>
						<?= Html::a($cpt['cp']['name'], '@web/cp/r/'.$cpt['dv_id'], ['style'=>'color:#060']) ?>
						@<?= Html::a($cpt['cp']['venue']['name'], '@web/venues/r/'.$cpt['cp']['venue']['id'], ['style'=>'color:#f60']) ?>
					</td-->
					<!--
					<td>
						<div class="dropdown">
							<a data-toggle="dropdown" href="#"><?= $cpt['b_status'] ?></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
								<li><a href="#">Draft</a></li>
								<li>Planned</li>
								<li>Ready, pre-booking not needed</li>
								<li>Sent to provider</li>
								<li>Waiting list</li>
								<li>Ready</li>
								<li>Canceled and none</li>
								<li>Canceled and replaced</li>
							</ul>
						</div>
					</td>
					<td><?= $cpt['p_status'] ?></td>
					-->
					<!--td><?= $cpt['adminby'] ?></td-->
					<!--td><?= $cpt['cp']['unit'] ?></td-->
					<td>
						<? if ($cpt['company']) { ?>
						<?= Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['company']['id']) ?>
						<? } else { ?>
						<?= $cpt['oppr'] ?>
						<? } ?>
					</td>
					<td></td>
					<td></td>
				</tr>
<?
										// END LINE
									}
								} // foreach cptx
							}
						}
					}
				} // if theTour
?>
				<tr>
					<td colspan="<?= $theTour ? '6' : '9' ?>" class="text-right">Tổng tiền</td>
					<td class="text-right">
						<? if ($total['vnd'] != 0) { ?>
						<div>
							<span class="text-danger"><strong><?= number_format($total['vnd']) ?></strong></span>
							<span class="text-muted">VND</span>
						</div>
						<? } ?>
						<? if ($total['usd'] != 0) { ?>
						<div>
							<span class="text-warning"><strong><?= number_format($total['usd']) ?></strong></span>
							<span class="text-muted">USD</span>
						</div>
						<? } ?>
						<? if ($total['eur'] != 0) { ?>
						<div>
							<span class="text-info"><strong><?= number_format($total['eur']) ?></strong></span>
							<span class="text-muted">EUR</span>
						</div>
						<? } ?>
					</td>
					<td colspan="3">
						<div class="text-success text-right" style="font-size:28px">
							=
							<strong><?= number_format($total['all']) ?></strong>
							<span class="text-muted">VND</span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<p>Exchange rates: 1 EUR = <?= number_format($xrates['eur'], 0) ?> VND | 1 USD = <?= number_format($xrates['usd']) ?> VND</p>
	</div>
	<? } ?>
</div>
<style>
.label.cpt-gd {background-color:#ccc; color:#fff; cursor:pointer;}
.label.cpt-gd.xacnhan {background-color:#393; color:#fff; cursor:pointer;}
.label.cpt-ktt {background-color:#ccc; color:#fff; cursor:pointer;}
.label.cpt-ktt.xacnhan {background-color:#393; color:#fff; cursor:pointer;}
.label.cpt-tra {background-color:#ccc; color:#fff; cursor:pointer;}
.label.cpt-tra.pct50 {background-color:#cfc; color:#fff;}
.label.cpt-tra.pct100 {background-color:#393; color:#fff;}
.label.cpt-vat {background-color:#ccc; color:#fff; cursor:pointer;}
.label.cpt-vat.pct50 {background-color:#cfc; color:#fff;}
.label.cpt-vat.pct100 {background-color:#393; color:#fff;}
.popover {max-width:700px;}

.form-control.select2-container {height:34px!important;}
	.select2-container .select2-choice {height:32px; line-height:32px; background-image:none!important;}
	.select2-container .select2-choice .select2-arrow {background:none!important;}
</style>
<?
$js = <<<TXT
// KE TOAN CHECK THANH TOAN
$('.cpt-tra').on('click', function(){
	action = 'tra';
	tour_id = $(this).data('tour_id');
	dvtour_id = $(this).attr('rel');
	var span = $(this);
	var formdata = $('#formx').serializeArray();
	$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
			alert(data[1]);
		} else {
			span.removeClass('pct0 pct50 pct100').addClass(data[1]);
		}
	}, 'json');
	return false;
});
$('.cpt-ktt').on('click', function(){
	action = 'ktt';
	tour_id = $(this).data('tour_id');
	dvtour_id = $(this).attr('rel');
	var span = $(this);
	var formdata = $('#formx').serializeArray();
	$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
			alert(data[1]);
		} else {
			span.removeClass('xacnhan').addClass(data[1]);
		}
	}, 'json');
	return false;
});

$('.cpt-gd').on('click', function(){
	action = 'gd';
	tour_id = $(this).data('tour_id');
	dvtour_id = $(this).attr('rel');
	var span = $(this);
	var formdata = $('#formx').serializeArray();
	$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
			alert(data[1]);
		} else {
			span.removeClass('xacnhan').addClass(data[1]);
		}
	}, 'json');
	return false;
});

// KE TOAN DANH DAU VAT
$('.cpt-vat').on('click', function(){
	action = 'vat';
	tour_id = $(this).data('tour_id');
	dvtour_id = $(this).attr('rel');
	var span = $(this);
	var formdata = $('#formx').serializeArray();
	$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
			alert(data[1]);
		} else {
			if (span.hasClass('pct50')) {
				span.removeClass('pct50').addClass('pct100');
			} else if (span.hasClass('pct100')) {
				span.removeClass('pct100');
			} else {
				span.addClass('pct50');
			}
		}
	}, 'json');
});

$('.popovers').popover();
var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substrRegex;
 
    // an array that will be populated with substring matches
    matches = [];
 
    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');
 
    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        // the typeahead jQuery plugin expects suggestions to a
        // JavaScript object, refer to typeahead docs for more info
        matches.push({ value: str });
      }
    });
 
    cb(matches);
  };
};
 
var states = [{venues}];
 
$('.typeahead').typeahead({
	hint: true,
	highlight: true,
	minLength: 1
},
{
  name: 'states',
  displayKey: 'value',
  source: substringMatcher(states)
});
TXT;

$js = <<<TXT
$('.select2').select2();
TXT;
$this->registerCssFile(DIR.'assets/select2_3.5.0/select2.css', ['depends'=>'app\assets\MainAsset']);
$this->registerCssFile(DIR.'assets/select2_3.5.0/select2-bootstrap.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/select2_3.5.0/select2.min.js', ['depends'=>'app\assets\MainAsset']);

$this->registerCssFile(DIR.'assets/typeahead.js_0.10.4/typeaheadjs.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/typeahead.js_0.10.4/typeahead.bundle.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);