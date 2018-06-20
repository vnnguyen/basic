<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_supplier_inc.php');

$this->title = 'Edit: '.$theSupplier['name'];

?>

<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-lg-5">
			<?=$form->field($theSupplier, 'name'); ?>
		</div>
		<div class="col-lg-7">
			<?=$form->field($theSupplier, 'search'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<?=$form->field($theSupplier, 'name_full'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3">
			<?=$form->field($theSupplier, 'accounting_code'); ?>
		</div>
	</div>
	<p><strong>CONTACT INFORMATION</strong></p>
	<?=$form->field($theSupplier, 'info')->textArea(['rows'=>4]); ?>
	<?=$form->field($theSupplier, 'tax_info')->textArea(['rows'=>4]); ?>
	<?=$form->field($theSupplier, 'bank_info')->textArea(['rows'=>4]); ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
</div>
<div class="col-md-4">
	<?= $form->field($theSupplier, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theSupplier->image == '' ? 'https://placehold.it/100x100&text=NO+IMAGE' : $theSupplier->image ?>" alt="Image"></p>
</div>
<? ActiveForm::end(); ?>
<?
app\assets\CkeditorAsset::register($this);
$this->registerJs(app\assets\CkeditorAsset::ckeditorJs());