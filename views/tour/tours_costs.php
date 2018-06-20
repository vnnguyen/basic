<?
use yii\helpers\Html;

$this->title = 'Tour costs: '.$theTour['op_code'];

Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_breadcrumbs'] = [['Tour', 'tours']];
Yii::$app->params['page_breadcrumbs'][] = [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)];
Yii::$app->params['page_breadcrumbs'][] = [$theTour['op_code'], 'tours/r/'.$theTourOld['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Costs', 'tours/costs/'.$theTour['id']];

$getFilter = Yii::$app->request->get('filter', '');
$getCurrency = Yii::$app->request->get('currency', '');
$getFromDay = Yii::$app->request->get('from_day', '');
$getUntilDay = Yii::$app->request->get('until_day', '');

$tgx =[];
$xRates['VND'] = 1;

$theTour['op'] = [1];

// Array for filters
$filterArray = [
'v'=>[],
'vc'=>[],
'bc'=>[],
'p'=>[],
'n'=>[],
];
foreach ($theCptx as $cpt) {
	if (!isset($filterArray['p'][md5($cpt['payer'])])) $filterArray['p'][md5($cpt['payer'])] = $cpt['payer'];
	if ($cpt['venue_id'] != 0) {
		if (!isset($filterArray['v'][md5($cpt['venue_id'])])) $filterArray['v'][md5($cpt['venue_id'])] = $cpt['venue']['name'];
	} elseif ($cpt['via_company_id'] != 0) {
		if (!isset($filterArray['vc'][md5($cpt['via_company_id'])])) $filterArray['vc'][md5($cpt['via_company_id'])] = $cpt['viaCompany']['name'];
	} elseif ($cpt['by_company_id'] != 0) {
		if (!isset($filterArray['bc'][md5($cpt['by_company_id'])])) $filterArray['bc'][md5($cpt['by_company_id'])] = $cpt['company']['name'];
	} else {
		if (!isset($filterArray['n'][md5($cpt['oppr'])])) $filterArray['n'][md5($cpt['oppr'])] = $cpt['oppr'];
	}
}

?>
<div class="col-md-12">
	<div class="well well-sm clearfix">
		<form class="form-inline" style="float:left">
			Filter this tour:
			<select name="filter" class="form-control" style="width:auto;">
				<option value="">- chọn xem theo tên nhà cung cấp -</option>
				<optgroup label="Tên do điều hành nhập vào">
					<?
					asort($filterArray['n']);
					foreach ($filterArray['n'] as $k=>$v) { ?>
					<option value="hn-<?=$k?>" <?=$getFilter == 'hn-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
					<? } ?>
				</optgroup>
				<optgroup label="Tên do IMS tự động link">
					<?
					asort($filterArray['v']);
					foreach ($filterArray['v'] as $k=>$v) { ?>
					<option value="hi-<?=$k?>" <?=$getFilter == 'hi-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
					<? } ?>
				</optgroup>
				<optgroup label="Công ty cung cấp dịch vụ">
					<? foreach ($filterArray['vc'] as $k=>$v) { ?>
					<option value="hv-<?=$k?>" <?=$getFilter == 'hv-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
					<? } ?>
					<? foreach ($filterArray['bc'] as $k=>$v) { ?>
					<option value="hb-<?=$k?>" <?=$getFilter == 'hb-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
					<? } ?>
				</optgroup>
				<optgroup label="Ai trả tiền">
					<? foreach ($filterArray['p'] as $k=>$v) { ?>
					<option value="hp-<?=$k?>" <?=$getFilter == 'hp-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
					<? } ?>
				</optgroup>
			</select>
			<select name="currency" class="form-control" style="width:auto">
				<option value="all">Currency</option>
				<? foreach (['USD', 'EUR', 'VND'] as $cu) { ?>
				<option value="<?= $cu ?>" <?= $cu == $getCurrency ? 'selected="selected"' : '' ?>><?= $cu ?></option>
				<? } ?>
			</select>
			<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		</form>
		<form class="form-inline" style="float:left; margin-left:2em;">
			View another tour:
			<?= Html::textInput('code', $theTour['op_code'], ['class'=>'form-control']) ?>
			<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		</form>
	</div>


	<?
$allDays = []; // Tat ca cac ngay co dich vu, hoac nam trong chuong trinh tour

foreach ($theCptx as $cpt) {
	$allDays[$cpt['dvtour_day']] = '';
}

$cnt = 0;
$dayIdList = explode(',', $theTour['day_ids']);
foreach ($dayIdList as $di) {
	foreach ($theTour['days'] as $ng) {
		if ($ng['id'] == $di) {
			$cnt ++;
			$ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
			$allDays[$ngay] = $ng;
		}
	}
}

ksort($allDays);
$allOffices = array(
	'hn'=>'Hà Nội',
	'sg'=>'Saigon',
	'sr'=>'Siem Reap',
	'vt'=>'Vientiane',
	'nt'=>'Nha Trang',
	);

$thus = ['-', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy', 'Chủ nhật'];
$total = 0;
$totalUSD = 0;
$sub = 0;
$subUSD = 0;

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
	<table id="okle" class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th width="17%">Nội dung</th>
				<th width="15%">Đối tác / Cung cấp</th>
				<th width="3%">Số</th>
				<th width="7%">Đvị</th>
				<th width="10%">Giá tiền</th>
				<? if (!in_array(USER_ID, [3404, 5805, 14029, 14030, 15007])) { ?>
				<th width="11%">=VND</th>
				<? } ?>
				<th width="12%">Admin/Resv?</th>
				<th width="12%">Ai thanh toán</th>
				<th>KT</th>
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
									<select class="form-control" name="dvtour_day"><?
										$cnt = 0;
										foreach ($dayIdList as $di) {
											foreach ($theTour['days'] as $ng) {
												if ($ng['id'] == $di) {
													$cnt ++;
													$ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($cnt - 1).'days')); ?>
													<option value="<?= $ngay ?>">Day <?= $cnt ?>: <?= $ng['name'] ?></option><?
												}
											}
										} ?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="">Name of service</label>
								<div class="controls">
									<input class="blank form-control" type="text" name="dvtour_name" value="">
									Provider/Venue
									<input class="blank form-control" type="text" name="oppr" value="">
									Admin
									<select class="form-control" name="adminby">
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
									<select class="form-control select2" name="venue_id">
										<option value="0">- Hotel, sightseeing... -</option>
										<? foreach ($allVenues as $vn) {?>
										<option value="<?=$vn['id']?>"><?=$vn['name']?></option>
										<? } ?>
									</select>
									Time
									<input class="span1" type="text" name="start" value="" maxlength="4" />
									Number
									<input class="form-control" type="text" name="number" value="" maxlength="30" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="">Nhà cung cấp</label>
								<div class="controls">
									<select class="form-control select2" name="by_company_id">
										<option value="0">- Tàu, xe, dịch vụ gốc... -</option>
										<? foreach ($allCompanies as $c) { ?>
										<option value="<?=$c['id']?>"><?=$c['name']?></option>
										<? } ?>
									</select>
									Đại lý
									<select class="form-control select2" name="via_company_id">
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
									<input class="blank form-control text-right" type="text" name="qty" value="">
									Unit
									<input class="blank form-control" type="text" name="unit" value="">
									Unit price
									<select class="span1" name="plusminus">
										<option value="plus">+</option>
										<option value="minus">-</option>
									</select>
									<input class="blank form-control text-right" type="text" name="price" value="">
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
										<option>Amica Saigon</option>
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
			</tr><?
			foreach ($allDays as $k=>$v) { ?>
			<tr id="day<?= $k ?>" class="bg-info">
				<td><?=$thus[date('N', strtotime($k))]?> <?=$k?></td>
				<td colspan="<?=!in_array(USER_ID, array(3404, 5805, 14029, 14030, 15007)) ? 7 : 6?>">
					<? if ($v == '') { ?>
					Ngày này không nằm trong chương trình tour chính thức
					<? } else { ?>
					<? echo Html::a($v['name'].' ('.$v['meals'].')', '#tours/ngaytour/'.SEG3, ['class'=>"fw-b", 'title'=>str_replace('"', '`', $v['body'])]) ?>
					<? } ?>
				</td>
				<td><? /*= USER_ID == $theTour['op'] || in_array(USER_ID, $tourOperatorIds) ? '<a class="dvt-c" href="#xdvtour-create" day="'.$k.'">+ Add service</a>' : ''*/?></td>
			</tr>
			<? foreach ($tgx as $tg) {  if ($getFilter == '' && $tg['day'] == $k) { ?>
			<tr>
				<td><span title="Điều hành đánh dấu đã đặt xong" class="dvtour-ok s-status xacnhan">OK</span> <?= Html::a('Tour guide', 'users/lichguide/'.$tg['user_id'].'?month='.substr($tg['day'], 0, 7))?></td>
				<td><?=Html::a($tg['fname'].' '.$tg['lname'].' - '.$tg['uabout'], 'users/r/'.$tg['user_id'], ['class'=>'td-n', 'style'=>'color:#939'])?></td>
				<td>1</td>
				<td>người</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
			</tr>
			<? } } ?>
			<?
			foreach ($theCptx as $cpt) {
				$hashedOppr = 'hn-'.md5($cpt['oppr']);
				$hashedVenueId = 'hi-'.md5($cpt['venue_id']);
				$hashedByCId = 'hb-'.md5($cpt['by_company_id']);
				$hashedViaCId = 'hv-'.md5($cpt['via_company_id']);
				$hashedPayer = 'hp-'.md5($cpt['payer']);
				if ($cpt['dvtour_day'] == $k) {
					if (
						$getFilter == ''
						|| ($getFilter != '' && $getFilter == $hashedOppr)
						|| ($getFilter != '' && $getFilter == $hashedVenueId)
						|| ($getFilter != '' && $getFilter == $hashedByCId)
						|| ($getFilter != '' && $getFilter == $hashedViaCId)
						|| ($getFilter != '' && $getFilter == $hashedPayer)
						) {
							?>
							<tr id="dvtour-<?=$cpt['dvtour_id']?>">
								<td class="show-on-hover">
									<?
									$hasComments = !empty($cpt['mm']);
									?>
									<span title="Điều hành đánh dấu đã đặt xong" class="dvtour-ok s-status <?=$cpt['status'] == 'k' ? 'xacnhan' : ''?>" rel="<?=$cpt['dvtour_id']?>">OK</span>
									<? if ($cpt['start'] != '00:00:00') { ?><span style="background:#069; padding:0 2px; color:#ffc;"><?=substr($cpt['start'], 0, 5)?></span><? } ?>
									<a title="Sửa dịch vụ tour (ID: <?=$cpt['dvtour_id']?>)" rel="<?=$cpt['dvtour_id']?>" class="dvt-u" href="#cdvtour-update"><?=$cpt['dvtour_name']?></a>
									<a title="Thêm / Xem các ghi chú" class="<?=$hasComments ? 'hasComments' : ' muted shown-on-hover'?> td-n" href="<?=DIR?>cpt/r/<?=$cpt['dvtour_id']?>">+note</a>
								</td>
								<td><?
									if ($cpt['venue_id'] != 0) {
										echo Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue_id'], ['style'=>'text-decoration:none; color:#600']);
									} elseif ($cpt['via_company_id'] != 0) { 
										echo Html::a($cpt['viaCompany']['name'], '@web/companies/r/'.$cpt['via_company_id'], ['title'=>$hashedViaCId, 'style'=>'text-decoration:none; color:#060']);
									} elseif ($cpt['by_company_id'] != 0) {
										echo Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['by_company_id'], ['style'=>'text-decoration:none; color:#c60']);
									} else {
										echo Html::a($cpt['oppr'], DIR.URI.'?filter=hn-'.md5($cpt['oppr']));
									}
									?>
								</td>
								<td class="text-right"><?= (float)$cpt['qty'] ?></td>
								<td><?=$cpt['unit']?></td>
								<td class="text-right">
									<? if ($cpt['plusminus'] == 'minus') echo '-'; ?><?
									$str = (float)$cpt['price'];
									$strPart = explode('.', $str);
									if (!isset($strPart[1])) {
										$strPart[1] = '';
									} else {
										$strPart[1] = '.'.$strPart[1];
									}
									echo number_format($strPart[0], 0).$strPart[1];
									?>
									<span class="text-muted"><?= $cpt['unitc'] ?></span>
								</td>
								<?
			$xRates[$cpt['unitc']] = 1;// HUAN
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
			<? if (!in_array(USER_ID, array(3404, 5805, 14029, 14030, 15007))) { ?>
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
				$subStr = number_format($sub, 2);
				if (substr($subStr, -3) == '.00') {
					$subStr = number_format($sub);
				}
				echo Html::a($subStr, 's-approve/'.$cpt['dvtour_id'], ['title'=>'Approve: '.$approverNames, 'class'=>"approve", 'rel'=>$cpt['dvtour_id']]);
				echo '&nbsp;<span style="color:#fff; font:bold 11px Courier New; padding:1px; background:'.$theColor.'">'.count($approvers).'</span>';
				?>
			</td>
			<? } ?>
			<td><?
				echo $allOffices[$cpt['adminby']];
				echo $cpt['prebooking'] == 'yes' ? ' / Yes' : '';
				?></td>
				<td><?= Html::a($cpt['payer'], DIR.URI.'?filter=hp-'.md5($cpt['payer']))?></td>
				<td>
					<?
					$traC7 = 0;
					if (substr($cpt['c3'], 0, 2) == 'on') {
						$traC7 = 100;
					}
					?>
					<span title="Kế toán đánh dấu đã thanh toán (một phần hoặc toàn bộ)" class="dvtour-tra pct<?= $traC7 ?>">TRẢ</span>
					<?= (USER_ID == $theTour['op'] || in_array(USER_ID, $tourOperatorIds)) && USER_ID == $cpt['ub'] && $traC7 == 0 ? '<a title="Xoá dịch vụ" href="#" class="td-n danger dvt-d" rel="'.$cpt['dvtour_id'].'">del</a>' : ''?>
				</td>
			</tr>
			<?
						} // if getFilter
					} // if $cpt[]
				} // foreach $sx
			} // foreach $allDays
			?>
		</tbody>
	</table>
