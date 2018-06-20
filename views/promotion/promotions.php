<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Promotions';
$this->params['icon'] = 'money';
$this->params['breadcrumb'] = [
	['Promotions', 'promotions'],
];
$this->params['active'] = 'sales';
$this->params['active2'] = 'promotions';
?>
<div class="col-lg-12">
	<? if (empty($models)) { ?>
	<p>No promotions found. <?=Html::a('Create the first one', 'promotions/c')?>.</p>
	<? } else { ?>
	<div class="panel">
		<div class="table-responsive">
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Code</th>
						<th>Name</th>
						<th>Information</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($models as $li) { ?>
					<tr>
						<td><?=$li['code']?></td>
						<td><?=$li['name']?></td>
						<td><?=$li['info']?></td>
						<td>
							<?=Html::a('<i class="fa fa-edit"></i>', 'promotions/u/'.$li['id'])?>
						</td>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>		
	</div>

	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>
</div>