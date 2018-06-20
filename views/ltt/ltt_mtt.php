<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_ltt_inc.php');

$this->title = 'Sửa các mục thanh toán cho #'.$theLtt['payment_ref'];

// Array for filters
$filterArray = [
	'v'=>[],
	'vc'=>[],
	'bc'=>[],
	'p'=>[],
	'n'=>[],
];
foreach ($theCptx as $s) {
	if (!isset($filterArray['p'][md5($s['payer'])])) $filterArray['p'][md5($s['payer'])] = $s['payer'];
	if ($s['venue_id'] != 0) {
		if (!isset($filterArray['v'][md5($s['venue_id'])])) $filterArray['v'][md5($s['venue_id'])] = $s['venue']['name'];
	} elseif ($s['via_company_id'] != 0) {
		//if (!isset($filterArray['vc'][md5($s['via_company_id'])])) $filterArray['vc'][md5($s['via_company_id'])] = $s['via_company']['name'];
	} elseif ($s['by_company_id'] != 0) {
		//if (!isset($filterArray['bc'][md5($s['by_company_id'])])) $filterArray['bc'][md5($s['by_company_id'])] = $s['by_company']['name'];
	} else {
		if (!isset($filterArray['n'][md5($s['oppr'])])) $filterArray['n'][md5($s['oppr'])] = $s['oppr'];
	}
}

$ketoan = [
	'1'=>'Ngọc Huân',
	'4065'=>'Anh Tuấn',
	'28431'=>'Tú Phương',
	'11'=>'Thu Hiền',
	'17'=>'Đức Hạnh',
	'16'=>'Tr. Thị Lan',
	'20787'=>'Thanh Bình',
	'20787'=>'Thanh Huyền',
	'30085'=>'Đ. Thị Ngọc',
];
$check = [
	'c1'=>'CHECK-1',
	'c2'=>'CHECK-2',
	'c3'=>'TH/TOAN',
	'c4'=>'DUYET',

	'c5'=>'DC',
	'c6'=>'DC-OK',
	'c7'=>'TT',
	'c8'=>'TT-OK',
	'c9'=>'KTT',
];

?>
<style type="text/css">
#formx .form-control {margin-bottom:4px;}
</style>
<div class="col-md-12">
	<? if (USER_ID == 1) { ?>
	<div class="alert alert-danger"><?= $sql ?></div>
	<? } ?>
	<p><strong>THÔNG TIN LƯỢT THANH TOÁN</strong></p>
	<table class="table table-bordered table-condensed">
		<tr>
			<td><?= $theLtt['status'] ?></td>
			<td><?= $theLtt['payment_dt'] ?></td>
			<td><?= $theLtt['payment_method'] ?></td>
			<td><?= $theLtt['payment_ref'] ?></td>
		</tr>
	</table>
	<hr>
	<p><strong>THÊM MỤC THANH TOÁN VÀO LƯỢT NÀY</strong></p>
	<form class="form-inline well well-sm" id="formx">
		<?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Nhà cung cấp']) ?>
		<?= Html::textInput('tour', $tour, ['class'=>'form-control', 'placeholder'=>'Code/ID/tháng tour']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/cpt/search') ?>
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
	<? if (empty($theCptx)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th>Tour</th>
					<th width="100">Day</th>
					<th>Name @Venue $Provider</th>
					<th>Qty</th>
					<th>$$$</th>
					<th>=$$$</th>
					<th>Payment by</th>
					<th title="Thanh toán">Adm</th>
					<th title="Xác nhận">XN</th>
					<th>Check (new)</th>
					<th>Check TT</th>
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

					$dayCnt = 0;
					$currentDay = '';
					$total['all'] = 0;
					$total['vnd'] = 0;
					$total['usd'] = 0;
					$total['eur'] = 0;
					$xrates['usd'] = 21250;
					$xrates['eur'] = 28250;
					$xrates['vnd'] = 1;
					foreach ($theCptx as $cpt) {
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
				<tr>
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
					<td><?= $cpt['payer'] ?></td>
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
						<small title="Đã đặt cọc<?= $title['c5'] ?>" data-action="c5" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c5 <?= $cpt['c5'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c5'], 'on') !== false ? 'on' : 'off' ?>">DC</small>
						<small title="KTT xác nhận đặt cọc<?= $title['c6'] ?>" data-action="c6" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c6 <?= $cpt['c6'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c6'], 'on') !== false ? 'on' : 'off' ?>">DC!</small>
						<small title="Đã thanh toán<?= $title['c7'] ?>" data-action="c7" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c7 <?= $cpt['c7'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c7'], 'on') !== false ? 'on' : 'off' ?>">TT</small>
						<small title="KTT xác nhận thanh toán<?= $title['c8'] ?>" data-action="c8" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c8 <?= $cpt['c8'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c8'], 'on') !== false ? 'on' : 'off' ?>">TT!</small>
					</td>
				</tr>
<?
					}
?>
				<tr>
					<td colspan="6" class="text-right">Tổng tiền</td>
					<td class="text-right" colspan="2">
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
					<td colspan="5">
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
.label.cpt {cursor:pointer; color:#fff;}
.label.cpt.on {background-color:#393;}
.label.cpt.off {background-color:#ccc;}
.label.cpt.off.dirty {background-color:#baa;}
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
</style>
<?
$js = <<<TXT
// 150917 Tu Phuong
$('.cpt.c1, .cpt.c2, .cpt.c3, .cpt.c4, .cpt.c5, .cpt.c6, .cpt.c7, .cpt.c8, .cpt.c9').on('click', function(){
	action = $(this).data('action');
	tour_id = $(this).data('tour_id');
	dvtour_id = $(this).data('dvtour_id');
	var span = $(this);
	var formdata = $('#formx').serializeArray();
	$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
			alert(data[1]);
		} else {
			span.removeClass('on off').addClass(data[1]);
		}
	}, 'json');
});

$('.popovers').popover();
TXT;
$this->registerJs($js);