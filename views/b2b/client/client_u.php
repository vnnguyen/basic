<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_client_inc.php');

?>
<style>
.row.meta {padding-right:20px;}
.row.meta, .mb6 {margin-bottom:6px;}
.action_remove_meta {position:absolute; right:0; margin:10px 20px 0 0}
.intl-tel-input {width: 100%;}
</style>
<div class="col-md-8">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card card-body">
        <div class="row">
            <div class="col-md-6"><?= $form->field($theClient, 'name')->label(Yii::t('x', 'Name of client account')) ?></div>
            <div class="col-md-6"><?= $form->field($theClient, 'owner_id')->dropdownList(ArrayHelper::map($ownerList, 'id', 'name'), ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('x', 'Account owner')) ?></div>
        </div>
        <!--
        <div class="row">
            <div class="col-md-6"><?= $form->field($theClient, 'login')->label(Yii::t('x', 'Login name')) ?></div>
            <div class="col-md-6"><?= $form->field($theClient, 'newpassword')->passwordInput()->label(Yii::t('x', 'Password')) ?></div>
        </div>
        -->
        <fieldset>
            <legend><?= Yii::t('x', 'Contact information') ?></legend>
            <div id="list_tel">
                <?php foreach ($data['tel'] as $item) { ?>
                <div class="row meta data-tel">
                    <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                    <div class="col-sm-3">
                        <?= Html::dropdownList('name[]', $item['name'], $dataTelList, ['class'=>'form-control']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= Html::textInput('value[]', $item['value'], ['class'=>'form-control', 'placeholder'=>'Value']) ?>
                        <?= Html::hiddenInput('full[]', $item['full']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= Html::textInput('note[]', $item['note'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <p><a class="action_add_meta" data-meta="tel" href="#">+<?= Yii::t('x', 'Telephone/fax number') ?></a></p>

            <div id="list_email">
                <?php foreach ($data['email'] as $item) { ?>
                <div class="row meta data-email">
                    <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                    <div class="col-sm-3">
                        <?= Html::dropdownList('name[]', $item['name'], $dataEmailList, ['class'=>'form-control']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= Html::textInput('value[]', $item['value'], ['class'=>'form-control', 'placeholder'=>'Value']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= Html::textInput('note[]', $item['note'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <p><a class="action_add_meta" data-meta="email" href="#">+<?= Yii::t('x', 'Email address') ?></a></p>

            <div id="list_url">
                <?php foreach ($data['url'] as $item) { ?>
                <div class="row meta data-url">
                    <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                    <div class="col-sm-3">
                        <?= Html::dropdownList('name[]', $item['name'], $dataUrlList, ['class'=>'form-control']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= Html::textInput('value[]', $item['value'], ['class'=>'form-control', 'placeholder'=>'Value']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= Html::textInput('note[]', $item['note'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <p><a class="action_add_meta" data-meta="url" href="#">+<?= Yii::t('x', 'Website/Link') ?></a></p>

            <div id="list_addr">
                <?php foreach ($data['addr'] as $item) { ?>
                <div class="row meta data-addr">
                    <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                    <div class="col-sm-3">
                        <?= Html::dropdownList('name[]', $item['name'], $dataAddrList, ['class'=>'form-control']) ?>
                    </div>
                    <div class="col-sm-9">
                        <div class="mb6"><?= Html::textInput('addr_line_1[]', $item['addr_line_1'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Address line 1')]) ?></div>
                        <div class="mb6"><?= Html::textInput('addr_line_2[]', $item['addr_line_2'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Address line 2')]) ?></div>
                        <div class="row mb6">
                            <div class="col-sm-6"><?= Html::textInput('addr_city[]', $item['addr_city'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'City/Province')]) ?></div>
                            <div class="col-sm-6"><?= Html::textInput('addr_state[]', $item['addr_state'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'State/Region')]) ?></div>
                        </div>
                        <div class="row mb6">
                            <div class="col-sm-4"><?= Html::textInput('addr_postal[]', $item['addr_postal'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Postal code')]) ?></div>
                            <div class="col-sm-8"><?= Html::dropdownList('addr_country[]', $item['addr_country'], ArrayHelper::map($countryList, 'code', 'name'), ['class'=>'form-control', 'prompt'=>Yii::t('app', '- Select -')]) ?></div>
                        </div>
                        <div><?= Html::textInput('note[]', $item['note'], ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?></div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <p><a class="action_add_meta" data-meta="addr" href="#">+<?= Yii::t('x', 'Address') ?></a></p>
        </fieldset>

<!--         <fieldset>
            <legend><?= Yii::t('x', 'Contacts under this client account') ?></legend>
        </fieldset> -->

        <fieldset id="cke_needed">
            <legend><?= Yii::t('x', 'General information') ?></legend>
            <?= $form->field($theClient, 'body')->textArea(['rows'=>5])->label(false) ?>
            <?= $form->field($theClient, 'info_type_of_cooperation')->textArea(['rows'=>5])->label(Yii::t('x', 'Note about sales')) ?>
            <?= $form->field($theClient, 'info_client_service')->textArea(['rows'=>5])->label(Yii::t('x', 'Note about client services')) ?>
            <?= $form->field($theClient, 'info_tour_operation')->textArea(['rows'=>5])->label(Yii::t('x', 'Note about tour operation')) ?>
            <?= $form->field($theClient, 'info_payment_conditions')->textArea(['rows'=>5])->label(Yii::t('x', 'Conditions of payment')) ?>
            <?= $form->field($theClient, 'info_bank_accounts')->textArea(['rows'=>5])->label(Yii::t('x', 'Bank accounts')) ?>
            <?= $form->field($theClient, 'info_urgent_contact')->textArea(['rows'=>5])->label(Yii::t('x', 'Urgent contact')) ?>
            <?= $form->field($theClient, 'info_debt')->label(Yii::t('x', 'Outstanding balance')) ?>
        </fieldset>

        <fieldset>
            <legend><?= Yii::t('x', Yii::t('x', 'Note')) ?></legend>
            <?= $form->field($theClient, 'note')->textArea(['rows'=>5])->label(false) ?>
        </fieldset>

        <? if (!$theClient->isNewRecord) { ?>
        <p><i class="fa fa-info-circle text-muted"></i> <?= Yii::t('x', 'Last update {time} by {user}.', ['time'=>Yii::$app->formatter->asRelativetime($theClient->updated_dt), 'user'=>$theClient->updatedBy['name']]) ?></p>
        <? } ?>
        <div>
            <?= Html::submitButton(Yii::t('x', 'Save changes'), ['class' => 'btn btn-primary']) ?>
            <?= Yii::t('x', 'or') ?>
            <?= Html::a(Yii::t('x', 'Cancel'), '#back') ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="col-md-4">
    <div class="card card-body">
        <?= Yii::t('x', 'Logo') ?>
        <div class="slim -thumb text-center -thumb-rounded -thumb-slide"
             data-service="/assets/slim_1.1.1/server/async.php"
             -data-fetcher="/assets/slim_1.1.1/server/fetch.php"
             -data-ratio="1:1"
             -data-min-size="250,250"
             data-push="true"
             data-max-file-size="2">
            <?php if ($theClient->image != '') { ?>
            <img src="<?= $theClient->image ?>" alt="<?= Yii::t('x', 'Logo') ?>">
            <?php } ?>
            <input type="file" name="slim[]"/>
        </div>
    </div>
</div>

<div id="data_templates" style="display:none;">
    <div class="row meta data-tel">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?= Html::dropdownList('name[]', '', $dataTelList, ['class'=>'form-control']) ?>
        </div>
        <div class="col-sm-6">
            <?= Html::textInput('value[]', '', ['class'=>'form-control', 'placeholder'=>'Value']) ?>
            <?= Html::hiddenInput('full[]', '') ?>
        </div>
        <div class="col-sm-3">
            <?= Html::textInput('note[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
        </div>
    </div>
    <div class="row meta data-email">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?= Html::dropdownList('name[]', '', $dataEmailList, ['class'=>'form-control']) ?>
        </div>
        <div class="col-sm-6">
            <?= Html::textInput('value[]', '', ['class'=>'form-control', 'placeholder'=>'Value']) ?>
        </div>
        <div class="col-sm-3">
            <?= Html::textInput('note[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
        </div>
    </div>
    <div class="row meta data-url">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?= Html::dropdownList('name[]', '', $dataUrlList, ['class'=>'form-control']) ?>
        </div>
        <div class="col-sm-6">
            <?= Html::textInput('value[]', '', ['class'=>'form-control', 'placeholder'=>'Value']) ?>
        </div>
        <div class="col-sm-3">
            <?= Html::textInput('note[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
        </div>
    </div>
    <div class="row meta data-addr">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?= Html::dropdownList('name[]', '', $dataAddrList, ['class'=>'form-control']) ?>
        </div>
        <div class="col-sm-9">
            <div class="mb6"><?= Html::textInput('addr_line_1[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Address line 1')]) ?></div>
            <div class="mb6"><?= Html::textInput('addr_line_2[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Address line 2')]) ?></div>
            <div class="row mb6">
                <div class="col-sm-6"><?= Html::textInput('addr_city[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'City/Province')]) ?></div>
                <div class="col-sm-6"><?= Html::textInput('addr_state[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'State/Region')]) ?></div>
            </div>
            <div class="row mb6">
                <div class="col-sm-4"><?= Html::textInput('addr_postal[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Postal code')]) ?></div>
                <div class="col-sm-8"><?= Html::dropdownList('addr_country[]', '', ArrayHelper::map($countryList, 'code', 'name'), ['class'=>'form-control', 'prompt'=>Yii::t('app', '- Select -')]) ?></div>
            </div>
            <div><?= Html::textInput('note[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?></div>
        </div>
    </div>
</div>

<?php
$js = <<<'JS'
$(document).on('click', '.action_add_meta', function(e){
    e.preventDefault()
    var meta = $(this).data('meta')
    $('#data_templates .row.data-' + meta + ':first').clone(true, true).appendTo($('#list_' + meta)).find(':input:first').focus()
    if (meta == 'tel') {
        $('#list_tel .row.meta.data-tel:last').find('input:eq(0)').intlTelInput({
            initialCountry: 'fr',
            preferredCountries: ['fr', 'be', 'ca', 'vn', 'us'],
            utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/utils.js',
        })
    }
})

$(document).on('click', '.action_remove_meta', function(e){
    if (!confirm('Delete item?')) {
        return false
    }
    $(this).closest('.row.meta').remove()
})

$('#list_tel .row.meta.data-tel input[name="value[]"]').intlTelInput({
    initialCountry: 'fr',
    preferredCountries: ['fr', 'be', 'ca', 'vn', 'us'],
    utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/utils.js',
});

$('#w0').on('submit',function(){
    $('#list_tel .row.meta.data-tel input[name="value[]"]').each(function(i){
        var full = $(this).intlTelInput('getNumber')
        $('input[name="full[]"]:eq(' + i + ')').val(full)
    })
})

$('#cke_needed textarea, #client-note').ckeditor({
    allowedContent: 'p sub sup strong em s i u ul ol li blockquote; a[!href]; img[!src]{*}(*);',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:200,
    contentsCss: '/assets/css/style_ckeditor.css'
});
JS;

$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.10.1/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.10.1/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.min.js', ['depends'=>'yii\web\JqueryAsset']);

