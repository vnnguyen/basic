<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
include('_cpt_inc.php');
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
// $this->registerCssFile($baseUrl.'/css/processBar.css');
$this->registerCss('
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
');
$this->registerCss('
    .add-dntt { cursor: pointer}
    .green-color { background: green;}
    #list-dntt-modal .modal-dialog, #list-ldntt-modal .modal-dialog, #list-detail-ltt-modal .modal-dialog {width: 900px;}
    p.text-right a {margin-left: 10px}
    .my_tooltip {
    display:none; position:absolute; border:1px solid #333; background-color:#161616; border-radius:5px; padding:5px; color:#fff; font-size:12px "Arial"; }
    .remove_link { display: inline-block;  border-radius:2px; padding: 3px 7px; cursor: pointer }
    .remove_link:hover {background: #f3f3f3}



    #list-option-modal form input{margin-left: 15px}
    #list-option-modal .modal-dialog { width: 220px}
    #btn-close-option, #optionSave { padding: 3px 5px}
    #wrap-check {background: #ccc; padding: 3px 7px ; border-radius:2px}
    #wrap-ckeck .option_check {display: inline-block; margin-left: 4px!important; padding-top: 4px}
    #wrap-check input, #wrap-ckeck .option_check { float: left; margin-right: 10px}

    .option_check {display: inline-block;}
    #wrap-check .dropdown-menu{ min-width: 50px}
    #wrap-check .dropdown-menu li a:hover { background: #f3f3f3; color: #000;}
    #wrap-check .dropdown-menu li .active{ background: #1F8EE7; color: #fff}
    .sugggest_link { display: inline-block; padding-left: 6px; padding-top: 0}
    .sugggest_link .fa {top: -1px}
');

$this->registerJsFile($baseUrl.'/js/jquery.autocomplete.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/link_dv.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

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
    $sql = 'SELECT u.name FROM at_users u, at_tour_user tu WHERE u.id=tu.user_id AND tu.role="operator" AND tu.tour_id=:id';
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
];

// if (USER_ID == 1) {
//     //\fCore::expose($theCptx);
// }

?>
<style type="text/css">
#formx .form-control {margin-bottom:4px;}
.table-xxs>tbody>tr>td, .table-xxs>tbody>tr>th, .table-xxs>tfoot>tr>td, .table-xxs>tfoot>tr>th, .table-xxs>thead>tr>td, .table-xxs>thead>tr>th {padding:6px;}
.links {
    cursor: pointer;
}
.checked {
    color: green !important;
}
</style>
<div class="col-md-12">
    <form class="form-inline panel-search" id="formx">
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
        <?= Html::textInput('unit', $unit, ['class'=>'form-control', 'placeholder'=>'Loại phòng']) ?>
        <?= Html::dropdownList('link', $link, ['all' => 'Tất cả', 'on'=>'Đã được link', 'off'=>'Chưa được link'], ['class'=>'form-control']) ?>
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
    <? if (empty($theCptx)) { ?>
    <div class="alert alert-warning">No data found</div>
    <? } else { ?>
    <p class="text-right">
        <a id="link_auto">Link automatic</a>
    </p>
    <?php
    if (Yii::$app->session->getAllFlashes()) {
        $errors = [];
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            if ($message == 'links ok') {
                $success = '<div class="alert alert-success no-border">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">' . $message . '!</span></div>';
            } else {
                if ($message == 'links nok') {
                    $errors[] = '<div class="alert alert-danger no-border">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                    <span class="text-semibold">' . $message . ': [' .$key. ']</span></div>';
                } else {
                    $success = '<div class="alert alert-success no-border">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">' . $message . '!</span></div>';
                }
            }
        }
        if (count($errors) > 0) {
            foreach ($errors as $v) {
                echo $v;
            }
        } else {
            echo $success;
        }

    }
    ?>
    <div class="panel panel-default">
        <form class="form-inline panel-link" method="post" id="formlink">
        <div class="table-responsive">
        <table id="tbl-cpt" class="table table-xxs table-bordered table-condensed table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <? if (!$theTour) { ?>
                    <th>Tour</th>
                    <th>Ngày dv</th>
                    <? } ?>
                    <th style="width: 80px" class="text-center">
                        <div class="input-group">
                          <div id="wrap-check" class="input-group-btn ">
                            <input type="checkbox" class="">
                            <a  class="dropdown-toggle option_check" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#">this page</a></li>
                                <li><a href="#">all pages</a></li>
                            </ul>
                          </div><!-- /btn-group -->
                        </div><!-- /input-group -->
                    </th>
                    <th>Tên dv/cpt @Địa điểm $Nhà cung cấp</th>
                    <th>Đ/vị</th>
                    <th>SL</th>
                    <th>x giá</th>
                    <th>Thành tiền</th>
                    <th>Thanh toán</th>

                </tr>
            </thead>
            <tbody id = "list_cpt_link">
                <?
                $total['all'] = 0;
                $total['vnd'] = 0;
                $total['usd'] = 0;
                $total['eur'] = 0;
                $total['lak'] = 0;
                $total['khr'] = 0;
                $xrates['usd'] = 21250;
                $xrates['eur'] = 28250;
                $xrates['vnd'] = 1;
                $xrates['lak'] = 2.78;
                $xrates['khr'] = 5.61;

                if ($theTour) {
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
                <tr class="info">
                    <td class="text-muted"><?= $cnt ?><?= $day['id'] ?></td>
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
                                        include('cpt__tr_link.php');
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
                        include('cpt__tr_link.php');
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
                            <span class="text-pink"><strong><?= number_format($total['vnd'], 2) ?></strong></span>
                            <span class="text-muted">VND</span>
                        </div>
                        <? } ?>
                        <? if ($total['usd'] != 0) { ?>
                        <div>
                            <span class="text-orange"><strong><?= number_format($total['usd'], 2) ?></strong></span>
                            <span class="text-muted">USD</span>
                        </div>
                        <? } ?>
                        <? if ($total['eur'] != 0) { ?>
                        <div>
                            <span class="text-info"><strong><?= number_format($total['eur'], 2) ?></strong></span>
                            <span class="text-muted">EUR</span>
                        </div>
                        <? } ?>
                        <? if ($total['lak'] != 0) { ?>
                        <div>
                            <span class="text-brown"><strong><?= number_format($total['lak'], 2) ?></strong></span>
                            <span class="text-muted">LAK</span>
                        </div>
                        <? } ?>
                        <? if ($total['khr'] != 0) { ?>
                        <div>
                            <span class="text-slate"><strong><?= number_format($total['khr'], 2) ?></strong></span>
                            <span class="text-muted">KHR</span>
                        </div>
                        <? } ?>
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
        </div>
        <label class="pull-left">Liên kết tới: </label>
        <div class="col-md-6 form-group">
            <?= Html::textInput('dv_link', $tour, ['style' => 'width: 100%', 'class'=>'form-control autocomplete', 'placeholder'=>'Dịch vụ được liên kết']) ?>
            <input type="hidden" name="venue_id" value="">
            <input type="hidden" name="select_type" value="">
        </div>
        <?= Html::checkbox('remember', false, ['label' => 'Remember']);?>
        <?= Html::submitButton('Go', ['name' => 'active_link', 'class'=>'btn btn-primary']) ?>
        </form>
    </div>
    <? if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="text-center">
        <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
        ]) ?>
    </div>
    <? } ?>

    <? } ?>
