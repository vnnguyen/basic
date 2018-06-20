<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tourguides_inc.php');

if ($theUser->isNewRecord) {
	$this->title = 'New tour guide';
	$this->params['breadcrumb'] = [
		['Tour guides', 'tourguides'],
		['New', 'tourguides/c'],
	];

} else {
	if ($theProfile->isNewRecord) {
		$this->title = 'Add guide profile for: '.$theUser['fname'].' / '.$theUser['lname'];
		$this->params['breadcrumb'] = [
			['People', 'users'],
			['View', 'users/r/'.$theUser['id']],
			['Add tour guide profile', 'tourguides/u/'.$theUser['id']],
		];
	} else {
		$this->title = 'Edit guide profile of: '.$theUser['fname'].' / '.$theUser['lname'];
		$this->params['breadcrumb'] = [
			['Tour guides', 'tourguides'],
			['View profile', 'tourguides/r/'.$theUser['id']],
			['Edit', 'tourguides/u/'.$theUser['id']],
		];
	}	
}

$form = ActiveForm::begin();

?>
<div class="col-md-8">
	<? if (!$theUser->isNewRecord && $theProfile->isNewRecord) { ?>
	<div class="alert alert-info">
		<i class="fa fa-info-circle"></i>
		<strong>NOTE</strong>
		You are adding a tour guide profile for this person. If you save the data, this person's name will be appear under the <a class="alert-link" href="<?= DIR ?>tourguides">Tour guide list</a>.
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
		<div class="col-md-4">
			<?= $form->field($theProfile, 'guide_since') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($theProfile, 'guide_us_since') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($theProfile, 'ratings')->dropdownList([0,1,2,3,4,5,6,7,8,9,10]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4"><?=$form->field($theProfile, 'languages'); ?></div>
		<div class="col-md-4"><?=$form->field($theProfile, 'regions'); ?></div>
		<div class="col-md-4"><?=$form->field($theProfile, 'tour_types') ?></div>
	</div>
	<?=$form->field($theProfile, 'pros')->textArea(['rows'=>3]) ?>
	<?=$form->field($theProfile, 'cons')->textArea(['rows'=>3]) ?>

	<?=$form->field($theProfile, 'note')->textArea(['rows'=>10]); ?>
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
	<?= $form->field($theUser, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theUser->image == '' ? 'https://placehold.it/100x100&text=NO+IMAGE' : $theUser->image ?>" alt="Image"></p>
</div>
<?
ActiveForm::end();

app\assets\DatetimePickerAsset::register($this);
app\assets\CkfinderAsset::register($this);
$this->registerJs(app\assets\CkfinderAsset::ckfinderJs('user'.$theUser['id']));

$js = <<<TXT
$('#profiletourguide-guide_since, #profiletourguide-guide_us_since').daterangepicker({
	minDate:'1960-01-01',
	maxDate:'2060-01-01',
	// startDate:{dt},
	format:'YYYY-MM-DD',
	showDropdowns:true,
	singleDatePicker:true
});

TXT;
//$this->registerJs(str_replace(['{dt}'], ['null'], $js));