</div>

<?
/*

// Users: have to include old users as this is in list of approvers
$q = $db->query('SELECT u.*, r.* FROM persons u, at_user_role r WHERE r.user_id=u.id AND (r.role_id=%i OR r.role_id=%i) ORDER BY lname LIMIT 100', 1, 2);
foreach ($q->fetchAllRows() as $r) {
  $_users[$r['id']] = $r;
}

$q = $db->query('SELECT b.*, c.name AS case_name from at_bookings b, at_cases c WHERE c.id=b.case_id AND product_id=%i', $theTour['ct_id']);
$tourBookings = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : [];

$realPax = 0;
foreach ($tourBookings as $booking) {
  if ($booking['finish'] != 'canceled') {
    $realPax += $booking['pax'];
  }
}


// Chuong trinh tour if any
$q = $db->query('select * from at_ct where id=%i LIMIT 1', $theTour['ct_id']);
$theTour = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404, 'Không có chương trình tour!');

// Days
$q = $db->query('SELECT * FROM at_days WHERE rid=%i ORDER BY id', $theTour['ct_id']);
$ctDays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : [];

// Get exchange rates
$q = $db->query('SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="'.$theTour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1');
$xRates['USD'] = $q->countReturnedRows() > 0 ? $q->fetchScalar() : 21000;

// Get exchange rates
$q = $db->query('SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="EUR" AND rate_dt<="'.$theTour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1');
$xRates['EUR'] = $q->countReturnedRows() > 0 ? $q->fetchScalar() : 21000;


// AJAX POST: APPROVE
if (fRequest::isAjax()) {
  // AJAX
  if (isset($_POST['action']) && isset($_POST['sid'])) {
    // Check if dvtour exists
    $q = $db->query('SELECT * FROM cpt WHERE dvtour_id=%i LIMIT 1', $_POST['sid']);
    if ($q->countReturnedRows() == 0) exit('nok');
    $s = $q->fetchRow();
    // Kiểm tra tour của s
    $q = $db->query('SELECT * FROM at_tours WHERE id=%i LIMIT 1', $cpt['tour_id']);
    if ($q->countReturnedRows() == 0) exit('nok');
    $t = $q->fetchRow();
    

    // XAC NHAN CUA KE TOAN VAT G_ID = 7
    if ($_POST['action'] == 'va') {
      // Chi ke toan co quyen
      if (!$appUser->hasRoles('ketoan')) exit('nok');
      // Chi co the sua neu Mr Manh chua duyet
      //if ($cpt['duyet_by'] != 0) exit('nok');
      // Thay đổi trạng thái VAT
      $vatStatus = '';
      if ($cpt['vat_ok'] == '') $vatStatus = 'needed';
      if ($cpt['vat_ok'] == 'needed') $vatStatus = 'ok';
      if ($cpt['vat_ok'] == 'ok') $vatStatus = '';
      $db->query('UPDATE cpt SET vat_ok=%s, vat_by=%s WHERE dvtour_id=%i LIMIT 1', $vatStatus, USER_ID, $_POST['sid']);
      // Reload
      die('ok');
    }
    


    // Duyet boss, USER_ID = 2
    if ($_POST['action'] == 'du') {
      // Chi Mr Manh va Mr Tuan (since 111031) co the duyet
      // Chi Mr Manh va Ms Tu Phuong (since 111031) co the duyet
      if (USER_ID != 2 && USER_ID != 28431) exit('nok');
      // Đã được duyệt thì bỏ
      if ($cpt['duyet_by'] == 0) {
        // Check it
        $db->query('UPDATE cpt SET duyet_date=NOW(), duyet_by=%s WHERE dvtour_id=%i LIMIT 1', USER_ID, $_POST['sid']);
      } else {
        // Disapprove it
        $db->query('UPDATE cpt SET duyet_by = 0 WHERE dvtour_id=%i LIMIT 1', array($_POST['sid']));
      }
      // Reload
      die('ok');
    }
    die('nok');
  }
  
  
  if (isset($_POST['dvtour_id'])) {
    // Kiểm tra s
    $q = $db->query('SELECT * FROM cpt WHERE dvtour_id=%i LIMIT 1', $_POST['dvtour_id']);
    if ($q->countReturnedRows() == 0) exit('nok');
    $s = $q->fetchRow();

    // Kiểm tra b của s
    $q = $db->query('SELECT * FROM at_tours WHERE id=%i LIMIT 1', $cpt['tour_id']);
    if ($q->countReturnedRows() == 0) exit('nok');
    $t = $q->fetchRow();

    // Đã được duyệt thì bỏ
    if (strpos($cpt['approved_by'], '['.USER_ID.':]') === false) {
      // Approve it
      $db->query('UPDATE cpt SET approved=NOW(), approved_by = CONCAT(approved_by, "['.USER_ID.':]") WHERE dvtour_id=%i LIMIT 1', $_POST['dvtour_id']);
    } else {
      // Disapprove it
      $db->query('UPDATE cpt SET approved=NOW(), approved_by = REPLACE(approved_by, "['.USER_ID.':]", "") WHERE dvtour_id=%i LIMIT 1', $_POST['dvtour_id']);
    }

    // Reload s
    $q = $db->query('SELECT * FROM cpt WHERE dvtour_id=%i LIMIT 1', $_POST['dvtour_id']);
    if ($q->countReturnedRows() == 0) exit('nok');
    $s = $q->fetchRow();

    $sub = $cpt['qty']*$cpt['price']*$xRates[$cpt['unitc']]*(1+$cpt['vat']/100);
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
      $approvers = [];
      $approverNames = '(chưa)';
    }
    $theColor = isset($approveColors[count($approvers)]) ? $approveColors[count($approvers)] : 0;
    echo anchor('s-approve/'.$cpt['dvtour_id'], number_format($sub, 2), 'title="Duyệt chấp nhận: '.$approverNames.'" class="approve" rel="'.$cpt['dvtour_id'].'"');
    echo '&nbsp;<span style="color:#fff; font:bold 11px Courier New; padding:1px; background:'.$theColor.'">'.count($approvers).'</span>';
    exit;
  }
}

$q = $db->query('SELECT *,
IF (venue_id=0, "-", (select name from venues v where v.id=venue_id limit 1)) AS venue_name,
IF (by_company_id=0, "-", (select name from at_companies c where c.id=by_company_id limit 1)) AS by_company_name,
IF (via_company_id=0, "-", (select name from at_companies c where c.id=via_company_id limit 1)) AS via_company_name,
(select name from at_dv d where d.id=dv_id limit 1) AS dv_name,
0 AS versions
FROM cpt WHERE tour_id=%i AND (latest=0 OR latest=dvtour_id) ORDER BY dvtour_day, dvtour_name, uo ASC', $theTour['id']);

if ($getCurrency != 'all') {
	$q = $db->query('SELECT *,
	IF (venue_id=0, "-", (select name from venues v where v.id=venue_id limit 1)) AS venue_name,
	IF (by_company_id=0, "-", (select name from at_companies c where c.id=by_company_id limit 1)) AS by_company_name,
	IF (via_company_id=0, "-", (select name from at_companies c where c.id=via_company_id limit 1)) AS via_company_name,
	(select name from at_dv d where d.id=dv_id limit 1) AS dv_name,
	0 AS versions
	FROM cpt WHERE unitc=%s AND tour_id=%i AND (latest=0 OR latest=dvtour_id) ORDER BY dvtour_day, dvtour_name, uo ASC', $getCurrency, $theTour['id']);
}

$sx = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : [];
$sStatus = array(
  'n'=>'Chưa đặt',
  'x'=>'Bị huỷ',
  'k'=>'OK'
);

// Cac mau sac

$_POST = array(
  'type'=>'Tham quan',
  'title'=>'',
  'oppr'=>'',
  'qty'=>0,
  'unit'=>'',
  'price'=>0,
  'unitc'=>'VND',
  'rates'=>1,
  'vat'=>0,
  'status'=>'n',
  'payer'=>'',
  'paid'=>'',
  'prebooking'=>'yes',
  'payer'=>'',
  'due'=>'',
	'mm'=>'',
);

// All oppr
$q = $db->query('SELECT oppr FROM cpt WHERE tour_id=%i GROUP BY oppr ORDER BY oppr', $theTour['id']);
$opprNames = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : [];

// Guides in this tour
$q = $db->query('SELECT u.id, u.fname, u.lname, u.about AS uabout, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=%i ORDER BY day LIMIT 100', $theTour['id']);
$tgx = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : [];

// Check svc without a tour day
for ($i = 0; $i < count($sx); $i ++) {
  $sx[$i]['has_day'] = true;
}

$opName = '';
if (!empty($tourOperators)) {
	foreach ($tourOperators as $to) {
		if (in_array($to['user_id'], array(7, 8, 9, 118, 4125, 5270, 8162, 7766, 9198, 15081, 25457))) $opName = $to['name'];
	} 
}




?>
	<div class="pull-right" id="total-cost"></div>
	<p>
		<? if ($appUser->hasOneOfRoles('ketoan', 'admin')) { ?><?=anchor('tours/atuan/'.seg3, 'Bản copy ra Excel', 'target="_blank"')?><? } ?>
		- <a style="color:#090" href="/work/ketoan?code=<?=$theTour['code']?>">Bảng lọc cho kế toán</a>
		- Mouse over a line to add/view notes
	</p>
	<form class="form-inline" method="get" action="">
		<p>
			<!--input type="date" name="from_day" style="width:100px" autocomplete="off" placeholder="From date">
			<input type="date" name="until_day" style="width:100px" autocomplete="off" placeholder="Until date"-->
			<button class="btn" type="submit">Filter</button>
			<a href="<?= DIR. URI ?>">Reset</a>
      |
      Xem chi phí tour khác:
      <input type="text" class="" style="width:100px" name="code" value="" autocomplete="off" placeholder="Tour code">
      <button class="btn" type="submit">Go</button>
		</p>
	</form>
	<div id="oklediv">
		<? include_once('tours_services__table.php'); ?>
	</div>
<style>
#total-cost {font:bold 20px/30px Arial; padding:5px; border:3px solid #996; background:#ffc; color:#c00;}
.approved {background:url(<?=DIR?>img/icons/accept.png) left center no-repeat;}
span.s-status, span.s-vat, span.dvtour-tra, span.dvtour-ktt, span.s-duyet {background:#ccc; cursor:pointer; padding:1px; font:10px Arial; color:#fff;}
span.xacnhan {background:#090;}
span.pct0 {background:#ccc;}
span.pct50 {background:#fc9;}
span.pct100 {background:#090;}
</style>
<script>
$(function(){
	var totalCost = '<? if (!in_array(USER_ID, array(3404, 5805, 14029, 14030, 15007))) {
		echo number_format($total).' VND';
	} else {
		echo number_format($totalUSD).' USD (only for costs in USD)';
	}
	?>';
	$('#total-cost').html(totalCost);
	
  var action = 'create';
  var tour_id = <?=seg3?>;
  var dvtour_id = 0;
	var currentday = '<?=$theTour['day_from']?>';
	
	// Action create
	$('tr.info a.dvt-c').live('click', function(){
		$(this).parent().parent().after($('tr#tr-form-dv'));
		$('tr#tr-form-dv').show(0).find('legend').html('Thêm dịch vụ');
		
    action = 'create';
    dvtour_id = 0;
    var day = $(this).attr('day');
    $('#form-dv').find('.blank').val('');
    $('#form-dv').find(':input:first').val(day);
    $('#form-dv').find('[type=text]:first').focus();
    $('#form-dv-copy').hide(0);
		return false;
	});
	
	// Action copy
	$('a#form-dv-copy').on('click', function(){
		action = 'copy';
    var formdata = $('#form-dv').serializeArray();
    $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
      if (data[0] == 'NOK') {
        alert(data[1]);
      } else if (data[0] == 'OK-COPY') {
				$('tr#tr-form-dv').before('<tr><td colspan="10">Please wait...</td></tr>');
				var prev = $('tr#tr-form-dv').prev('tr');
				dvtour_id = data[2];
				prev.attr('id', 'dvtour-'+dvtour_id).load('/tours/services/tr?id='+dvtour_id);
				$('[name=dvtour_name], [name=oppr], [name=qty], [name=unit], [name=unitc], [name=price], [name=due], [name=mm]').val('');
        $('tr#tr-form-dv').hide(0);
				// Change day
				if (currentday != data[3]) {
					$('tr#dvtour-'+dvtour_id).insertAfter($('tr#day' + data[3]));
				}
      }
    }, 'json');
		return false;
	});


	
	$('a#form-dv-submit').on('click', function(){
    //$('#form-dv-submit #form-dv-copy').addClass('disabled');
    var formdata = $('#form-dv').serializeArray();
    $.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
      if (data[0] == 'NOK') {
        alert(data[1]);
      } else if (data[0] == 'OK-CREATE') {
				$('tr#tr-form-dv').before('<tr><td colspan="10">Please wait...</td></tr>');
				var prev = $('tr#tr-form-dv').prev('tr');
				dvtour_id = data[2];
				prev.attr('id', 'dvtour-'+dvtour_id).load('/tours/services/tr?id='+dvtour_id);
      } else if (data[0] == 'OK-UPDATE') {
				$('tr#dvtour-'+dvtour_id).load('/tours/services/tr?id='+dvtour_id);
				$('[name=dvtour_name], [name=oppr], [name=qty], [name=unit], [name=unitc], [name=price], [name=due], [name=mm]').val('');
        $('tr#tr-form-dv').hide(0);
				// Change day
				if (currentday != data[3]) {
					$('tr#dvtour-'+dvtour_id).insertAfter($('tr#day' + data[3]));
				}
      }
    }, 'json');
    //$('#form-dv-submit #form-dv-copy').removeClass('disabled');
		return false;
	});
	
	// Action delete
	$('a.dvt-d').live('click', function(){
		if (!confirm('Bạn thực sự muốn xoá dịch vụ này?')) {
			return false;
		}
		action = 'delete';
		dvtour_id = $(this).attr('rel');
		var formdata = $('#form-dv').serializeArray();
		$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata, total:totalCost}, function(data){
		if (data[0] == 'NOK') {
		alert(data[1]);
		} else {
		$('tr#dvtour-' + dvtour_id).remove();
			$('#total-cost').html(data[2]);
		}
		}, 'json');
		return false;
	});

	$('a.approve').live('click', function(){
		rel = $(this).attr('rel');
		var td = $(this).parent();
		$.post(location.href, {dvtour_id:rel}, function(data){
		if (data != 'nok') {
		td.empty().html(data);
		} else {
		alert('Khong thanh cong...');
		}
		}, 'html');
		return false;
	});
  
	// DIEU HANH CHECK DAT DICH VU
	$('span.dvtour-ok').live('click', function(){
		action = 'ok';
		dvtour_id = $(this).attr('rel');
		var span = $(this);
		var formdata = $('#form-dv').serializeArray();
		$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
			alert(data[1]);
		} else {
			span.removeClass('xacnhan').addClass(data[1]);
		}
		}, 'json');
		return false;
	});

	// KE TOAN CHECK THANH TOAN
	/*
	$('span.dvtour-tra').live('click', function(){
		action = 'tra';
		dvtour_id = $(this).attr('rel');
		var span = $(this);
		var formdata = $('#form-dv').serializeArray();
		$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
		alert(data[1]);
		} else {
		span.removeClass('pct0 pct50 pct100').addClass(data[1]);
		}
		}, 'json');
		return false;
	});*/

	// KE TOAN DANH DAU VAT
	/*
	$('span.s-vat').live('click', function(){
		var sid = $(this).attr('rel');
		var span = $(this);
		$.post(location.href, {action:'va',sid:sid}, function(data){
		if (data == 'ok') {
		if (span.hasClass('pct50')) {
		  span.removeClass('pct50').addClass('pct100');
		} else if (span.hasClass('pct100')) {
		  span.removeClass('pct100');
		} else {
		  span.addClass('pct50');
		}
		} else {
		alert('Khong thanh cong...');
		}
		}, 'text');
	});
	*/

  	/*
	// KE TOAN TRUONG XN
	$('span.dvtour-ktt').live('click', function(){
		action = 'ktt';
		dvtour_id = $(this).attr('rel');
		var span = $(this);
		var formdata = $('#form-dv').serializeArray();
		$.post('/tours/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, formdata:formdata}, function(data){
		if (data[0] == 'NOK') {
		alert(data[1]);
		} else {
		span.removeClass('xacnhan').addClass(data[1]);
		}
		}, 'json');
		return false;
	});

	// BOSS DUYET
	$('span.s-duyet').live('click', function(){
		var sid = $(this).attr('rel');
		var span = $(this);
		$.post(location.href, {action:'du',sid:sid}, function(data){
		if (data == 'ok') {
		span.toggleClass('xacnhan');
		} else {
		alert('Khong thanh cong...');
		}
		}, 'text');
	});
	* /
});
</script>
<style>a.hasComments, a.danger {color:#c00; font-size:90%}</style> */ ?>
<?
$js = <<<'TXT'
var tour_id = $tour_id;



