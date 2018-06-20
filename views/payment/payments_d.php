<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_payments_inc.php');

$this->title = 'Confirm deletion';

$this->params['breadcrumb'][] = ['View', 'payments/r/'.$thePayment['id']];
$this->params['breadcrumb'][] = ['Delete', 'payments/d/'.$thePayment['id']];

?>

<div class="col-md-8">
	<form method="post" action="" class="form-inline well well-sm">
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete payment</button>
		or <?= Html::a('Cancel', 'payments/r/'.$thePayment['id']) ?>
	</form>
</div>
