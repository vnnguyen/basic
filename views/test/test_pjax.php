<?
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
//use yii\widgets\Pjax;
//use yii\jui\DatePicker;
use common\models\Venue;
$this->title = 'Testing Pjax '.NOW;
$this->params['breadcrumb'] = [
	['Testing', 'test'],
	['Pjax', 'test/pjax'],
];
?>
<div class="col-md-6">
	<div id="pjc">
	</div>
	<?/* Pjax::begin(); ?>
	<?
		echo GridView::widget([
			'dataProvider' => $dataProvider,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				// A simple column defined by the data contained in $dataProvider.
				['class' => 'yii\grid\CheckboxColumn'],
				[
					'label'=>'Created',
					'attribute'=>'created_at',
					'format'=>['date', 'd-m-Y H:i'],
					'filter'=>['One', 'Two', 'Three'],
				],
				'name',
				'stype',
				'search',
				'destination_id',
				//['class' => 'yii\grid\ActionColumn',],
			],
			'tableOptions' => ['class' => 'table table-condensed table-striped table-bordered'],
			'filterModel'=>Venue::className(),
			//'caption'=>'I test a table',
		]);
	?>
	<? Pjax::end(); */?>
</div>
<div class="col-md-6">
	<?= Html::a('Click me', 'test/pjax?a=c', ['class'=>'pjax']) ?>
	|
	<?= Html::a('Click me A', 'test/pjax?a=b', ['class'=>'pjax']) ?>
</div>
<?
$this->registerJsFile(DIR.'assets/jquery-pjax_1.8.2/jquery.pjax.js', ['app\config\MainAsset']);
$js = "$(document).pjax('a.pjax', '#pjc');";
$this->registerJs($js);