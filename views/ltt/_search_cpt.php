<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_ltt_inc.php');
?>
		<? if (empty($theCptx)) { ?>
		<p>Tìm kiếm không có kết quả...</p>
		<? } else { ?>
		<table class="table table-bordered table-condensed table-striped table-hover" id="table-search-cpt">
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
					<td class="text-muted"><?= $cpt['unit'] ?></td>
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
						<!--
						<small title="Đã đặt cọc<?= $title['c5'] ?>" data-action="c5" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c5 <?= $cpt['c5'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c5'], 'on') !== false ? 'on' : 'off' ?>">DC</small>
						<small title="KTT xác nhận đặt cọc<?= $title['c6'] ?>" data-action="c6" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c6 <?= $cpt['c6'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c6'], 'on') !== false ? 'on' : 'off' ?>">DC!</small>
						<small title="Đã thanh toán<?= $title['c7'] ?>" data-action="c7" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c7 <?= $cpt['c7'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c7'], 'on') !== false ? 'on' : 'off' ?>">TT</small>
						<small title="KTT xác nhận thanh toán<?= $title['c8'] ?>" data-action="c8" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c8 <?= $cpt['c8'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c8'], 'on') !== false ? 'on' : 'off' ?>">TT!</small>
						-->
						<a class="add-cpt-to-ltt" data-tr="<?= $cpt['dvtour_id'] ?>" href="javascript:;">+ Thêm</a>
						<a class="edit-mtt" data-tr="<?= $cpt['dvtour_id'] ?>" href="#">Sửa</a>
						<a class="remove-cpt-from-ltt text-danger" data-tr="<?= $cpt['dvtour_id'] ?>" href="javascript:;">- Bỏ</a>
					</td>
				</tr>
<?
					}
?>
			</tbody>
		</table>
		<? } ?>
<script type="text/javascript">
$('.popovers').popover();
</script>