</div>
<!-- edit Modal -->
    <div class="modal fade" id="link-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Link info</h4>
                </div>
                <?php $form = ActiveForm::begin([
                    'id' => 'linkForm',
                    ]) ?>
                <div class="modal-body">
                    <?= Html::input('hidden', 'cpt_id', '') ?>
                    <div class="col-md-12 content-main">
                        <div class="form-group">
                            <?= Html::input('text', 'name', '', ['class' => 'form-control', 'placeholder' => "Name", 'readonly' => true,]) ?>
                        </div>
                        <div class="cleat-fix">vs</div>
                        <div class="form-group">
                            <?= Html::dropDownList('dv', '', [],[
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="OK" id="btnSave" name="save">
                    <button type="submit" class="btn btn-default" data-dismiss="modal" id="btn-close-modal">Cancel</button>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
<!--end edit Modal -->


<!-- options Modal -->
    <div class="modal fade" id="list-option-modal" role="dialog">
        <div class="modal-dialog dialog-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <!-- <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Option</h4>
                </div> -->
                <div class="modal-body">
                    <form method="GET" class="form-inline" action="<?= $baseUrl.'/cpt/link_dv_auto'?>">
                        <fieldset>
                            <label for="">Tự động link: </label><br/>
                            <input type="radio" name="link_auto" value="my" checked> Chỉ của tôi
                            <input type="radio" name="link_auto" value="all"> Tất cả<br>
                        </fieldset>

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
$js = <<<TXT
// 150917 Tu Phuong
//$('.cpt.c1, .cpt.c2, .cpt.c3, .cpt.c4, .cpt.c5, .cpt.c6, .cpt.c7, .cpt.c8').on('click', function(){
/*$('.cpt.c1, .cpt.c2, .cpt.c3, .cpt.c4, .cpt.c5, .cpt.c6, .cpt.c7, .cpt.c8').on('click', function(){
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
});*/

// Thanh toan OK 100%
$('#tbl-cpt').on('click', 'a.mark-paid', function(event){
    event.preventDefault();
    var span = $(this);
    var action = 'mark-paid';
    var tour_id = $(this).data('tour_id');
    var dvtour_id = $(this).data('dvtour_id');
    var jqxhr = $.post('/cpt/ajax', {action:action, tour_id:tour_id, dvtour_id:dvtour_id})
    .done(function(data) {
        //alert(data['message']);
        span.parent().empty().html('<span title="Đã TT 100%" class="label label-success">TT</span>');
        //span.removeClass('label-default').addClass('label-success');
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
$('.popovers').popover();

TXT;

$this->registerJs($js);