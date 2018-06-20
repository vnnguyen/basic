<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Preferences';
$this->params['icon'] = 'cog';
$this->params['active'] = 'prefs';
$this->params['breadcrumb'] = [
	['Me', 'me'],
	['Preferences', URI],
];

$timezoneList = [];
foreach (timezone_identifiers_list() as $i)
	$timezoneList[$i] = str_replace(['/', '_'], [' / ', ' '], $i);

$genderList = ['male'=>'Male', 'female'=>'Female'];


$this->registerCss('
	#avatar { margin-bottom: 10px; position: relative; }
	#avatar:not(.img-fill) {
		border: 1px solid #ccc;
		background: #f2f2f2;
	}
	#fileupload {
		cursor:pointer;
		display: block;
	    height: 100%;
	    left: 0;
	    opacity: 0;
	    position: absolute;
	    top: 0;
	    width: 100%;
	}
');

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="panel panel-white">
		<div class="panel-heading">
			<h6 class="panel-title">Change your preferences</h6>
		</div>
		<div class="panel-body">
	<div class="row">
		<div class="col-lg-6"><?= $form->field($model, 'fname'); ?></div>
		<div class="col-lg-6"><?= $form->field($model, 'lname'); ?></div>
	</div>
	<div class="row">
		<div class="col-lg-6"><?= $form->field($model, 'gender')->dropdownList($genderList); ?></div>
		<div class="col-lg-2"><?= $form->field($model, 'bday'); ?></div>
		<div class="col-lg-2"><?= $form->field($model, 'bmonth'); ?></div>
		<div class="col-lg-2"><?= $form->field($model, 'byear'); ?></div>
	</div>
	<div class="row">
		<div class="col-lg-6"><?= $form->field($model, 'name'); ?></div>
		<div class="col-lg-6"><?= $form->field($model, 'nickname'); ?></div>
	</div>
	<div class="row">
		<div class="col-lg-12"><?= $form->field($model, 'country_code')->dropdownList(ArrayHelper::map($countries, 'code', 'name_en')) ?></div>
	</div>
	<div class="row">
		<div class="col-lg-6"><?= $form->field($model, 'language')->dropdownList(['vi'=>'Tiếng Việt', 'en'=>'English', 'fr'=>'Francais']); ?></div>
		<div class="col-lg-6"><?= $form->field($model, 'timezone')->dropdownList($timezoneList); ?></div>
	</div>
	<div class="row">
		<div class="col-lg-6"><?= $form->field($model, 'email'); ?></div>
		<div class="col-lg-6"><?= $form->field($model, 'phone'); ?></div>
	</div>
	<div class="row">
		<div class="col-lg-12"><?= $form->field($model, 'info')->textArea(['rows'=>5]); ?></div>
	</div>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary']); ?></div>
</div>
	</div>
</div>
<div class="col-md-4">
	<div class="panel panel-white">
		<div class="panel-heading">
			<h6 class="panel-title">Upload your avatar</h6>
		</div>
		<div class="panel-body">
			<?= $form->field($model, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh. Nếu cần sửa ảnh trước khi upload, hãy truy cập site sau: <a rel="external" href="http://apps.pixlr.com/express/">http://apps.pixlr.com/express/</a>'); ?>
			<!-- <p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $model->image == '' ? 'http://placehold.it/300x300&text=NO+IMAGE' : $model->image ?>" alt="Image"></p> -->
			<div id="avatar">
				<img class="img-responsive img-avat" src="<?= $model->image == '' ? 'http://placehold.it/300x300&text=NO+IMAGE' : $model->image ?>" alt="Image">
				<input type="hidden" name="img-avatar" value="">
				<input id="fileupload" type="file" name="files[]" data-url="/fileupload/index">
			</div>
		</div>
	</div>
</div>
<? ActiveForm::end();
		$text = <<<TXT
<script src="{DIR}assets/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
var ckfinderUpdate = '';

function BrowseServer()
{
	var finder = new CKFinder();
	finder.basePath = '{DIR}assets/ckfinder/';
	finder.selectActionFunction = SetFileField;
	finder.popup();
}

function SetFileField( fileUrl )
{
	$('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
	$('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(fileUrl);
}

</script>
TXT;
echo str_replace(['{DIR}'], [DIR], $text);
$jsText = <<<TXT
$(function(){
	$('.ckfinder').dblclick(function(){
		ckfinderUpdate = $(this).data('ckfinder-update')
		BrowseServer();
	});
	$('input.ckfinder').change(function(){
		fileUrl = $(this).val();
		if (fileUrl == '')
			fileUrl = 'http://placehold.it/300x300&text=NO+IMAGE'
		ckfinderUpdate = $(this).data('ckfinder-update')
		$('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
	});
});
$(function () {
    $('#fileupload').fileupload({
    	filesContainer: $('#avatar'),
	    dataType: 'json',
	    disableImageResize: false,
	    imageMaxWidth: 800,
	    imageMaxHeight: 800,
	    imageCrop: true, // Force cropped images
	    imageOrientation: true,
        done: function (e, data) {
            var file = data.result.files[0];
            console.log(file);
            $('#avatar').find('img.img-avat').prop('src', file.url);
            $('#avatar').find('[name="img-avatar"]').val(file.url);
        }
    }).on('fileuploadadd', function (e, data) {
	    	var file    = data.files[0];
	    	var reader = new FileReader();

			reader.addEventListener("load", function () {
				$('#avatar').find('img.img-avat').prop('src', reader.result);
			}, false);
			// Read in the image file as a data URL.
			reader.readAsDataURL(file);
			// return false;
		});
	//$('#fileupload').fileupload({autoUpload:true});
});
TXT;
$this->registerJsFile('/js/plugins/fileupload/vendor/jquery.ui.widget.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.iframe-transport.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload-process.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload-image.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($jsText);
//$model->insertCKFinder();
