<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$say = [
	'smile'=>'Likes',
	'frown'=>'Dislikes',
	'meh'=>'Comments',
];

include('_feedbacks_inc.php');

$this->title = 'Tour feedbacks ('.$pagination->totalCount.')';
?>
<div class="col-md-12">
	<? if (empty($theFeedbacks)) { ?>
	<p>No existing feedbacks for this tour.</p>
	<? } else { ?>
	<div class="table-responsive">
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th>Tour</th>
				<th colspan="3">Who says what</th>
				<th>Feedback</th>
				<th>Updated</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theFeedbacks as $feedback) { ?>
			<tr>
				<td><?= Html::a($feedback['tour']['op_code'], '@web/tours/feedback/'.$feedback['tour']['id'], ['title'=>$feedback['tour']['op_name']]) ?></td>
				<td><?= $feedback['who'] ?></td>
				<td class="<?= $feedback['say'] == 'frown' ? 'bg-danger' : '' ?>"><?= $say[$feedback['say']] ?></td>
				<td><?= $feedback['what'] ?></td>
				<td><?= $feedback['feedback'] ?></td>
				<td class="text-nowrap"><?= $feedback['updatedBy']['name'] ?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
	</div>

	<? if ($pagination->totalCount > $pagination->limit) { ?>
	<div class="text-center">
	<?=LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>

	<? } ?>
</div>