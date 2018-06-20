<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_mails_inc.php');

\app\assets\CkeditorAsset::register($this);

$this->registerJs(\app\assets\CkeditorAsset::ckeditorJs('textarea#editor'));

?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($theMail, 'body')->textArea(['rows'=>10, 'id'=>'editor']) ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
