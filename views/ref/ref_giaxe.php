<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

$this->title = 'Giá vận chuyển đường bộ';

$this->params['icon'] = 'car';

$this->params['breadcrumb'] = [
	['Ref', 'ref'],
	['Giá xe', 'ref/giaxe'],
];

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th width="30"></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theCpx as $cp) { ?>
				<tr>
					<td class="text-muted text-center"><?= $cp['id'] ?></td>
					<td><?= Html::a($cp['name'], 'cp/r/'.$cp['id']) ?></td>
					<td><?= $cp['abbr'] ?></td>
					<td><?= $cp['unit'] ?></td>
					<td><?= isset($cp['venue']) ? Html::a($cp['venue']['name'], 'venues/r/'.$cp['venue']['id']) : '' ?></td>
					<td><?= isset($cp['company']) ? Html::a($cp['company']['name'], 'companies/r/'.$cp['company']['id']) : '' ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
