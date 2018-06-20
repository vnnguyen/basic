<?
use yii\helpers\Html;

include('_kase_inc.php');

$this->title = 'People in this case: '.$theCase['name'];

$this->params['breadcrumb'][] = ['View', '@web/cases/r/'.$theCase['id']];
$this->params['breadcrumb'][] = ['People', '@web/cases/people/'.$theCase['id']];

?>
<div class="col-md-8">
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th>Display as</th>
				<th>First name</th>
				<th>Second name</th>
				<th>Gender</th>
				<th>Nationality</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theCase['people'] as $user) { ?>
			<tr>
				<td><?= Html::a($user['name'], '@web/users/r/'.$user['id']) ?></td>
				<td><?= $user['fname'] ?></td>
				<td><?= $user['lname'] ?></td>
				<td><?= $user['gender'] ?></td>
				<td><?= $user['country_code'] ?></td>
				<td><?= $user['email'] ?></td>
				<td><?= $user['phone'] ?></td>
				<td><?= Html::a('Remove', '@web/cases/people/'.$theCase['id'].'?action=remove&user='.$user['id']) ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
	<p><strong>Add another person to this case</strong></p>
	<form method="post" action="" class="form-inline well well-sm">
		Enter the user ID here
		<?= Html::hiddenInput('action', 'add') ?>
		<?= Html::textInput('user', null, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>
</div>
