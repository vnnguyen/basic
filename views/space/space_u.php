<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//include('_kbposts_inc.php');
$this->title  = 'Work spaces';


$form = ActiveForm::begin();
?>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-body">
			<fieldset>
				<legend>Space activities</legend>
                <?= $form->field($theSpace, 'name') ?>
                <?= $form->field($theSpace, 'description')->textArea(['rows'=>5]) ?>
			</fieldset>
			<?= Html::submitButton(Yii::t('app', 'Save changes'), ['class'=>'btn btn-primary']) ?>
		</div>
	</div>
</div>
<?
ActiveForm::end();