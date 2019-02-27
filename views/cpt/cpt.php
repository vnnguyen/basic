<?php
use app\widgets\LinkPager;

use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_cpt_inc.php');


Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'shopping-bag', 'label'=>'TT nhiều mục (<span id="dntt_count"></span>)', 'class'=>'text-info', 'link'=>'cpt/thanh-toan', 'active'=>SEG2=='thanh-toan'],
    ],[
        ['icon'=>'money', 'label'=>'Cpt đã TT', 'link'=>'cpt/da-thanh-toan', 'active'=>SEG2=='da-thanh-toan'],
    ]
];
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Chi phí tour';
Yii::$app->params['page_small_title'] = number_format($pagination->totalCount).' dòng';
Yii::$app->params['page_breadcrumbs'][] = ['Tour costs', '@web/cpt'];

if ($theTour) {
    $sql = 'SELECT u.nickname AS name FROM users u, at_tour_user tu WHERE u.id=tu.user_id AND tu.role="operator" AND tu.tour_id=:id';
    $staffListResults = \Yii::$app->db->createCommand($sql, [':id'=>$theTour['id']])->queryAll();
    $staffList = [];
    foreach ($staffListResults as $user) {
        $staffList[] = $user['name'];
    }

    $paxCount = 0;
    foreach ($theTour['product']['bookings'] as $booking) {
        $paxCount += $booking['pax'];
    }

    Yii::$app->params['page_title'] = 'Chi phí tour '.$theTour['code'].' - '.$theTour['name'].' - '.$paxCount.' pax ('.number_format($pagination->totalCount).' dòng)';
    Yii::$app->params['page_small_title'] = 'by '.implode(', ', $staffList);
    Yii::$app->params['page_breadcrumbs'][] = [$theTour['code'], '@web/cpt?tour='.$theTour['id']];
}

// Array for filters
$filterArray = [
    'v'=>[],
    'vc'=>[],
    'bc'=>[],
    'p'=>[],
    'n'=>[],
];
foreach ($theCptx as $cpt) {
    if (!isset($filterArray['p'][md5($cpt['payer'])])) {
        $filterArray['p'][md5($cpt['payer'])] = $cpt['payer'];
    }
    if ($cpt['venue_id'] != 0) {
        if (!isset($filterArray['v'][md5($cpt['venue_id'])])) {
            $filterArray['v'][md5($cpt['venue_id'])] = $cpt['venue']['name'];
        }
    } elseif ($cpt['via_company_id'] != 0) {
        if (!isset($filterArray['vc'][md5($cpt['via_company_id'])])) {
            $filterArray['vc'][md5($cpt['via_company_id'])] = $cpt['viaCompany']['name'];
        }
    } elseif ($cpt['by_company_id'] != 0) {
        if (!isset($filterArray['bc'][md5($cpt['by_company_id'])])) {
            $filterArray['bc'][md5($cpt['by_company_id'])] = $cpt['company']['name'];
        }
    } else {
        if (!isset($filterArray['n'][md5($cpt['oppr'])])) {
            $filterArray['n'][md5($cpt['oppr'])] = $cpt['oppr'];
        }
    }
}

$ketoan = [
    '1'=>'Huân',
    '4065'=>'Tuấn',
    '28431'=>'Tú Phương',
    '11'=>'Hiền',
    '17'=>'Hạnh',
    '16'=>'Lan',
    '20787'=>'Bình',
    '29739'=>'Huyền',
    '30085'=>'Ngọc',
    '32206'=>'Mong',
];
$check = [
    'c3'=>'TH/TOAN',
];

$viewvat = $_GET['viewvat'] ?? 'no';

// NCC with VAT
$sql = 'SELECT venue_id FROM at_atuan_codes WHERE vat="vat" AND venue_id!=0';
$vatList = \Yii::$app->db->createCommand($sql)->queryColumn();

