<?
use yii\helpers\Html;

$this->title = 'Confirm deletion';
$this->params['icon'] = 'trash-o';
$this->params['breadcrumb'] = [
	['People', 'users'],
	['Driver profile', 'drivers/r/'.$theUser['id']],
	['Delete', URI],
];

?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<i class="fa fa-fw fa-question-circle"></i>
		Are you sure you want to delete this driver profile? The person will no longer be listed as a driver.
	</div>
	<form method="post" action="" class="form-inline well well-sm">
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete profile</button>
		or <?= Html::a('Cancel', 'drivers/r/'.$theUser['id']) ?>
	</form>
</div>


