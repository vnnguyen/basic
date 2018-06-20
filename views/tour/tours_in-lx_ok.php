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
$ranges = explode(',', $theLichxe['days']);
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

$content = [];
$lines = explode('|||', $theLichxe['content']);
foreach ($lines as $i=>$line) {
    $rowspan = 1;
    for ($j = $i + 1; $j < count($lines); $j ++) {
        if (substr($lines[$j], 0, 4) == substr($line, 0, 4)) {
            $rowspan ++;
        }
    }
    $content[] = explode(';;;', $line.';;;'.$rowspan); // rowspan at last
}
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
        <a href="#" class="text-bold" onclick="window.print(); return false;">In luôn</a>
        |
        <?= Html::a('Quay lại tour', '/tours/r/'.$theTourOld['id'], ['class'=>'text-bold']) ?>
        hoặc
        <?= Html::a('Quay lại các lịch xe', '/tours/in-lx/'.$theTour['id'], ['class'=>'text-bold']) ?>
        <? if (in_array(USER_ID, [1, $theLichxe['created_by'], $theLichxe['updated_by']])) { ?>
        hoặc
        <?= Html::a('Sửa lịch xe này', '/tours/in-lx/'.$theTour['id'].'?action=edit&lichxe='.$theLichxe['id'], ['class'=>'text-bold']) ?>
        <? } ?>
        | Update <?= Yii::$app->formatter->asRelativetime($theLichxe['updated_dt']) ?> bởi <?= $theLichxe['updatedBy']['name'] ?>
    </div>
    <div class="clearfix">
        <img style="width:130px; float:left; display:inline-block;" src="<?= $logo ?>" alt="Logo" >
        <div style="margin-left:145px;" class="text-center">
            <div style="font-size:12px;"><strong>Công ty Cổ phần Đầu tư Thương mại và Du lịch Thân Thiện Việt Nam</strong></div>
            <? if ($theLichxe['vp'] == 'hanoi') { ?>
            <div style="font-size:11px;">Địa chỉ: Tầng 3, toà nhà Nikko, số 27 Nguyễn Trường Tộ, Ba Đình, Hà Nội
                <br>Tel: (04) 6273 4455 Email: info@amica-travel.com Web: https://www.amica-travel.com</div>
            <? } ?>
            <? if ($theLichxe['vp'] == 'saigon') { ?>
            <div style="font-size:11px;">Văn phòng TPHCM: Lầu 5, Resco Building, số 94-96 Nguyễn Du, Quận 1, TP. HCM
                <br>Tel: (08) 6685 4079 Fax: (08) 6273 3504 Email: info@amica-travel.com Web: https://www.amica-travel.com</div>
            <? } ?>
        </div>
    </div>

    <br>

    <h1 class="text-center text-uppercase text-bold no-margin mb-20">Lịch xe</h1>

    <table class="table table-xxs table-borderless">
        <tbody>
            <tr><th width="15%" class="text-right">Code tour</th><td width="35%"><?= $theTour['op_code'] ?></td><th width="15%" class="text-right">Chủ xe</th><td width="35%" contenteditable="true"><?= $theLichxe['chuxe'] ?></td></tr>
            <tr><th class="text-right">Tên đoàn</th><td><?= $theTour['op_name'] ?></td><th class="text-right">Loại xe</th><td contenteditable="true"><?= $theLichxe['loaixe'] ?></td></tr>
            <tr><th class="text-right">Số khách</th><td><?= $theTour['pax'] ?></td><th class="text-right">Lái xe</th><td contenteditable="true"><?= $theLichxe['laixe'] ?></td></tr>
            <tr><th class="text-right">Điều hành</th><td><?= $theLichxe['dieuhanh'] ?></td><th class="text-right">Hướng dẫn</th><td><?= $theLichxe['huongdan'] ?></td></tr>
            <? if ($theLichxe->note != '') { ?>
            <tr>
                <th class="text-right">Ghi chú:</th>
                <td colspan="3"><?= nl2br($theLichxe->note) ?></td>
            </tr>
            <? } ?>
        </tbody>
    </table>

    <br>

    <table id="table1" class="table table-xxs table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="5%"><?= Yii::t('tour_print', 'TT') ?></th>
                <th class="text-center" width="15%"><?= Yii::t('tour_print', 'Ngày') ?></th>
                <th width="50%"><?= Yii::t('tour_print', 'Chương trình xe') ?></th>
                <th class="text-center" width="5%"><?= Yii::t('tour_print', 'SL') ?></th>
                <th class="text-center" width="10%"><?= Yii::t('tour_print', 'Đ/vị') ?></th>
                <th class="text-center" width="15%"><?= Yii::t('tour_print', 'Thành tiền') ?></th>
            </tr>
        </thead>
        <tbody>
