<?
use yii\helpers\Html;
use yii\helpers\Markdown;

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
    'chang'=>'Lượt',
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
?><html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Lịch xe - <?= $theTour['op_code'] ?></title>
    <style type="text/css">
html, body {font-family:dejavusanscondensed, Helvetica, Arial, sans-serif;}
h1 {font-size:20px; color:#333; padding:24px 0 0; margin:0; font-weight:bold; text-align:center;}
h2 {font-size:14px; font-family:dejavusans, Helvetica, Arial, sans-serif; font-weight:bold; color:#0092CF; padding:4px 5px; border-top:1px solid #777; border-bottom:1px solid #777;}
p {padding:0; margin:0 5px 12px;}
.table {margin-bottom:12px; border-collapse: collapse; width:100%;}
.table td, .table th {font-size:11px; text-align:left; vertical-align:top; padding:4px;}
.table-bordered td, .table-bordered th {border:1px solid #777;}
.table-borderless td, .table-borderless th {border:0;}
#wrap {margin:20px 0; font-size:11px; background-color:#fff; padding:48px;}
.text-center, .table th.text-center, .table td.text-center {text-align:center;}
.text-right, .table th.text-right, .table td.text-right {text-align:right;}
@media print {
    .section-hab {page-break-before: always;}
    #wrap {padding:0; width:100%;}
}
@page {
    margin-header:1.3cm;
    margin-top:2.3cm;
    margin-footer:1.3cm;
    margin-bottom:2.3cm;
    header:myHTMLHeader;
    footer:myHTMLFooter;
}
@page :first {
    margin-top:3.3cm;
    header:myHTMLHeaderFirstPage;
}
    </style>
</head>
<body>
<htmlpageheader name="myHTMLHeaderFirstPage" style="display:none">
<table width="100%" style="vertical-align: top; font-family: sans; font-size: 8pt; border-bottom:1px solid #ccc;">
    <tr>
        <td width="30%">
            <img style="display:inline; width:120px; margin-top:-10px;" src="/assets/img/logo_161114_mcs.jpg">
        </td>
        <td width="70%" style="text-align: right;">
            <div style="font-weight:bold;">Công ty Cổ phần Đầu tư, Thương mại và Du lịch Thân Thiện Việt Nam</div>
            <div style="color:#777">Địa chỉ: Tầng 3, toà nhà Nikko, số 27 Nguyễn Trường Tộ, Ba Đình, Hà Nội</div>
            <div style="color:#777">Tel: (04) 6273 4455 Email: info@amica-travel.com Web: https://www.amica-travel.com</div>
        </td>
    </tr>
</table>
</htmlpageheader>

<htmlpageheader name="myHTMLHeader" style="display:none">
<table width="100%" style="vertical-align: top; font-family: sans; font-size: 8pt; border-bottom:1px solid #ccc;">
    <tr>
        <td width="70%">
            <div style="color:#777">Lịch xe - <?= $theTour['op_code'] ?></div>
        </td>
        <td width="30%" style="text-align: right;">
            <div style="color:#777">{DATE j/n/Y}</div>
        </td>
    </tr>
</table>
</htmlpageheader>

<htmlpagefooter name="myHTMLFooter" style="display:none">
<table width="100%" style="vertical-align: top; font-family: sans; font-size: 8pt;">
    <tr>
        <td style="text-align:center">- <?= Yii::t('app', 'Page') ?> {PAGENO} / {nb} -</td>
    </tr>
</table>
</htmlpagefooter>

    <div id="wrap">
        <h1 style="text-transform:uppercase">Lịch xe - <?= $theTour['op_code'] ?></h1>
        <br>

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
                $rowspan = 'rowspan="'.(int)$content[$i][6].'"';
            }
            ?>
                    <td class="text-center text-muted" <?= $rowspan ?>><?= $cnt ?></td>
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
                    <td class="text-nowrap text-right"><?= $content[$i][4] == 'chang' ? number_format((float)$content[$i][5]) : number_format($content[$i][3] * $theLichxe['gia'.$content[$i][4]]) ?></td>
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
                        <table class="table-borderless">
                        <? foreach ($dviList as $k=>$v) { if ($k != 'chang' && $total[$k] != 0) { ?>
                        <tr>
                            <td style="padding:0 0 2px 0; text-align:right"><?= number_format($total[$k]) ?></td>
                            <td style="padding:0 15px 2px 0;"> <?= $v ?></td>
                            <td style="padding:0 0 2px 0;">&times;</td>
                            <td style="padding:0 0 2px 0; text-align:right"><?= number_format($theLichxe['gia'.$k]) ?></td>
                            <td style="padding:0 15px 2px 0;"> VND/<?= $v ?></td>
                            <td style="padding:0 15px 2px 0;">= <?= number_format($theLichxe['gia'.$k] * $total[$k]) ?> VND</td>
                        </tr>
                        <? } } ?>
                        </table>
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
                    <td class="text-right" colspan="5">Tổng phát sinh (VND)</td>
                    <td></td>
                </tr>
                <!--
                <tr><td colspan="6">&nbsp;</td></tr>
                <tr>
                    <th class="text-right" colspan="5">Tổng cộng tiền cần thanh toán cho tour này (hợp đồng + phát sinh)</th>
                    <th class="text-right">< ?= number_format($tong) ?></th>
                </tr>
                -->
            <tbody>
        </table>

        <table class="table table-xxs table-borderless">
            <tbody>
                <tr>
                    <th width="67%" colspan="2"></th>
                    <td width="33%" class="text-center">
                        <?= $theLichxe['vp'] == 'hanoi' ? 'Hà Nội' : 'TP. Hồ Chí Minh' ?>, ngày <?= date('j/n/Y') ?>
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
</body>
</html>
