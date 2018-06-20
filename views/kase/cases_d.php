<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_kase_inc.php');

$this->title = 'Confirm case deletion: '.$theCase['name'];
$this->params['breadcrumb'][] = ['View', 'cases/r/'.$theCase['id']];

?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<i class="fa fa-fw fa-warning"></i>
		You are about to delete this case. All related proposals, notes, emails, inquiries, files and content will also be deleted.
		<br>Are you sure you want to proceed?
	</div>
	<form method="post" action="" class="form-inline">
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete case</button>
		or <?= Html::a('Cancel', '@web/cases/r/'.$theCase['id']) ?>
	</form>
</div>