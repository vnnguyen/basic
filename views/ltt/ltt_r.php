<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_ltt_inc.php');
$approvedBy = [];
$this->title = 'Lượt thanh toán cpt: #'.$theLtt['payment_ref'];

$statusDetail = explode(';', $theLtt['status_detail']);
foreach (array_keys($statusList) as $item) {
	$lttStatus[$item] = false;
}
foreach ($statusDetail as $item) {
	$item2 = explode('|', $item);
	if (count($item2) == 3 && isset($statusList[$item2[0]])) {
		$lttStatus[$item2[0]] = [
			'user'=>$item2[1],
			'time'=>$item2[2],
		];
	}
}

// \fCore::expose($lttStatus);
?>
<style>
.label.cpt {cursor:pointer; color:#fff;}
.label.cpt.on {background-color:#393;}
.label.cpt.off {background-color:#ccc;}
.label.cpt.off.dirty {background-color:#baa;}
.popover {max-width:700px;}
table#table-ltt-mtt a.add-cpt-to-ltt {display:none;}
table#table-search-cpt a.remove-cpt-from-ltt {display:none;}
table#table-search-cpt a.edit-mtt {display:none;}
</style>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-4">
			<table class="table table-bordered table-condensed">
				<tr><td><strong>Số CT:</strong> <strong class="text-danger"><?= $theLtt['payment_ref'] ?></strong></td></tr>
				<tr><td><strong>Ngày TT:</strong> <?= date('j/n/Y', strtotime($theLtt['payment_dt'])) ?></td></tr>
				<tr><td><strong>TT cho:</strong> <?= $theLtt['payment_to'] ?></td></tr>
				<tr><td><strong>Phương thức:</strong> <?= $methodList[$theLtt['payment_method']] ?></td></tr>
				<tr><td><strong>Tài khoản:</strong> <?= $theLtt['payment_account'] ?></td></tr>
				<tr><td><strong>Số tiền / loại tiền:</strong> <strong class="text-danger" id="ltt_amount"><?= number_format($theLtt['amount'], 2) ?></strong> <?= $currencyList[$theLtt['currency']] ?></td></tr>
			</table>
		</div>
		<div class="col-md-8">
			<table class="table table-bordered table-condensed">
				<tr><td><strong>Tình trạng:</strong> <strong class="text-danger"><?= $statusList[$theLtt['status']] ?></strong></td></tr>
				<tr>
					<td><strong>KT đề nghị TT:</strong>
						<? if (!isset($lttStatus[1]['user'], $lttStatus[1]['time'])) { ?>
						<?= Html::a('Click', '@web/ketoan/ltt/r/'.$theLtt['id'].'?action=status&status=1') ?>
						<? } else { ?>
						<?= $ketoan[$lttStatus[1]['user']] ?>
						<?= DateTimeHelper::convert($lttStatus[1]['time'], 'j/n/Y H:i') ?>
						<? } ?>
					</td>
				</tr>
				<tr>
					<td><strong>KTT duyệt Đề nghị TT:</strong>
						<? if (!isset($lttStatus[2]['user'], $lttStatus[2]['time'])) { ?>
						<?= Html::a('Click', '@web/ketoan/ltt/r/'.$theLtt['id'].'?action=status&status=2') ?>
						<? } else { ?>
						<?= $ketoan[$lttStatus[2]['user']] ?>
						<?= DateTimeHelper::convert($lttStatus[2]['time'], 'j/n/Y H:i') ?>
						<? } ?>
					</td></tr>
				<tr>
					<td><strong>GĐ đồng ý TT:</strong>
						<? if (!isset($lttStatus[3]['user'], $lttStatus[3]['time'])) { ?>
						<?= Html::a('Click', '@web/ketoan/ltt/r/'.$theLtt['id'].'?action=status&status=3') ?>
						<? } else { ?>
						<?= $ketoan[$lttStatus[3]['user']] ?>
						<?= DateTimeHelper::convert($lttStatus[3]['time'], 'j/n/Y H:i') ?>
						<? } ?>
					</td></tr>
				<tr>
					<td><strong>KT thanh toán:</strong>
						<? if (!isset($lttStatus[4]['user'], $lttStatus[4]['time'])) { ?>
						<?= Html::a('Click', '@web/ketoan/ltt/r/'.$theLtt['id'].'?action=status&status=4') ?>
						<? } else { ?>
						<?= $ketoan[$lttStatus[4]['user']] ?>
						<?= DateTimeHelper::convert($lttStatus[4]['time'], 'j/n/Y H:i') ?>
						<? } ?>
					</td></tr>
				<tr>
					<td><strong>KTT xác nhận:</strong>
						<? if (!isset($lttStatus[5]['user'], $lttStatus[5]['time'])) { ?>
						<?= Html::a('Click', '@web/ketoan/ltt/r/'.$theLtt['id'].'?action=status&status=5') ?>
						<? } else { ?>
						<?= $ketoan[$lttStatus[5]['user']] ?>
						<?= DateTimeHelper::convert($lttStatus[5]['time'], 'j/n/Y H:i') ?>
						<? } ?>
					</td></tr>
			</table>
		</div>
	</div>
	<p><strong>GHI CHÚ:</strong> <?= nl2br($theLtt['note']) ?></p>
	<p><strong>CÁC MỤC THANH TOÁN TRONG LƯỢT NÀY</strong></p>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed" id="table-ltt-mtt">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th>Tour</th>
					<th width="100">Day</th>
					<th>Name @Venue $Provider</th>
					<th>Qty</th>
					<th>Unit</th>
					<th>$$$</th>
					<th>=$$$</th>
					<th>Payment by</th>
					<th title="Xác nhận">XN</th>
					<th>Check (new)</th>
					<th>Check TT</th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($theLtt['mtt'])) { ?><tr id="tr-none-found"><td colspan="12">Chưa có mục nào</td></tr><? } else { ?>
<?
					$total['all'] = 0;
					$total['vnd'] = 0;
					$total['usd'] = 0;
					$total['eur'] = 0;
					$xrates['usd'] = 21250;
					$xrates['eur'] = 28250;
					$xrates['vnd'] = 1;

					$dayCnt = 0;
					$currentDay = '';
					$total['all'] = 0;
					$total['vnd'] = 0;
					$total['usd'] = 0;
					$total['eur'] = 0;
					$xrates['usd'] = 21250;
					$xrates['eur'] = 28250;
					$xrates['vnd'] = 1;
					foreach ($theLtt['mtt'] as $mtt) {
						$cpt = $mtt['cpt'];
$title = [];
foreach ($check as $k=>$v) {
	if ($cpt[$k] == '') {
		$status = 'off';
		$user = false;
		$time = false;
		$title[$k] = '';
	} else {
		$parts = explode(',', $cpt[$k]);
		$status = $parts[0];
		$user = isset($ketoan[$parts[1]]) ? $ketoan[$parts[1]] : '?';
		$time = DateTimeHelper::convert($parts[2], 'j/n/Y H:i', 'UTC', 'Asia/Ho_Chi_Minh');
		$title[$k] = ' : '.$user.' @ '.$time;
	}
}
						$sign = $cpt['plusminus'] == 'plus' ? 1 : -1;
						$cur = strtolower($cpt['unitc']);
						$total[$cur] += $sign * $cpt['price'] * $cpt['qty'];
						$total['all'] += $xrates[$cur] * $sign * $cpt['price'] * $cpt['qty'];
				?>
				<tr id="tr-<?= $cpt['dvtour_id'] ?>">
					<td class="text-muted text-center"><?= Html::a($cpt['dvtour_id'], '@web/tours/mm/'.$cpt['tour_id'].'/'.$cpt['dvtour_id'], ['class'=>'text-muted']) ?></td>
					<td><?= Html::a($cpt['tour']['code'], '@web/tours/r/'.$cpt['tour']['id']) ?></td>
					<td class="text-nowrap"><?= date('d-m-Y D', strtotime($cpt['dvtour_day'])) ?></td>
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
						<span title="<?= $cpt['updatedBy']['name'] ?> @ <?= date('j/n/Y H:i', strtotime($cpt['uo'])) ?>"><?= $cpt['dvtour_name'] ?></span>
						@<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id']) ?>

						<? if ($cpt['company']) { ?>
						$<?= Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['company']['id']) ?>
						<? } else { ?>
							<? if ($cpt['oppr'] != '') { ?>
						$<?= $cpt['oppr'] ?>
							<? } ?>
						<? } ?>
					</td>
					<td class="text-center"><?= rtrim(rtrim($cpt['qty'], '0'), '.') ?></td>
					<td class="text-center text-muted"><?= $cpt['unit'] ?></td>
					<td class="text-right"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= rtrim(rtrim(number_format($cpt['price'], 2), '0'), '.') ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
					<td class="text-right text-danger"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= rtrim(rtrim(number_format($cpt['price'] * $cpt['qty'], 2), '0'), '.') ?> <span class="text-muted"><?= $cpt['unitc'] ?></td>
					<td class="text-right"><?= number_format($mtt['amount'], 2) ?></td>
					<td>
						<?
						$cpt['approved_by'] = trim($cpt['approved_by'], '[');
						$cpt['approved_by'] = trim($cpt['approved_by'], ':]');
						$ids = explode(':][', $cpt['approved_by']);
						$apprCnt = 0;
						$apprName = [];
						foreach ($ids as $id2) {
							foreach ($approvedBy as $user) {
								if ($user['id'] == (int)$id2) {
									$apprCnt ++;
									$apprName[] = $user['name'];
								}
							}
						}
						if ($apprCnt > 0) {
						?><span class="badge badge-info" title="Xác nhận: <?= implode(', ', $apprName) ?>"><?= $apprCnt ?></span><?
						}
						?>
					</td>
					<td class="text-nowrap">
						<small title="Check 1<?= $title['c1'] ?>" data-action="c1" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c1 <?= $cpt['c1'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c1'], 'on') !== false ? 'on' : 'off' ?>">C1</small>
						<small title="Check 2<?= $title['c2'] ?>" data-action="c2" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c2 <?= $cpt['c2'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c2'], 'on') !== false ? 'on' : 'off' ?>">C2</small>
						<small title="Th/toan<?= $title['c3'] ?>" data-action="c3" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c3 <?= $cpt['c3'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c3'], 'on') !== false ? 'on' : 'off' ?>">TT</small>
						<small title="Duyet!!<?= $title['c4'] ?>" data-action="c4" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c4 <?= $cpt['c4'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c4'], 'on') !== false ? 'on' : 'off' ?>">DZ</small>
					</td>
					<td class="text-nowrap">
						<!--
						<small title="Đã đặt cọc<?= $title['c5'] ?>" data-action="c5" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c5 <?= $cpt['c5'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c5'], 'on') !== false ? 'on' : 'off' ?>">DC</small>
						<small title="KTT xác nhận đặt cọc<?= $title['c6'] ?>" data-action="c6" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c6 <?= $cpt['c6'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c6'], 'on') !== false ? 'on' : 'off' ?>">DC!</small>
						<small title="Đã thanh toán<?= $title['c7'] ?>" data-action="c7" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c7 <?= $cpt['c7'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c7'], 'on') !== false ? 'on' : 'off' ?>">TT</small>
						<small title="KTT xác nhận thanh toán<?= $title['c8'] ?>" data-action="c8" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c8 <?= $cpt['c8'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c8'], 'on') !== false ? 'on' : 'off' ?>">TT!</small>
						-->
						<a class="add-cpt-to-ltt" data-tr="<?= $cpt['dvtour_id'] ?>" href="javascript:;">+ Thêm</a>
						<a class="edit-mtt" data-tr="<?= $cpt['dvtour_id'] ?>" href="/ketoan/mtt/u/<?= $mtt['id'] ?>">Sửa</a>
						<a class="remove-cpt-from-ltt text-danger" data-tr="<?= $cpt['dvtour_id'] ?>" href="javascript:;">- Bỏ</a>
					</td>
				</tr><?
					}
				} // if empty ?>
			</tbody>
		</table>
	</tbody>

	<? if ($theLtt['status'] == '' || $theLtt['status'] == '0') { ?>

	<p><strong>TÌM VÀ THÊM MỤC THANH TOÁN</strong></p>

	<form class="form-inline well well-sm" id="form-search-cpt">
		<?= Html::textInput('tour', '', ['class'=>'form-control', 'placeholder'=>'Code/ID/tháng tour']) ?>
		<?= Html::textInput('search', '', ['class'=>'form-control', 'placeholder'=>'Nhà cung cấp/Dịch vụ']) ?>
		<?= Html::submitButton('Tìm', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Đặt lại', '@web/cpt/r/'.$theLtt['id'], ['id'=>'a-search-reset']) ?>
		|
		<a href="#" onclick="$('#help').toggle(); return false;">Chỉ dẫn</a>
	</form>
	<div class="alert alert-info" style="display:none;" id="help">
		<strong>Chỉ dẫn</strong> Cách chọn xem các dịch vụ
		<br>- Nhà cung cấp: môt phần hoặc toàn bộ tên nhà cung cấp (kể cả được link hay do nhập tay).
		<br>- Tour: tháng khởi hành, một phần hoặc toàn bộ code, hoặc toàn bộ ID tour. Tháng khởi hành phải có dạng yyyy-mm, vd 2016-01 (tháng khởi hành), F1510 (môt phần code), F1509051 (toàn bộ code), 12780 (ID). Chú ý: có thể ra nhiều tour.
		<br><strong>Chú ý thêm</strong>
		<br>- Click vào số ID (cột đầu tiên của mỗi dòng) để xem / thêm / xoá các ghi chú, và xem chi tiết ai check gì vào lúc nào
	</div>
	<div class="table-responsive" id="div-search-results"></div>

	<? } // if status 0 ?>

</div>
<?
$js = <<<'TXT'
$('div#div-search-results').on('click', 'a.add-cpt-to-ltt', function(){
	var cpt_id = $(this).data('tr');
	var jqxhr = $.post('/ketoan/ltt/ajax', {
		'action':'add-cpt-to-ltt',
		'ltt_id':$ltt_id,
		'cpt_id':cpt_id,
	})
	.done(function(data) {
		// $('tr#tr-none-found').remove();
		$('tr#tr-' + cpt_id).appendTo('table#table-ltt-mtt tbody');
		$('tr#tr-' + cpt_id + ' a.edit-mtt').attr('href', '/ketoan/mtt/u/' + data.mtt_id);
		$('#ltt_amount').html(data.ltt_amount);
	})
	.fail(function() {
		alert('Có lỗi!');
	});

	return false;
});

$('table#table-ltt-mtt').on('click', 'a.remove-cpt-from-ltt', function(){
	var cpt_id = $(this).data('tr');
	var jqxhr = $.post('/ketoan/ltt/ajax', {
		'action':'remove-cpt-from-ltt',
		'ltt_id':$ltt_id,
		'cpt_id':cpt_id,
	})
	.done(function(data) {
		$('tr#tr-' + cpt_id).remove();
		$('#ltt_amount').html(data.ltt_amount);
		// $('tr#tr-' + cpt_id).prependTo('table#table-search-cpt tbody');
	})
	.fail(function() {
		alert('Có lỗi!');
	});

	return false;
});
$('a#a-search-reset').click(function(){
	$('form#form-search-cpt input[type=text]').val('');
	$('table#table-search-cpt').remove();
	return false;
});
$("#form-search-cpt").submit(function(event){
	var search = $('input[name=search]').val();
	var tour = $('input[name=tour]').val();
	event.preventDefault();
	var jqxhr = $.post('/ketoan/ltt/ajax', {
		action:'search-cpt',
		search:search,
		tour:tour,
	})
	.done(function(data) {
		$('div#div-search-results').empty().html(data);
	})
	.fail(function() {
		alert('Có lỗi!');
	});
});
TXT;
$js = str_replace(['$ltt_id'], [$theLtt['id']], $js);
$this->registerJs($js);