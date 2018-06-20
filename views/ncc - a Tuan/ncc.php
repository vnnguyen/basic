<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Nha cung cap (list tu ke toan)';
$this->params['icon'] = 'home';
$this->params['breadcrumb'] = [
	['NCC', 'v2ncc'],
];

?>
<div class="col-lg-12">
	<? if (empty($models)) { ?>
	<p>No providers found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Code KT</th>
					<th>Tên</th>
					<th>Mã số thuế</th>
					<th>Địa chỉ</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?=$li['code_kt']?></td>
					<td><?=$li['ten']?></td>
					<td><?=$li['mst']?></td>
					<td><?=$li['dc']?></td>
					<td>
						<?=Html::a('<i class="fa fa-edit"></i>', 'v2ncc/u/'.$li['id'])?>
						<?=Html::a('<i class="fa fa-trash-o"></i>', 'v2ncc/u/'.$li['id'])?>
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