<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_collections_inc.php');
$this->title  = 'Thêm collection mới';

$form = ActiveForm::begin(); ?>
<div class="col-lg-8">
	<?= $form->field($theCollection, 'title'); ?>
	<?= $form->field($theCollection, 'summary')->textArea(['rows'=>5]); ?>
</div>
<? ActiveForm::end();

