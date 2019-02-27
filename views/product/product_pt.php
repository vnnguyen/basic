<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;

$this->context->layout = 'main';

$roomTypes = [
    'Chambre:',
    'Chambre avec vue jardin',
    'Chambre vue sur la ville',
    'Chambre vue mer',
    'Chambre vue jardin',
    'Chambre vue piscine',
    'Chambre standard:',
    'Chambre standard',
    'Chambre Deluxe:',
    'Chambre Deluxe',
    'Chambre Deluxe avec fenêtre',
    'Chambre Deluxe vue sur la ville',
    'Chambre Deluxe vue mer',
    'Chambre Deluxe vue jardin',
    'Chambre Deluxe vue piscine',
    'Chambre Deluxe Premium',
    'Chambre Supérieure:',
    'Chambre Supérieure',
    'Chambre Supérieure avec fenêtre',
    'Chambre Supérieure vue sur la ville',
    'Chambre Supérieure vue mer',
    'Chambre Supérieure vue jardin',
    'Chambre Supérieure vue piscine',
    'Suite:',
    'Suite',
    'Suite Executive',
    'Suite Executive Premium',
    'Suite Junior',
    'Grande Suite',
    'Suite Famille',
    'Suite VIP',
    'Chambres connectées:',
    'Chambres connectées',
    'Chambre familiale ',
    'Chambres maison d’hôtes:',
    'Chambre privée',
    'Chambre commune, type dortoir',
    'Bungalow:',
    'Bungalow',
    'Bungalow vue jardin',
    'Bungalow vue mer',
    'Bungalow vue piscine',
    'Cabine:',
    'Cabine double',
    'Cabine individuelle',
    'Cabine triple',
    'Cabine Premium avec balcon',
    'Cabine avec balcon',
    'Cabine Deluxe',
    'Cabine Deluxe avec balcon',
    'Cabine Premium',
    'Cabine Supérieure',
];
$roomTypeList = [];
$cat = '';
foreach ($roomTypes as $type) {
    if (substr($type, -1) == ':') {
        $cat = $type;
    } else {
        $roomTypeList[] = [
            'key'=>$type,
            'value'=>$type,
            'cat'=>$cat,
        ];
    }
}

Yii::$app->params['page_title'] = 'Price table by hotel type - '.$theProduct['title'];
Yii::$app->params['page_breadcrumbs'] = [
    ['Products', 'products'],
    ['View', 'products/r/'.$theProduct['id']],
    ['Price table'],
];

$venueList = [];
foreach ($theVenues as $venue) {
    if (strpos($venue['search'], '3s') !== false) {
        $venue['name'] .= ' ***';
    } elseif (strpos($venue['search'], '4s') !== false) {
        $venue['name'] .= ' ****';
    } elseif (strpos($venue['search'], '5s') !== false) {
        $venue['name'] .= ' *****';
    }
    $key = $venue['name'].'|||';
    if (!empty($venue['metas'])) {
        $ws = $venue['metas'][0]['value'];
        if (substr($ws, 0, 4) != 'http') {
            $ws = 'http://'.$ws;
        }
        $key .= $ws;
    }
    $venueList[] = [
        'key'=>$key,
        'name'=>$venue['name'],
        'dest'=>$venue['destination']['name_en'],
    ];
}

