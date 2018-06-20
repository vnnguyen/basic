<?
use yii\helpers\Html;
if (!isset($camStaffIds))  {
  $camStaffIds = [3696, 3404, 19371, 1906, 31399, 14030,];  
}
$total = 0;
// Tour operators
$tourOperators = Yii::$app->db->createCommand('SELECT tu.*, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id AND tu.tour_id=:tour_id ORDER BY u.lname LIMIT 100', ['tour_id' => $theTour['id']])->queryAll();
if ($tourOperators == null) {
	$tourOperators = [];
}
$tourOperatorIds = [];
foreach ($tourOperators as $to) $tourOperatorIds[] = $to['user_id'];

$allOffices = array(
	'hn'=>'Hà Nội',
	'sg'=>'Saigon',
	'sr'=>'Siem Reap',
	'vt'=>'Vientiane',
	'nt'=>'Nha Trang',
	);
$q = Yii::$app->db->createCommand('SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="'.$theTour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1')->queryOne();
if ($q != null) {
	$xRates['USD'] = $q['rate'];
} else {
	$xRates['USD'] = 21000;
}
// Get exchange rates
$q = Yii::$app->db->createCommand('SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="EUR" AND rate_dt<="'.$theTour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1')->queryOne();
if ($q != null) {
	$xRates['EUR'] = $q['rate'];
} else {
	$xRates['EUR'] = 21000;
}
$xRates['VND'] = 1;
	?>
	<!-- tr id="dvtour-<?=$theCpt['dvtour_id']?>" -->
	<td class="show-on-hover no-padding-left">
		<span title="Điều hành đánh dấu đã đặt xong" class="dvtour-ok s-status <?=$theCpt['status'] == 'k' ? 'xacnhan' : ''?>" rel="<?=$theCpt['dvtour_id']?>">OK</span>
		<? if ($theCpt['crfund'] == 'yes') { ?><i title="CR Fund / Quỹ QHKH" class="fa fa-quora text-warning"></i><? } ?>
		<a title="Sửa dịch vụ tour (ID: <?=$theCpt['dvtour_id']?>)" rel="<?=$theCpt['dvtour_id']?>" class="cpt-u" href="#"><?=$theCpt['dvtour_name']?></a>
		<?
		if ($theCpt['venue_id'] != 0) {
			echo Html::a($theCpt['venue_name'], 'venues/r/'.$theCpt['venue_id'], [
				'style' => "text-decoration:none; color:#600"]);
		} elseif ($theCpt['via_company_id'] != 0) { 
			echo Html::a($theCpt['via_company_name'], 'companies/r/'.$theCpt['via_company_id'], 'title="hashedViaCId" style="text-decoration:none; color:#060"');
		} elseif ($theCpt['by_company_id'] != 0) {
			echo Html::a($theCpt['by_company_name'], 'companies/r/'.$theCpt['by_company_id'], 'style="text-decoration:none; color:#c60"');
		} else {
			echo Html::a($theCpt['oppr'],  DIR.URI.'?filter=hn-'.md5($theCpt['oppr']));
		}
		?>
		<a title="View cost and add comments" class="<?= $theCpt['comment_count'] > 0 ? 'text-danger' : ' text-muted shown-on-hover'?>" href="/cpt/r/<?= $theCpt['dvtour_id'] ?>"><i class="fa <?= $theCpt['comment_count'] > 0 ? 'fa-comment-o' : 'fa-ellipsis-h'?>"></i></a>
	</td>
	<td class="text-right"><?= number_format($theCpt['qty'], 1) ?><?// trim(trim($theCpt['qty'], '0'), '.')?></td>
	<td><?=$theCpt['unit']?></td>
	<td class="text-right">
		<? if ($theCpt['plusminus'] == 'minus') echo '-'; ?><?= number_format($theCpt['price']) ?> <span class="small quieter"><?=$theCpt['unitc']?></span>
	</td>
	<? 
	$subUSD = 0;
	$total = 0;
	$totalUSD = 0;
	$sub = $theCpt['qty']*$theCpt['price']*$xRates[$theCpt['unitc']]*(1+$theCpt['vat']/100);
	if ($theCpt['unitc'] == 'USD') {
		if ($theCpt['plusminus'] == 'plus') {
			$subUSD = $theCpt['qty']*$theCpt['price']*(1+$theCpt['vat']/100);
		} else {
			$subUSD = $theCpt['qty']*$theCpt['price']*(1+$theCpt['vat']/100);
		}
	}
	if ($theCpt['latest']==0) {
		if ($theCpt['plusminus'] == 'plus') {
			$total += $sub; $totalUSD += $subUSD;
		} else {
			$total -= $sub; $totalUSD -= $subUSD;
		}
	} ?>
	<? if (!in_array(USER_ID, $camStaffIds)) { ?>
	<td class="text-right <? if($theCpt['approved_by'] !=0) {?>approved<? } ?>" title="<?=$theCpt['unitc'] != 'VND' ? 'Tỉ giá: '.$xRates[$theCpt['unitc']] : ''?>">
		<?
		$approveColors = array('#fff', '#ccc', '#666', '#960', '#660', '#090');
		if ($theCpt['approved_by'] != '') {
			$theCpt['approved_by'] = trim($theCpt['approved_by'], '[');
			$theCpt['approved_by'] = trim($theCpt['approved_by'], ']');
			$approvers = explode('][', $theCpt['approved_by']);
			$approverNames = '';
			foreach ($approvers as $ap) {
				if (isset($_users[trim($ap, ':')]['name'])) {
					$approverNames .= $_users[trim($ap, ':')]['name'].', ';
				} else {
					$approverNames .= 'user-'.trim($ap, ':').', ';
				}
			}
		} else {
			$approvers = array();
			$approverNames = '(chưa)';
		}
		$theColor = isset($approveColors[count($approvers)]) ? $approveColors[count($approvers)] : 0;
		echo $theCpt['plusminus'] == 'minus' ? '-' : '';
		echo Html::a(number_format($sub), 's-approve/'.$theCpt['dvtour_id'], [
			'title' => "Duyệt chấp nhận: '.$approverNames.'",
			'class' => "approve",
			'rel' => $theCpt['dvtour_id']
			]);
		echo '&nbsp;<span style="color:#fff; font:bold 11px Courier New; padding:1px; background:'.$theColor.'">'.count($approvers).'</span>';
		?>
	</td>
	<? } ?>
	<td><?
		echo $theCpt['updated_by'];
            // echo $allOffices[$theCpt['adminby']];
            // echo $theCpt['prebooking'] == 'yes' ? ' / Yes' : '';
		?></td>
		<td><?=Html::a($theCpt['payer'],'/tours/services/'.$theTour['id'].'?filter=hp-'.md5($theCpt['payer']), [])?></td>
		<td>
			<?
			$traC7 = 100;
            // if (substr($theCpt['c5'], 0, 2) == 'on') {
            //     $traC7 = 50;
            // }
            // if (substr($theCpt['c7'], 0, 2) == 'on') {
            //     $traC7 = 100;
            // }
			?>
			<!-- span title="Kế toán đánh dấu đã thanh toán (một phần hoặc toàn bộ)" class="dvtour-tra pct<?= $traC7 ?>">TRẢ</span -->
			<?
			$cptPaidInFull = false;
			$cptInBasket = false;
			if (!isset($mttx)) $mttx = [];
			foreach ($mttx as $mtt) {
				if ($mtt['cpt_id'] == $theCpt['dvtour_id']) {
					if ($mtt['status'] == 'on') {
						?><span title="<?= number_format($mtt['amount']) ?> <?= $cpt['unitc'] ?><?= $cpt['unitc'] == $mtt['currency'] ? '' : ' ='.$mtt['currency'] ?>" class="label label-<?= $mtt['check'] == '' ? 'info' : 'success' ?>"><?
						if ($mtt['paid_in_full'] == 'yes') {
							$cptPaidInFull = true;
							echo 'TT 100%';
						} else {
							echo 'TT';
						}
						?></span> <?
					} elseif ($mtt['status'] == 'draft') {
						if ($mtt['created_by'] == USER_ID) {
							$cptInBasket = true;
						}
					}
				}
			}
			?>
	        <? /*if (!$cptPaidInFull) { ?>
	        <a title="Đánh dấu đã TT 100%" href="#" class="label label-default mark-paid" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT</a>
	        <a title="Sửa TT riêng mục này" href="/cpt/r/<?= $cpt['dvtour_id'] ?>?action=new-mtt" class="label label-default" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT+</a>
	            <? if ($cptInBasket) { ?>
	        <a title="Thêm vào Thanh toán nhiều mục" href="#" class="label label-info add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
	            <? } else { ?>
	        <a title="Thêm vào Thanh toán nhiều mục" href="#" class="label label-default add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
	            <? } ?>
	            <? } */ ?>

	            <?= (USER_ID == $theTour['op'] || in_array(USER_ID, $tourOperatorIds)) && USER_ID == $theCpt['updated_by'] && $traC7 == 0 ? '<a title="Xoá dịch vụ" href="#" class="danger dvt-d" rel="'.$theCpt['dvtour_id'].'">del</a>' : '<a title="Xoá dịch vụ" href="#" class="danger dvt-d" rel="'.$theCpt['dvtour_id'].'">del</a>'?>
	        </td>
    <!-- /tr -->