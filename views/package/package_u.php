<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_package_inc.php');

Yii::$app->params['page_title'] = 'New package tour';

?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin() ?>
	<?= $form->field($thePackage, 'name') ?>
    <p class="ckfinder" style="padding:10px; background-color:#fff; border:1px solid #eee;">Double-click để upload file</p>
	<?= $form->field($thePackage, 'info')->textarea(['rows'=>10]) ?>
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
<?

\app\assets\CkeditorAsset::register($this);
\app\assets\CkfinderAsset::register($this);
$this->registerJs(\app\assets\CkeditorAsset::ckeditorJs('#package-info', 'basic', 'package'.$thePackage['id']));
$this->registerJs(\app\assets\CkfinderAsset::ckfinderJs('package'.$thePackage['id']));
