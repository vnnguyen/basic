<?
// Simple cpt list, to check (Huan, Nguyen, Chinh, Thu, 180820)

use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_cpt_inc.php');


Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'shopping-bag', 'label'=>'TT nhiều mục', 'class'=>'btn btn-info', 'link'=>'cpt/thanh-toan', 'active'=>SEG2=='thanh-toan'],
    ],[
        ['icon'=>'money', 'label'=>'Cpt đã TT', 'class'=>'btn btn-default', 'link'=>'cpt/da-thanh-toan', 'active'=>SEG2=='da-thanh-toan'],
    ]
];
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Chi phí tour';
Yii::$app->params['page_small_title'] = number_format($pagination->totalCount).' dòng';
Yii::$app->params['page_breadcrumbs'][] = ['Tour costs', '@web/cpt'];

if ($theTour) {
    $sql = 'SELECT u.name FROM persons u, at_tour_user tu WHERE u.id=tu.user_id AND tu.role="operator" AND tu.tour_id=:id';
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

if (USER_ID == 1) {
    //\fCore::expose($theCptx);
}

?>
<style type="text/css">
#formx .form-control {margin-bottom:4px;}
.table-xxs>tbody>tr>td, .table-xxs>tbody>tr>th, .table-xxs>tfoot>tr>td, .table-xxs>tfoot>tr>th, .table-xxs>thead>tr>td, .table-xxs>thead>tr>th {padding:6px;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline" id="formx">
                <?= Html::textInput('tour', $tour, ['class'=>'form-control', 'placeholder'=>'Code/ID/Tháng tour']) ?>
                <?= Html::textInput('dvtour', $dvtour, ['class'=>'form-control', 'placeholder'=>'Ngày/Tháng sử dụng']) ?>
                <?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Tên nhà cung cấp/chi phí']) ?>
                <? if ($theTour) { ?>
                <select name="filter" style="width:400px" class="form-control">
                    <option value="">- chọn xem theo tên nhà cung cấp -</option>
                    <optgroup label="Tên do điều hành nhập vào">
                        <?
                        asort($filterArray['n']);
                        foreach ($filterArray['n'] as $k=>$v) { ?>
                        <option value="hn-<?=$k?>" <?= $filter == 'hn-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                        <? } ?>
                    </optgroup>
                    <optgroup label="Tên do IMS tự động link">
                        <?
                        asort($filterArray['v']);
                        foreach ($filterArray['v'] as $k=>$v) { ?>
                        <option value="hi-<?=$k?>" <?= $filter == 'hi-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                        <? } ?>
                    </optgroup>
                    <optgroup label="Công ty cung cấp dịch vụ">
                        <? foreach ($filterArray['vc'] as $k=>$v) { ?>
                        <option value="hv-<?=$k?>" <?= $filter == 'hv-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                        <? } ?>
                        <? foreach ($filterArray['bc'] as $k=>$v) { ?>
                        <option value="hb-<?=$k?>" <?= $filter == 'hb-'.$k ? 'selected="selected"' : '' ?>><?= $v ?></option>
                        <? } ?>
                    </optgroup>
                    <optgroup label="Ai trả tiền">
                        <? foreach ($filterArray['p'] as $k=>$v) { ?>
                        <option value="hp-<?=$k?>" <?=$filter == 'hp-'.$k ? 'selected="selected"' : '' ?>><?=$v?></option>
                        <? } ?>
                        <option value="miennam" <?=$filter == 'miennam' ? 'selected="selected"' : '' ?>>Miền Nam (HDMN & VPSG)</option>
                    </optgroup>
                </select>
                <? } ?>
                <?= Html::dropdownList('sign', $sign, [''=>'+/-', 'plus'=>'+', 'minus'=>'-'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('currency', $currency, [''=>'Tiền', 'eur'=>'EUR', 'usd'=>'USD', 'vnd'=>'VND', 'lak'=>'LAK', 'khr'=>'KHR'], ['class'=>'form-control']) ?>
                <? if (!$theTour) { ?>
                <select class="form-control" name="payer">
                    <option value="">Người TT</option>
                    <? foreach ($payerList as $pay) { ?>
                    <option value="<?= $pay['payer'] ?>" <?= $pay['payer'] == $payer ? 'selected="selected"' : '' ?>><?= $pay['payer'] ?></option>
                    <? } ?>
                    <option value="miennam" <?= $pay['payer'] == 'miennam' ? 'selected="selected"' : '' ?>>Miền Nam (HDMN & VPSG)</option>
                </select>
                <? } ?>
                <?= Html::dropdownList('tt', $tt, [
                ''=>'Tình trạng TT',
                'no'=>'Chưa TT',
                'overdue'=>'Chưa TT, quá hạn',
                'yes'=>'Đã TT',
                'c3'=>'Đã TT, chưa DZ',
                'c4'=>'Đã TT, đã DZ',
                ], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('vat', $vat, [''=>'Hoá đơn VAT', 'nok'=>'Chưa lấy hoá đơn VAT', 'ok'=>'Đã lấy hoá đơn VAT'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('orderby', $orderby, ['updated_at'=>'Xếp theo ngày sửa', 'plus'=>'Xếp theo ngày tour'], ['class'=>'form-control']) ?>
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
        </div>
        <? if (empty($theCptx)) { ?>
        <table class="table">
            <tbody><tr><td><div class="alert alert-warning">No data found</div></td></tr></tbody>
        </table>
        <? } else { ?>
        <div class="table-responsive">
        <table id="tbl-cpt" class="table table-xxs table-condensed table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <? if (!$theTour) { ?>
                    <th>Tour</th>
                    <th>Ngày dv</th>
                    <? } ?>
                    <th>Tên dv/cpt @Địa điểm $Nhà cung cấp</th>
                    <th>Đ/vị</th>
                    <th>SL</th>
                    <th>x giá</th>
                    <th>Thành tiền</th>
                    <th>Thanh toán</th>
                    <th>TT</th>
                    <th>XN</th>
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

                if ($theTour) {
                    $dayIdList = explode(',', $theTour['product']['day_ids']);
                    $cnt = 0;
                    $totalVND = 0;
                    foreach ($dayIdList as $di) {
                        foreach ($theTour['product']['days'] as $day) {
                            if ($day['id'] == $di) {
                                $currentDay = date('d-m-Y', strtotime('+'.$cnt.' day', strtotime($theTour['product']['day_from'])));
                                $currentDOW = date('d-m-Y D', strtotime('+'.$cnt.' day', strtotime($theTour['product']['day_from'])));
                                $cnt ++;
                        ?>
                <tr class="info">
                    <td class="text-muted"><?= Html::a('V', '/ct/r/'.$theTour['product']['id']) ?></td>
                    <td colspan="13">
                        <strong><?= $currentDOW ?></strong>
                        <?= $day['name'] ?>
                        (<?= $day['meals'] ?>)
                    </td>
                </tr>
<?
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
                                        include('cpt_x__tr.php');
                                    }
                                } // foreach cptx
                            }
                        }
                    }
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
                        include('cpt_x__tr.php');
                    }
                } // if theTour

?>
                <tr>
                    <td colspan="<?= $theTour ? '3' : '6' ?>" class="text-right">Tổng tiền
                        <div class="text-muted">Tỉ giá tạm tính: 1 EUR = 28,250 VND | 1 USD = 21,250 VND</div>
                    </td>
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
                    <td colspan="4">
                        <div class="text-success text-right" style="font-size:28px">
                            =
                            <strong><?= number_format($total['all']) ?></strong>
                            <span class="text-muted">VND</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        <? } ?>

        <? if ($pagination->totalCount > $pagination->pageSize) { ?>
        <div class="panel-body text-center">
            <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
            ]) ?>
        </div>
        <? } ?>
    </div>
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

.form-control.select2-container {height:34px!important;}
    .select2-container .select2-choice {height:32px; line-height:32px; background-image:none!important;}
    .select2-container .select2-choice .select2-arrow {background:none!important;}
</style>

<?
$js = <<<'TXT'
$('.popovers').popover();
TXT;

$this->registerJs($js);