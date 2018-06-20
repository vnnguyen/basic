<?
require_once('/var/www/__apps/my.amicatravel.com/views/fdb.php');

$getMonth = \fRequest::get('month', 'string', date('Y-m'), true); if ($getMonth == '0') $getMonth = date('Y-m');
$getSe = \fRequest::get('se', 'integer', 0, true);
$getOp = \fRequest::get('op', 'integer', 0, true);
$getCs = \fRequest::get('cs', 'integer', 0, true);
$getStatus = \fRequest::getValid('status', ['all', 'on', 'deleted']);
$getLanguage = \fRequest::getValid('language', ['all', 'de', 'en', 'es', 'fr', 'it', 'vi']);

//$appUser->hasOneOfRoles('dieuhanh', 'admin', 'cskh', 'it') || show_error(403);

$q = $db->query('select min(year(day_from)) as miny, max(year(day_from)) as maxy from at_ct ct, at_tours WHERE ct.id=at_tours.ct_id');
$mima = $q->fetchRow();
$miny = $mima['miny'];
$maxy = $mima['maxy'];

$q = $db->query('SELECT SUBSTRING(day_from, 1, 7) AS ym, YEAR(day_from) AS y, MONTH(day_from) AS m, COUNT(*) AS total FROM at_ct, at_tours WHERE at_ct.id=at_tours.ct_id GROUP BY ym ORDER BY y DESC, m DESC');
$allMonthTourCount = $q->fetchAllRows();

$q = $db->query('SELECT SUBSTRING(day_from, 1, 7) AS ym, COUNT(*) AS total FROM at_ct, at_tours WHERE at_tours.status=%s AND at_ct.id=at_tours.ct_id GROUP BY ym', 'deleted');
$allMonthCanceledTours = $q->fetchAllRows();

