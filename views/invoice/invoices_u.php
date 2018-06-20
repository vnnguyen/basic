<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_invoice_inc.php');

if ($theInvoice->isNewRecord) {
    $this->title = 'New invoice';
} else {
    $this->title = 'Edit invoice: '.number_format($theInvoice['amount'], 2).' '.$theInvoice['currency'];
}

$sigClientList = [
    'none'=>'Do not display',
    'sig'=>'Blank line without name',
    'name'=>'Blank line with name of customer',
];
$sigSellerList = [
    'none'=>'Do not display',
    'sig'=>'Blank line without name',
    'name'=>'Blank line with name of seller',
    'seal'=>'Seal of Amica Travel',
    'sealname'=>'Seal of Amica Travel with name of seller',
];

?>
<style>.control-label {color:#999}</style>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
    <? if (USER_ID == 1) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Invoice</h6>
        </div>
        <div class="panel-body">
            
        </div>
    </div>
    <? } ?>
    <p>
        <? if ($theInvoice->isNewRecord) { ?>
        Booking <?= Html::a($theBooking['id'], '@web/bookings/r/'.$theBooking['id']) ?> | Tour <?= Html::a($theBooking['product']['tour']['code'], '@web/tours/r/'.$theBooking['product']['tour']['id']) ?> | Product <?= Html::a($theBooking['product']['title'], '@web/products/r/'.$theInvoice['booking']['product']['id']) ?> by <?= $theInvoice['booking']['createdBy']['name'] ?>
        <? } else { ?>
        Booking <?= Html::a($theInvoice['booking']['id'], '@web/bookings/r/'.$theInvoice['booking']['id']) ?> | Tour <?= Html::a($theInvoice['booking']['product']['tour']['code'], '@web/tours/r/'.$theInvoice['booking']['product']['tour']['id']) ?> | Product <?= Html::a($theInvoice['booking']['product']['title'], '@web/products/r/'.$theInvoice['booking']['product']['id']) ?> by <?= $theInvoice['booking']['createdBy']['name'] ?>
        <? } ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Invoice</h6>
        </div>
        <div class="panel-body">
            <p class="text-warning">
                <i class="fa fa-info-circle"></i> NOTE: Hoá đơn hoàn tiền cũng KHÔNG ghi số tiền âm, hệ thống sẽ tự tính trừ!!
                    <br>Never use a negative amount on both types of invoice.
            </p>
    <div class="row">
        <div class="col-md-3"><?= $form->field($theInvoice, 'brand')->dropdownList(['at'=>'Amica Travel', 'si'=>'Secret Indochina'])->label('Issued by') ?></div>
        <div class="col-md-6"><?= $form->field($theInvoice, 'stype')->dropdownList($typeList)->label('Invoice type') ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'nho_thu')->dropdownList($nhothuList)->label('Nhờ thu / trả qua') ?></div>
    </div>

    <div class="row">
        <div class="col-md-3"><?= $form->field($theInvoice, 'status')->dropdownList($statusList) ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'ref')->label('Reference ID') ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'lang')->dropdownList($languageList) ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'currency')->dropdownList($currencyList, ['prompt'=>'- Select -']) ?></div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <?= $form->field($theInvoice, 'bill_to_name')->label('Bill to name / address') ?>
            <?= $form->field($theInvoice, 'bill_to_address')->textArea(['rows'=>4])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($theInvoice, 'due_dt') ?>
        </div>
    </div>
    
    <!--
    <div class="row">
        <div class="col-md-6"><?= $form->field($theInvoice, 'body')->textArea(['rows'=>8])->label('Service & Description | Price | Qty') ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'body2')->textArea(['rows'=>8])->label('Description | Num or %') ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'body3')->textArea(['rows'=>8])->label('Description | Num or %') ?></div>
    </div>
    -->

    <div class="mb-20">
    <?
    
    if ($theInvoice['body'] == '') {
        $lines = [['', '', '']];
    } else {
        $tbl1 = explode(chr(10), $theInvoice['body']);
        foreach ($tbl1 as $line) {
            $parts = explode('|', $line);
            $lines[] = [trim($parts[0]), trim($parts[2] ?? ''), trim($parts[1] ?? '')];
        }
    }
    ?>
    <div>Main price table (<a id="addthis_1" href="#">+</a>)</div>
    <? foreach ($lines as $line) { ?>
    <div class="add_row_1 row">
        <div class="col-sm-7">
            <?= Html::textInput('desc1[]', $line[0], ['class'=>'form-control', 'placeholder'=>'Service & Description', 'autocomplete'=>'off']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::textInput('qty1[]', $line[1], ['class'=>'form-control text-right', 'placeholder'=>'Qty', 'autocomplete'=>'off']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::textInput('price1[]', $line[2], ['class'=>'form-control text-right', 'placeholder'=>'Price', 'autocomplete'=>'off']) ?>
        </div>
        <div class="col-sm-1"><i <? if (count($lines) == 1) { ?>style="display:none;" <? } ?>class="delthis_1 valign-bottom fa fa-trash-o text-danger cursor-pointer"></i></div>
    </div>
    <? } ?>
    </div>

    <div class="mb-20">

    <?
    if ($theInvoice['body2'] == '') {
        $lines = [['', '']];
    } else {
        $lines = [];
        $tbl1 = explode(chr(10), $theInvoice['body2']);
        foreach ($tbl1 as $line) {
            $parts = explode('|', $line);
            $lines[] = [trim($parts[0]), trim($parts[1] ?? '')];
        }
    }
    ?>
    <div>Deductions and additions (<a id="addthis_2" href="#">+</a>)</div>
    <? foreach ($lines as $line) { ?>
    <div class="add_row_2 row">
        <div class="col-sm-9">
            <?= Html::textInput('desc2[]', $line[0], ['class'=>'form-control text-right', 'placeholder'=>'Service & Description', 'autocomplete'=>'off']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::textInput('price2[]', $line[1], ['class'=>'form-control text-right', 'placeholder'=>'Price', 'autocomplete'=>'off']) ?>
        </div>
        <div class="col-sm-1"><i <? if (count($lines) == 1) { ?>style="display:none;" <? } ?>class="delthis_2 valign-bottom fa fa-trash-o text-danger cursor-pointer"></i></div>
    </div>
    <? } ?>
    </div>

    <div class="row">
        <div class="col-md-3"><?= $form->field($theInvoice, 'method')->dropdownList($methodList, ['prompt'=>'- Select -']) ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'gw_name')->label('Gateway (Onepay, etc)') ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'gw_currency')->dropdownList($currencyList, ['prompt'=>'- Select -']) ?></div>
        <div class="col-md-3"><?= $form->field($theInvoice, 'gw_xrate') ?></div>
    </div>

    <div class="mb-20">
    <?    
    if ($theInvoice['body3'] == '') {
        $lines = [['', '']];
    } else {
        $lines = [];
        $tbl1 = explode(chr(10), $theInvoice['body3']);
        foreach ($tbl1 as $line) {
            $parts = explode('|', $line);
            $lines[] = [trim($parts[0]), trim($parts[1] ?? '')];
        }
    }
    ?>
    <div>Tax and extra imposed by the payment gateway (<a id="addthis_3" href="#">+</a>)</div>
    <? foreach ($lines as $line) { ?>
    <div class="add_row_3 row">
        <div class="col-sm-9">
            <?= Html::textInput('desc3[]', $line[0], ['class'=>'form-control text-right', 'placeholder'=>'Service & Description', 'autocomplete'=>'off']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::textInput('price3[]', $line[1], ['class'=>'form-control text-right', 'placeholder'=>'Price or %', 'autocomplete'=>'off']) ?>
        </div>
        <div class="col-sm-1"><i <? if (count($lines) == 1) { ?>style="display:none;" <? } ?>class="delthis_3 valign-bottom fa fa-trash-o text-danger cursor-pointer"></i></div>
    </div>
    <? } ?>
    </div>

    <?= $form->field($theInvoice, 'link') ?>

    <?= $form->field($theInvoice, 'note_invoice')->textArea(['rows'=>5]) ?>

    <p><strong>Signature options (when Print with signature)</strong> Kiểu chữ ký khi chọn In có chữ ký</p>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theInvoice, 'sig_client')->dropdownList($sigClientList)->label('Signature of customer / left') ?></div>
        <div class="col-md-6"><?= $form->field($theInvoice, 'sig_seller')->dropdownList($sigSellerList)->label('Signature of seller / right') ?></div>
    </div>
    
    <?= $form->field($theInvoice, 'note')->textArea(['rows'=>4])->hint('Ghi chú dành cho Amica') ?>
    <div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
        </div>
    </div>

</div>
<div class="col-md-4">
    <p><strong>Tham khảo: ghi phần dịch vụ</strong></p>
    <pre>Acompte de 10% du prix total du voyage "<?= $theBooking['product']['title'] ?>" | <?= number_format(0.1 * $theBooking['price'], 2) ?> | 1</pre>
    <pre>Organisation du voyage "<?= $theBooking['product']['title'] ?>" | <?= number_format($theBooking['price']) ?> | 1</pre>
    <p><strong>Link tỉ giá</strong>
    <br>Taux de change: http://www.ecb.europa.eu/stats/exchange/eurofxref/html/index.en.html
    <br>Taux de change: http://www.vietcombank.com.vn/ExchangeRates
    </p>
    <p><strong>Tai khoan ACB (EUR)</strong></p>
    <pre>
Bénéficiaire : AMICA., JSC
Adresse: 3rd Floor, Nikko building, 27 Nguyen Truong To, Ba Dinh, Ha Noi, Vietnam
Numéro de compte (EUR) : 8 8 8 1 9 3 7 9
Banque : ASIA COMMERCIAL BANK
Adresse : 40 Hang Giay, Dong Xuan, Hoan Kiem, Hanoi
Swift code : ASCB VNVX
Website: http://www.acb.com.vn</pre>
    <p><strong>Tai khoan ACB (USD)</strong></p>
    <pre>
Bénéficiaire : AMICA., JSC
Adresse: 3rd Floor, Nikko building, 27 Nguyen Truong To, Ba Dinh, Ha Noi, Vietnam
Numéro de compte (USD) : 8 8 8 1 9 2 4 9
Banque : ASIA COMMERCIAL BANK
Adresse : 40 Hang Giay, Dong Xuan, Hoan Kiem, Hanoi
Swift code : ASCB VNVX
Website: http://www.acb.com.vn</pre>
    <p><strong>Tai khoan Vietcombank (USD)</strong></p>
    <pre>
Bénéficiaire : AMICA, JSC
Adresse: 3rd Floor, Nikko building, 27 Nguyen Truong To, Ba Dinh, Ha noi, Viet Nam
Numéro de compte (USD): 0611371444414
Banque : VIETCOMBANK - BA DINH BRANCH
Adresse : 521 Kim Ma - Ba Dinh - Hanoi - Vietnam
Swift code : BFTVVNVX</pre>
    <p><strong>Tai khoan Vietcombank (VND)</strong></p>
    <pre>Beneficiary:  AMICA, JSC
Address:  3rd Floor, Nikko building, 27 Nguyen Truong To, Ba Dinh, Ha noi, Viet Nam   
Account number (VNĐ) : 0611001444385     
Bank :  VIETCOMBANK - BA ĐINH BRANCH     
Bank's address :  521 Kim Ma - Ba Dinh - Hanoi     
Swift code : BFTVVNVX</pre>
    <p><strong>Amica Travel Cambodia (USD)</strong></p>
    <pre>Beneficiary: AMICA TRAVEL (CAMBODGE)
Address: Canadia Building, 4th floor, Street Sivatha, Mondul 1 Svay d'Angkum, Siem Reap, Cambodia
Account number ( USD): 00 80 00 02 61 053
Bank : CANADIA BANK PLC
Bank's address: 315, Ang Duong St.( Corner of Monivong Blvd), Phnom Penh, Cambodia
Swift code: CADIKHPP</pre>
    <p><strong>Laos - BCEL (USD) - Francais</strong></p>
    <pre>Bénéficiaire : NGUYEN VAN TU MR
Adresse: Ban Akard M Sikhottabong VTC, Laos P.D.R
Numéro de compte (USD) : 092-12-01-01279960-001
Banque : Banque Pour Le Commerce Exterieur Lao (BCEL)
Swift code : COEBLALA
Website: http://www.bcel.com.la/bcel/</pre>
    <p><strong>Laos - BCEL (USD) - English</strong></p>
    <pre>Account name: NGUYEN VAN TU MR
Account number: 092-12-01-01279960-001
Bank Name: Banque Pour Le Commerce Exterieur Lao (BCEL) 
Address: Ban Akard M Sikhottabong VTC
Swift code: COEBLALA
Website: http://www.bcel.com.la/bcel/</pre>
</div>
<?
ActiveForm::end();

$js = <<<TXT
$('#invoice-due_dt').daterangepicker({
    minDate:'2007-01-01',
    maxDate:'2027-01-01',
    startDate:moment(),
    format:'YYYY-MM-DD HH:mm',
    showDropdowns:true,
    singleDatePicker:true,
    timePicker:true,
    timePicker12Hour:false,
    timePickerIncrement:1
});
$('#invoice-payment_dt').daterangepicker({
    minDate:'2007-01-01',
    maxDate:'2027-01-01',
    //startDate:moment(),
    format:'YYYY-MM-DD HH:mm',
    showDropdowns:true,
    singleDatePicker:true,
    timePicker:true,
    timePicker12Hour:false,
    timePickerIncrement:1
});
TXT;
$js = str_replace('moment()', "'".$theInvoice['due_dt']."'", $js);
$this->registerCssFile(DIR.'assets/bootstrap-daterangepicker_1.3.7/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment_2.7.0/moment.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-daterangepicker_1.3.7/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);

//if (USER_ID == 1) {
$js = <<<'TXT'
$('i.delthis_1').on('click', function(){
    $(this).parent().parent().remove();
    if ($('i.delthis_1').length == 1) {
        $('i.delthis_1').hide();
    }
});
$('i.delthis_2').on('click', function(){
    $(this).parent().parent().remove();
    if ($('i.delthis_2').length == 1) {
        $('i.delthis_2').hide();
    }
});
$('i.delthis_3').on('click', function(){
    $(this).parent().parent().remove();
    if ($('i.delthis_3').length == 1) {
        $('i.delthis_3').hide();
    }
});
$('#addthis_1').on('click', function(){
    $('.add_row_1:eq(0)').clone(true).insertAfter($('.add_row_1:last')).find(':input').val('');
    $('i.delthis_1').show();
    return false;
});
$('#addthis_2').on('click', function(){
    $('.add_row_2:eq(0)').clone(true).insertAfter($('.add_row_2:last')).find(':input').val('');
    $('i.delthis_2').show();
    return false;
});
$('#addthis_3').on('click', function(){
    $('.add_row_3:eq(0)').clone(true).insertAfter($('.add_row_3:last')).find(':input').val('');
    $('i.delthis_3').show();
    return false;
});
TXT;

$this->registerJs($js);
//}