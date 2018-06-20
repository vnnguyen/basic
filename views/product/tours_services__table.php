<?
use yii\helpers\Html;

$allDays = []; // Tat ca cac ngay co dich vu, hoac nam trong chuong trinh tour

foreach ($sx as $s) {
	$allDays[$s['dvtour_day']] = '';
}

$cnt = 0;
$dayIdList = explode(',', $theCt['day_ids']);
foreach ($dayIdList as $di) {
	foreach ($ctDays as $ng) {
		if ($ng['id'] == $di) {
			$cnt ++;
			$ngay = date('Y-m-d', strtotime($theCt['day_from'].' + '.($cnt - 1).'days'));
			$allDays[$ngay] = $ng;
		}
	}
}

ksort($allDays);
$allOffices = array(
	'hn'=>'Hà Nội',
	'sr'=>'Siem Reap',
	'vt'=>'Vientiane',
	'nt'=>'Nha Trang',
);
?>
<!--
<div class="hide alert alert-info">
	<strong>Các thay đổi trong cách trình bày bảng này (trong giai đoạn chuyển tiếp)</strong>
	<br />8/2 : Đổi màu các dịch vụ đã có link đến nhà cung cấp / địa điểm (được điều hành chọn khi nhập dich vụ vào)
	<br />9/2 : Tên guide (do Ms Chinh nhập) sẽ được thêm thành một dòng trong bảng vào mỗi ngày có guide. Chưa có tác dụng tính chi phí.
	<br />10/2 : Cột "Ai đặt dịch vụ" đổi thành "Admin/Resv?" ghi tên văn phòng sẽ điều hành dịch vụ (Amica hiện có 4 vp) và liệu có phải book trước không. Nếu hướng dẫn là người đặt thì cột này là Không. Vẫn nhập vào như cũ.
	<br />7/8 : Có thể thêm số âm cho dịch vụ (trường hợp trả lại sản phẩm, lấy lại tiền).
</div>
-->
<table id="okle" class="table table-condensed">
	<thead>
	<tr>
		<th width="17%">Nội dung</th>
		<th width="15%">Đối tác / Cung cấp</th>
		<th width="3%">Số</th>
		<th width="7%">Đvị</th>
		<th width="10%">Giá tiền</th>
		<? if (!in_array(myID, array(3404, 5805, 14029, 14030, 15007))) { ?>
		<th width="11%">Thành VND</th>
		<? } ?>
		<th width="12%">Admin/Resv?</th>
		<th width="12%">Ai thanh toán</th>
		<th width="13%">Cho kế toán</th>
	</tr>
	</thead>
	<tbody>
	<tr id="tr-form-dv" style="display:none;">
		<td colspan="12" style="border-left:1px solid #36f;">
			<form id="form-dv" class="form-horizontal" method="post" action="">
				<fieldset>
					<legend>Add dvt</legend>
					<div class="control-group">
						<label class="control-label" for="">Day in tour</label>
						<div class="controls">
							<select class="span8" name="dvtour_day">
								<? $cnt = 0;
