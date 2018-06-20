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
					<th width="100">Tour date</th>
					<th>Tour name</th>
					<th width="40">Days</th>
					<th width="40">Pax</th>
					<th>Author</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?= $li->tour_from ?></td>
					<td><?= Html::a($li->name, 'products/r/'.$li->id) ?></td>
					<td class="text-center"><?= $li->day_count ?></td>
					<td class="text-center"><?= $li->pax_count ?></td>
					<td><?= Html::a($li->createdBy->name, 'users/r/'.$li->createdBy->id) ?></td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>products/u/<?= $li->id ?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>products/d/<? $li->id ?>"><i class="fa fa-trash-o"></i></a>
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
