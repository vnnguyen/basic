<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_drivers_inc.php');

if ($theProfile->isNewRecord) {
	$this->title = 'Add driver profile for: '.$theUser['fname'].' / '.$theUser['lname'];
} else {
	$this->title = 'Edit driver profile of: '.$theUser['fname'].' / '.$theUser['lname'];
}

$this->params['breadcrumb'] = [
	['People', '@web/users'],
	['View', '@web/users/r/'.$theUser['id']],
	['Driver profile', '@web/drivers/r/'.$theUser['id']],
	['Edit', '@web/drivers/u/'.$theUser['id']],
];

$form = ActiveForm::begin();
?>
<div class="col-md-8">
	<? if ($theProfile->isNewRecord) { ?>
	<div class="alert alert-info">
		<i class="fa fa-info-circle"></i>
		<strong>NOTE</strong>
		You are adding a driver profile for this person. If you save the data, this person's name will be appear under the <a class="alert-link" href="<?= DIR ?>drivers">Drivers list</a>.
	</div>
	<? } ?>
	<div class="row">
		<div class="col-sm-3"><?= $form->field($theUser, 'fname') ?></div>
		<div class="col-sm-3"><?= $form->field($theUser, 'lname') ?></div>
		<div class="col-sm-6"><?= $form->field($theUser, 'name') ?></div>
	</div>
	<div class="row">
		<div class="col-md-2"><?= $form->field($theUser, 'bday') ?></div>
		<div class="col-md-2"><?= $form->field($theUser, 'bmonth') ?></div>
		<div class="col-md-2"><?= $form->field($theUser, 'byear') ?></div>
		<div class="col-md-6"><?= $form->field($theUser, 'gender')->dropdownList(['male'=>'Male', 'female'=>'Female']) ?></div>
	</div>
	<?= $form->field($theUser, 'country_code')->dropdownList(ArrayHelper::map($allCountries, 'code', 'name_en')) ?>
	<div class="row">
		<div class="col-sm-6"><?= $form->field($theUser, 'phone') ?></div>
		<div class="col-sm-6"><?= $form->field($theUser, 'email') ?></div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<?=$form->field($theProfile, 'since'); ?>
		</div>
		<div class="col-md-2">
			<?=$form->field($theProfile, 'us_since'); ?>
		</div>
		<div class="col-md-6">
			<?=$form->field($theProfile, 'vehicle_types'); ?>
		</div>
		<div class="col-md-2">
			<?= $form->field($theProfile, 'points')->dropdownList([0,1,2,3,4,5,6,7,8,9,10]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?=$form->field($theProfile, 'languages'); ?>
		</div>
		<div class="col-md-4">
			<?=$form->field($theProfile, 'regions'); ?>
		</div>
		<div class="col-md-4">
			<?=$form->field($theProfile, 'tour_types'); ?>
		</div>
	</div>
	<?=$form->field($theProfile, 'pros')->textArea(['rows'=>3]); ?>
	<?=$form->field($theProfile, 'cons')->textArea(['rows'=>3]); ?>

	<?=$form->field($theProfile, 'note')->textArea(['rows'=>10]); ?>
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
	<?= $form->field($theUser, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theUser->image == '' ? 'https://placehold.it/100x100&text=NO+IMAGE' : $theUser->image ?>" alt="Image"></p>
	<hr>
	<ul>
		<li>Loại xe: 4S, 7S, 45S, vv</li>
		<li>Ngôn ngữ: ES, IT, FR, VI, EN, ZH, TH, vv</li>
		<li>Vùng miền: VN, VN-NORTH, LA, LA-SOUTH, KH, vv</li>
	</ul>
</div>
<?

ActiveForm::end();

app\assets\CkeditorAsset::register($this);
$this->registerJs(app\assets\CkeditorAsset::ckeditorJs());

$js = <<<TXT
$('#profiledriver-since, #profiledriver-us_since').daterangepicker({
	minDate:'1960-01-01',
	maxDate:'2060-01-01',
	// startDate:{dt},
	format:'YYYY-MM-DD',
	showDropdowns:true,
	singleDatePicker:true
});

TXT;
$this->registerCssFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs(str_replace(['{dt}'], ['null'], $js));