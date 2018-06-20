<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Campaigns';
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Campaigns', 'campaigns'],
];
include('campaigns__inc.php');

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New campaign', 'link'=>'campaigns/c', 'active'=>SEG2 == 'c'],
	],
];

?>
<div class="col-lg-12">
	<? if (empty($models)) { ?>
	<p>No campaigns found. <?=Html::a('Create the first one', 'campaigns/c')?>.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>From</th>
					<th>Until</th>
					<th>Code</th>
					<th>Name</th>
					<th>Information</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?= date('j/n/Y', strtotime($li['start_dt'])) ?></td>
					<td><?= date('j/n/Y', strtotime($li['end_dt'])) ?></td>
					<td><?= $li['code'] ?></td>
					<td><?= $li['name'] ?></td>
					<td><?= $li['info'] ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'campaigns/u/'.$li['id']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>
</div>