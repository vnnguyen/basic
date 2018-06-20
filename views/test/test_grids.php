<?
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
use common\models\Venue;
$this->title = 'Testing GridView';

?>
<div class="col-md-12">
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
			['class' => 'yii\grid\ActionColumn',],
		],
		'tableOptions' => ['class' => 'table table-condensed table-striped table-bordered'],
		'filterModel'=>Venue::className(),
		'caption'=>'I test a table',
	]);
?>
</div>
