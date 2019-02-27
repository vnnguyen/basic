<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include '_venue_inc.php';

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Edit: ' . $theVenue['name'];

$oldNewList = [
    'new'  => 'New data only',
    'old'  => 'Old data only',
    'both' => 'Both old and new data',
];
?>
<?php
$venueItinerary = [
    'day'=>'Day use',
    '2D1N'=>'2D1N',
    '3D2N'=>'3D2N',
    'other'=>'other',
];
$venueServiceList_include_price = [
    //include price
    '5_1_01'=>Yii::t('x', 'Air conditioner'),
    '5_1_02'=>Yii::t('x', 'Bathtub'),
    '5_1_03'=>Yii::t('x', 'Breakfast'),
    '5_1_04'=>Yii::t('x', 'Brunch'),
    '5_1_05'=>Yii::t('x', 'Complimentary mineral water'),
    '5_1_06'=>Yii::t('x', 'Cooking class'),
    '5_1_07'=>Yii::t('x', 'Dinner'),
    '5_1_08'=>Yii::t('x', 'Doulbe room'),
    '5_1_09'=>Yii::t('x', 'English speaking guide on board'),
    '5_1_10'=>Yii::t('x', 'Entrance fees as itinerary'),
    '5_1_11'=>Yii::t('x', 'Family room'),
    '5_1_12'=>Yii::t('x', 'Fruit or drink'),
    '5_1_13'=>Yii::t('x', 'Gym / Fitness central'),
    '5_1_14'=>Yii::t('x', 'Hair dryer'),
    '5_1_15'=>Yii::t('x', 'LED TV'),
    '5_1_16'=>Yii::t('x', 'Life jacket'),
    '5_1_17'=>Yii::t('x', 'Lunch'),
    '5_1_18'=>Yii::t('x', 'private toilet'),
    '5_1_19'=>Yii::t('x', 'Room with balcony'),
    '5_1_20'=>Yii::t('x', 'Safety box'),
    '5_1_21'=>Yii::t('x', 'Shower'),
    '5_1_22'=>Yii::t('x', 'Shuttle bus'),
    '5_1_23'=>Yii::t('x', 'Sundeck'),
    '5_1_24'=>Yii::t('x', 'Tai chi'),
    '5_1_25'=>Yii::t('x', 'Twin room'),
    '5_1_26'=>Yii::t('x', 'Wifi'),
];
$venueServiceList_extra_charge = [
    // Extra charge
    '5_2_01'=>Yii::t('x', 'Breakfast'),
    '5_2_02'=>Yii::t('x', 'Casino'),
    '5_2_03'=>Yii::t('x', 'Dinner'),
    '5_2_04'=>Yii::t('x', 'English speaking guide on board'),
    '5_2_05'=>Yii::t('x', 'Entrance fees as itinerary'),
    '5_2_06'=>Yii::t('x', 'Family room'),
    '5_2_07'=>Yii::t('x', 'French speaking guide on board'),
    '5_2_08'=>Yii::t('x', 'Fruit or drink'),
    '5_2_09'=>Yii::t('x', 'Gym / Fitness central'),
    '5_2_10'=>Yii::t('x', 'Brunch'),
    '5_2_11'=>Yii::t('x', 'Laundry'),
    '5_2_12'=>Yii::t('x', 'Lunch'),
    '5_2_13'=>Yii::t('x', 'Safety box'),
    '5_2_14'=>Yii::t('x', 'Salon'),
    '5_2_15'=>Yii::t('x', 'Shop'),
    '5_2_16'=>Yii::t('x', 'Spa and massage serices'),
    '5_2_17'=>Yii::t('x', 'Sundeck'),
    '5_2_18'=>Yii::t('x', 'Tai chi'),
    '5_2_19'=>Yii::t('x', 'Transportation to the deck'),
    '5_2_20'=>Yii::t('x', 'Twin room'),
    '5_2_21'=>Yii::t('x', 'WifiLunch'),
    '5_2_22'=>Yii::t('x', 'Cooking class'),
    '5_2_23'=>Yii::t('x', 'Other activities'),
];
$venueReccList = [
    '6_01'=>Yii::t('x', 'Couple'),
    '6_02'=>Yii::t('x', 'Family with kids'),
    '6_03'=>Yii::t('x', 'Group'),
    '6_04'=>Yii::t('x', 'Honeymoon'),
    '6_05'=>Yii::t('x', 'Demanding travelers'),
    '6_06'=>Yii::t('x', 'Old people'),
    '6_07'=>Yii::t('x', 'Young people'),
    '6_08'=>Yii::t('x', 'Family with teens'),
];

?>
<style type="text/css">
.form-update {display: flex}

