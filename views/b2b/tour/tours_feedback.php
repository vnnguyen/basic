<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

$this->title = 'Tour feedback: '.$theTour['op_code'];

$this->params['breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
	[$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
	['Feedback', URI],
];

$say = [
	'smile'=>'Likes',
	'frown'=>'Dislikes',
	'meh'=>'Comments on',
];
?>
<div class="col-md-12">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theFeedback, 'who') ?></div>
		<div class="col-md-2"><?= $form->field($theFeedback, 'say')->dropdownList($say) ?></div>
		<div class="col-md-6"><?= $form->field($theFeedback, 'what') ?></div>
	</div>
	<?= $form->field($theFeedback, 'feedback') ?>
	<div class="text-right"><?= Html::submitButton('Save feedback', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>

	<? if (empty($theFeedbacks)) { ?>
	<p>No existing feedbacks for this tour.</p>
	<? } else { ?>
	<hr>
	<p><strong>EXISTING FEEDBACKS FOR THIS TOUR</strong></p>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th>Who</th>
				<th>Say</th>
				<th>What</th>
				<th>Feedback</th>
				<th>Updated</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theFeedbacks as $feedback) { ?>
			<tr>
				<td><?= $feedback['who'] ?></td>
				<td><?= $say[$feedback['say']] ?></td>
				<td><?= $feedback['what'] ?></td>
				<td><?= $feedback['feedback'] ?></td>
				<td class="text-nowrap"><?
				if ($feedback['created_by'] == MY_ID) {
					echo 'Me - ', Html::a('Delete', DIR.URI.'?action=remove&feedback='.$feedback['id']);
				} else {
					echo $feedback['createdBy']['name'];
				}
				?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
	<? } ?>
</div>