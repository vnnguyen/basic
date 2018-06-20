<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_message_inc.php');

$this->title = 'Confirm message deletion: '.$theNote['title'];
$this->params['breadcrumb'][] = ['View', 'notes/r/'.$theNote['id']];

?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<i class="fa fa-fw fa-warning"></i>
		Are you sure you want to delete this message?
	</div>
	<form method="post" action="" class="form-inline">
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete message</button>
		or <?= Html::a('Cancel', $theNote['rtype'].'s/r/'.$theNote['rid']) ?>
	</form>
</div>