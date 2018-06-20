<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_bookings_inc.php');

$this->title = 'Confirm booking deletion';

$this->params['breadcrumb'][] = ['View', 'bookings/r/'.$theBooking['id']];
$this->params['breadcrumb'][] = ['Delete', 'bookings/d/'.$theBooking['id']];

?>

<div class="col-md-8">
	<form method="post" action="" class="form-inline well well-sm">
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete booking</button>
		or <?= Html::a('Cancel', 'cases/r/'.$theBooking['case']['id']) ?>
	</form>
</div>
