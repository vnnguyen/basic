<?
use yii\helpers\Html;
use yii\helpers\Markdown;

Yii::$app->language = 'vi';

Yii::$app->params['page_title'] = 'Vehicle schedule: '.$theTour['op_code'].' - '.$theTour['op_name'];
Yii::$app->params['page_layout'] = '-t -s -f';
Yii::$app->params['body_class'] = 'bg-white';

$logo = Yii::$app->params['print_logo'];

$dayIdList = explode(',', $theTour['day_ids']);
$showDays = [];
$ranges = explode(',', $theForm->days);
foreach ($ranges as $range) {
    $rr = explode('-', $range);
    if (isset($rr[1])) {
        //1-3
        for ($i = (int)trim($rr[0]); $i <= (int)trim($rr[1]); $i ++) {
            $showDays[] = $i;
        }
    } else {
        //4
        $showDays[] = trim($range);
    }
}

$dviList = [
    'km'=>'Km',
    'db'=>'Ngày ĐB',
    'tb'=>'Ngày TB',
    'chang'=>'Chặng',
];

$total = [
    'km'=>0,
    'db'=>0,
    'tb'=>0,
];

?>
<style>
.table .hidden-print {text-decoration:line-through;}
@media print{
    .table {font-size:11px!important;}
    .table>tbody>tr>td, .table>tbody>tr>th {padding:<?= count($showDays) > 10 ? '2px ' : '' ?>4px!important; vertical-align:top!important;}
    @page {size: A4 portrait;}
}
</style>
<div class="col-md-8 col-md-offset-2">
    <div class="alert alert-info hidden-print">
        Click to go
        <?= Html::a('Back to tour', '/tours/r/'.$theTourOld['id'], ['class'=>'text-bold']) ?>
        or
        <?= Html::a('Back to select print options', '/tours/in-lx/'.$theTour['id'], ['class'=>'text-bold']) ?>
    </div>
    <div class="clearfix">
        <img style="width:130px; float:left; display:inline-block;" src="<?= $logo ?>" alt="Logo" >
        <div style="margin-left:145px;" class="text-center">
            <div style="font-size:12px;"><strong>Công ty Cổ phần Đầu tư Thương mại và Du lịch Thân Thiện Việt Nam</strong></div>
            <? if ($theForm['vp'] == 'hanoi') { ?>
            <div style="font-size:11px;">Địa chỉ: Tầng 3, toà nhà Nikko, số 27 Nguyễn Trường Tộ, Ba Đình, Hà Nội
                <br>Tel: (04) 6273 4455 Email: info@amica-travel.com Web: https://www.amica-travel.com</div>
            <? } ?>
            <? if ($theForm['vp'] == 'saigon') { ?>
            <div style="font-size:11px;">Văn phòng TPHCM: Lầu 5, Resco Building, số 94-96 Nguyễn Du, Quận 1, TP. HCM
                <br>Tel: (08) 6685 4079 Fax: (08) 6273 3504 Email: info@amica-travel.com Web: https://www.amica-travel.com</div>
            <? } ?>
        </div>
    </div>

    <hr>

    <h1 class="text-center text-uppercase text-bold no-margin mb-20">Lịch xe</h1>

    <table class="table table-xxs table-borderless">
        <tbody>
            <tr><th width="15%" class="text-right">Code tour</th><td width="35%"><?= $theTour['op_code'] ?></td><th width="15%" class="text-right">Chủ xe</th><td width="35%" contenteditable="true"><?= $theForm['chuxe'] ?></td></tr>
            <tr><th class="text-right">Tên đoàn</th><td><?= $theTour['op_name'] ?></td><th class="text-right">Loại xe</th><td contenteditable="true"><?= $theForm['loaixe'] ?></td></tr>
            <tr><th class="text-right">Số khách</th><td><?= $theTour['pax'] ?></td><th class="text-right">Lái xe</th><td contenteditable="true"><?= $theForm['laixe'] ?></td></tr>
            <tr><th class="text-right">Điều hành</th><td><?= $theForm['dieuhanh'] ?></td><th class="text-right">Hướng dẫn</th><td><?= $theForm['huongdan'] ?></td></tr>
            <? if ($theForm->note != '') { ?>
            <tr>
                <th class="text-right">Ghi chú:</th>
                <td colspan="3"><?= nl2br($theForm->note) ?></td>
            </tr>
            <? } ?>
        </tbody>
    </table>

    <br>

    <table id="table1" class="table table-xxs table-bordered">
        <thead>
            <tr>
                <th width="5%"><?= Yii::t('tour_print', 'TT') ?></th>
                <th width="15%"><?= Yii::t('tour_print', 'Ngày') ?></th>
                <th width="50%"><?= Yii::t('tour_print', 'Chương trình tour') ?></th>
                <th width="5%"><?= Yii::t('tour_print', 'SL') ?></th>
                <th width="10%"><?= Yii::t('tour_print', 'Đ/vị') ?></th>
                <th width="15%"><?= Yii::t('tour_print', 'Thành tiền') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="6" class="text-center">Lịch xe theo hợp đồng được duyệt (trước khi đi tour)</th>
            </tr>
