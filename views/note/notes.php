<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

include('_notes_inc.php');

$this->title = 'Notes ('.$pages->totalCount.')';

?>
<div class="col-lg-12">
	<form class="form-inline well well-sm">
		Month <?= Html::dropdownList('month', $month, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'All months']) ?>
		<? if ($viewAll) { ?>
		From <?= Html::dropdownList('from', $from, $fromList, ['class'=>'form-control']) ?>
		To <?= Html::dropdownList('to', $to, $toList, ['class'=>'form-control']) ?>
		<? } else { ?>
		From <?= Html::dropdownList('from', $from, [0=>'Anybody', Yii::$app->user->id=>'Me'], ['class'=>'form-control']) ?>
		To <?= Html::dropdownList('to', $to, [0=>'Anybody', Yii::$app->user->id=>'Me'], ['class'=>'form-control']) ?>
		<? } ?>
		Via <?= Html::dropdownList('via', $via, $viaList, ['class'=>'form-control', 'prompt'=>'All']) ?>
		Title <?= Html::textInput('title', $title, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', 'notes') ?>
	</form>
	<? if (empty($theNotes)) { ?><p>No data found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Time (Hanoi)</th>
					<th>Title</th>
					<th>From / To</th>
					<th>Related to</th>
					<th>Content</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theNotes as $li) { ?>
				<tr>
					<td style="white-space:nowrap;" class="text-muted"><?= date('d-m-Y H:i', strtotime($li['uo'])) ?></td>
					<td style="white-space:nowrap;">
						<? if ($li['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
						<? if ($li['via'] == 'form') { ?><i class="fa fa-desktop"></i><? } ?>
						<?= Html::a($li['title'] == '' ? '( No title )' : $li['title'], 'notes/r/'.$li['id']) ?>
					</td>
					<td style="white-space:nowrap;">
						<?= Html::a($li['from']['name'], 'users/r/'.$li['from']['id'])?>
						<?
						if ($li['to']) {
							echo ' &rarr; ';
							$cnt = 0;
							foreach ($li['to'] as $to) {
								$cnt ++;
								if ($cnt != 1) echo ', ';
								echo Html::a($to['name'], 'users/r/'.$to['id'], ['style'=>'color:purple;']);
							}
						}
						?>
					</td>
					<td style="white-space:nowrap;"><?
					if ($li['rtype'] == 'case' && $li['relatedCase']) {
						echo '<i class="text-muted fa fa-briefcase"></i> '.Html::a($li['relatedCase']['name'], 'cases/r/'.$li['relatedCase']['id']);
					}
					if ($li['rtype'] == 'tour' && $li['relatedTour']) {
						echo '<i class="text-muted fa fa-truck"></i> '.Html::a($li['relatedTour']['code'].' - '.$li['relatedTour']['name'], 'tours/r/'.$li['relatedTour']['id']);
					}
					?></td>
					<td><div class="text-muted" style="height:20px; overflow:hidden"><?= strip_tags($li['body']) ?></div></td>
					<td class="muted td-n">
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>notes/u/<?= $li['id'] ?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>notes/d/<?= $li['id'] ?>"><i class="fa fa-trash-o"></i></a>
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
		]
	)
	?>
	</div>
	<? } ?>
	<? } ?>
</div>