// Danh sách tour
$q = $db->query('SELECT ct.pax, ct.language, ct.days, ct.day_from, t.code, t.name, t.status, t.ct_id, t.id, t.se
  FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id AND SUBSTRING(day_from, 1, 7)=%s ORDER BY day_from, SUBSTRING(code,-3) LIMIT 1000', $getMonth);
$monthTours = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Danh sách khách
$q = $db->query('SELECT p.*, fname, lname, u.name, country_code, gender, byear, bmonth, bday
  FROM at_pax p, at_tours t, at_ct ct, persons u WHERE u.id=p.user_id AND ct_id=ct.id AND p.tour_id=t.id AND SUBSTRING(day_from,1,7)=%s ORDER BY tour_id LIMIT 1000', $getMonth);
$monthPax = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Cac tour Id
$tourIds = array(); foreach ($monthTours as $t) $tourIds[] = $t['id']; if (empty($tourIds)) $tourIds[] = 0;

// Danh sach Ban hang, all
$q = $db->query('SELECT u.id, u.name FROM persons u, at_tours t WHERE t.se=u.id GROUP BY u.id ORDER BY u.lname');
$tourSes = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Danh sach Seller trong thang
$tourSellerIds = array(0);
foreach ($monthTours as $mt) if (!in_array($mt['se'], $tourSellerIds)) $tourSellerIds[] = $mt['se'];
$q = $db->query('SELECT id, name FROM persons WHERE id IN ('.implode(',', $tourSellerIds).') ORDER BY lname');
$tourSellers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Danh sach Dieu hanh, all
$q = $db->query('SELECT u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role=%s AND tu.user_id=u.id GROUP BY u.id ORDER BY u.lname', 'operator');
$tourOps = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Danh sach Dieu hanh, thang nay
$q = $db->query('SELECT tu.tour_id, u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role=%s AND tu.user_id=u.id AND tu.tour_id IN ('.implode(',', $tourIds).') ORDER BY u.lname', 'operator');
$monthTourOps = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
foreach ($monthTourOps as $to) $monthTourOpList[$to['tour_id']][] = $to['id'];

// Danh sach CSKH, all
$q = $db->query('SELECT u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role=%s AND tu.user_id=u.id GROUP BY u.id ORDER BY u.lname', 'cservice');
$tourCss = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Danh sach Dieu hanh, thang nay
$q = $db->query('SELECT tu.tour_id, u.id, u.name FROM persons u, at_tour_user tu WHERE tu.role=%s AND tu.user_id=u.id AND tu.tour_id IN ('.implode(',', $tourIds).') ORDER BY u.lname', 'cservice');
$monthTourCss = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
foreach ($monthTourCss as $tc) $monthTourCsList[$tc['tour_id']][] = $tc['id'];

// Max number of tours
$max_total = 0;
foreach ($allMonthTourCount as $getMonthi) {
  if ($getMonthi['total'] > $max_total) $max_total = $getMonthi['total'];
}

$this->title = 'Tour tháng '.$getMonth.' ('.count($monthTours).' tour, '.count($monthPax).' lượt khách hàng)';
$this->params['breadcrumb'] = [
	['Tours', 'tours'],
	[$getMonth, 'me/tours?month='.$getMonth],
];

?>
<div class="col-lg-12">
	<form method="get" action="" class="well well-sm form-inline">
		<select class="form-control w-auto" name="month">
			<option value="0">All months</option>
			<? foreach ($allMonthTourCount as $getMonthi) { ?>
			<option value="<?=$getMonthi['ym']?>" <?=$getMonth == $getMonthi['ym'] ? 'selected="selected"' : ''?>><?=$getMonthi['ym']?> (<?=$getMonthi['total']?>)</option>
			<? } ?>
		</select>
		<select class="form-control w-auto" name="status">
			<option value="all">- Trạng thái -</option>
			<option value="on" <?= $getStatus == 'on' ? 'selected="selected"' : '' ?>>Normal</option>
			<option value="deleted" <?= $getStatus == 'deleted' ? 'selected="selected"' : '' ?>>Canceled</option>
		</select>
		<select class="form-control w-auto" name="language">
			<option value="all">- Ngôn ngữ -</option>
			<option value="de" <?= $getLanguage == 'de' ? 'selected="selected"' : '' ?>>Deutsch</option>
			<option value="en" <?= $getLanguage == 'en' ? 'selected="selected"' : '' ?>>English</option>
			<option value="es" <?= $getLanguage == 'es' ? 'selected="selected"' : '' ?>>Español</option>
			<option value="fr" <?= $getLanguage == 'fr' ? 'selected="selected"' : '' ?>>Francais</option>
			<option value="it" <?= $getLanguage == 'it' ? 'selected="selected"' : '' ?>>Italiano</option>
			<option value="vi" <?= $getLanguage == 'vi' ? 'selected="selected"' : '' ?>>Tiếng Việt</option>
		</select>
		<select class="form-control w-auto" name="se">
			<option value="0">- Bán hàng -</option>
			<? foreach ($tourSes as $u) { ?>
			<option value="<?=$u['id']?>" <?=$u['id'] == $getSe ? 'selected="selected"' : '' ?>><?=$u['name']?></option>
			<? } ?>
		</select>
		<select class="form-control w-auto" name="op">
			<option value="0">- Điều hành -</option>
			<? foreach ($tourOps as $u) { ?>
			<option value="<?=$u['id']?>" <?=$u['id'] == $getOp ? 'selected="selected"' : '' ?>><?=$u['name']?></option>
			<? } ?>
		</select>
		<select class="form-control w-auto" name="cs">
			<option value="0">- CSKH -</option>
			<? foreach ($tourCss as $u) { ?>
			<option value="<?=$u['id']?>" <?=$u['id'] == $getCs ? 'selected="selected"' : '' ?>><?=$u['name']?></option>
			<? } ?>
		</select>
		<button type="submit" class="btn btn-primary">Tìm</button>
	</form>
	<div style="margin-left:0;" class="span8x">
		<table id="tourlist" class="table table-condensed">
			<thead><tr>
				<th width="30">#</th>
				<th width="30">Vào</th>
				<th width="30">Ra</th>
				<th width="">Code / Tên tour - <a class="fw-n" href="#" onclick="$('tr.paxLine').toggleClass('hide'); return false;">Ẩn / hiện danh sách khách</a></th>
				<th width="70">Ngày</th>
				<th width="70">Pax</th>
				<th>Bán hàng</th>
				<th>Điều hành</th>
				<th>CSKH</th>
			</tr></thead>
			<tbody>
			<?
			$dayIn = '';
			$cnt = 0; foreach ($monthTours as $t) {
				$t['cs'] = 0;
				$t['op'] = 0;
				if (1 == 1
				&& ($getStatus == 'all' || ($getStatus != 'all' && $t['status'] == $getStatus))
				&& ($getLanguage == 'all' || ($getLanguage != 'all' && $t['language'] == $getLanguage))
				&& ($getSe == 0 || ($getSe != 0 && $t['se'] == $getSe))
				&& ($getOp == 0 || ($getOp != 0 && isset($monthTourOpList[$t['id']]) && in_array($getOp, $monthTourOpList[$t['id']])))
				&& ($getCs == 0 || ($getCs != 0 && isset($monthTourCsList[$t['id']]) && in_array($getCs, $monthTourCsList[$t['id']])))
				) {
				$cnt ++;
				
			?>
			<tr style="background-color:#ffc;">
				<td class="ta-c quiet"><?=$cnt?></td>
				<td><?
				if ($dayIn != $t['day_from']) {
					$dayIn = $t['day_from'];
					echo substr($dayIn, -2);
				}
					?>
				</td>
				<td><?=date('d', strtotime($t['day_from'].' + '.($t['days'] - 1).'days'))?></td>
				<td>
					<?
					$flag = $t['language'];
					if ($t['language'] == 'en') $flag = 'us';
					if ($t['language'] == 'vi') $flag = 'vn';
					echo '<img src="http://my.amicatravel.com/images/flags/16x11/', $flag,'.png">';
					?>
					<?=$t['status'] == 'deleted' ? '<strong style="color:#c00;">(CXL)</strong> ' : ''?>
					<?=anchor('tours/r/'.$t['id'], $t['code'].' - '.$t['name'])?>
					
				</td>
				<td><?=anchor('tours/services/'.$t['id'], $t['days'].' ngày')?></td>
				<td><?=anchor('tours/pax/'.$t['id'], $t['pax'].' pax')?></td>
				<td>
					<?
					foreach ($tourSes as $u) {
						if ($u['id'] == $t['se']) { 
							echo anchor('tours?month='.$getMonth.'&se='.$u['id'], $u['name']);
							break;
						}
					}
					?>
				</td>
				<td>
					<?
					if (isset($monthTourOpList[$t['id']])) {
						foreach ($tourOps as $liTO) {
							if (in_array($liTO['id'], $monthTourOpList[$t['id']])) {
								echo anchor('tours?month='.$getMonth.'&op='.$liTO['id'], $liTO['name']);
								break;
							}
						}
					}
					?>
				</td>
				<td>
					<?
					if (isset($monthTourCsList[$t['id']])) {
						foreach ($tourCss as $liTO) {
							if (in_array($liTO['id'], $monthTourCsList[$t['id']])) {
								echo anchor('tours?month='.$getMonth.'&cs='.$liTO['id'], $liTO['name']);
								break;
							}
						}
					}
					?>
				</td>
			</tr>
			<? // Nhung khach di tour nay
			$pcnt = 0;
			foreach ($monthPax as $p) {
				if ($p['tour_id'] == $t['id']) {
					$pcnt ++;
				?>
			<tr class="paxLine hide">
				<td style="color:#999;"><?=$pcnt?></td>
				<td></td>
				<td></td>
				<td>
					<i class="fa fa-<?=$p['gender'] ?>"></i>
					<img src="http://my.amicatravel.com/assets/img/flags/16x11/<?=$p['country_code'] ?>.png" alt="<?=$p['country_code'] ?>">
					<?=anchor('users/r/'.$p['user_id'], $p['fname'].' / '.$p['lname'], 'title="Click để xem"')?>
					<?='<em>'.(date('Y') - $p['byear']).'</em>'?>
					<?
					$pBirthDay = strtotime(substr($getMonth, 0, 4).'-'.$p['bmonth'].'-'.$p['bday']);
					if ((strtotime($t['day_from']) <= $pBirthDay) && (strtotime(date('Y-m-d', strtotime('+'.($t['days'] - 1).' days', strtotime($t['day_from'])))) >= $pBirthDay))
					 echo '<span class="label label-important"><i class="icon-white icon-gift"></i> Sinh nhật '.$p['bday'].'/'.$p['bmonth'].'</span>';?>
				</td>
				<td colspan="5">
					<a title="Passeport" href="<?=DIR?>tours/edit-passport?user_id=<?=$p['user_id']?>&tour_id=<?=$p['tour_id']?>"><i class="icon-user"></i></a>
					<a title="Fiche" href="<?=DIR?>tours/edit-passport?user_id=<?=$p['user_id']?>&tour_id=<?=$p['tour_id']?>"><i class="icon-file"></i></a>
				</td>
			</tr>
				<?
				}
			}
			?>
			
			<? } // Conditions
			}
			?>
			<? if (count($monthTours) == 0) { ?>
			<tr><td colspan="7">Không có tour trong tháng này</td></tr>
			<? } ?>
			</tbody>
		</table>
	</div>
	<div class="span4 hide">
		<table class="table table-condensed">
			<thead><th colspan="4">Tour các tháng, số OK / số CXL:</th></thead>
			<tbody>
			<? foreach ($allMonthTourCount as $getMonthi) {
				$monthOKTotal = $getMonthi['total'];
				$monthCXTotal = 0;
				foreach ($allMonthCanceledTours as $cxt) {
					if ($cxt['ym'] == $getMonthi['ym']) {
						$monthOKTotal = $getMonthi['total'] - $cxt['total'];
						$monthCXTotal = $cxt['total'];
					}
				}
			?>
			<tr>
				<td width="20%"><a href="<?=DIR?>tours?se=<?=$getSe?>&op=<?=$getOp?>&cs=<?=$getCs?>&month=<?=$getMonthi['ym']?>" class="td-n" style="<?=$getMonthi['ym'] == $getMonth ? 'color:#333; font-weight:bold;' : 'color:#08f'?>"><?=$getMonthi['ym']?></a></td>
				<td width="60%">
					<div style="background:#9c9; float:left; height:10px; margin-top:3px; width:<?=round(100 * $monthOKTotal / $max_total)?>%;"></div>
					<div style="background:#c00; float:left; height:10px; margin-top:3px; width:<?=round(100 * $monthCXTotal / $max_total)?>%;"></div>
				</td>
				<td width="15%" style="text-align:right;"><?=$monthOKTotal?></td>
				<td width="5%" style="color:#c00; font-size:11px;"><?=$monthCXTotal == 0 ? '' : $monthCXTotal?></td>
			</tr>
			<? } ?>
			</tbody>
		</table>
	</div>
</div>
<style>
.fa-male {color:blue;}
.fa-female {color:purple;}
.form-control.w-auto {width:auto; display:inline;}
</style>
<?