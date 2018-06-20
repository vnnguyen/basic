<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\OtaxonomyHelper;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Edit hotel information (new): '.$theVenue['name'];

include('_venue_inc.php');

// include ('test_js.php');

$inspectionData = $theTmp['inspections'] == '' ? [] : unserialize($theTmp['inspections']);
$roomData = $theTmp['rooms'] == '' ? [] : unserialize($theTmp['rooms']);

$rate1To5List = [1=>1, 2=>2, 3=>3, 4=>4, 5=>5];

$recommendedForList = [
    'couple'=>'Couple',
    'family'=>'Family',
    'group'=>'Group',
    'honeymoon'=>'Honeymoon',
    'demanding'=>'Demanding travelers',
];

$hotelFacilityList = [
    'lift'=>'Lift',
    'pool'=>'Swimming pool',
    'garden'=>'Garden',
    'spa'=>'Spa',
    'restaurant'=>'Restaurant to recommend',
];

$form = ActiveForm::begin();
?>

<style>
.hint-block {font-style: italic; color:#999;}
</style>
<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend>Hotel information</legend>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($theTmp, 'cat')->label('Hotel category')->hint('Eg. Superior') ?>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($theTmp, 'cmt')->label('Comment by Amica')->hint('Eg. Comfortable hotel with nice staff, very good location') ?>
                    </div>
                </div>
                <?= $form->field($theTmp, 'loc')->label('Description of location')->hint('Eg. 2 min walk to Hoan Kiem lake, fairly noisy crossroads') ?>
                <div class="row">
                    <div class="col-md-3">
                        <?=$form->field($theTmp, 'price_min', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>9999, 'step'=>1]])->label('Price range (USD)') ?>
                    </div>
                    <div class="col-md-3">
                        <?=$form->field($theTmp, 'price_max', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>9999, 'step'=>1]])->label('&nbsp;') ?>
                    </div>
                </div>

                <legend>Rooms</legend>
                <div class="row">
                    <div class="col-md-3">
                        <?=$form->field($theTmp, 'room_total_count', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>9999, 'step'=>1]])->label('Total number of rooms') ?>
                    </div>
                </div>
                <div class="room-list-item cloneable" style="display:none; background-color:#ffe; margin:0 -20px; padding:20px; border-top:1px dotted #ccc;">
                    <div class="row mb-10">
                        <div class="col-md-4">Name of room type<br><input type="text" class="form-control" name="room_name[]" value=""></div>
                        <div class="col-md-2">Total<br><input type="text" class="form-control" name="room_count[]" value=""></div>
                        <div class="col-md-6">
                            <i class="delete-room pull-right text-danger fa fa-trash-o cursor-pointer"></i>
                            Room features<br><input type="text" class="form-control" name="room_features[]" value=""></div>
                    </div>
                    <div class="row mb-10">
                        <div class="col-md-2">Twin<br><input type="text" class="form-control" name="room_twn[]" value=""></div>
                        <div class="col-md-2">Double<br><input type="text" class="form-control" name="room_dbl[]" value=""></div>
                        <div class="col-md-2">Triple<br><input type="text" class="form-control" name="room_tpl[]" value=""></div>
                        <div class="col-md-2">Connecting<br><input type="text" class="form-control" name="room_conn[]" value=""></div>
                        <div class="col-md-2">Extra bed<br><input type="text" class="form-control" name="room_eb[]" value=""></div>
                        <div class="col-md-2">Sell?<br><input type="text" class="form-control" name="room_sell[]" value=""></div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">Price (USD)<br><input type="text" class="form-control" name="room_price[]" value=""></div>
                        <div class="col-md-10">Other note<br><input type="text" class="form-control" name="room_note[]" value=""></div>
                    </div>
                </div>

                <div id="room-list">
                    <? foreach ($roomData as $item) { ?>
                    <div class="room-list-item" style="background-color:#ffe; margin:0 -20px; padding:20px; border-top:1px dotted #ccc;">
                        <div class="row mb-10">
                            <div class="col-md-4">Name of room type<br><input type="text" class="form-control" name="room_name[]" value="<?= $item['name'] ?? '' ?>"></div>
                            <div class="col-md-2">Total<br><input type="text" class="form-control" name="room_count[]" value="<?= $item['count'] ?? '' ?>"></div>
                            <div class="col-md-6">
                                <i class="delete-room pull-right text-danger fa fa-trash-o cursor-pointer"></i>
                                Room features<br><input type="text" class="form-control" name="room_features[]" value="<?= $item['features'] ?? '' ?>"></div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-2">Twin<br><input type="text" class="form-control" name="room_twn[]" value="<?= $item['twn'] ?? '' ?>"></div>
                            <div class="col-md-2">Double<br><input type="text" class="form-control" name="room_dbl[]" value="<?= $item['dbl'] ?? '' ?>"></div>
                            <div class="col-md-2">Triple<br><input type="text" class="form-control" name="room_tpl[]" value="<?= $item['tpl'] ?? '' ?>"></div>
                            <div class="col-md-2">Connecting<br><input type="text" class="form-control" name="room_conn[]" value="<?= $item['conn'] ?? '' ?>"></div>
                            <div class="col-md-2">Extra bed<br><input type="text" class="form-control" name="room_eb[]" value="<?= $item['eb'] ?? '' ?>"></div>
                            <div class="col-md-2">Sell?<br><input type="text" class="form-control" name="room_sell[]" value="<?= $item['sell'] ?? '' ?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">Price (USD)<br><input type="text" class="form-control" name="room_price[]" value="<?= $item['price'] ?? '' ?>"></div>
                            <div class="col-md-10">Other note<br><input type="text" class="form-control" name="room_note[]" value="<?= $item['note'] ?? '' ?>"></div>
                        </div>
                    </div>
                    <? } ?>
                </div>
                <p><a id="add-room" href="#">+Room type</a></p>


                <legend>Services and facilities</legend>
                <div class="form-group">
                    <label class="control-label">Facilities and comments:</label>
                <?
                foreach ($hotelFacilityList as $kfac=>$vfac) {
                    ?>
                    <div class="row form-group">
                        <div class="col-md-3"><label class="control-label"><input type="checkbox" name="fac_<?= $kfac ?>_ok" value="yes"> <?= $vfac ?></label></div>
                        <div class="col-md-9"><?= $form->field($theTmp, 'fac_'.$kfac)->label(false) ?></div>
                    </div>
                    <?
                }
                ?>
                </div>
                <div class="row">
                    <div class="col-md-3">&nbsp;</div>
                    <div class="col-md-9"><?= $form->field($theTmp, 'fac_breakfast_type')->label('Breakfast type') ?></div>
                </div>
                

                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="control-label">Eco-friendly approach:</label>
                    </div>                    
                    <div class="col-md-9">
                    <?= $form->field($theTmp, 'is_eco')->radioList(['yes'=>'Yes', 'no'=>'No'])->label(false) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Recommended for:</label>
                <?
                foreach ($recommendedForList as $krec=>$vrec) {
                    ?>
                    <div class="row form-group">
                        <div class="col-md-3"><label class="control-label"><input type="checkbox" name="rec_<?= $krec ?>_ok" value="yes"> <?= $vrec ?></label></div>
                        <div class="col-md-9"><?= $form->field($theTmp, 'rec_'.$krec)->label(false) ?></div>
                    </div>
                    <?
                }
                ?>
                </div>
            </fieldset>

            <fieldset>
                <legend>Amica ratings</legend>
                <p>(1=Poor, 2=Average, 3=Good, 4=Very good, 5=Excellent)</p>
                <div class="row">
                    <div class="col-md-3">     
                        <?=$form->field($theTmp, 'rating_bedding')->dropdownlist($rate1To5List, ['prompt'=>'- Select -'])->label('Bedding') ?>
                    </div>
                    <div class="col-md-3">     
                        <?=$form->field($theTmp, 'rating_service')->dropdownlist($rate1To5List, ['prompt'=>'- Select -'])->label('Services') ?>
                    </div>
                    <div class="col-md-3">     
                        <?=$form->field($theTmp, 'rating_value')->dropdownlist($rate1To5List, ['prompt'=>'- Select -'])->label('Value for money') ?>
                    </div>
                    <div class="col-md-3">     
                        <?=$form->field($theTmp, 'rating_cleanliness')->dropdownlist($rate1To5List, ['prompt'=>'- Select -'])->label('Cleanliness') ?>
                    </div>
                    <div class="col-md-3">     
                        <?=$form->field($theTmp, 'rating_general')->dropdownlist($rate1To5List, ['prompt'=>'- Select -'])->label('General rating') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?=$form->field($theTmp, 'verdict')->textArea(['rows'=>5])->label('Final verdict') ?>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Inspection history</legend>
                <div class="row" id="inspection-labels" style="display:<?= empty($inspectionData) ? 'none' : 'block' ?>;">
                    <div class="col-md-3">
                        <label class="control-label">Inspection date (Y-m-d)</label>
                    </div>
                    <div class="col-md-9">
                        <label class="control-label">Inspection by (names)</label>
                    </div>
                </div>
                <div class="row mb-10 inspection-list-item cloneable" style="display:none;">
                    <div class="col-md-3 has-datepicker">
                        <input type="text" name="inspection_date[]" value="" class="form-control">
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="inspection_by[]" value="" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <i class="delete-inspection fa fa-trash-o text-danger cursor-pointer"></i>
                    </div>
                </div>
                <div id="inspection-list">
                    <? foreach ($inspectionData as $item) { ?>
                    <div class="row mb-10 inspection-list-item">
                        <div class="col-md-3 has-datepicker">
                            <input type="text" name="inspection_date[]" value="<?= $item['date'] ?? '' ?>" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="inspection_by[]" value="<?= $item['by'] ?? '' ?>" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <i class="delete-inspection fa fa-trash-o text-danger cursor-pointer"></i>
                        </div>
                    </div>
                    <? } ?>
                </div>
                <p><a id="add-inspection" href="#">+Inspection</a></p>
            </fieldset>
            <div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary btn-sub', 'name' => 'submitButton', 'id'=> 'submitbtn']); ?></div> 
        </div>
    </div>
