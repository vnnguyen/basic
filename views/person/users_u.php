<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\OtaxonomyHelper;

$userGenderList = [
	'male'=>'Male',
	'female'=>'Female',
];

include('_users_inc.php');

if ($theUser->isNewRecord) {
	$this->title = 'New user';
	$this->params['icon'] = 'plus';
	$this->params['breadcrumb'] = [
		['Users', 'users'],
		['Add', 'users/c'],
	];
} else {
	$this->title = 'Edit: '.$theUser['name'];
	$this->params['icon'] = 'edit';
	$this->params['breadcrumb'] = [
		['Users', 'users'],
		['View', 'users/r/'.$theUser['id']],
		['Edit', 'users/u/'.$theUser['id']],
	];
}
?>

<div class="col-md-8">
	<? $form = ActiveForm::begin();?>
	<div class="row">
		<div class="col-md-3">
			<?=$form->field($theForm, 'fname'); ?>
		</div>
		<div class="col-md-3">
			<?=$form->field($theForm, 'lname'); ?>
		</div>
		<div class="col-md-4">
			<?=$form->field($theForm, 'name'); ?>
		</div>
		<div class="col-md-2">
			<?=$form->field($theForm, 'gender')->dropdownList($userGenderList) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<?=$form->field($theForm, 'bday') ?>
		</div>
		<div class="col-md-2">
			<?=$form->field($theForm, 'bmonth') ?>
		</div>
		<div class="col-md-2">
			<?=$form->field($theForm, 'byear') ?>
		</div>
		<div class="col-md-6">
			<?=$form->field($theForm, 'country_code')->dropdownList(ArrayHelper::map($allCountries, 'code', 'name_en')); ?>
		</div>
	</div>
	<p><strong>CONTACT INFORMATION</strong></p>
	<div class="row">
		<div class="col-md-6">
			<?=$form->field($theForm, 'email1') ?>
		</div>
		<div class="col-md-6">
			<?=$form->field($theForm, 'email2') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?=$form->field($theForm, 'email3') ?>
		</div>
		<div class="col-md-6">
			<?=$form->field($theForm, 'website') ?>
		</div>
	</div>
	<?=$form->field($theForm, 'address') ?>
	<div class="row">
		<div class="col-md-6">
			<?=$form->field($theForm, 'phone1') ?>
		</div>
		<div class="col-md-6">
			<?=$form->field($theForm, 'phone2') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?=$form->field($theForm, 'pob') ?>
		</div>
		<div class="col-md-6">
			<?=$form->field($theForm, 'profession') ?>
		</div>
	</div>
	<?= $form->field($theForm, 'note')->textArea(['rows'=>10]) ?>
	<?= $form->field($theForm, 'tags') ?>
	<?/*? foreach ($theMetas as $i=>$meta) { ?>
	<div class="row">
		<div class="col-md-3">
			<?= $form->field($meta, '['.$i.']k')->label(false) ?>
		</div>
		<div class="col-md-7">
			<?= $form->field($meta, '['.$i.']v')->label(false) ?>
		</div>
		<div class="col-md-2">
			<?= $form->field($meta, '['.$i.']x')->label(false) ?>
		</div>
	</div>
	<? } ?>
	<?= $form->field($theForm, 'info')->textArea(['rows'=>4]) */?>
	<!--input class="form-control select2" name="tags" -->
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
	
</div>
<div class="col-md-4">
	<?= $form->field($theUser, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theUser->image == '' ? 'https://placehold.it/100x100&text=NO+IMAGE' : $theUser->image ?>" alt="Image"></p>
</div>
<? ActiveForm::end(); ?>
<style type="text/css">
.field-meta-k label, .field-meta-v label, .field-meta-x label {display: none}
</style>
<?
app\assets\CkeditorAsset::register($this);
$this->registerJs(app\assets\CkeditorAsset::ckeditorJs());