?>
<style>
.bg-grey-100 {background-color:#f3f3f3;}
.table-xxs th, .table-xxs td {padding:6px!important;}
.ui-state-highlight {background-color:#fffff3;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <p><strong>PRICE TABLE / BẢNG GIÁ</strong> You can edit directly here or user the form below</p>
            <?php $form = ActiveForm::begin() ?>
            <?= $form->field($theProduct, 'prices')->textArea(['rows'=>10]) ?>
            <p><strong>PRICE SUMMARY / TÓM TẮT GIÁ</strong></p>
            <div class="row">
                <div class="col-md-2"><?= $form->field($theProduct, 'price') ?></div>
                <div class="col-md-2"><?= $form->field($theProduct, 'price_unit')->dropdownList(['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND'])->label('&nbsp;') ?></div>
                <div class="col-md-2"><?= $form->field($theProduct, 'price_for')->dropdownList(['personne'=>'personne', 'groupe'=>'groupe']) ?></div>
                <div class="col-md-3"><?= $form->field($theProduct, 'price_from')->label('Validity from') ?></div>
                <div class="col-md-3"><?= $form->field($theProduct, 'price_until')->label('Validity until') ?></div>
            </div>
            <p>
                <button type="submit" class="btn btn-primary">Save and return</button>
                or <a href="/products/pt/<?= $theProduct['id'] ?>">Reset</a>
                or <a href="/products/r/<?= $theProduct['id'] ?>">Cancel</a>
            </p>
            <?php ActiveForm::end() ?>
            <p><strong>ADD ELEMENTS (LINES) / THÊM DÒNG</strong> Remember to save above when done</p>
            <div class="row form-group">
                <div class="col-sm-2">
                    <select id="addwhat" name="addwhat" class="form-control">
                        <option value="1">Option / Option</option>
                        <option value="2">Hotel / Khách sạn</option>
                        <option value="3">Price / Giá</option>
                        <option value="4">Blank / Dòng trắng</option>
                    </select>
                </div>
                <div class="col-sm-9">
                    <div class="row add" id="add1">
                        <div class="col-sm-12"><?= Html::textInput('option1', '', ['class'=>'form-control', 'placeholder'=>'Option name']) ?></div>
                    </div>
                    <div class="row add" id="add2" style="display:none">
                        <div class="col-sm-4"><?= Html::textInput('destination', '', ['class'=>'form-control', 'placeholder'=>'Destination']) ?></div>
                        <div class="col-sm-4"><?= Html::dropdownList('accommodation', '', ArrayHelper::map($venueList, 'key', 'name', 'dest'), ['class'=>'xform-control select2', 'prompt'=>'- Accommodation -']) ?></div>
                        <div class="col-sm-4"><?= Html::dropdownList('roomtype', '', ArrayHelper::map($roomTypeList, 'key', 'value', 'cat'), ['class'=>'form-control select2', 'placeholder'=>'Room type']) ?></div>
                    </div>
                    <div class="row add" id="add3" style="display:none">
                        <div class="col-sm-8"><?= Html::textInput('price1', '', ['class'=>'form-control', 'placeholder'=>'Price name']) ?></div>
                        <div class="col-sm-4"><?= Html::textInput('price2', '', ['class'=>'form-control', 'placeholder'=>'Price']) ?></div>
                    </div>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-primary btn-block" id="addthis">+</button>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
select:focus {border-color:red!important;}
</style>
<?php

$js = <<<'TXT'
$('.select2').selectpicker({
    liveSearch: true,
});
$('#addwhat').on('change', function(){
    var what = $(this).val();
    $('div.add.row').hide();
    $('div#add' + what).show();
    return false;
});
$('button#addthis').on('click', function(){
    var what = $('#addwhat').val();
    if (what == 1) {
        var row = 'OPTION : ' + $('input[name="option1"]').val();
    }
    if (what == 2) {
        var res = $('select[name="accommodation"]').val();
        res = res.replace('|||', ' : ' + $('select[name="roomtype"]').val() + ' : ');
        var row = '+ ' + $('input[name="destination"]').val() + ' : ' + res;
    }
    if (what == 3) {
        var row = '- ' + $('input[name="price1"]').val() + ' : ' + $('input[name="price2"]').val();
    }
    if (what == 4) {
        var row = '';
    }

    var xyz = $('textarea#product-prices').val();
    var textarea = document.getElementById('prices');
    $('textarea#product-prices').val(xyz + "\n" + row);
    textarea.scrollTop = textarea.scrollHeight;
    $('#addwhat').focus();
});
$('#product-day_from, #product-price_from, #product-price_until').datepicker({
    weekStart: 1,
    format: 'yyyy-mm-dd',
    showDropdowns:true,
    autoclose: true,
    todayHighlight: true,
    todayBtn: "linked",
    clearBtn: true,
});
TXT;

$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css', ['depends'=>'yii\web\JqueryAsset']);

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/css/bootstrap-datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
