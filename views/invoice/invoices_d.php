<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_invoice_inc.php');

$this->title = 'Confirm deletion';

$this->params['breadcrumb'][] = ['View', 'invoices/r/'.$theInvoice['id']];
$this->params['breadcrumb'][] = ['Delete', 'invoices/d/'.$theInvoice['id']];

?>

<div class="col-md-8">
	<form method="post" action="" class="form-inline well well-sm">
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete invoice</button>
		or <?= Html::a('Cancel', 'invoices/r/'.$theInvoice['id']) ?>
	</form>
</div>