?>
<div class="col-md-12">
    <form class="form-inline mb-2" id="formx">
        <?= Html::textInput('tour', $tour, ['class'=>'form-control', 'placeholder'=>'Code/ID/Tháng tour']) ?>
        <?= Html::textInput('dvtour', $dvtour, ['class'=>'form-control', 'placeholder'=>'Ngày/Tháng sử dụng']) ?>
        <?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Tên nhà cung cấp/chi phí']) ?>
        <?php if ($theTour) { ?>
        <select name="filter" style="width:400px" class="form-control">
            <option value="">- chọn xem theo tên nhà cung cấp -</option>
            <optgroup label="Tên do điều hành nhập vào">
                <?
                asort($filterArray['n']);
                foreach ($filterArray['n'] as $k=>$v) { ?>
                <option value="hn-<?=$k?>" <?= $filter == 'hn-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                <?php } ?>
            </optgroup>
            <optgroup label="Tên do IMS tự động link">
                <?
                asort($filterArray['v']);
                foreach ($filterArray['v'] as $k=>$v) { ?>
                <option value="hi-<?=$k?>" <?= $filter == 'hi-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                <?php } ?>
            </optgroup>
            <optgroup label="Công ty cung cấp dịch vụ">
                <?php foreach ($filterArray['vc'] as $k=>$v) { ?>
                <option value="hv-<?=$k?>" <?= $filter == 'hv-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                <?php } ?>
                <?php foreach ($filterArray['bc'] as $k=>$v) { ?>
                <option value="hb-<?=$k?>" <?= $filter == 'hb-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                <?php } ?>
            </optgroup>
            <optgroup label="Ai trả tiền">
                <?php foreach ($filterArray['p'] as $k=>$v) { ?>
                <option value="hp-<?=$k?>" <?=$filter == 'hp-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
                <?php } ?>
                <option value="miennam" <?=$filter == 'miennam' ? 'selected="selected"' : '' ?>>Miền Nam (HDMN & VPSG)</option>
                <option value="mueanglaos" <?=$filter == 'mueanglaos' ? 'selected="selected"' : '' ?>>Laos (toàn bộ)</option>
            </optgroup>
        </select>
        <?php } ?>
        <?= Html::dropdownList('sign', $sign, [''=>'+/-', 'plus'=>'+', 'minus'=>'-'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('currency', $currency, [''=>'Tiền', 'eur'=>'EUR', 'usd'=>'USD', 'vnd'=>'VND', 'lak'=>'LAK', 'khr'=>'KHR', 'thb'=>'THB'], ['class'=>'form-control']) ?>
        <?php if (!$theTour) { ?>
        <select class="form-control" name="payer">
            <option value="">Người TT</option>
            <?php foreach ($payerList as $pay) { ?>
            <option value="<?= $pay['payer'] ?>" <?= $pay['payer'] == $payer ? 'selected="selected"' : '' ?>><?= $pay['payer'] ?></option>
            <?php } ?>
            <option value="miennam" <?= $pay['payer'] == 'miennam' ? 'selected="selected"' : '' ?>>Miền Nam (HDMN & VPSG)</option>
            <option value="mueanglaos" <?= $pay['payer'] == 'mueanglaos' ? 'selected="selected"' : '' ?>>Laos (toàn bộ)</option>
        </select>
        <?php } ?>
        <?= Html::dropdownList('tt', $tt, [
        ''=>'Tình trạng TT',
        'no'=>'Chưa TT',
        'overdue'=>'Chưa TT, quá hạn',
        'yes'=>'Đã TT',
        'c3'=>'Đã TT, chưa DZ',
        'c4'=>'Đã TT, đã DZ',
        ], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('vat', $vat, [''=>'Hoá đơn VAT', 'nok'=>'Chưa lấy hoá đơn VAT', 'ok'=>'Đã lấy hoá đơn VAT'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('viewvat', $viewvat, ['no'=>'Gộp thuế', 'yes'=>'Tách thuế'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('orderby', $orderby, ['updated_at'=>'Xếp theo ngày sửa', 'plus'=>'Xếp theo ngày tour', 'tt'=>'Xếp theo hạn TT'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('limit', $limit, [25=>'25 dòng', 50=>'50 dòng', 100=>'100 dòng', 500=>'500 dòng', 1000=>'Toàn bộ'], ['class'=>'form-control']) ?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Reset', '@web/cpt') ?>
        |
        <a href="#" onclick="$('#help').toggle(); return false;">Chỉ dẫn</a>
    </form>
    <div class="alert alert-info" style="display:none;" id="help">
        <strong>Chỉ dẫn</strong> Cách chọn xem các dịch vụ
        <br>- Tour: tháng khởi hành (dạng yyyy-mm), code tour, hoặc ID tour. Vd 2016-01 (tháng khởi hành), F1510 (môt phần code), F1509051 (toàn bộ code), 12780 (ID). Chú ý: có thể ra nhiều tour.
        <br>- Ngày tháng sử dụng dịch vụ: dạng yyyy-mm-dd hoặc yyyy-mm. Vd 2016-01-01 hoặc 2016-01.
        <br>- Tên: tên nhà cung cấp (kể cả được link hay do nhập tay) hoặc tên dịch vụ. Viết @tên (có dấu @ ở đầu) để chỉ tìm tên nhà cung cấp, không tìm tên dịch vụ.
        <br>- Nếu kết quả ra 1 tour, các chi phí được sắp xếp theo từng ngày tour.
        <br><strong>Quy tắc phân quyền check</strong>
        <br>- Điều hành không sửa được chi phí khi đã có bất kỳ mục nào được check
        <br>- KTT được check hay bỏ check mọi mục, nhân viên chỉ có thể bỏ check của mình
        <br>- Phần Check thanh toán: chỉ check được mục bên trái nếu chưa có mục nào bên phải được check
        <br>- Phần Check (new): chỉ TT nếu C1 hoặc C2 đã check
        <br><strong>Chú ý thêm</strong>
        <br>- Kế toán chỉ check chi phí tour từ trang này, không thể check được từ trang tour như trước
        <br>- Các mục check trước đây không được sử dụng nữa (TT, VAT, TRA, KTT, GĐ)
        <br>- Click vào số ID (cột đầu tiên của mỗi dòng) để xem / thêm / xoá các ghi chú, và xem chi tiết ai check gì vào lúc nào
    </div>
    <?php if (empty($theCptx)) { ?>
    <div class="alert alert-warning">No data found</div>
    <?php } else { ?>
    <div class="card table-responsive position-relative">
        <table id="tbl-cpt" class="table table-narrow -table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <?php if (!$theTour) { ?>
                    <th>Tour</th>
                    <th>Ngày dv</th>
                    <?php } ?>
                    <th>Tên dv/cpt @Địa điểm $Nhà cung cấp</th>
                    <th>SL</th>
                    <th>Đ/vị</th>
                    <th class="text-right">x giá</th>
                    <?php if ($viewvat == 'yes') { ?>
                    <!-- TACH COT THUE -->
                    <th class="text-right">Chưa VAT</th>
                    <th class="text-right">VAT</th>
                    <?php } ?>
                    <th class="text-right">Thành tiền</th>
                    <th>Thanh toán</th>
                    <th>Mã Tài Khoản</th>
                    <th>Hạn TT / Đã TT</th>
                    <th>XN</th>
                    <th class="Thanh toán">TT</th>
                    <th>HĐ</th>
                    <th style="width: 80px" class="text-center">
                        <label class="m-1 w-100 h-100">
                          <div id="wrap-check" class="input-group-btn ">
                            <input type="checkbox" class="">
                            <!-- <a  class="dropdown-toggle option_check" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#">this page</a></li>
                                <li><a href="#">all pages</a></li>
                            </ul> -->
                          </div><!-- /btn-group -->
                        </label><!-- /input-group -->
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total['all'] = 0;
                $total['vnd'] = 0;
                $total['usd'] = 0;
                $total['eur'] = 0;
                $total['lak'] = 0;
                $total['khr'] = 0;
                $total['thb'] = 0;
                $xrates['usd'] = 21250;
                $xrates['eur'] = 28250;
                $xrates['vnd'] = 1;
                $xrates['lak'] = 2.78;
                $xrates['khr'] = 5.61;
                $xrates['thb'] = 683.45;

                if ($theTour) {
                    // Mot so tour co ngay chi phi khong nam trong CT
                    $hasCptBefore = false;
                    foreach ($theCptx as $cpt) {
                        if (strtotime($cpt['dvtour_day']) < strtotime($theTour['product']['day_from'])) {
                            if (!$hasCptBefore) {
                                $hasCptBefore = true;
                                ?>
                <tr class="warning">
                    <td></td>
                    <td colspan="13">Chi phí với ngày dv nằm ngoài CT tour</td>
                </tr>
                                <?php
                            }

                            $hashedOppr = 'hn-'.md5($cpt['oppr']);
                            $hashedVenueId = 'hi-'.md5($cpt['venue_id']);
                            $hashedByCId = 'hb-'.md5($cpt['by_company_id']);
                            $hashedViaCId = 'hv-'.md5($cpt['via_company_id']);
                            $hashedPayer = 'hp-'.md5($cpt['payer']);
                            if (
                                    $filter == ''
                                    || ($filter == 'miennam' && in_array($cpt['payer'], ['Amica Saigon', 'Hướng dẫn MN 1', 'Hướng dẫn MN 2', 'Hướng dẫn MN 3']))
                                    || ($filter == 'mueanglaos' && in_array($cpt['payer'], ['Laos BCEL', 'BCEL Laos', 'Medsanh (Laos)', 'Thonglish (Laos)', 'Feuang (Laos)', 'Amica Luang Prabang', 'iTravelLaos', 'Hướng dẫn Laos 1', 'Hướng dẫn Laos 2', 'Hướng dẫn Laos 3']))
                                    || $filter == $hashedOppr
                                    || $filter == $hashedOppr
                                    || $filter == $hashedVenueId
                                    || $filter == $hashedByCId
                                    || $filter == $hashedViaCId
                                    || $filter == $hashedPayer
                                ) {
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
                                // BEGIN LINE
                                $sign = $cpt['plusminus'] == 'plus' ? 1 : -1;
                                $cur = strtolower($cpt['unitc']);
                                $total[$cur] += $sign * $cpt['price'] * $cpt['qty'];
                                $total['all'] += $xrates[$cur] * $sign * $cpt['price'] * $cpt['qty'];
                                include('cpt__tr.php');
                            }
                        }
                    } // foreach cptx

                    $dayIdList = explode(',', $theTour['product']['day_ids']);
                    $cnt = 0;
                    $totalVND = 0;
                    foreach ($dayIdList as $di) {
                        foreach ($theTour['product']['days'] as $day) {
                            if ($day['id'] == $di) {
                                $currentDay = date('d-m-Y', strtotime('+'.$cnt.' day', strtotime($theTour['product']['day_from'])));
                                $currentDOW = Yii::$app->formatter->asDate($currentDay, 'php:j/n l');
                                $cnt ++;
                        ?>
                <tr class="alpha-info">
                    <td class="text-center text-muted" title="ID:<?= $day['id'] ?>"><?= $cnt ?></td>
                    <td colspan="13">
                        <i class="fa fa-file-text-o popovers text-muted"
                            data-trigger="hover"
                            data-placement="right"
                            data-html="true"
                            data-title="<?= $currentDOW ?> | <?= Html::encode($day['name']) ?> (<?= $day['meals'] ?>)"
                            data-content="
                        <p><?= Html::encode(Markdown::process($day['body'])) ?></p>
                        "></i>
                        <strong><?= $currentDOW ?></strong>
                        <?= $day['name'] ?>
                        (<?= $day['meals'] ?>)
                        <?= Html::a('Dịch', 'https://translate.google.com/#fr/vi/'.urlencode(str_replace(['_', '*'], [' ', ' '], $day['body'])), ['rel'=>'external', 'title'=>'Dịch bằng Google']) ?>
                    </td>
                </tr>
                                <?php
                                foreach ($theCptx as $cpt) {
                                    $hashedOppr = 'hn-'.md5($cpt['oppr']);
                                    $hashedVenueId = 'hi-'.md5($cpt['venue_id']);
                                    $hashedByCId = 'hb-'.md5($cpt['by_company_id']);
                                    $hashedViaCId = 'hv-'.md5($cpt['via_company_id']);
                                    $hashedPayer = 'hp-'.md5($cpt['payer']);
                                    if (
                                        $currentDay == date('d-m-Y', strtotime($cpt['dvtour_day'])
                                        ) && (
                                            $filter == ''
                                            || ($filter == 'miennam' && in_array($cpt['payer'], ['Amica Saigon', 'Hướng dẫn MN 1', 'Hướng dẫn MN 2', 'Hướng dẫn MN 3']))
                                            || ($filter == 'mueanglaos' && in_array($cpt['payer'], ['Laos BCEL', 'BCEL Laos', 'Medsanh (Laos)', 'Thonglish (Laos)', 'Feuang (Laos)', 'Amica Luang Prabang', 'iTravelLaos', 'Hướng dẫn Laos 1', 'Hướng dẫn Laos 2', 'Hướng dẫn Laos 3']))
                                            || $filter == $hashedOppr
                                            || $filter == $hashedOppr
                                            || $filter == $hashedVenueId
                                            || $filter == $hashedByCId
                                            || $filter == $hashedViaCId
                                            || $filter == $hashedPayer
                                            )
                                        ) {
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
                                        // BEGIN LINE
                                        $sign = $cpt['plusminus'] == 'plus' ? 1 : -1;
                                        $cur = strtolower($cpt['unitc']);
                                        $total[$cur] += $sign * $cpt['price'] * $cpt['qty'];
                                        $total['all'] += $xrates[$cur] * $sign * $cpt['price'] * $cpt['qty'];
                                        include('cpt__tr.php');
                                    }
                                } // foreach cptx
                            } // if day[id] = day id
                        } // foreach day
                    } // foreach dayIdList

                    // Mot so tour co ngay chi phi khong nam trong CT
                    $hasCptAfter = false;
                    foreach ($theCptx as $cpt) {
                        if (strtotime($cpt['dvtour_day']) > strtotime('+'.$cnt.' day', strtotime($theTour['product']['day_from']))) {
                            if (!$hasCptAfter) {
                                $hasCptAfter = true;
                                ?>
                <tr class="warning">
                    <td></td>
                    <td colspan="13">Chi phí với ngày dv nằm ngoài CT tour</td>
                </tr>
                                <?php
                            }

                            $hashedOppr = 'hn-'.md5($cpt['oppr']);
                            $hashedVenueId = 'hi-'.md5($cpt['venue_id']);
                            $hashedByCId = 'hb-'.md5($cpt['by_company_id']);
                            $hashedViaCId = 'hv-'.md5($cpt['via_company_id']);
                            $hashedPayer = 'hp-'.md5($cpt['payer']);
                            if (
                                    $filter == ''
                                    || ($filter == 'miennam' && in_array($cpt['payer'], ['Amica Saigon', 'Hướng dẫn MN 1', 'Hướng dẫn MN 2', 'Hướng dẫn MN 3']))
                                    || ($filter == 'mueanglaos' && in_array($cpt['payer'], ['Laos BCEL', 'BCEL Laos', 'Medsanh (Laos)', 'Thonglish (Laos)', 'Feuang (Laos)', 'Amica Luang Prabang', 'iTravelLaos', 'Hướng dẫn Laos 1', 'Hướng dẫn Laos 2', 'Hướng dẫn Laos 3']))
                                    || $filter == $hashedOppr
                                    || $filter == $hashedOppr
                                    || $filter == $hashedVenueId
                                    || $filter == $hashedByCId
                                    || $filter == $hashedViaCId
                                    || $filter == $hashedPayer
                                ) {
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
                                // BEGIN LINE
                                $sign = $cpt['plusminus'] == 'plus' ? 1 : -1;
                                $cur = strtolower($cpt['unitc']);
                                $total[$cur] += $sign * $cpt['price'] * $cpt['qty'];
                                $total['all'] += $xrates[$cur] * $sign * $cpt['price'] * $cpt['qty'];
                                include('cpt__tr.php');
                            }
                        }
                    } // foreach cptx
                } else {

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
                        include('cpt__tr.php');
                    }
                } // if theTour

?>
                <tr>
                    <td colspan="<?= $theTour ? '3' : '6' ?>" class="text-right">Tổng tiền
                        <div class="text-muted">Tỉ giá tạm tính: 1 EUR = 28,250 VND | 1 USD = 21,250 VND</div>
                    </td>
                    <td class="text-right" colspan="2">
                        <?php if ($total['vnd'] != 0) { ?>
                        <div>
                            <span class="text-pink"><strong><?= number_format($total['vnd'], 2) ?></strong></span>
                            <span class="text-muted">VND</span>
                        </div>
                        <?php } ?>
                        <?php if ($total['usd'] != 0) { ?>
                        <div>
                            <span class="text-orange"><strong><?= number_format($total['usd'], 2) ?></strong></span>
                            <span class="text-muted">USD</span>
                        </div>
                        <?php } ?>
                        <?php if ($total['eur'] != 0) { ?>
                        <div>
                            <span class="text-info"><strong><?= number_format($total['eur'], 2) ?></strong></span>
                            <span class="text-muted">EUR</span>
                        </div>
                        <?php } ?>
                        <?php if ($total['lak'] != 0) { ?>
                        <div>
                            <span class="text-brown"><strong><?= number_format($total['lak'], 2) ?></strong></span>
                            <span class="text-muted">LAK</span>
                        </div>
                        <?php } ?>
                        <?php if ($total['khr'] != 0) { ?>
                        <div>
                            <span class="text-slate"><strong><?= number_format($total['khr'], 2) ?></strong></span>
                            <span class="text-muted">KHR</span>
                        </div>
                        <?php } ?>
                        <?php if ($total['thb'] != 0) { ?>
                        <div>
                            <span class="text-slate"><strong><?= number_format($total['thb'], 2) ?></strong></span>
                            <span class="text-muted">THB</span>
                        </div>
                        <?php } ?>
                    </td>
                    <td colspan="4">
                        <div class="text-success text-right" style="font-size:28px">
                            =
                            <strong><?= number_format($total['all'], 2) ?></strong>
                            <span class="text-muted">VND</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <style>
            #action-buttons {  display: none; max-width: 50px;}
            #action-buttons button {width: 100%; margin-top: 10px;}
            #edit-form-modal .dialog-lg {max-width: 400px}

        </style>
        <div id="action-buttons" class="position-fixed" style="right: 0">
            <button type="button" class="btn btn-warning" id="bt_vat">VAT</button>
            <button type="button" class="btn btn-info" id="bt_tk">TK</button>
        </div>
    </div>

    <?= LinkPager::widget([
    'pagination' => $pagination,
    'firstPageLabel' => '<<',
    'prevPageLabel' => '<',
    'nextPageLabel' => '>',
    'lastPageLabel' => '>>',
    ]) ?>

    <?php } ?>
</div>
<!-- options Modal -->
    <div class="modal fade" id="edit-form-modal" role="dialog">
        <div class="modal-dialog dialog-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <!-- <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Option</h4>
                </div> -->
                <div class="modal-body">
                    <form id="edit-form">
                        <div class="row group-vat">
                            <div class="col-md-8 form-group">
                            <?= Html::textInput('vat', 0, ['class'=>'form-control numberOnly', 'type' => 'text']) ?>
                            </div>
                            <div class="col-md-4 form-group">
                            <?= Html::dropdownList('unit', '', ['VND'=>'VND', '%'=>'%'], ['class'=>'form-control']) ?>
                            </div>
                        </div>

                        <div class="form-group tk">
                        <?= Html::textInput('tk', '', ['class'=>'form-control', 'placeholder'=>'Code']) ?>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit" id="optionSave" name="optionsave">
                    <button class="btn btn-default" data-dismiss="modal" id="btn-close-option">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>
<!--end options Modal -->
<style>
.badge.cpt {cursor:pointer; color:#fff;}
.badge.cpt.on {background-color:#393;}
.badge.cpt.off {background-color:#ccc;}
.badge.cpt.off.dirty {background-color:#baa;}
.badge.cpt-gd {background-color:#ccc; color:#fff; cursor:pointer;}
.badge.cpt-gd.xacnhan {background-color:#393; color:#fff; cursor:pointer;}
.badge.cpt-ktt {background-color:#ccc; color:#fff; cursor:pointer;}
.badge.cpt-ktt.xacnhan {background-color:#393; color:#fff; cursor:pointer;}
.badge.cpt-tra {background-color:#ccc; color:#fff; cursor:pointer;}
.badge.cpt-tra.pct50 {background-color:#cfc; color:#fff;}
.badge.cpt-tra.pct100 {background-color:#393; color:#fff;}
.badge.cpt-vat {background-color:#ccc; color:#fff; cursor:pointer;}
.badge.cpt-vat.pct50 {background-color:#cfc; color:#fff;}
.badge.cpt-vat.pct100 {background-color:#393; color:#fff;}
.popover {max-width:700px;}

.form-control.select2-container {height:34px!important;}
    .select2-container .select2-choice {height:32px; line-height:32px; background-image:none!important;}
    .select2-container .select2-choice .select2-arrow {background:none!important;}
</style>
<?
$js = <<<TXT
var cpts_selected = [], edit_action = '', tr_selected_class="alpha-success";

    $('#wrap-check').find('input[type="checkbox"]').click(function(){
        var clicked = $(this);
        if ($(this).prop('checked')) {
            $.each($('#tbl-cpt').find('input[type="checkbox"]'), function(index, item){
                $(this).prop('checked', true);
                if ($(item).data('dvtour_id') > 0) {
                    cpts_selected.push($(item).data('dvtour_id'));
                    $(item).closest('tr').addClass(tr_selected_class);
                }
            });
            if (cpts_selected.length > 0) {
                $('#action-buttons').show();
            }

        } else {
            $('#tbl-cpt tbody tr').removeClass(tr_selected_class);
            $('#action-buttons').hide();
            cpts_selected = [];
            edit_action = '';
            $.each($('#tbl-cpt').find('input[type="checkbox"]'), function(index, item){
                $(this).prop('checked', false);
            });
        }
        $('#wrap-check').find('li a').removeClass('active');
        $('#wrap-check').find('li a:first').addClass('active');
    });
    $('#tbl-cpt tbody input[type="checkbox"]').on('click', function(e){
        console.log(e);
        var has_selected = false;
        cpts_selected = [];
        $.each($('#tbl-cpt tbody input[type="checkbox"]'), function(index, item){
            if ($(item).prop("checked")) {
                $(item).closest('tr').addClass(tr_selected_class);
                has_selected = true;
                if ( $(item).data('dvtour_id') > 0) {
                    cpts_selected.push($(item).data('dvtour_id'));
                }
            } else {
                $(item).closest('tr').removeClass(tr_selected_class);
            }
        });
        if (!has_selected) {
            $('#action-buttons').hide();
        } else {
            $('#action-buttons').show();
        }
    });

    $('#action-buttons button').on('click', function(){
        if($(this).prop('id') == 'bt_vat')
            edit_action = 'vat';
        else
            edit_action = 'tk';
        $('#edit-form-modal').modal('show');
    });
    $('#edit-form-modal').on('show.bs.modal', function() {
        $('.group-vat, .tk').hide();
        if( edit_action == 'vat') {
            $('.group-vat').show().find('input').focus();
        } else {
            $('.tk').show().find('input[type="text"]').focus();
        }
    });
    $('#edit-form-modal').on('hide.bs.modal', function() {
        if (edit_action == '') {
            cpts_selected = [];
            reset_select();
        }
    });
    function reset_select()
    {
        $.each($('#tbl-cpt').find('input[type="checkbox"]'), function(index, item){
            $(item).prop('checked', false);
            $(item).closest('tr').removeClass(tr_selected_class);
        });
    }
    $('#tbl-cpt tbody tr td.vat, #tbl-cpt tbody tr td.m-tk').dblclick(function(){
        var clicked = $(this);
        cpts_selected = [];
        clicked.prop('contenteditable', true).focus().addClass('bg-white');
        clicked.closest('tr').addClass(tr_selected_class);
        var dvtour_id = clicked.closest('tr').data('dvtour_id');
        if (! dvtour_id > 0) { return false;}
        cpts_selected.push(dvtour_id);
        if (clicked.text() == 'NO VAT' || clicked.text() == 'NO TK') {
            clicked.text('');
        }
        if (clicked.hasClass('vat')) {
            edit_action = 'vat';
        }
        if (clicked.hasClass('m-tk')) {
            edit_action = 'tk';
        }
    });
    $('#tbl-cpt tbody tr td.vat, #tbl-cpt tbody tr td.m-tk').blur(function(){
        reset_select();
        var text_val = $(this).text();
        var unit = '';
        var tk = '';
        if (edit_action == 'vat') {
            if(text_val != '' && text_val.indexOf('%') != -1) {
                unit = '%';
                text_val = text_val.replace('%', '').trim();
            }
        }
        text_val = text_val.replace(',', '');
        var clicked = $(this);
        var data_post = {
            cpts_selected: cpts_selected,
            action: edit_action,
            vat: text_val.trim() == '' ? 0 : text_val,
            unit: unit,
            tk: text_val,
        };
        $.ajax({
            url: '/cpt/ajax_u',
            method: 'POST',
            data: data_post})
        .done(function(data) {
            var cpts = JSON.parse(data);
            if (cpts.err != undefined) {
                alert(JSON.parse(data).err);return false;
            }
            $.each(cpts, function(i, cpt){
                if (edit_action == 'vat') {
                    $(clicked).prev().empty().append(cpt['th']);
                    $(clicked).empty().append(cpt['vat']);
                }
                if (edit_action == 'tk') {
                    if (!cpt['tk']) {
                        cpt['tk'] = 'NO TK';
                    }
                    $(clicked).empty().append(cpt['tk']);
                }

            });
            edit_action = '';

        }, 'json')
        .fail(function(data) {
            if (data['message']) {
                alert(data['message']);
            } else {
                alert('Error updating CPT!');
            }
        });
        clicked.prop('contenteditable', false).removeClass('bg-white numberOnly');
        clicked.closest('tr').removeClass(tr_selected_class);
        cpts_selected = [];
    });
    $('#optionSave').on('click', function(){
        var data_post = {
            cpts_selected: cpts_selected,
            action: edit_action,
            vat: $('input[name="vat"]').val(),
            unit: $('select[name="unit"]').val(),
            tk: $('input[name="tk"]').val(),
        };
        // console.log(data_post);return false;
        $.ajax({
            url: '/cpt/ajax_u',
            method: 'POST',
            data: data_post})
        .done(function(data) {
            // console.log(data);return false;
            var cpts = JSON.parse(data);
            if (cpts.err != undefined) {
                alert(JSON.parse(data).err);return false;
            }
            $.each(cpts, function(i, cpt){
                var cpt_id = parseInt(cpt['id']);
                $('#tbl-cpt').find('tbody tr').each(function(idrow, item){
                    if ($(item).data("dvtour_id") > 0 && $(item).data("dvtour_id") == cpt_id) {
                        if (edit_action == 'vat') {
                            $(item).find('td:eq(7)').empty().append(cpt['th']);
                            $(item).find('td:eq(8)').empty().append(cpt['vat']);
                        }
                        if (edit_action == 'tk') {
                            $(item).find('td.m-tk').empty().append(cpt['tk']);
                        }
                    }
                });

            });
            edit_action = '';
            $('#action-buttons').hide();
            $('#edit-form-modal').modal('hide');
        }, 'json')
        .fail(function(data) {
            if (data['message']) {
                alert(data['message']);
            } else {
                alert('Error updating CPT!');
            }
        });
    });
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
/////////////////////////////////format number/////////////////////////////////////
    $(document).on('keydown', '.numberOnly', function(e){
        var cnt = 0;
        if(this.selectionStart || this.selectionStart == 0){
            // selectionStart won't work in IE < 9

            var key = e.which;
            var prevDefault = true;

            var thouSep = ",";  // your seperator for thousands
            var deciSep = ".";  // your seperator for decimals
            var deciNumber = 2; // how many numbers after the comma

            var thouReg = new RegExp(thouSep,"g");
            var deciReg = new RegExp(deciSep,"g");

            function spaceCaretPos(val, cPos){
                /// get the right caret position without the spaces

                if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
                    cPos = cPos-1;

                if(val.substring(0,cPos).indexOf(thouSep) >= 0){
                    cPos = cPos - val.substring(0,cPos).match(thouReg).length;
                }

                return cPos;
            }

            function spaceFormat(val, pos){
                /// add spaces for thousands

                if(val.indexOf(deciSep) >= 0){
                    var comPos = val.indexOf(deciSep);
                    var int = val.substring(0,comPos);
                    var dec = val.substring(comPos);
                } else{
                    var int = val;
                    var dec = "";
                }
                var ret = [val, pos];

                if(int.length > 3){

                    var newInt = "";
                    var spaceIndex = int.length;

                    while(spaceIndex > 3){
                        spaceIndex = spaceIndex - 3;
                        newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
                        if(pos > spaceIndex) pos++;
                    }
                    ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
                }
                return ret;
            }

            $(this).on('keyup', function(ev){
                if(ev.which == 8){
                    // reformat the thousands after backspace keyup

                    var value = this.value || this.innerText;
                    var caretPos = this.selectionStart;

                    caretPos = spaceCaretPos(value, caretPos);
                    value = value.replace(thouReg, '');

                    var newValues = spaceFormat(value, caretPos);
                    this.value = newValues[0];
                    this.selectionStart = newValues[1];
                    this.selectionEnd   = newValues[1];
                }
            });

            if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
               (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
                prevDefault = false;

            if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
                e.preventDefault();

                if(!e.altKey && !e.shiftKey && !e.ctrlKey){

                    var value = this.value || this.innerText;
                    if((key > 95 && key < 106)||(key > 47 && key < 58) ||
                        (deciNumber > 0 && (key == 110 || key == 188 || key == 190))){

                        var keys = { // reformat the keyCode
                            48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
                            96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
                            110: deciSep, 188: deciSep, 190: deciSep
                        };

                        var caretPos = this.selectionStart;
                        var caretEnd = this.selectionEnd;

                        if(caretPos != caretEnd) // remove selected text
                            value = value.substring(0,caretPos) + value.substring(caretEnd);

                        caretPos = spaceCaretPos(value, caretPos);

                        value = value.replace(thouReg, '');

                        var before = value.substring(0,caretPos);
                        var after = value.substring(caretPos);
                        var newPos = caretPos+1;

                        if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
                            if(before.indexOf(deciSep) >= 0){ newPos--; }
                            before = before.replace(deciReg, '');
                            after = after.replace(deciReg, '');
                        }
                        var newValue = before + keys[key] + after;

                        if(newValue.substring(0,1) == deciSep){
                            newValue = "0"+newValue;
                            newPos++;
                        }

                        while(newValue.length > 1 &&
                            newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
                            newValue = newValue.substring(1);
                        newPos--;
                    }

                    if(newValue.indexOf(deciSep) >= 0){
                        var newLength = newValue.indexOf(deciSep)+deciNumber+1;
                        if(newValue.length > newLength){
                            newValue = newValue.substring(0,newLength);
                        }
                    }

                    newValues = spaceFormat(newValue, newPos);

                    this.value = newValues[0];
                    this.selectionStart = newValues[1];
                    this.selectionEnd   = newValues[1];
                }
            }
        }

        $(this).on('blur', function(e){
            if(deciNumber > 0){
                var value = this.value || this.innerText;

                var noDec = "";
                for(var i = 0; i < deciNumber; i++)
                    noDec += "0";

                if(value == "0"+deciSep+noDec)
                    this.value = ""; //<-- put your default value here
                else
                    if(value.length > 0){
                        if(value.indexOf(deciSep) >= 0){
                            var newLength = value.indexOf(deciSep)+deciNumber+1;
                            if(value.length < newLength){
                                while(value.length < newLength){ value = value+"0"; }
                                this.value = value.substring(0,newLength);
                            }
                        }
                        else this.value = value;// + deciSep + noDec;
                    }
                }
            });
        }
    });
///////////////////////////////////////////////////////////////////////////////////


TXT;
$this->registerJs($js);
$js = <<<TXT
// Thanh toan OK 100%
$('#tbl-cpt').on('click', 'a.mark-paid', function(event){
    event.preventDefault();
    var span = $(this);
    var action = 'mark-paid';
    var tour_id = $(this).data('tour_id');
    var dvtour_id = $(this).data('dvtour_id');
    var jqxhr = $.post('/cpt/ajax?xh', {action:action, tour_id:tour_id, dvtour_id:dvtour_id})
    .done(function(data) {
        //alert(data['message']);
        span.parent().empty().html('<span title="Đã TT 100%" class="badge badge-success">TT</span>');
        //span.removeClass('badge-secondary').addClass('badge-success');
    }, 'json')
    .fail(function(data) {
        if (data['message']) {
            alert(data['message']);
        } else {
            alert('Error updating CPT!');
        }
    });
});

// 160111 Them vao gio thanh toan
$('#tbl-cpt').on('click', 'a.add-to-b', function(event){
    event.preventDefault();
    var span = $(this);
    var action = 'add-to-b';
    var tour_id = $(this).data('tour_id');
    var dvtour_id = $(this).data('dvtour_id');
    var jqxhr = $.post('/cpt/ajax?xh', {action:action, tour_id:tour_id, dvtour_id:dvtour_id})
    .done(function(data) {
        //alert(data['message']);
        span.removeClass('label-default label-info').addClass(data['class']);
        //$('#dntt_count').html(data['count']);
    }, 'json')
    .fail(function() {
        alert('Error updating CPT!');
    });
});

// 161001 Check anything
$('#tbl-cpt').on('click', 'i.check', function(event){
    event.preventDefault();
    var span = $(this);
    var action = 'check';
    var tour_id = $(this).data('tour_id');
    var dvtour_id = $(this).data('dvtour_id');
    var jqxhr = $.post('/cpt/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id})
    .done(function(data) {
        //alert(data['message']);
        span.removeClass('label-default label-info').addClass(data['class']);
        //$('#dntt_count').html(data['count']);
    }, 'json')
    .fail(function() {
        alert('Error updating CPT!');
    });
});

$('#tbl-cpt').on('click', 'a.vat-ok', function(event){
    event.preventDefault();
    var span = $(this);
    var action = 'vat-ok';
    var tour_id = $(this).data('tour_id');
    var dvtour_id = $(this).data('dvtour_id');
    var jqxhr = $.post('/cpt/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id})
    .done(function(data) {
        //alert(data['message']);
        span.removeClass('label-default label-success').addClass(data['class']);
    }, 'json')
    .fail(function() {
        alert('Error updating CPT!');
    });
});

// Check / Uncheck payment
$('#tbl-cpt').on('click', 'span.ajax-check-mtt', function(event){
    var span = $(this);
    var action = 'check-mtt';
    var tour_id = $(this).data('tour_id');
    var dvtour_id = $(this).data('dvtour_id');
    var mtt_id = $(this).data('mtt_id');
    var jqxhr = $.post('/cpt/ajax?xh', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, mtt_id:mtt_id})
    .done(function(data) {
        if (data['code'] == 200) {
            cssClass = span.hasClass('badge-success') ? 'badge-info' : 'badge-success';
            span.removeClass('badge-info badge-success');
            span.addClass(cssClass);
        } else {
            alert(data['message']);
        }
    }, 'json')
    .fail(function(data) {
        if (data['message']) {
            alert(data['message']);
        } else {
            alert('Error updating CPT!');
        }
    });
});

$('.popovers').popover();
TXT;

$this->registerJs($js);