</style>
<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'form-update col-sm',
    ]]);?>
    <div class="col-sm-8">
        <div class="card card-default card-body">
            <fieldset>
                <legend><?=Yii::t('x', 'General information')?></legend>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theVenue, 'destination_id')->dropdownList(ArrayHelper::map($destinationList, 'id', 'name_en', 'country_code'), ['prompt' => '- Select destination -'])->label(Yii::t('x', 'Destination'))?></div>
                    <div class="col-sm-6"><?=$form->field($theVenue, 'vtype')->dropdownList($venueTypeList, ['prompt' => Yii::t('x', '- Select -')])->label(Yii::t('x', 'Type'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theVenue, 'name')->label(Yii::t('x', 'Current name'))?></div>
                    <div class="col-md-6"><?=$form->field($theVenue, 'about')->label(Yii::t('x', 'Previous name(s)'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theVenue, 'latlng')->label(Yii::t('x', 'Latitude,Longitude') . ' - <a target="_blank" id="googlelatlng" href="https://www.google.com.vn/search?q=">Google</a>')?></div>
                    <div class="col-md-6"><?=$form->field($theVenue, 'c_amica')->dropdownList(['no'=>'No', 'yes'=>'Yes'])->label(Yii::t('x', 'Involvement of Amica'))?></div>
                    <div class="col-md-6 d-none"><?=$form->field($theVenue, 'supplier_id')->dropdownList(ArrayHelper::map($supplierList, 'id', 'name'), ['prompt' => '- Select supplier -', 'disabled' => 'disabled'])->label(Yii::t('x', 'Supplier'))?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?=Yii::t('x', 'Overview')?></legend>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theVenue, 'contact_expried')->label(Yii::t('x', 'Contact expried'))?></div>
                    <div class="col-sm-6"><?=$form->field($theVenue, 'vclassi')->dropdownList($venueClassiList, ['prompt' => Yii::t('x', '- Select -')])->label(Yii::t('x', 'Classification'))?></div>
                </div>
                <div class="row">
                    <div class="col-sm-6"><?=$form->field($theVenue, 'vitinerary')->dropdownList($venueItinerary, ['class' => 'form-control select2', 'multiple'=>'multiple', 'prompt' => Yii::t('x', '- Select -')])->label(Yii::t('x', 'Itinerary'))?></div>
                    <div class="col-sm-12">
                    <?=$form->field($theVenue, 'vnote_itinerary')->textArea(['rows' => 2])->label(Yii::t('x', 'Note for itinerary'))?></div>
                </div>
                <div class="row">
                    <div class="col-sm-6"><?=$form->field($theVenue, 'vdepart_from')->label(Yii::t('x', 'Depart from'))?></div>
                    <div class="col-sm-6"><?=$form->field($theVenue, 'vcheck_in')->label(Yii::t('x', 'Check in'))?></div>
                    <div class="col-sm-6"><?=$form->field($theVenue, 'vcheck_out')->label(Yii::t('x', 'Check out'))?></div>
                    <div class="col-sm-6"><?=$form->field($theVenue, 'vpricerange')->label(Yii::t('x', 'Price range (most common room type)') . ' USD-USD')?></div>
                </div>
                <?=$form->field($theVenue, 'vship_profile')->textArea(['rows' => 3])->label('Ship profile')?>

                <?=$form->field($theVenue, 'cruise_meta')->textArea(['rows' => 5])->label('Info')?>

                <?=$form->field($theVenue, 'vreccfor')->checkboxList($venueReccList)->label(Yii::t('x', 'Recommended for'))?>

                <?=$form->field($theVenue, 'vservice_include_price')->checkboxList($venueServiceList_include_price, [
                    'data-fee' => $theVenue->new_tags,
                    'class' => 'wrap-tags',
                    'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::checkbox($name, $checked, [
                                'value' => $value,
                                'label' => $label,
                                'class' => 'tag ',
                            ]);
                        }])->label(Yii::t('x', 'Amenities & Services - include price') . ' (<a href="#" id="sel-all-include-price" class="cursor-pointer all-include-price">Select all</a> | <a href="#" class="cursor-pointer sel-none">None</a>)')?>
                <?=$form->field($theVenue, 'vservice_extra_charge')->checkboxList($venueServiceList_extra_charge, [
                    'data-fee' => $theVenue->new_tags,
                    'class' => 'wrap-tags',
                    'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::checkbox($name, $checked, [
                                'value' => $value,
                                'label' => $label,
                                'class' => 'tag ',
                            ]);
                        }])->label(Yii::t('x', 'Amenities & Services - extra charge') . ' (<a href="#" id="sel-all-include-price" class="cursor-pointer all-include-price">Select all</a> | <a href="#" class="cursor-pointer sel-none">None</a>)')?>
                <?/*=$form->field($theVenue, 'vfaci')->checkboxList($venueFaciList, [
                    'data-fee' => $theVenue->new_tags,
                    'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::checkbox($name, $checked, [
                                'value' => $value,
                                'label' => $label . '<span class="d-none"><input name="add_fee[]" value="" type="checkbox" class="add_fee ml-2 "> <small class="text-muted"> add charge</small></span>',
                                'class' => 'tag ',
                            ]);
                        }])->label(Yii::t('x', 'Facilities/Services') . ' (<a href="#" class="cursor-pointer" id="sel-all">Select all</a> | <a href="#" class="cursor-pointer" id="sel-none">None</a>)')*/?>

            </fieldset>

            <?=$form->field($theVenue, 'info')->textArea(['rows' => 15])->label(Yii::t('x', 'Description'))?>

            <fieldset>
                <legend>Price</legend>
                <?=$form->field($theVenue, 'new_pricetable')->textArea(['rows' => 15])->label(Yii::t('x', 'Paste pricetable from Excel'))?>
            </fieldset>

            <fieldset>
                <legend>How to display New information</legend>
                <div class="row">
                    <div class="col-sm-6"><?=$form->field($theVenue, 'new_o')->dropdownList($oldNewList)->label(Yii::t('x', 'Overview'))?></div>
                    <div class="col-sm-6"><?=$form->field($theVenue, 'new_p')->dropdownList($oldNewList)->label(Yii::t('x', 'Price table'))?></div>
                </div>
            </fieldset>

            <?php if (in_array(USER_ID, [1])) {?>
            <fieldset>
                <legend>Image links</legend>
                <?=$form->field($theVenue, 'images')->textArea(['rows' => 15])->label('copy paste from booking.com')?>
            </fieldset>
            <?php }?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-default card-body">
            <?=$form->field($theVenue, 'image', ['inputOptions' => ['class' => 'form-control ckfinder', 'data-ckfinder-update' => 'image']])->hint('Double-click to upload/change image.');?>
            <p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?=$theVenue['image'] == '' ? 'http://placehold.it/300x100&text=NO+IMAGE' : $theVenue['image']?>" alt="Image"></p>
            <?=$form->field($theVenue, 'link_agoda')->label(Yii::t('x', 'Link on agoda.com'))?>
            <?=$form->field($theVenue, 'link_booking')->label(Yii::t('x', 'Link on booking.com'))?>
            <?=$form->field($theVenue, 'link_tripadvisor')->label(Yii::t('x', 'Link on tripadvisor.com'))?>
            <?=Html::submitButton(Yii::t('x', 'Save changes'), ['class' => 'btn btn-primary'])?>
        </div>
    </div>
