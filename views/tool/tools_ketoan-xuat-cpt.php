<?php
use yii\helpers\Html;
use app\widgets\LinkPager;

$this->title = 'Xuất chi phí tour cho kế toán ('.number_format($pagination->totalCount).' dòng)';
$this->params['breadcrumb'] = [
    ['Tools', '@web/tools']
];
//$date = date('d/m/Y', strtotime($theProduct['day_from']));

$xrate['EUR'] = isset($_GET['eur']) && (int)$_GET['eur'] != 0 ? (int)$_GET['eur'] : 24200;
$xrate['USD'] = isset($_GET['usd']) && (int)$_GET['usd'] != 0 ? (int)$_GET['usd'] : 22300;
$xrate['LAK'] = isset($_GET['lak']) && (int)$_GET['lak'] != 0 ? (int)$_GET['lak'] : 2.75;
$xrate['KHR'] = isset($_GET['khr']) && (int)$_GET['khr'] != 0 ? (int)$_GET['khr'] : 19.73;
$xrate['THB'] = isset($_GET['thb']) && (int)$_GET['thb'] != 0 ? (int)$_GET['thb'] : 683.45;
$xrate['VND'] = 1;
?>
<div class="col-md-12">
    <p>Kiểu xem / Tour (code) hoặc tháng (yyyy-mm) / tỉ giá EUR / USD / VND. Chỉ hiển thị tối đa 4,000 dòng.</p>
    <form class="form-inline mb-2">
        <?= Html::dropdownList('view', $view, ['use'=>'Ngày dịch vụ', 'tour-code'=>'Tour code', 'tour-start'=>'Tháng khởi hành', 'tour-end'=>'Tháng kết thúc'], ['class'=>'form-control']) ?>
        <?= Html::textInput('search', $search, ['class'=>'form-control']) ?>
        <?= Html::textInput('eur', $xrate['EUR'], ['class'=>'form-control', 'style'=>'width:100px', 'title'=>'USD/VND']) ?>
        <?= Html::textInput('usd', $xrate['USD'], ['class'=>'form-control', 'style'=>'width:100px', 'title'=>'EUR/VND']) ?>
        <?= Html::textInput('lak', $xrate['LAK'], ['class'=>'form-control', 'style'=>'width:100px', 'title'=>'LAK/VND']) ?>
        <?= Html::textInput('khr', $xrate['KHR'], ['class'=>'form-control', 'style'=>'width:100px', 'title'=>'KHR/VND']) ?>
        <?= Html::textInput('thb', $xrate['THB'], ['class'=>'form-control', 'style'=>'width:100px', 'title'=>'THB/VND']) ?>
        <?= Html::dropdownList('output', $output, ['view'=>'View', 'download'=>'Download'], ['class'=>'form-control']) ?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Sửa mã', '@web/tools/ketoan-xuat-cpt-update-code', ['rel'=>'external']) ?>
        <?= Html::input('hidden', 'downloadToken', '', ['id' => 'download_token_value']) ?>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Tour</th>
                        <th>Nội dung DV</th>
                        <th>SL</th>
                        <th>Đơn vị</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                        <th>Mã NCC</th>
                        <th>TKGN</th>
                        <th>Mã phí</th>
                        <th>Venue</th>
                        <th>Company</th>
                        <th>Operator</th>
                        <th>Provider</th>
                    </tr>
                </thead>
                <tbody>
