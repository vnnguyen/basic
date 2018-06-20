<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Markdown;

include('_cp_inc_old.php');

?>
<div class="col-md-12">
	<form id="formx" method="get" action="" class="form-inline well well-sm">
		Cp type <?= Html::dropdownList('type', $getType, $cpTypeList, ['class'=>'form-control', 'prompt'=>'All types']) ?>
		Company <?= Html::textInput('company', $getCompany, ['class'=>'form-control', 'autocomplete'=>'off']) ?>
		Venue <?= Html::textInput('venue', $getVenue, ['class'=>'form-control', 'autocomplete'=>'off']) ?>
		Name <?= Html::textInput('name', $getName, ['class'=>'form-control', 'autocomplete'=>'off']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>
	<? if (empty($theCpx)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Phân loại</th>
					<th>Nhà cung cấp</th>
					<th>Địa điểm sử dụng</th>
					<th>Tên chi phí</th>
					<th>Đơn vị</th>
					<th>Abbr</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theCpx as $li) { ?>
				<tr>
					<td class="text-muted"><?= $li['id'] ?></td>
					<td><?= $li['stype'] ?></td>
					<td><?= Html::a($li['company']['name'], 'companies/r/'.$li['company_id']) ?></td>
					<td><?
					if ($li['venue']) {
						echo Html::a($li['venue']['name'], 'venues/r/'.$li['venue_id']);
						echo ' ', $li['venue']['abbr'];
					}
					?>
					</td>
					<td><?= Html::a($li['name'], 'cp/r/'.$li['id']) ?></td>
					<td><?= $li['unit'] ?></td>
					<td><?= $li['abbr'] ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'cp/u/'.$li['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', 'cp/d/'.$li['id'], ['class'=>'text-muted', 'title'=>'Delete']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->totalCount > $pages->pageSize) { ?>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	));?>
	</div>
	<? } ?>
	<? } ?>
</div>