<?php ActiveForm::end();?>
<?php

$js = <<<'TXT'

// add change
var Data_fee = $('#venue-vservice_include_price').data('fee');
$.each($('input.tag'), function(index, tag){
    var tag_value = $(this).val();
    var tag_check_fee = tag_value + '_';
    if(Data_fee.indexOf(tag_check_fee) != -1){
        $(this).closest('label').find('span').removeClass('d-none');
        $(this).closest('label').find('span input').val(tag_value).prop('checked', true);
    }
});





var ckfinderUpdate = '';

function BrowseServer()
{
    var finder = new CKFinder();
    finder.basePath = '/assets/ckfinder/';
    finder.selectActionFunction = SetFileField;
    finder.popup();
}

function SetFileField( fileUrl )
{
    $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
    $('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(fileUrl);
}

$(function(){
    $('.ckfinder').dblclick(function(){
        ckfinderUpdate = $(this).data('ckfinder-update')
        BrowseServer();
    });
    $('input.ckfinder').change(function(){
        fileUrl = $(this).val();
        if (fileUrl == '')
            fileUrl = 'https://placehold.it/300x300&text=NO+IMAGE'
        ckfinderUpdate = $(this).data('ckfinder-update')
        $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
    });
})

$('#venue-info').ckeditor({
    allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:400,
    contentsCss: '/assets/css/ckeditor_160828.css'
});

$('#venue-new_pricetable').ckeditor({
    allowedContent: 'table thead tbody tr; th td[colspan, rowspan];',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:800,
    contentsCss: '/assets/css/ckeditor_160828.css'
});

// Search for coords
$('#googlelatlng').on('click', function(){
    var href = $(this).attr('href')
    $(this).attr('href', href + $('#venue-name').val())
})

// Check all/none of faci
$('.all-include-price').on('click', function(e){
    $(this).closest('.form-group').find('input[type="checkbox"]').prop('checked', true);
    return false;
})
$('.sel-none').on('click', function(e){
    e.preventDefault()
    $(this).closest('.form-group').find('input[type="checkbox"]').prop('checked', false);
})
$('.select2').select2({
    multiple: true,
});
$('#venue-contact_expried').datepicker({
    selectDate: new Date(),
    language: 'en',
    dateFormat: 'dd/mm/yyyy'
});
TXT;

$this->registerJsFile('https://cdn.ckeditor.com/4.9.2/basic/ckeditor.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.9.2/basic/adapters/jquery.js', ['depends' => 'yii\web\JqueryAsset']);

$this->registerJsFile(DIR . 'assets/ckfinder/ckfinder.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);