// Action update
$('#okle').on('click', 'tr a.dvt-u', function(){
	$(this).parent().parent().after($('tr#tr-form-dv'));
	$('tr#tr-form-dv').show(0).find('legend').html('Sửa dịch vụ');
	$('#form-dv-copy').show(0);
	action = 'update-prepare';
	dvtour_id = $(this).attr('rel');
	var formdata = $('#form-dv').serializeArray();
	$.post('/tours/ajax', {
		action:action,
		tour_id:tour_id,
		dvtour_id:dvtour_id,
		formdata:formdata
	}, function(data){
		if (data[0] == 'NOK') {
			//alert(data[1]);
		} else {
			$('#form-dv').find('[type=text]:first').focus();
			$('[name=dvtour_day]').val(data['dvtour_day']);
			$('[name=dvtour_name]').val(data['dvtour_name']);
			$('[name=oppr]').val(data['oppr']);
			$('[name=venue_id]').select2('val', data['venue_id']);
			$('[name=adminby]').val(data[\adminby]);
			$('[name=start]').val(data['start']);
			$('[name=number]').val(data['crfund']);
			$('[name=via_company_id]').select2('val', data['via_company_id']);
			$('[name=by_company_id]').select2('val', data['by_company_id']);
			$('[name=qty]').val(data['qty']);
			$('[name=unit]').val(data['unit']);
			$('[name=price]').val(data['price']);
			$('[name=unitc]').val(data['unitc']);
			$('[name=vat]').val(data['vat']);
			$('[name=prebooking]').val(data['prebooking']);
			$('[name=payer]').val(data['payer']);
			$('[name=status]').val(data['status']);
			$('[name=due]').val(data['due']);
			$('[name=plusminus]').val(data['plusminus']);
			$('[name=mm]').val('');
			action = 'update';
			currentday = data['dvtour_day'];
		}
	}, 'json');

	return false;
});

// Close form
$('a#form-dv-close').on('click', function(){
	$('tr#tr-form-dv').hide(0);
	return false;
});

TXT;

$js = str_replace(['$tour_id'], [$theTourOld['id']], $js);

$this->registerJs($js);