</div>




</div>

</div>
<?php

ActiveForm::end();


$js = <<<'TXT'
$('a#add-room').on('click', function(){
    $('.room-list-item.cloneable').clone(true, true).removeClass('cloneable').appendTo('#room-list').show().find(':input:eq(0)').focus();
    return false;
})
$('#room-list').on('click', 'i.delete-room', function(){
    if (confirm('Delete room type?')) {
        $(this).closest('.room-list-item').remove();
    }
    return false;
})

$('a#add-inspection').on('click', function(){
    $('.inspection-list-item.cloneable')
        .clone(true, true)
        .removeClass('cloneable')
        .appendTo('#inspection-list')
        .show()
        .find(':input:eq(0)')
        .focus();
    if ($('#inspection-list .inspection-list-item').length == 0) {
        $('#inspection-labels').hide()
    } else {
        $('#inspection-labels').show()
    }
    return false;
})
$('#inspection-list').on('click', 'i.delete-inspection', function(){
    if (confirm('Delete inspection data?')) {
        $(this).closest('.inspection-list-item').remove();
    }
    if ($('#inspection-list .inspection-list-item').length == 0) {
        $('#inspection-labels').hide()
    } else {
        $('#inspection-labels').show()
    }
    return false;
})

$('.has-datepickerx :input').datepicker({
    format: 'yyyy-mm-dd',
    weekStart: 1,
    maxViewMode: 2,
    todayBtn: "linked",
    clearBtn: true,
    language: "vi",
    autoclose: true,
    todayHighlight: true
})

$('body').on('focus', '.has-datepicker :input', function(){
    $(this).datepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        maxViewMode: 2,
        todayBtn: "linked",
        clearBtn: true,
        language: "vi",
        autoclose: true,
        todayHighlight: true
    });
})
TXT;

$this->registerJs($js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.'.\Yii::$app->language.'.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
