<?
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_products_inc.php');

$this->title = '(Not Operational) '.$theProduct['title'];

$this->params['breadcrumb'][] = ['View', 'products/r/'.$theProduct['id']];
$this->params['breadcrumb'][] = ['Operation', 'products/op/'.$theProduct['id']];

?>
<div class="col-md-12">
	<ul class="nav nav-tabs mb-1em">
		<? foreach ($productViewTabs as $tab) { ?>
		<li class="<?= URI == $tab['link'] ? 'active' : '' ?>"><?= Html::a($tab['label'], $tab['link']) ?></li>
		<? } ?>
	</ul>
</div>
<div class="col-md-8">
	<div class="alert alert-warning">
		This product is currently NOT OPERATIONAL. <?= Html::a('Make it operational', 'products/u-op/'.$theProduct['id'], ['class'=>'alert-link']) ?>
	</div>
</div>
