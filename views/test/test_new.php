<?
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
//use yii\widgets\Pjax;
//use yii\jui\DatePicker;
use common\models\Venue;
$this->title = 'Testing: '.NOW;
$this->params['breadcrumb'] = [
	['Testing', 'test'],
	['New workspace', 'test/new'],
];
?>
<div class="col-md-3">
	<form method="post" action="" class="form-inline well well-sm">
		<input type="text" class="form-control" name="search" value="">
	</form>
	<? foreach ($theCases as $case) { ?>
	<div class="">
		<?= Html::a($case['name'], 'kases/r/'.$case['id'], ['class'=>'case-name', 'style'=>'padding:2px 5px; display:block;']) ?>
	</div>
	<? } ?>
</div>
<div class="col-md-6" id="xx"></div>
<div class="col-md-3"></div>

<?
//$this->registerJsFile(DIR.'assets/jquery-pjax_1.8.2/jquery.pjax.js', ['app\config\MainAsset']);
$js = <<<'TXT'
$('.case-name').click(function(){
	$('.case-name').removeClass('text-danger bg-warning');
	$(this).addClass('text-danger bg-warning');
	href = $(this).attr('href');
	$('#xx').html('<div class="alert alert-info">LOADING, PLEASE WAIT...</div>');
	$('#xx').load(href+ ' #xxx');
	return false;
});
TXT;
$this->registerJs($js);