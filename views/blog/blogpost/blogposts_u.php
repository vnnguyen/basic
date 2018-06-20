<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_blogposts_inc.php');
$this->title  = 'Edit: '.$theEntry['title'];
$this->params['icon'] = 'edit';

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-lg-8">
	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">Post content</h6></div>
		<div class="panel-body">
			<?= $form->field($theEntry, 'title'); ?>
			<?= $form->field($theEntry, 'summary')->textArea(['rows'=>5]); ?>
			<?= $form->field($theEntry, 'body')->textArea(['rows'=>10, 'class'=>'form-control editor']); ?>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">Post meta</h6></div>
		<div class="panel-body">
			<?= $form->field($theEntry, 'author_id')->dropdownList(ArrayHelper::map($authorList, 'id', 'name'), ['prompt'=>'( No change )']); ?>
			<div class="datetimepicker"><?= $form->field($theEntry, 'online_from') ?></div>
			<?= $form->field($theEntry, 'image', ['inputOptions'=>['class'=>'form-control ckfinder', 'data-ckfinder-update'=>'image']])->hint('Double-click ảnh hoặc ô này để upload/đổi ảnh.') ?>
			<p><img class="ckfinder img-responsive" data-ckfinder-update="image" src="<?= $theEntry->image == '' ? 'https://placehold.it/350x150?text=No+Image' : $theEntry->image ?>" alt="Image"></p>
			<?= $form->field($theEntry, 'cats')->dropdownList(ArrayHelper::map(Yii::$app->params['acc1/blog/cats'], 'id', 'name')) ?>
			<?= $form->field($theEntry, 'tags') ?>
			<?= $form->field($theEntry, 'is_sticky')->dropdownList(['yes'=>'Yes', 'no'=>'No']) ?>
			<?= $form->field($theEntry, 'status')->dropdownList(['on'=>'On', 'off'=>'Off', 'draft'=>'Draft', 'deleted'=>'Deleted']) ?>
			<div><?=Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary btn-block']); ?></div>
		</div>
	</div>
</div>
<? ActiveForm::end();

\app\assets\CkeditorAsset::register($this);
\app\assets\CkfinderAsset::register($this);
$this->registerJs(\app\assets\CkeditorAsset::ckeditorJs('#post-body', 'full', 'blogpost'.$theEntry['id']));
$this->registerJs(\app\assets\CkfinderAsset::ckfinderJs('blogpost'.$theEntry['id']));