<?
$cnt = 0;
$tong = 0;
$nextDate = '';
$sameday = '';
for ($i = 0; $i < count($content); $i ++) {
    $ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($content[$i][0] - 1).'days'));
    // ngay+1
    $ngayMai = date('Y-m-d', strtotime($theTour['day_from'].' + '.($content[$i][0]).'days'));
    // ngay trong ct tour
    if (isset($content[$i + 1]) && $content[$i + 1] == '') {
        $ngaySau = $ngayMai;
    } else {
        $ngaySau = isset($content[$i + 1]) ? date('Y-m-d', strtotime($theTour['day_from'].' + '.($content[$i + 1][0] - 1).'days')) : $ngayMai;
    }
    $ngayDep = Yii::$app->formatter->asDate($ngay, 'php:j/n/Y (D)');

    if ($content[$i][4] != 'chang') {
        $total[$content[$i][4]] += $content[$i][3];
        $tong += $content[$i][3] * $theLichxe['gia'.$content[$i][4]];
    } else {
        $tong += $content[$i][5];
    }

?>
            <tr>
<?
    if ($sameday != $ngay) {
        $sameday = $ngay;
        $cnt ++;
        if ((int)$content[$i][6] == 1) {
            $rowspan = '';
        } else {
            $rowspan = 'rowspan="'.$content[$i][6].'"';
        }
        ?>
                <td title="<?= $rowspan ?>" class="text-center text-muted" <?= $rowspan ?>><?= $cnt ?></td>
                <td class="text-nowrap text-center" <?= $rowspan ?>><?= $ngayDep ?></td>
<?
    } else { ?>
<!--                 <td class="text-center text-muted"></td>
                <td class="text-nowrap text-center"></td>
 --><?
    } ?>
                <td><?= $content[$i][2] ?></td>
                <td class="text-center"><?= $content[$i][3] ?></td>
                <td class="text-nowrap text-center"><?= $dviList[$content[$i][4]] ?></td>
                <td class="text-nowrap text-right"><?= $content[$i][4] == 'chang' ? number_format($content[$i][5]) : number_format($content[$i][3] * $theLichxe['gia'.$content[$i][4]]) ?></td>
            </tr>
<?
    if ($cnt != 0 && $ngayMai != $ngaySau && isset($content[$i+1]) && $content[$i+1][1] != '') {
?>
            <tr><td colspan="6"></td></tr>
<?
    }
}
?>
            <tr>
                <td colspan="3" class="text-right">
                    <? foreach ($dviList as $k=>$v) { if ($k != 'chang' && $total[$k] != 0) { ?>
                    <div><?= number_format($total[$k]) ?> <div style="width:60px; text-align:left; display:inline-block;"> <?= $v ?></div><div style="width:100px; display:inline-block;"> &times; <?= number_format($theLichxe['gia'.$k]) ?></div><div style="width:140px; display:inline-block;">= <?= number_format($theLichxe['gia'.$k] * $total[$k]) ?> VND</div></div>
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
                    <span onclick=""><?= $theLichxe['vp'] == 'hanoi' ? 'Hà Nội' : 'TP. Hồ Chí Minh' ?></span>, ngày <?= date('j/n/Y') ?>
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
                <td class="text-center"><?= $theLichxe['dieuhanh'] ?></td>
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

TXT;

$this->registerJs($js);

//$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js', ['depends'=>'yii\web\JqueryAsset']);