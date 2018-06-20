<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_venue_inc.php');

$this->title = 'Edit: '.$theVenue['name'];


?>
<? $form = ActiveForm::begin(['class'=>'form-inline well well-sm']); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-4"><?= $form->field($theVenue, 'supplier_id')->dropdownList(ArrayHelper::map($supplierList, 'id', 'name'), ['prompt'=>'- Select supplier -']) ?></div>
		<div class="col-md-4"><?= $form->field($theVenue, 'destination_id')->dropdownList(ArrayHelper::map($destinationList, 'id', 'name_en', 'country_code'), ['prompt'=>'- Select destination -']) ?></div>
		<div class="col-md-4"><?= $form->field($theVenue, 'stype')->dropdownList($venueTypes, ['prompt'=>'- Select type -'])->label('Type') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theVenue, 'name') ?></div>
		<div class="col-md-6"><?= $form->field($theVenue, 'about') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theVenue, 'search') ?></div>
		<div class="col-md-2"><?= $form->field($theVenue, 'abbr') ?></div>
		<div class="col-md-4"><?= $form->field($theVenue, 'latlng')->label('Map LatLng - <a target="_blank" href="http://itouchmap.com/latlong.html">Link</a>') ?></div>
	</div>
	<? if ($theVenue['stype'] == 'cruise') { ?>
	<?= $form->field($theVenue, 'cruise_meta')->textArea(['rows'=>5])->label('Thông tin tàu') ?>
	<? } ?>
	<?= $form->field($theVenue, 'info')->textArea(['rows'=>15])->label('Information (Markdown-format)') ?>
	<?= $form->field($theVenue, 'info_facilities')->textArea(['rows'=>15])->label('Facilities / Services provided') ?>
</div>
<div class="col-md-4">
	<?= $form->field($theVenue, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click to upload/change image.'); ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theVenue['image'] == '' ? 'http://placehold.it/300x100&text=NO+IMAGE' : $theVenue['image'] ?>" alt="Image"></p>
	<?= $form->field($theVenue, 'link_agoda') ?>
	<?= $form->field($theVenue, 'link_booking') ?>
	<?= $form->field($theVenue, 'link_tripadvisor') ?>
	<?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?>
</div>
<? ActiveForm::end();

$js = <<<'TXT'

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
TXT;

$this->registerJsFile(DIR.'assets/ckfinder/ckfinder.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);