foreach ($dayIdList as $di) {
	foreach ($ctDays as $ng) {
		if ($ng['id'] == $di) {
								$cnt ++;
								$ngay = date('Y-m-d', strtotime($theCt['day_from'].' + '.($cnt - 1).'days'));
								?>
								<option value="<?=$ngay?>">Day <?=$cnt?>: <?=$ng['name']?></option>
								<? } } } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="">Name of service</label>
						<div class="controls">
							<input class="blank span3" type="text" name="dvtour_name" value="">
							Provider/Venue
							<input class="blank span3" type="text" name="oppr" value="">
							Admin
							<select class="span3" name="adminby">
								<option value="hn">Ha Noi</option>
								<option value="vt">Vientiane</option>
								<option value="sr">Siem Reap</option>
								<option value="nt">Nha Trang</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="">Venue/location</label>
						<div class="controls">
							<select class="span5 select2" name="venue_id">
								<option value="0">- Hotel, sightseeing... -</option>
								<? foreach ($allVenues as $vn) {?>
								<option value="<?=$vn['id']?>"><?=$vn['name']?></option>
								<? } ?>
							</select>
							Time
							<input class="span1" type="text" name="start" value="" maxlength="4" />
							Number
							<input class="span2" type="text" name="number" value="" maxlength="30" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="">Nhà cung cấp</label>
						<div class="controls">
							<select class="span4 select2" name="by_company_id">
								<option value="0">- Tàu, xe, dịch vụ gốc... -</option>
								<? foreach ($allCompanies as $c) { ?>
								<option value="<?=$c['id']?>"><?=$c['name']?></option>
								<? } ?>
							</select>
							Đại lý
							<select class="span4 select2" name="via_company_id">
								<option value="0">- Vé tàu, vé máy bay, package... -</option>
								<? foreach ($allCompanies as $c) { ?>
								<option value="<?=$c['id']?>"><?=$c['name']?></option>
								<? } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="">Quantity</label>
						<div class="controls">
							<input class="blank span1 ta-r" type="text" name="qty" value="">
							Unit
							<input class="blank span2" type="text" name="unit" value="">
							Unit price
							<select class="span1" name="plusminus">
								<option value="plus">+</option>
								<option value="minus">-</option>
							</select>
							<input class="blank span2 ta-r" type="text" name="price" value="">
							<select class="input-small" name="unitc">
								<option>VND</option>
								<option>USD</option>
								<option>EUR</option>
								<option>LAK</option>
								<option>KHR</option>
							</select>
							VAT
							<input tabindex="-1" class="span1 ta-c" type="text" name="vat" value="0">
							%
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="">Prebooking needed?</label>
						<div class="controls">
							<select class="span2" name="prebooking">
								<option value="yes">Yes / Có</option>
								<option value="no">No / Không</option>
							</select>
							Who pays
							<select class="span2" name="payer">
								<option>Amica Hà Nội</option>
								<option>Hướng dẫn MB 1</option>
								<option>Hướng dẫn MB 2</option>
								<option>Hướng dẫn MB 3</option>
								<option>Hướng dẫn MB 4</option>
								<option>Hướng dẫn MN 1</option>
								<option>Hướng dẫn MN 2</option>
								<option>Đức Minh</option>
								<option>An Hoà</option>
								<option>Anh Tấn</option>
								<option>Anh Thơ</option>
								<option>Anh Vinh</option>
								<option>Bunthol</option>
								<option>Dak Viet</option>
								<option>Thonglish (Laos)</option>
								<option>Medsanh (Laos)</option>
								<option>Feuang (Laos)</option>
								<option>Indo-Siam</option>
								<option>VEI Travel</option>
								<option>Chita</option>
								<option>Nanco</option>
								<option>Farid</option>
								<option>Jason</option>
								<option>Khác</option>
								<option>iTravelLaos (old)</option>
							</select>
							Booking status
							<select class="span2" name="status">
								<option value="n">Not OK</option>
								<option value="k">OK</option>
							</select>
							Payment due
							<input type="text" tabindex="-1" class="blank span2 datepicker" name="due" value="" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="">Note (optional)</label>
						<div class="controls">
							<textarea class="span8 h-50" name="mm"></textarea>
						</div>

					</div>
					<div class="form-actions">
						<a id="form-dv-submit" class="btn btn-primary" href="#">Save changes</a>
						<a id="form-dv-copy" class="btn btn-info" href="#">Save as copy</a>
						<a id="form-dv-close" href="#">Cancel</a>
					</div>
				</fieldset>
				<!-- p><a id="a-submit" class="btn btn-primary" href="#">Ghi các thay đổi</a> hoặc <a href="#" onclick="$('#div-form').addClass('hide').hide(0); $('td.fw-b').removeClass('fw-b'); return false;">Thôi, quay lại</a></p -->
			</form>
		</td>
	</tr>
	<?
	foreach ($allDays as $k=>$v) {
	?>
	<tr id="day<?=$k?>" class="info">
		<td><?=$thus[date('N', strtotime($k))]?> <?=$k?></td>
		<td colspan="<?=!in_array(myID, array(3404, 5805, 14029, 14030, 15007)) ? 7 : 6?>">
			<? if ($v == '') { ?>
			Ngày này không nằm trong chương trình tour chính thức
			<? } else { ?>
			<? echo anchor('#tours/ngaytour/'.SEG3, $v['name'].' ('.$v['meals'].')', 'class="fw-b" title="'.str_replace('"', '`', $v['body']).'"');?>
			<? } ?>
		</td>
		<td><?=myID == $theTour['op'] || in_array(myID, $tourOperatorIds) ? '<a class="dvt-c" href="#xdvtour-create" day="'.$k.'">+ Add service</a>' : ''?></td>
	</tr>
	<? foreach ($tgx as $tg) {  if ($getFilter == '' && $tg['day'] == $k) { ?>
	<tr>
		<td><span title="Điều hành đánh dấu đã đặt xong" class="dvtour-ok s-status xacnhan">OK</span> <?=anchor('users/lichguide/'.$tg['user_id'].'?month='.substr($tg['day'], 0, 7), 'Tour guide')?></td>
		<td><?=anchor('users/r/'.$tg['user_id'], $tg['fname'].' '.$tg['lname'].' - '.$tg['uabout'], 'class="td-n" style="color:#939;"')?></td>
		<td>1</td>
		<td>người</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
	</tr>
	<? } } ?>
	<?
		foreach ($sx as $s) {
			$hashedOppr = 'hn-'.md5($s['oppr']);
			$hashedVenueId = 'hi-'.md5($s['venue_id']);
			$hashedByCId = 'hb-'.md5($s['by_company_id']);
			$hashedViaCId = 'hv-'.md5($s['via_company_id']);
			$hashedPayer = 'hp-'.md5($s['payer']);
			if ($s['dvtour_day'] == $k) {
				if (
					$getFilter == ''
					|| ($getFilter != '' && $getFilter == $hashedOppr)
					|| ($getFilter != '' && $getFilter == $hashedVenueId)
					|| ($getFilter != '' && $getFilter == $hashedByCId)
					|| ($getFilter != '' && $getFilter == $hashedViaCId)
					|| ($getFilter != '' && $getFilter == $hashedPayer)
				) {
			?>
	<tr id="dvtour-<?=$s['dvtour_id']?>">
		<td class="show-on-hover">
			<?
			$hasComments = false;
			foreach ($allMMs as $mm) {
				if ($mm['rel_id'] == $s['dvtour_id']) {
					$hasComments = true;
					break;
				}
			}
			?>
			<span title="Điều hành đánh dấu đã đặt xong" class="dvtour-ok s-status <?=$s['status'] == 'k' ? 'xacnhan' : ''?>" rel="<?=$s['dvtour_id']?>">OK</span>
			<? if ($s['start'] != '00:00:00') { ?><span style="background:#069; padding:0 2px; color:#ffc;"><?=substr($s['start'], 0, 5)?></span><? } ?>
			<a title="Sửa dịch vụ tour (ID: <?=$s['dvtour_id']?>)" rel="<?=$s['dvtour_id']?>" class="dvt-u" href="#cdvtour-update"><?=$s['dvtour_name']?></a>
			<a title="Thêm / Xem các ghi chú" class="<?=$hasComments ? 'hasComments' : ' muted shown-on-hover'?> td-n" href="<?=DIR?>tours/mm/<?=$theTour['id']?>/<?=$s['dvtour_id']?>">+note</a>
		</td>
		<td><?
		if ($s['venue_id'] != 0) {
			echo anchor('/venues/r/'.$s['venue_id'], $s['venue_name'], 'style="text-decoration:none; color:#600"');
		} elseif ($s['via_company_id'] != 0) { 
			echo anchor('/companies/r/'.$s['via_company_id'], $s['via_company_name'], 'title="'.$hashedViaCId.'" style="text-decoration:none; color:#060"');
		} elseif ($s['by_company_id'] != 0) {
			echo anchor('/companies/r/'.$s['by_company_id'], $s['by_company_name'], 'style="text-decoration:none; color:#c60"');
		} else {
			echo Html::a($s['oppr'], '@web/'.URI.'?filter=hn-'.md5($s['oppr']));
		}
		?>
		</td>
		<td class="text-right"><?=trim(trim($s['qty'], '0'), '.')?></td>
		<td class="text-center"><?=$s['unit']?></td>
		<td class="text-right">
			<? if ($s['plusminus'] == 'minus') echo '-'; ?>
			<?=number_format($s['price'])?>
			<span class="text-muted"><?=$s['unitc']?></span>
		</td>
		<?
		$sub = $s['qty']*$s['price']*$xRates[$s['unitc']]*(1+$s['vat']/100);
		if ($s['unitc'] == 'USD') {
			if ($s['plusminus'] == 'plus') {
				$subUSD = $s['qty']*$s['price']*(1+$s['vat']/100);
			} else {
				$subUSD = $s['qty']*$s['price']*(1+$s['vat']/100);
			}
		}
		if ($s['latest']==0) {
			if ($s['plusminus'] == 'plus') {
				$total += $sub; $totalUSD += $subUSD;
			} else {
				$total -= $sub; $totalUSD -= $subUSD;
			}
		} ?>
		<? if (!in_array(myID, array(3404, 5805, 14029, 14030, 15007))) { ?>
		<td class="text-right <? if($s['approved_by'] !=0) {?>approved<? } ?>" title="<?=$s['unitc'] != 'VND' ? 'Tỉ giá: '.$xRates[$s['unitc']] : ''?>">
			<?
			$approveColors = array('#fff', '#ccc', '#666', '#960', '#660', '#090');
			if ($s['approved_by'] != '') {
				$s['approved_by'] = trim($s['approved_by'], '[');
				$s['approved_by'] = trim($s['approved_by'], ']');
				$approvers = explode('][', $s['approved_by']);
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
			echo $s['plusminus'] == 'minus' ? '-' : '';
			echo anchor('s-approve/'.$s['dvtour_id'], number_format($sub), 'title="Duyệt chấp nhận: '.$approverNames.'" class="approve" rel="'.$s['dvtour_id'].'"');
			echo '&nbsp;<span style="color:#fff; font:bold 11px Courier New; padding:1px; background:'.$theColor.'">'.count($approvers).'</span>';
			?>
		</td>
		<? } ?>
		<td><?
			echo $allOffices[$s['adminby']];
			echo $s['prebooking'] == 'yes' ? ' / Yes' : '';
		?></td>
		<td><?=anchor(URI.'?filter=hp-'.md5($s['payer']), $s['payer'])?></td>
		<td>
			<span title="Kế toán đánh dấu đã thanh toán" class="dvtour-tra pct<?=$s['thanhtoan_pct']?>" rel="<?=$s['dvtour_id']?>">TRẢ</span>
			<span title="Kế toán trưởng xác nhận" class="dvtour-ktt <?=$s['xacnhan_by'] == 0 ? '' : 'xacnhan'?>" rel="<?=$s['dvtour_id']?>">KTT</span>
			<? if (in_array(myID, [2])) { ?>
			<span title="Giám đốc duyệt lần cuối" class="s-duyet <?=$s['duyet_by'] == 0 ? '' : 'xacnhan'?>" rel="<?=$s['dvtour_id']?>">GĐ</span>
			<? } ?>
			<span title="Kế toán đánh dấu VAT" class="s-vat <?=$s['vat_ok'] == '' ? '' : ($s['vat_ok'] == 'needed' ? 'pct50' : 'pct100')?>" rel="<?=$s['dvtour_id']?>">VAT</span>
			<?=(myID == $theTour['op'] || in_array(myID, $tourOperatorIds)) && myID == $s['ub'] && $s['thanhtoan_pct'] == 0 ? '<a title="Xoá dịch vụ" href="#" class="td-n danger dvt-d" rel="'.$s['dvtour_id'].'">del</a>' : ''?>
		</td>
	</tr>
			<?
				} // if getFilter
			} // if $s[]
		} // foreach $sx
	} // foreach $allDays
	?>
	</tbody>
</table>