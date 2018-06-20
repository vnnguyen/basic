<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_companies_inc.php');

$this->title = 'Edit: '.$theCompany['name'];

?>

<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-lg-5">
			<?=$form->field($theCompany, 'name'); ?>
		</div>
		<div class="col-lg-7">
			<?=$form->field($theCompany, 'search'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<?=$form->field($theCompany, 'name_full'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3">
			<?=$form->field($theCompany, 'accounting_code'); ?>
		</div>
	</div>
	<p><strong>CONTACT INFORMATION</strong></p>
	<?=$form->field($theCompany, 'info')->textArea(['rows'=>4]); ?>
	<?=$form->field($theCompany, 'tax_info')->textArea(['rows'=>4]); ?>
	<?=$form->field($theCompany, 'bank_info')->textArea(['rows'=>4]); ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
</div>
<div class="col-md-4">
	<?= $form->field($theCompany, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theCompany->image == '' ? 'https://placehold.it/100x100&text=NO+IMAGE' : $theCompany->image ?>" alt="Image"></p>
</div>
<? ActiveForm::end(); ?>
<?

app\assets\CkfinderAsset::register($this);
$this->registerJs(app\assets\CkfinderAsset::ckfinderJs('company'.$theCompany['id']));