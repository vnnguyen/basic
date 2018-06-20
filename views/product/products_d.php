<?
use yii\helpers\Html;

include('_products_inc.php');

$this->title = 'Confirm product deletion: '.$theProduct['title'];

$this->params['breadcrumb'][] = ['View', 'products/r/'.$theProduct['id']];
$this->params['breadcrumb'][] = ['Delete', 'products/d/'.$theProduct['id']];

?>

<div class="col-md-8">
	<form method="post" action="" class="form-inline well well-sm">
		<p>Are you sure you want to delete this Product?</p>
		<input type="hidden" name="confirm" value="delete">
		<button class="btn btn-danger" type="submit">Delete product</button>
		or <?= Html::a('Cancel', 'products/r/'.$theProduct['id']) ?>
	</form>
</div>
