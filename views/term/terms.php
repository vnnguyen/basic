<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

include('_terms_inc.php');

$this->title = 'Terms ('.$pages->totalCount.')';
if (isset($theTaxonomy)) {
	$this->title = 'Terms in: '.$theTaxonomy['name'].' ('.$pages->totalCount.')';
}

?>
<div class="col-md-12">
	<?= Html::beginForm(URI, 'get', ['class'=>'form-inline well well-sm']); ?>
	<?= Html::dropdownList('taxonomy_id', $getTaxonomyId, ArrayHelper::map($theTaxonomies, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'All taxonomies']) ?>
	<?= Html::textInput('name', '', ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	<?= Html::a('Reset', 'terms') ?>
	<?= Html::endForm(); ?>
	<? if (empty($theTerms)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th width="40">ID</th>
					<? if (!$theTaxonomy) { ?>
					<th width="150">Taxonomy</th>
					<? } ?>
					<th>Name</th>
					<th>Alias</th>	
					<th>Description</th>
					<th width="40">Count</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theTerms as $term) { ?>
				<tr>
					<td class="text-muted text-center"><?= $term['id'] ?></td>
					<? if (!$theTaxonomy) { ?>
					<td><?= Html::a($term['taxonomy']['name'], 'terms?taxonomy_id='.$term['taxonomy']['id']) ?></td>
					<? } ?>
					<td><?= Html::a($term['name'], 'terms/r/'.$term['id']) ?></td>
					<td><?= $term['alias'] ?></td>
					<td><?= $term['info'] ?></td>
					<td class="text-center"><?= $term['rcount'] ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'terms/u/'.$term['id'], ['class'=>'text-muted']) ?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', 'terms/d/'.$term['id'], ['class'=>'text-muted']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->totalCount > $pages->pageSize) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } // if pages ?>
	<? } // if empty terms ?>
</div>