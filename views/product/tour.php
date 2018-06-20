<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_tour_inc.php');

$this->title = 'Private tours';

?>
<div class="col-lg-12">
	<? if (empty($models)) { ?><p>No tours found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Category</th>
					<th width="20">M</th>
					<th width="20">V</th>
					<th>Name</th>
					<th width="40">Days</th>
					<th width="40">Nights</th>
					<th width="80">Kms</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?= $li->id ?></td>
					<td><?= $li->id ?></td>
					<td class="text-muted"><?= $li->map == '' ? '' : '<i class="fa fa-fw fa-picture-o"></i>' ?></td>
					<td class="text-muted"><?= $li->movie == '' ? '' : '<i class="fa fa-fw fa-youtube-play"></i>' ?></td>
					<td><?= Html::a($li->name, 'products/r/'.$li->product_id) ?></td>
					<td class="text-center"><?= $li->day_count ?></td>
					<td class="text-center"><?= $li->nights ?></td>
					<td class="text-center"><?= number_format($li->kms, 0) ?></td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>products/u/<?= $li->product_id ?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" href="<?=DIR?>products/d/<? $li->product_id ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
		));?>
	</div>
	<? } // if no tours ?>
</div>
