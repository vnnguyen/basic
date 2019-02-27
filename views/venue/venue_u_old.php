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
<style type="text/css">
#venue-vfaci, #venue-vstyle, #venue-vreccfor {-webkit-column-count: 3; -moz-column-count: 3; column-count:3}
#venue-vfaci label, #venue-vstyle label, #venue-vreccfor label {display:block;}
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
					<div class="col-md-6"><?=$form->field($theVenue, 'supplier_id')->dropdownList(ArrayHelper::map($supplierList, 'id', 'name'), ['prompt' => '- Select supplier -', 'disabled' => 'disabled'])->label(Yii::t('x', 'Supplier'))?></div>
				</div>
			</fieldset>

			<?php if ($theVenue['stype'] == 'hotel' || $theVenue['stype'] == 'home') {
    ?>
			<fieldset>
				<legend><?=Yii::t('x', 'Overview')?></legend>
				<div class="row">
					<div class="col-sm-6"><?=$form->field($theVenue, 'vstr')->dropdownList($venueStraRecList, ['prompt' => Yii::t('x', '(Not selected)')])->label(Yii::t('x', 'Strategic/Recommended'))?></div>
					<div class="col-sm-6"><?=$form->field($theVenue, 'vstar')->dropdownList($venueStarList, ['prompt' => Yii::t('x', '(Not selected)')])->label(Yii::t('x', 'Stars'))?></div>
				</div>
				<div class="row">
					<div class="col-sm-6"><?=$form->field($theVenue, 'vclassi')->dropdownList($venueClassiList, ['prompt' => Yii::t('x', '- Select -')])->label(Yii::t('x', 'Classification'))?></div>
					<div class="col-sm-6"><?=$form->field($theVenue, 'varchi')->dropdownList($venueArchiList, ['prompt' => Yii::t('x', '- Select -')])->label(Yii::t('x', 'Architecture'))?></div>
				</div>

				<div class="row">
					<div class="col-sm-6"><?=$form->field($theVenue, 'vdistc', ['inputOptions' => ['type' => 'number', 'min' => 0, 'max' => 99, 'class' => 'form-control', 'style' => 'width:100px']])->label(Yii::t('x', 'Distance from city center (km)'))?></div>
					<div class="col-sm-6"><?=$form->field($theVenue, 'vdistb', ['inputOptions' => ['type' => 'number', 'min' => 0, 'max' => 99, 'class' => 'form-control', 'style' => 'width:100px']])->label(Yii::t('x', 'Distance from beach (km)'))?></div>
				</div>
				<div class="row">
					<div class="col-sm-6"><?=$form->field($theVenue, 'vdista', ['inputOptions' => ['type' => 'number', 'min' => 0, 'max' => 99, 'class' => 'form-control', 'style' => 'width:100px']])->label(Yii::t('x', 'Distance from airport (km)'))?></div>
					<div class="col-sm-6"><?=$form->field($theVenue, 'vpricerange')->label(Yii::t('x', 'Price range (most common room type)') . ' USD-USD')?></div>
				</div>

				<?=$form->field($theVenue, 'vstyle')->checkboxList($venueStyleList)->label(Yii::t('x', 'Style'))?>
				<?=$form->field($theVenue, 'vfaci')->checkboxList($venueFaciList, [
					'data-fee' => $theVenue->new_tags,
        			'item' => function ($index, $label, $name, $checked, $value) {
				            return Html::checkbox($name, $checked, [
				                'value' => $value,
				                'label' => $label . '<span class="d-none"><input name="add_fee[]" value="" type="checkbox" class="add_fee ml-2 "> <small class="text-muted"> add change</small></span>',
				                'class' => 'tag ',
				            ]);
				        }])->label(Yii::t('x', 'Facilities/Services') . ' (<a href="#" class="cursor-pointer" id="sel-all">Select all</a> | <a href="#" class="cursor-pointer" id="sel-none">None</a>)')?>
				<?=$form->field($theVenue, 'vreccfor')->checkboxList($venueReccList)->label(Yii::t('x', 'Recommended for'))?>
			</fieldset>
			<?php } // hotels only ?>

			<?php if ($theVenue['stype'] == 'cruise') {?>
			<?=$form->field($theVenue, 'cruise_meta')->textArea(['rows' => 5])->label('Thông tin tàu')?>
			<?php }?>

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
var Data_fee = $('#venue-vfaci').data('fee');
$.each($('input.tag'), function(index, tag){
	var tag_value = $(this).val();
	var tag_check_fee = tag_value + '_';
	if(Data_fee.indexOf(tag_check_fee) != -1){
		$(this).closest('label').find('span').removeClass('d-none');
		$(this).closest('label').find('span input').val(tag_value).prop('checked', true);
	}
});


$(document).on('click', '.add_fee', function(){
	if($(this).prop("checked")) {
		$(this).val($(this).closest("label").find('.tag').val());
	}
	console.log($(this).closest("label").find('.tag').val());
});
$('.tag').on('click', function(){
	$(this).closest('label').find('span').toggleClass('d-none');
	if(!$(this).prop("checked")) {
		$(this).closest('label').find('span input').val('').prop('checked', false);
		$(this).closest('label').find('span').addClass('d-none');
	} else {
		$(this).closest('label').find('span').removeClass('d-none');
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
$('#sel-all').on('click', function(e){
	e.preventDefault()
	$('#venue-vfaci input[type="checkbox"]').prop('checked', true)
	if($('#venue-vfaci input[type="checkbox"]').hasClass("add_fee")){
		$('.add_fee').prop('checked', false);
		$('#venue-vfaci input[type="checkbox"]').closest('span').removeClass('d-none');
	}
})
$('#sel-none').on('click', function(e){
	e.preventDefault()
	$('#venue-vfaci input[type="checkbox"]').prop('checked', false);
	if($('#venue-vfaci input[type="checkbox"]').hasClass("add_fee")){
		$('#venue-vfaci input[type="checkbox"]').closest('span').addClass('d-none');
	}
})
TXT;

$this->registerJsFile('https://cdn.ckeditor.com/4.9.2/basic/ckeditor.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.9.2/basic/adapters/jquery.js', ['depends' => 'yii\web\JqueryAsset']);

$this->registerJsFile(DIR . 'assets/ckfinder/ckfinder.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs($js);