<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_dv_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Giá dịch vụ của: '.$theVenue['name'];

?>

<? $this->beginBlock('tab01'); ?>
<form method="post" action="">
    <?= Html::hiddenInput('action', 'add-contract') ?>
    <p><strong>ADD A CONTRACT</strong> with <?= Html::a($theVenue['name'], '/venues/r/'.$theVenue['id'], ['target'=>'_blank']) ?></p>
    <div class="row">
        <div class="col-md-3"><?= Html::textInput('contract_name', '', ['class'=>'form-control', 'placeholder'=>'Contract short name']) ?></div>
        <div class="col-md-6"><?= Html::textInput('contract_description', '', ['class'=>'form-control', 'placeholder'=>'Contract description']) ?></div>
        <div class="col-md-3"><?= Html::submitButton('Save', ['class'=>'btn btn-primary']) ?></div>
    </div>
    <p><strong>EXISTING CONTRACTS</strong></p>
    <? /*foreach ($theVenue['dvc'] as $dvc) { ?>
    <div><?= $dvc['name'] ?></div>
    <? } */ ?>
</form>
<? $this->endBlock(); ?>

<? $this->beginBlock('tab02'); ?>
<form method="post" action="">
    <?= Html::hiddenInput('action', 'add-prices') ?>
            <p><strong>CONTRACT</strong> with <?= Html::a($theVenue['name'], '/venues/r/'.$theVenue['id'], ['target'=>'_blank']) ?></p>
            <div class="row">
                <div class="col-md-3">
                    <?= Html::dropdownList('contract', '', ArrayHelper::map($theVenue['dvc'], 'id', 'name'),  ['class'=>'form-control', 'placeholder'=>'Contract name', 'autocomplete'=>'off']) ?>
                </div>
                <div class="col-md-9 text-right">
                    <button type="submit" class="btn btn-primary">Save and return</button>
                    or <a href="/dv/gia?venue_id=<?= $theVenue['id'] ?>">Reset</a>
                    or <a href="/venues/r/<?= $theVenue['id'] ?>">Cancel</a>
                </div>
            </div>

            <hr>
            <p><strong>PRICE TABLE</strong></p>
            <div id="price_table">
                <? if (Yii::$app->request->isPost && isset($_POST['dv']) && is_array($_POST['dv'])) { ?>
                <? foreach ($_POST['dv'] as $i=>$post) { ?>
                <div class="row">
                    <div class="col-sm-3">
                        <?= Html::textInput('ok_dv[]', $_POST['dv'][$i], ['class'=>'form-control', 'placeholder'=>'Service', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Html::textInput('ok_validity[]', $_POST['validity'][$i], ['class'=>'form-control', 'placeholder'=>'Validity date range', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= Html::textInput('ok_conds[]', $_POST['conds'][$i], ['class'=>'form-control', 'placeholder'=>'Conditions', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Html::textInput('ok_price[]', $_POST['price'][$i], ['class'=>'form-control text-right', 'placeholder'=>'Price', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-1">
                        <?= Html::textInput('ok_currency[]', $_POST['currency'][$i], ['class'=>'form-control text-right', 'placeholder'=>'Price + Currency', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn xbtn-default btn-block delthis"><i class="fa fa-edit"></i></button>
                    </div>
                </div>
                <? } ?>
                <? } ?>
            </div>
</form>

            <div id="sample_row" style="display:none">
                <div class="row">
                    <div class="col-sm-3">
                        <?= Html::textInput('dv[]', '', ['class'=>'form-control', 'placeholder'=>'Service', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Html::textInput('validity[]', '', ['class'=>'form-control', 'placeholder'=>'Validity date range', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= Html::textInput('conds[]', '', ['class'=>'form-control', 'placeholder'=>'Conditions', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Html::textInput('price[]', '', ['class'=>'form-control text-right', 'placeholder'=>'Price', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-1">
                        <?= Html::textInput('currency[]', '', ['class'=>'form-control text-right', 'placeholder'=>'Price + Currency', 'autocomplete'=>'off']) ?>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn xbtn-default btn-block delthis"><i class="fa fa-trash-o text-danger"></i></button>
                    </div>
                </div>
            </div>

            <hr>
            <div id="add_row" class="row">
                <div class="col-sm-3">
                    <?= Html::dropdownList('dv', '', ArrayHelper::map($theVenue['dv'], 'name', 'name'), ['class'=>'form-control']) ?>
                </div>
                <div class="col-sm-2">
                    <?= Html::textInput('validity', '', ['class'=>'form-control', 'placeholder'=>'Validity date range', 'autocomplete'=>'off']) ?>
                </div>
                <div class="col-sm-3">
                    <?= Html::textInput('conds', '', ['class'=>'form-control', 'placeholder'=>'Conditions', 'autocomplete'=>'off']) ?>
                </div>
                <div class="col-sm-2">
                    <?= Html::textInput('price', '', ['class'=>'form-control text-right', 'placeholder'=>'Price', 'autocomplete'=>'off']) ?>
                </div>
                <div class="col-sm-1">
                    <?= Html::dropdownList('currency', '', ['VND'=>'VND', 'USD'=>'USD'], ['class'=>'form-control', 'tabindex'=>-1]) ?>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-default btn-block" id="addthis">+</button>
                </div>
            </div>
<? $this->endBlock(); ?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li><a href="#tab01" data-toggle="tab"><?= Yii::t('dv', 'Contracts') ?></a></li>
                <li class="active"><a href="#tab02" data-toggle="tab"><?= Yii::t('dv', 'Services') ?></a></li>
                <li><a href="#tab03" data-toggle="tab"><?= Yii::t('dv', 'Prices') ?></a></li>
                <li><a href="#tab04" data-toggle="tab"><?= Yii::t('dv', 'Others') ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="tab01"><?= $this->blocks['tab01'] ?? '1'  ?></div>
                <div class="tab-pane active" id="tab02"><?= $this->blocks['tab02'] ?? '2' ?></div>
                <div class="tab-pane" id="tab03">3</div>
                <div class="tab-pane" id="tab04">4</div>
            </div>
        </div>
    </div>
</div>
<style>
#price_table div.row {margin-bottom:4px;}
#add_row input[type="text"]:focus, #add_row select:focus, #add_row button:focus {border-color:red;}
</style>
<?php

$js = <<<'TXT'
$('div#price_table').on('click', 'button.delthis', function(){
    $(this).parent().parent().remove();
});
$('button#addthis').on('click', function(){
    if ($('div#add_row input[name="price"]').val() != '') {
        $('div#sample_row div.row')
            .clone(true, true)
            .appendTo('div#price_table')
            .show();
        $('div#price_table div.row:last').find('input:eq(0)').val($('#add_row').find('select:eq(0)').val());
        $('div#price_table div.row:last').find('input:eq(1)').val($('#add_row').find('input:eq(0)').val());
        $('div#price_table div.row:last').find('input:eq(2)').val($('#add_row').find('input:eq(1)').val());
        $('div#price_table div.row:last').find('input:eq(3)').val($('#add_row').find('input:eq(2)').val());
        $('div#price_table div.row:last').find('input:eq(4)').val($('#add_row').find('select:eq(1)').val());

        $('div#add_row input[name="price"]').val('');
        $('div#add_row input[name="conds"]').val('');
        $('div#add_row select[name="dv"]').focus();
    }

});
TXT;

$this->registerJs($js);
