<?
use yii\helpers\Html;

$this->title = 'Confirm deletion';
$this->params['icon'] = 'trash-o';
$this->params['breadcrumb'] = [
	['Sales', '@web/spaces/sales'],
	['Inquiries', '@web/inquiries'],
	['Delete', DIR.URI],
];

?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<i class="fa fa-fw fa-warning"></i>
		Are you sure you want to delete this inquiry?
	</div>
	<form method="post" action="" class="form-inline well well-sm">
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete inquiry</button>
		or <?= Html::a('Cancel', '@web/inquiries') ?>
	</form>
</div>


