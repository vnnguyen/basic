<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

include('_files_inc.php');

$this->title = 'Files ('.$pages->totalCount.')';
if (isset($theFolder)) {
	$this->title = 'Files in: '.$theFolder['name'].' ('.$pages->totalCount.')';
}

?>
<div class="col-md-12">
	<?= Html::beginForm(URI, 'get', ['class'=>'form-inline well well-sm']); ?>
	<?= Html::dropdownList('taxonomy_id', $getFolderId, ArrayHelper::map($theFolders, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'All folders']) ?>
	<?= Html::dropdownList('rtype', $getRtype, ['case'=>'Case', 'tour'=>'Tour', 'user'=>'User', 'venue'=>'Venue',], ['class'=>'form-control', 'prompt'=>'Related to']) ?>
	<?= Html::textInput('name', $getName, ['class'=>'form-control', 'placeholder'=>'Search file name']) ?>
	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	<?= Html::a('Reset', 'files') ?>
	<?= Html::endForm(); ?>
	<? if (empty($theFiles)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th width="40">ID</th>
					<? if (!$theFolder) { ?>
					<th width="150">Folder</th>
					<? } ?>
					<th>Name</th>
					<th>Description</th>
					<th>Related to</th>
					<th>Size</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theFiles as $file) { ?>
				<tr>
					<td class="text-muted text-center"><?= $file['id'] ?></td>
					<? if (!$theFolder) { ?>
					<td><?//= Html::a($file['folder']['name'], 'files?folder_id='.$file['folder']['id']) ?></td>
					<? } ?>
					<td><?= Html::a($file['name'], 'files/r/'.$file['id']) ?></td>
					<td><?= $file['updatedBy']['name'] ?>, <?= $file['uo'] ?></td>
					<td><?= $file['rtype'] ?>-<?= $file['rid'] ?></td>
					<td class="text-right"><?= number_format($file['size'] / 1024, 2) ?> KB</td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'files/u/'.$file['id'], ['class'=>'text-muted']) ?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', 'files/d/'.$file['id'], ['class'=>'text-muted']) ?>
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
	<? } // if empty files ?>
</div>