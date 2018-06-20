<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_forum_topics_inc.php');

if ($theTopic->isNewRecord) {
	$this->title = 'New forum topic';
} else {
	$this->title = 'Edit topic: '.$theTopic['title'];
}
?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<div id="files-list"></div>
	<p id="files-container">
		<a id="files-browse" href="javascript:;">Upload files</a>
		<span id="files-console" class="text-danger"></span>
	</p>
	<?= $form->field($theTopic, 'title') ?>
	<?= $form->field($theTopic, 'body')->textArea(['rows'=>15]) ?>
	<?= $form->field($theTopic, 'cats')->dropdownList($forumCatList, ['prompt'=>'- Select -']) ?>
	<?= $form->field($theTopic, 'tags') ?>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
	<p><strong>NOTE</strong></p>
	<ul>
		<li>File uploading is not working at the moment</li>
		<li>If you are editing an existing post, all people who have replied to the post will be notified by email</li>
	</ul>
</div>
<?
include('_plupload_inc.php');