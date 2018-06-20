<?
use yii\helpers\Html;

include('_taxonomies_inc.php');

$this->title = 'Taxonomies (Groups)';

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th width="40">ID</th>
					<th>Name</th>
					<th>Alias</th>
					<th>Description</th>
					<th width="40">Terms</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theTaxonomies as $taxonomy) { ?>
				<tr>
					<td class="text-muted text-center"><?= $taxonomy['id'] ?></td>
					<td><?= Html::a($taxonomy['name'], 'taxonomies/r/'.$taxonomy['id']) ?></td>
					<td><?= $taxonomy['alias'] ?></td>
					<td><?= $taxonomy['info'] ?></td>
					<td class="text-center"><?= Html::a($taxonomy['term_count'], 'terms?taxonomy_id='.$taxonomy['id']) ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'taxonomies/u/'.$taxonomy['id'], ['class'=>'text-muted']) ?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', 'taxonomies/d/'.$taxonomy['id'], ['class'=>'text-muted']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>