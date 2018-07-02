<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>PDF PRINT SAMPLE</title>
    <style type="text/css">
html, body {font-family:dejavusanscondensed, Helvetica, Arial, sans-serif;}
h1 {font-size:20px; text-transform:uppercase; color:#333; padding:50px 0 0; margin:0; font-weight:bold;}
h2 {font-size:16px; font-family:dejavusans, Helvetica, Arial, sans-serif; padding:0; margin:0; font-weight:normal; color:#777;}
p {padding:0; margin:0 5px 12px;}
.table {margin-bottom:12px; border-collapse: collapse; width:100%;}
.table td, .table th {font-size:11px; text-align:left; vertical-align:top; padding:4px;}
.table-bordered td, .table-bordered th {border:1px solid #777;}
.table-borderless td, .table-borderless th {border:0;}
#wrap {margin:20px auto; font-size:11px; background-color:#fff; padding:48px;}
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
.table td, .table th {border-bottom:1px solid #ddd;}
    </style>
}
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
                <div style="color:#777">Sample PDF print</div>
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
            <td width="50%" style="color:#777">Mẫu số ABCD-1234</td>
            <td width="50%" style="text-align:right; color:#777"><?= Yii::t('app', 'Trang') ?> {PAGENO} / {nb}</td>
        </tr>
    </table>
    </htmlpagefooter>

    <?= $content ?>

</body>
</html>