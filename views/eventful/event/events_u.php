<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_events_inc.php');
$this->title  = 'Edit: '.$theEvent['name'];

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-lg-8">
	<?= $form->field($theEvent, 'name'); ?>
	<div class="row">
		<div class="datetimepicker col-sm-4"><?= $form->field($theEvent, 'from_dt'); ?></div>
		<div class="datetimepicker col-sm-4"><?= $form->field($theEvent, 'until_dt'); ?></div>
		<div class="datetimepicker col-sm-4"><?= $form->field($theEvent, 'timezone'); ?></div>
	</div>
	<?= $form->field($theEvent, 'summary')->textArea(['rows'=>5]); ?>
	<?= $form->field($theEvent, 'body')->textArea(['rows'=>10, 'class'=>'form-control ckeditor']); ?>
</div>
<div class="col-lg-4">
	<?= $form->field($theEvent, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theEvent['image'] == '' ? 'https://placehold.it/300x100&text=NO+IMAGE' : $theEvent->image ?>" alt="Image"></p>
	<?= $form->field($theEvent, 'is_sticky')->dropdownList(['yes'=>'Yes', 'no'=>'No']) ?>
	<?= $form->field($theEvent, 'status')->dropdownList(['on'=>'On', 'off'=>'Off', 'draft'=>'Draft', 'deleted'=>'Deleted']) ?>
	<div><?=Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary btn-block']); ?></div>
</div>
<?
ActiveForm::end();

app\assets\CkeditorAsset::register($this);
$this->registerJs(app\assets\CkeditorAsset::ckeditorJs());