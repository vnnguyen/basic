<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_collections_inc.php');
$this->title  = 'Sửa: '.$theCollection['title'];

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-lg-8">
	<?= $form->field($theCollection, 'title'); ?>
	<?= $form->field($theCollection, 'summary')->textArea(['rows'=>5]); ?>
	<div class="row">
		<div class="col-md-8"><?= $form->field($theCollection, 'external_url') ?></div>
		<div class="col-md-4"><?= $form->field($theCollection, 'event_date') ?></div>
	</div>
</div>
<div class="col-lg-4">
	<?= $form->field($theCollection, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
	<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theCollection->image == '' ? 'https://placehold.it/300x100&text=NO+IMAGE' : $theCollection->image ?>" alt="Image"></p>
	<?= $form->field($theCollection, 'is_sticky')->dropdownList(['yes'=>'Yes', 'no'=>'No']) ?>
	<?= $form->field($theCollection, 'status')->dropdownList(['on'=>'On', 'off'=>'Off', 'draft'=>'Draft', 'deleted'=>'Deleted']) ?>
	<div><?=Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary btn-block']); ?></div>
</div>
<? ActiveForm::end();

app\assets\CkeditorAsset::register($this);
$this->registerJs(app\assets\CkeditorAsset::ckeditorJs());