<?
// \fCore::expose($_POST); exit;
$cnt = 0;
$tong = 0;
$nextDate = '';
$sameday = '';
for ($i = 0; $i < count($_POST['noidung']); $i ++) {
    $ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($_POST['tt'][$i] - 1).'days'));
    // ngay+1
    $ngayMai = date('Y-m-d', strtotime($theTour['day_from'].' + '.($_POST['tt'][$i]).'days'));
    // ngay trong ct tour
    if (isset($_POST['ngay'][$i + 1]) && $_POST['ngay'][$i + 1] == '') {
        $ngaySau = $ngayMai;
    } else {
        $ngaySau = isset($_POST['tt'][$i + 1]) ? date('Y-m-d', strtotime($theTour['day_from'].' + '.($_POST['tt'][$i + 1] - 1).'days')) : $ngayMai;
    }
    $ngayDep = Yii::$app->formatter->asDate($ngay, 'php:j/n/Y');

    if ($_POST['dvi'][$i] != 'chang') {
        $total[$_POST['dvi'][$i]] += $_POST['sl'][$i];
        $tong += $_POST['sl'][$i] * $theForm['gia'.$_POST['dvi'][$i]];
    } else {
        $tong += $_POST['gia'][$i];
    }

?>
            <tr>
<?
    if ($sameday != $ngay) {
        $sameday = $ngay;
        $cnt ++; ?>
                <td class="text-center text-muted"><?= $cnt ?></td>
                <td class="text-nowrap text-center"><?= $ngayDep ?></td>
<?
    } else { ?>
                <td class="text-center text-muted"></td>
                <td class="text-nowrap text-center"></td>
<?
    } ?>
                <td><?= $_POST['noidung'][$i] ?></td>
                <td class="text-right"><?= $_POST['sl'][$i] ?></td>
                <td class="text-nowrap text-center"><?= $dviList[$_POST['dvi'][$i]] ?></td>
                <td class="text-nowrap text-right"><?= $_POST['dvi'][$i] == 'chang' ? number_format($_POST['gia'][$i]) : number_format($_POST['sl'][$i] * $theForm['gia'.$_POST['dvi'][$i]]) ?></td>
            </tr>
<?
    if ($cnt != 0 && $ngayMai != $ngaySau && $_POST['ngay'][$i] != '') {
?>
            <tr><td colspan="6"></td></tr>
<?
    }
}
?>
            <tr>
                <td colspan="3" class="text-right">
                    <? foreach ($dviList as $k=>$v) { if ($k != 'chang' && $total[$k] != 0) { ?>
                    <div><?= number_format($total[$k]) ?> <div style="width:60px; text-align:left; display:inline-block;"> <?= $v ?></div><div style="width:100px; display:inline-block;"> &times; <?= number_format($theForm['gia'.$k]) ?></div><div style="width:140px; display:inline-block;">= <?= number_format($theForm['gia'.$k] * $total[$k]) ?> VND</div></div>
                    <? } } ?>
                </td>
                <td colspan="2" class="text-right">Tổng (VND)</td>
                <th class="text-right"><?= number_format($tong) ?></th>
            </tr>
            <tr>
                <th colspan="6" class="text-center">Phát sinh trong quá trình đi tour (dành cho nhà xe ghi)</th>
            </tr>
            <? for ($i = 1; $i <= 3; $i ++) { ?>
            <tr>
                <td contenteditable="true">&nbsp;</td>
                <td contenteditable="true">&nbsp;</td>
                <td contenteditable="true">&nbsp;</td>
                <td contenteditable="true">&nbsp;</td>
                <td contenteditable="true">&nbsp;</td>
                <td contenteditable="true">&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
                <td class="text-right" colspan="5">Tổng (VND)</td>
                <td></td>
            </tr>
            <!--
            <tr><td colspan="6">&nbsp;</td></tr>
            <tr>
                <th class="text-right" colspan="5">Tổng cộng tiền cần thanh toán cho tour này (hợp đồng + phát sinh)</th>
                <th class="text-right"><?= number_format($tong) ?></th>
            </tr>
            -->
        <tbody>
    </table>

    <table class="table table-xxs table-borderless">
        <tbody>
            <tr>
                <th width="67%" colspan="2"></th>
                <td width="33%" class="text-center">
                    <span onclick=""><?= $theForm['vp'] == 'hanoi' ? 'Hà Nội' : 'TP. Hồ Chí Minh' ?></span>, ngày <?= date('j/n/Y') ?>
                </td>
            </tr>
            <tr>
                <th width="33%" class="valign-top text-center">Lái xe</th>
                <th width="33%" class="valign-top text-center">Kế toán</th>
                <th width="33%" class="valign-top text-center">Điều hành</th>
            </tr>
            <tr><td colspan="3" style="height:80px;"></td></tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-center"><?= $theForm['dieuhanh'] ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?

$js = <<<'TXT'
function doCalc() {
    var total1 = 0;
    var num = 0;
    $('#table1 .add1').each(function() {
        num = parseInt($(this).text(),10);
        if (!isNaN(num)) {
            total1 += num;
        }
    });
    $('#total1').html(total1);
}
//window.print();

TXT;

$this->registerJs($js);

//$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js', ['depends'=>'yii\web\JqueryAsset']);