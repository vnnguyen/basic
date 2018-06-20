<?
// Read and return a line for tour cost input form
$theId = fRequest::get('id', 'integer', 0, true);
$q = $db->query('SELECT *,
	(SELECT COUNT(*) FROM at_mm WHERE rel_type="service" AND rel_id=dvtour_id) AS comment_count,
	IF (venue_id=0, "-", (select name from venues v where v.id=venue_id limit 1)) AS venue_name,
	IF (by_company_id=0, "-", (select name from at_companies c where c.id=by_company_id limit 1)) AS by_company_name,
	IF (via_company_id=0, "-", (select name from at_companies c where c.id=via_company_id limit 1)) AS via_company_name,
	(select name from at_dv d where d.id=dv_id limit 1) AS dv_name,
  0 AS versions
  FROM cpt WHERE dvtour_id=%i LIMIT 1', $theId);
$cpt = $q->countReturnedRows() > 0 ? $q->fetchRow() : die('Data not found');

$q = $db->query('SELECT ct.day_from, ct.pax, t.* FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id AND t.id=%i LIMIT 1', $cpt['tour_id']);

$theTour = $q->countReturnedRows() > 0 ? $q->fetchRow() : die('Data not found');

// Tour operators
$q = $db->query('SELECT tu.*, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id AND tu.tour_id=%i ORDER BY u.lname LIMIT 100', $theTour['id']);
$tourOperators = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
$tourOperatorIds = array();
foreach ($tourOperators as $to) $tourOperatorIds[] = $to['user_id'];

$allOffices = array(
	'hn'=>'Hà Nội',
	'sr'=>'Siem Reap',
	'vt'=>'Vientiane',
	'nt'=>'Nha Trang',
);
?>
	<!-- tr id="dvtour-<?=$cpt['dvtour_id']?>" -->
		<td class="show-on-hover">
			<span title="Điều hành đánh dấu đã đặt xong" class="dvtour-ok s-status <?=$cpt['status'] == 'k' ? 'xacnhan' : ''?>" rel="<?=$cpt['dvtour_id']?>">OK</span>
			<? if ($cpt['start'] != '00:00:00') { ?><span style="background:#069; padding:0 2px; color:#ffc;"><?=substr($cpt['start'], 0, 5)?></span><? } ?>
			<a title="Sửa dịch vụ tour (ID: <?=$cpt['dvtour_id']?>)" rel="<?=$cpt['dvtour_id']?>" class="dvt-u" href="#"><?=$cpt['dvtour_name']?></a>
			<a title="Thêm / Xem các ghi chú" class="<?=$cpt['comment_count'] > 0 ? 'hasComments' : ' muted shown-on-hover'?> td-n" href="<?=DIR?>tours/mm/<?=$cpt['tour_id']?>/<?=$cpt['dvtour_id']?>">+note</a>
		</td>
		<td><?
		if ($cpt['venue_id'] != 0) {
			echo anchor('venues/tours/'.$cpt['venue_id'], $cpt['venue_name'], 'style="text-decoration:none; color:#600"');
		} elseif ($cpt['via_company_id'] != 0) { 
			echo anchor('companies/r/'.$cpt['via_company_id'], $cpt['via_company_name'], 'title="hashedViaCId" style="text-decoration:none; color:#060"');
		} elseif ($cpt['by_company_id'] != 0) {
			echo anchor('companies/r/'.$cpt['by_company_id'], $cpt['by_company_name'], 'style="text-decoration:none; color:#c60"');
		} else {
			echo anchor(uris.'?filter=hn-'.md5($cpt['oppr']), $cpt['oppr']);
		}
		?>
		</td>
		<td class="text-right"><?=trim(trim($cpt['qty'], '0'), '.')?></td>
		<td class="text-center"><?=$cpt['unit']?></td>
		<td class="text-right">
			<? if ($cpt['plusminus'] == 'minus') echo '-'; ?><?=number_format($cpt['price'])?><span class="small quieter"><?=$cpt['unitc']?></span>
		</td>
		<? 
		$sub = $cpt['qty']*$cpt['price']*$xRates[$cpt['unitc']]*(1+$cpt['vat']/100);
		if ($cpt['unitc'] == 'USD') {
			if ($cpt['plusminus'] == 'plus') {
				$subUSD = $cpt['qty']*$cpt['price']*(1+$cpt['vat']/100);
			} else {
				$subUSD = $cpt['qty']*$cpt['price']*(1+$cpt['vat']/100);
			}
		}
		if ($cpt['latest']==0) {
			if ($cpt['plusminus'] == 'plus') {
				$total += $sub; $totalUSD += $subUSD;
			} else {
				$total -= $sub; $totalUSD -= $subUSD;
			}
		} ?>
		<? if (!in_array(myID, [3404, 5805, 14029, 14030, 15007])) { ?>
		<td class="text-right <? if($cpt['approved_by'] !=0) {?>approved<? } ?>" title="<?=$cpt['unitc'] != 'VND' ? 'Tỉ giá: '.$xRates[$cpt['unitc']] : ''?>">
			<?
			$approveColors = array('#fff', '#ccc', '#666', '#960', '#660', '#090');
			if ($cpt['approved_by'] != '') {
				$cpt['approved_by'] = trim($cpt['approved_by'], '[');
				$cpt['approved_by'] = trim($cpt['approved_by'], ']');
				$approvers = explode('][', $cpt['approved_by']);
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
			echo $cpt['plusminus'] == 'minus' ? '-' : '';
			echo anchor('s-approve/'.$cpt['dvtour_id'], number_format($sub), 'title="Duyệt chấp nhận: '.$approverNames.'" class="approve" rel="'.$cpt['dvtour_id'].'"');
			echo '&nbsp;<span style="color:#fff; font:bold 11px Courier New; padding:1px; background:'.$theColor.'">'.count($approvers).'</span>';
			?>
		</td>
		<? } ?>
		<td><?
			echo $allOffices[$cpt['adminby']];
			echo $cpt['prebooking'] == 'yes' ? ' / Yes' : '';
		?></td>
		<td><?=anchor('/tours/services/'.$theTour['id'].'?filter=hp-'.md5($cpt['payer']), $cpt['payer'])?></td>
		<td>
			<span title="Kế toán đánh dấu đã thanh toán" class="dvtour-tra pct<?=$cpt['thanhtoan_pct']?>" rel="<?=$cpt['dvtour_id']?>">TRẢ</span>
			<span title="Kế toán trưởng xác nhận" class="dvtour-ktt <?=$cpt['xacnhan_by'] == 0 ? '' : 'xacnhan'?>" rel="<?=$cpt['dvtour_id']?>">KTT</span>
			<? if (myID == 2 || myID == 4065) { ?>
			<span title="Giám đốc duyệt lần cuối" class="s-duyet <?=$cpt['duyet_by'] == 0 ? '' : 'xacnhan'?>" rel="<?=$cpt['dvtour_id']?>">GĐ</span>
			<? } ?>
			<span title="Kế toán đánh dấu VAT" class="s-vat <?=$cpt['vat_ok'] == '' ? '' : ($cpt['vat_ok'] == 'needed' ? 'pct50' : 'pct100')?>" rel="<?=$cpt['dvtour_id']?>">VAT</span>
			<?=(myID == $theTour['op'] || in_array(myID, $tourOperatorIds)) && myID == $cpt['ub'] && $cpt['thanhtoan_pct'] == 0 ? '<a title="Xoá dịch vụ" href="#" class="td-n danger dvt-d" rel="'.$cpt['dvtour_id'].'">del</a>' : ''?>
		</td>
	<!-- /tr -->