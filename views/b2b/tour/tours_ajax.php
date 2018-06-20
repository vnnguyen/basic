<?
include('/var/www/__apps/my.amicatravel.com/views/fdb.php');
// Ajaxed
// if (!fRequest::isAjax()) show_error(404, 'Truy cập không hợp lệ');

if (isset($_POST['action']) && isset($_POST['dvtour_id']) && isset($_POST['tour_id']) && isset($_POST['formdata'])) {
	// Kiem tra tour
	$q = $db->query('select * from at_tours where id=%i limit 1', $_POST['tour_id']);
	if ($q->countReturnedRows() == 0) {
		die(json_encode(array('NOK', 'Tour not found: ['.$_POST['tour_id'].']')));
	}
	$t = $q->fetchRow();
	// Danh sach dieu hanh
	$q = $db->query('SELECT tu.*, u.name FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.tour_id=%i AND tu.role="operator" ORDER BY u.lname LIMIT 100', $t['id']);
	$tourOperators = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
	$tourOperatorIds = array();
	foreach ($tourOperators as $to) $tourOperatorIds[] = $to['user_id'];

	// Kiem tra quyen truy cap
	// TBA: ke toan van co quyen truy cap, chi can check o action
	// if (myID != $t['op']) die(json_encode(array('NOK', '1 - Access denied for tour : ['.$_POST['tour_id'].']')));
	// Kiem tra dvtour
	if ($_POST['dvtour_id'] != 0) {
		$q = $db->query('select * from cpt where dvtour_id=%i limit 1', $_POST['dvtour_id']);
		if ($q->countReturnedRows() == 0) die(json_encode(array('NOK', 'DVtour not found: ['.$_POST['dvtour_id'].']')));
		$dv = $q->fetchRow();
	}
	foreach ($_POST['formdata'] as $fd) {
		$_POST[$fd['name']] = $fd['value'];
	}
	// Action create
	if ($_POST['action'] == 'create') {
		if (!in_array(myID, $tourOperatorIds)) {
			die(json_encode(array('NOK', '2 - Access denied for tour : ['.$_POST['tour_id'].']')));
		}
		$fv = new hxFormValidation();
		$_POST['qty'] = str_replace(',', '', $_POST['qty']);
		$_POST['price'] = str_replace(',', '', $_POST['price']);
		$fv->setRules('dvtour_name', 'Tên dịch vụ', 'trim|required|max_length[64]');
		$fv->setRules('oppr', 'Đối tác / Cung cấp', 'trim|max_length[64]');
		$fv->setRules('venue_id', 'Chọn địa điểm (venue)', 'trim|required|is_natural');
		$fv->setRules('qty', 'Số lượng', 'trim|required|is_numeric');
		$fv->setRules('unit', 'Đơn vị', 'trim|required|max_length[64]');
		$fv->setRules('price', 'Đơn giá', 'trim|required|is_numeric');
		$fv->setRules('unitc', 'Đơn vị tiền tệ', 'trim|required|exact_length[3]');
		$fv->setRules('vat', 'VAT (%)', 'trim|required|is_natural');
		$fv->setRules('prebooking', 'Cần book trước hay không', 'trim|required');
		$fv->setRules('payer', 'Người trả', 'trim|required|max_length[64]');
		$fv->setRules('due', 'Hạn thanh toán', 'trim|exact_length[10]');
		$fv->setRules('status', 'Tình trạng đặt trước', 'trim|required|exact_length[1]');
		if ($fv->run()) {
			$q = $db->query('INSERT INTO cpt (uo, ub, tour_id, dvtour_day, dvtour_name, oppr,
				adminby, via_company_id, by_company_id, venue_id, start, number, qty, unit, price, unitc, vat, prebooking, payer, status, due, plusminus)
				VALUES (%s, %i, %i, %s, %s, %s, %s, %i, %i, %i, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', 
				NOW, myID, $_POST['tour_id'], $_POST['dvtour_day'], $_POST['dvtour_name'], $_POST['oppr'],
				$_POST['adminby'], $_POST['via_company_id'], $_POST['by_company_id'], $_POST['venue_id'], $_POST['start'], $_POST['number'],
				$_POST['qty'], $_POST['unit'], $_POST['price'], $_POST['unitc'], $_POST['vat'], $_POST['prebooking'], $_POST['payer'], $_POST['status'], $_POST['due'], $_POST['plusminus']
			);
			$newDvId = $q->getAutoIncrementedValue();

			// Save note if any
			if ($_POST['mm'] != '') {
				$db->query('INSERT INTO at_mm (uo, ub, rel_type, rel_id, pid, mm) VALUES (%s, %i, %s, %i, %i, %s)',
				NOW, myID, 'service', $newDvId, $_POST['tour_id'], $_POST['mm']
				);
			}

			die(json_encode(array('OK-CREATE', '', $newDvId, $_POST['dvtour_day'])));
		} else {
			die(json_encode(array('NOK',strip_tags($fv->getErrorMessage()))));
		}
	}

// Action copy
if ($_POST['action'] == 'copy') {
if (!in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', 'Action COPY is denied for tour : ['.$_POST['tour_id'].']')));
$fv = new hxFormValidation();
$_POST['qty'] = str_replace(',', '', $_POST['qty']);
$_POST['price'] = str_replace(',', '', $_POST['price']);
$fv->setRules('dvtour_name', 'Tên dịch vụ', 'trim|required|max_length[64]');
$fv->setRules('oppr', 'Đối tác / Cung cấp', 'trim|max_length[64]');
$fv->setRules('venue_id', 'Chọn địa điểm (venue)', 'trim|required|is_natural');
$fv->setRules('qty', 'Số lượng', 'trim|required|is_numeric');
$fv->setRules('unit', 'Đơn vị', 'trim|required|max_length[64]');
$fv->setRules('price', 'Đơn giá', 'trim|required|is_numeric');
$fv->setRules('unitc', 'Đơn vị tiền tệ', 'trim|required|exact_length[3]');
$fv->setRules('vat', 'VAT (%)', 'trim|required|is_natural');
$fv->setRules('prebooking', 'Cần book trước hay không', 'trim|required');
$fv->setRules('payer', 'Người trả', 'trim|required|max_length[64]');
$fv->setRules('due', 'Hạn thanh toán', 'trim|exact_length[10]');
$fv->setRules('status', 'Tình trạng đặt trước', 'trim|required|exact_length[1]');
if ($fv->run()) {
$q = $db->query('INSERT INTO cpt (uo, ub, tour_id, dvtour_day, dvtour_name, oppr,
adminby, via_company_id, by_company_id, venue_id, start, number, qty, unit, price, unitc, vat, prebooking, payer, status, due, plusminus)
VALUES (%s, %i, %i, %s, %s, %s, %s, %i, %i, %i, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', 
NOW, myID, $_POST['tour_id'], $_POST['dvtour_day'], $_POST['dvtour_name'], $_POST['oppr'],
$_POST['adminby'], $_POST['via_company_id'], $_POST['by_company_id'], $_POST['venue_id'], $_POST['start'], $_POST['number'],
$_POST['qty'], $_POST['unit'], $_POST['price'], $_POST['unitc'], $_POST['vat'], $_POST['prebooking'], $_POST['payer'], $_POST['status'], $_POST['due'], $_POST['plusminus']
);
$newDvId = $q->getAutoIncrementedValue();

// Save note if any
if ($_POST['mm'] != '') {
$db->query('INSERT INTO at_mm (uo, ub, rel_type, rel_id, pid, mm) VALUES (%s, %i, %s, %i, %i, %s)',
NOW, myID, 'service', $newDvId, $_POST['tour_id'], $_POST['mm']
);
}

die(json_encode(array('OK-COPY', '', $newDvId, $_POST['dvtour_day'])));
} else {
die(json_encode(array('NOK',strip_tags($fv->getErrorMessage()))));
}
}
// Action update
if ($_POST['action'] == 'update-prepare') {
//if (!in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', '3 - Access denied for tour : ['.$_POST['tour_id'].']')));
// 121108 Requires ub
// if (myID != $dv['ub']) die(json_encode(array('NOK', '4 - Access denied for tour : ['.$_POST['tour_id'].']')));
// Van con Mai Thuy ghost
if (!in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', '3 - Access denied for tour : ['.$_POST['tour_id'].']')));
die(json_encode(
array(
'OK-UPDATE-PREPARE',
$dv['dvtour_day'],
'',
$dv['dvtour_name'],
$dv['oppr'],
$dv['qty'],
$dv['unit'],
number_format($dv['price'], 0),
$dv['unitc'],
0,
$dv['vat'],
$dv['prebooking'],
$dv['payer'],
$dv['status'],
$dv['due'] != '0000-00-00' ? $dv['due'] : '',
$dv['venue_id'],
$dv['adminby'],
substr($dv['start'], 0, 2).substr($dv['start'], 3, 2),
$dv['number'],
$dv['via_company_id'],
$dv['by_company_id'],
$dv['plusminus'],
)
));
}

// Action update
if ($_POST['action'] == 'update') {
//die(json_encode(array('NOK', 'Tạm thời ngưng edit dv tour ; liên hệ Mr Huân')));
$myApprovers = explode('][', $dv['approved_by']);
if ($dv['approved_by'] != '' && count($myApprovers) >= 3) {
$mustSaveAs = true;
$saveAsId = $_POST['dvtour_id'];
}
// Phai la nguoi dieu hanh
// if (myID != $dv['ub'] || !in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', '4 - Access denied for tour : ['.$_POST['tour_id'].']')));

// Back to updater 121108
// if (myID != $dv['ub']) die(json_encode(array('NOK', '4 - Access denied for tour : ['.$_POST['tour_id'].']')));
// Since 120926 Ms Mai Thuy retires
if (!in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', '4 - Access denied for tour : ['.$_POST['tour_id'].']')));
// Dich vu phai chua duoc duyet [TRA][KTT][TGD]
if ($dv['xacnhan_by'] != 0) die(json_encode(array('NOK', 'Không thể sửa mục đã được kế toán trưởng đánh dấu [KTT]')));
if ($dv['duyet_by'] != 0) die(json_encode(array('NOK', 'Không thể sửa mục đã được TGĐ duyệt')));
$fv = new hxFormValidation();
$_POST['qty'] = str_replace(',', '', $_POST['qty']);
$_POST['price'] = str_replace(',', '', $_POST['price']);
$fv->setRules('dvtour_name', 'Tên dịch vụ', 'trim|required|max_length[64]');
$fv->setRules('oppr', 'Đối tác / Cung cấp', 'trim|max_length[64]');
$fv->setRules('venue_id', 'Chọn địa điểm (venue)', 'trim|required|is_natural');
$fv->setRules('qty', 'Số lượng', 'trim|required|is_numeric');
$fv->setRules('unit', 'Đơn vị', 'trim|required|max_length[64]');
$fv->setRules('price', 'Đơn giá', 'trim|required|is_numeric');
$fv->setRules('unitc', 'Đơn vị tiền tệ', 'trim|required|exact_length[3]');
$fv->setRules('vat', 'VAT (%)', 'trim|required|is_natural');
$fv->setRules('prebooking', 'Phải đặt trước DV', 'trim|required');
$fv->setRules('payer', 'Người trả', 'trim|required|max_length[64]');
$fv->setRules('due', 'Hạn thanh toán', 'trim|exact_length[10]');
$fv->setRules('status', 'Tình trạng đặt trước', 'trim|required|exact_length[1]');
$fv->setRules('mm', 'Note', 'trim|htmlspecialchars');
if ($fv->run()) {
if (isset($mustSaveAs)) {
// Nếu dv cũ đã có từ 3 người duyệt trở lên, phải insert dv mới
$q = $db->query('INSERT INTO cpt (uo, ub, tour_id, dvtour_day, dvtour_name, oppr, venue_id, qty, unit, price, unitc, vat, prebooking, payer, status, due)
VALUES (%s,%i,%i,%s,%s,%s,%i,%s,%s,%s,%s,%s,%s,%s,%s,%s)',
NOW,
myID,
$_POST['tour_id'],
$_POST['dvtour_day'],
$_POST['dvtour_name'],
$_POST['oppr'],
$_POST['venue_id'],
$_POST['qty'],
$_POST['unit'],
$_POST['price'],
$_POST['unitc'],
$_POST['vat'],
$_POST['prebooking'],
$_POST['payer'],
'n',
$_POST['due']
);
// Thay doi cac latest cua cu sang moi: bản mới nhất là dv vừa ghi lại
$newId = $q->getAutoIncrementedValue();
$db->query('UPDATE cpt SET latest=%s WHERE latest=%s OR dvtour_id=%i', $newId, $_POST['dvtour_id'], $_POST['dvtour_id']);
} else {
$db->query('UPDATE cpt SET uo=%s, ub=%i, approved=0, approved_by="", dvtour_day=%s, dvtour_name=%s, oppr=%s, adminby=%s, start=%s, number=%s, via_company_id=%i, by_company_id=%i, venue_id=%i, qty=%s, unit=%s, price=%s, unitc=%s, vat=%s, prebooking=%s, payer=%s, status=%s, due=%s, plusminus=%s WHERE dvtour_id=%s LIMIT 1',
NOW, myID, $_POST['dvtour_day'], $_POST['dvtour_name'], $_POST['oppr'], $_POST['adminby'], $_POST['start'].'00', $_POST['number'], $_POST['via_company_id'], $_POST['by_company_id'], $_POST['venue_id'], $_POST['qty'], $_POST['unit'], $_POST['price'], $_POST['unitc'], $_POST['vat'], $_POST['prebooking'], $_POST['payer'], $_POST['status'], $_POST['due'], $_POST['plusminus'], $_POST['dvtour_id']
);
}

// Save note if any
if ($_POST['mm'] != '') {
	$db->query('INSERT INTO at_mm (uo, ub, rel_type, rel_id, pid, mm) VALUES (%s, %i, %s, %i, %i, %s)',
		NOW, myID, 'service', $_POST['dvtour_id'], $_POST['tour_id'], $_POST['mm']
	);
}
// Bao cho nhung nguoi lien quan, neu co
/*
if ($dv['approved_by'] != '') {
foreach ($myApprovers as $myApprover) {
$myApprover = trim(trim($myApprover, '['), ':');
// if ($myApprover != myID) $db->query('INSERT INTO chat (`from`, `to`, `message`, `sent`) VALUES (?,?,?,NOW())', array(myID, $myApprover, 'Dịch vụ <a href="'.SITE_HOME.'tours-dvtour/'.$_POST['tour_id'].'#day'.$_POST['dvtour_day'].'">'.$t['tour_code'].' : '.$_POST['dvtour_day'].' : '.$_POST['dvtour_name'].'</a> đã thay đổi. Đề nghị duyệt lại.'));
}
}
*/
die(json_encode(array('OK-UPDATE', '', $_POST['dvtour_id'], $_POST['dvtour_day'])));
} else {
die(json_encode(array('NOK',$fv->getErrorMessage())));
}
die(json_encode(array('NOK', 'Welcome to 2012')));
}

// Action delete
if ($_POST['action'] == 'delete') {
	if (myID != $dv['ub']) die(json_encode(array('NOK', 'Access denied for tour : ['.$_POST['tour_id'].']')));
	if ($dv['xacnhan_by'] != 0) die(json_encode(array('NOK', 'Không thể sửa mục đã được kế toán trưởng đánh dấu [KTT]')));
	// Delete all mm
	$db->query('DELETE FROM at_mm WHERE rel_type="service" AND rel_id=%i LIMIT 1', $_POST['dvtour_id']);
	// Delete dvt
	$db->query('DELETE FROM cpt WHERE dvtour_id=%s LIMIT 1', $_POST['dvtour_id']);
	if ($dv['plusminus'] == 'plus') {
		$newTotalCost = (int)$_POST['total'] - $dv['qty']*$dv['price']*$xRates[$dv['unitc']]*(1+$dv['vat']/100);
	} else {
		$newTotalCost = (int)$_POST['total'] + $dv['qty']*$dv['price']*$xRates[$dv['unitc']]*(1+$dv['vat']/100);
	}
	die(json_encode(array('OK-DELETE', '', number_format($newTotalCost, 2))));
}

// Action mark [ok]
if ($_POST['action'] == 'ok') {
	// Chi dieu hanh co quyen
	if (myID != $dv['ub'] || !in_array(myID, $tourOperatorIds)) die(json_encode(array('NOK', 'Chỉ dành cho người điều hành tour')));
	// Chi co the sua neu Mr Manh chua duyet
	if ($dv['duyet_by'] != 0) die(json_encode(array('NOK', 'Không thể sửa mục đã được TGĐ duyệt')));
	// Đã được duyệt thì bỏ
	if ($dv['status'] == 'k') {
		// Check it
		$q = $db->query('UPDATE cpt SET status="n" WHERE dvtour_id=%s LIMIT 1', $_POST['dvtour_id']);
		if ($q->countAffectedRows() == 1) die(json_encode(array('OK-OK', '')));
	} else {
		// Disapprove it
		$q = $db->query('UPDATE cpt SET status="k" WHERE dvtour_id=%s LIMIT 1', $_POST['dvtour_id']);
		if ($q->countAffectedRows() == 1) die(json_encode(array('OK-OK', 'xacnhan')));
	}
	die(json_encode(array('NOK', 'Không thực hiện được thao tác.')));
}

}
die(json_encode(array('NOK', 'Operation failed')));