<?php
foreach ($theTours as $tour) {
    foreach ($theCptx as $cpt) {
        if ($tour['oid'] == $cpt['tour_id']) {
?>
                    <tr>
                        <td class="text-nowrap"><?= $tour['day_from'] ?></td>
                        <td><?= Html::a($tour['op_code'], '@web/tours/r/'.$tour['oid'], ['target'=>'_blank']) ?></td>
                        <td><?= Html::a($cpt['dvtour_name'], '@web/tours/mm/'.$tour['oid'].'/'.$cpt['dvtour_id'], ['target'=>'_blank']) ?></td>
                        <td class="text-right"><?= number_format($cpt['qty'], 2) ?></td>
                        <td><?= $cpt['unit'] ?></td>
                        <td class="text-right"><?= number_format($cpt['price'], 2) ?></td>
                        <td class="text-right"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($cpt['qty'] * $cpt['price'] * $xrate[$cpt['unitc']], 0) ?></td>
                        <td>
<?
$text = '';
if (in_array($cpt['payer'], ['Amica Hà Nội', 'Amica Luang Prabang', 'BCEL Laos', 'Hướng dẫn Laos 1', 'Hướng dẫn Laos 2', 'Hướng dẫn Laos 3'])) {
    if ($cpt['venue']) {
        $text = $cpt['venue']['name'];
    } elseif ($cpt['company']) {
        $text = $cpt['company']['name'];
    } else {
        $text = $cpt['oppr'];
    }
} else {
    $text = $cpt['payer'];
}
$name = '';
if (isset($list[$text])) {
    $name = $list[$text]['code'];
    echo mb_strtoupper($name);
} else {
    echo Html::a('new?', '@web/tools/ketoan-xuat-cpt-update-code?name='.urlencode($text), ['rel'=>'external', 'style'=>'color:red']);
}

?>
                        </td>
                        <td><?//= isset($list[$tour['cbname']]) ? $list[$tour['cbname']]['code'] : Html::a($tour['cbname'], '@web/tools/ketoan-xuat-cpt-update-code?name='.urlencode($tour['cbname']), ['rel'=>'external']) ?></td>
                        <td>
<?
/*
// Check code vs name
$code = '02';

if (isset($list[$text])) {
    $code = $list[$text]['cost'];
}

foreach ($list as $item) {
    if ($item['code'] == '') {
        if (strpos(strtolower($cpt['dvtour_name']), $item['name']) !== false) {
            $code = $item['cost'];
        }
    }
}

echo $code;
*/
?>
                        </td>
                <!--td>
<?
/*
$code = '3311';
if (in_array($name, ['BUNTHOL', 'ERIC', 'FEUANG', 'ITRAVEL', 'MEDSANH', 'PHMINH', 'THONGLIS', 'VISA'])) {
    $code = '3312';
}
if (in_array($name, ['TCGARDEN'])) {
    $code = '336';
}
echo $code;
*/
?>
                        </td-->
                        <td><?= $cpt['venue']['name'] ?></td>
                        <td><?= $cpt['company']['name'] ?></td>
                        <td><?= $cpt['oppr'] ?></td>
                        <td><?= $cpt['payer'] ?></td>
                    </tr>
<?
        }
    }
}
?>
                </tbody>
            </table>
        </div>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]) ?>
</div>
<div id="domMessage" style="display:none;">
    <h2 class="text-center"><img style="width: 20px; height: 20px" src="/img/busy1.gif" /> We are processing your request.  Please be patient.</h1>
</div>
<?php
$js = <<<TXT
    // processing download
    var downloadToken = new Date().getTime();
    $('form').submit(function(){
        // if ($('#export_fields').val().length > 0) {
        blockUIForDownload();
        // }
        return true;
    });
    var fileDownloadCheckTimer;
    function blockUIForDownload() {
        var attempts = 10000;
        var token = new Date().getTime(); //use the current timestamp as the token value
        $('#download_token_value').val(token);
        $.blockUI({
            message: $('#domMessage'),
            css: {
                borderRadius: '5px'
            }
            });
        fileDownloadCheckTimer = window.setInterval(function () {
            var cookieValue = $.cookie('downloadToken');
            attempts--;
            if (cookieValue == token || attempts == 0)
                finishDownload();
        }, 1000);
    }
    function finishDownload() {
        window.clearInterval(fileDownloadCheckTimer);
        $.cookie('downloadToken', null); //clears this cookie value
        $.unblockUI();
    }
    // end download

TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);
?>