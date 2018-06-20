<?
use yii\helpers\Html;

$this->title = 'Media gallery';
Yii::$app->params['page_breadcrumbs'] = [
	['Media gallery', 'gallery'],
];

?>
<div class="col-md-12">
	<h3>EVENTS</h3>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?
				$year = 0;
				foreach ($theCollections as $collection) {
					if ($collection['stype'] == 'event') {
						if ($year != substr($collection['event_date'], 0, 4)) {
							$year = substr($collection['event_date'], 0, 4); ?>
				<tr><th colspan="10" class="bg-info"><?= $year ?></th></tr>
<?
						} ?>
				<tr>
					<td><?= $collection['event_date'] ?></td>
					<td>
						<? if ($collection['external_url'] != '') { ?>
						<img src="http://is5.mzstatic.com/image/pf/us/r30/Purple3/v4/1e/f8/2f/1ef82ff2-4fac-236e-cb96-7f4836fc5022/pr_source.256x256-75.png" style="height:20px;">
						<?= Html::a($collection['title'], $collection['external_url'], ['target'=>'_blank', 'title'=>$collection['summary']]) ?>
						<? } else { ?>
						<?= Html::a($collection['title'], '@web/gallery/collections/r/'.$collection['id'], ['title'=>$collection['summary']]) ?>
						<? } ?>
					</td>
					<td><?= $collection['updated_by'] ?></td>
					<td><?= $collection['updated_at'] ?></td>
					<td><?= Html::a('Edit', '@web/gallery/collections/u/'.$collection['id']) ?></td>
				</tr><?
					}
				}
?>
			</tbody>
		</table>
	</div>

	<hr>

	<h3>TOPICS</h3>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
		</table>
	</div>
</div>