<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

include('_users_inc.php');

?>
<div class="col-lg-12">
	<form method="get" action="" class="form-inline well well-sm">
	<?= Html::textInput('fname', $getFname, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'First name']) ?>
	<?= Html::textInput('lname', $getLname, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Second name']) ?>
	<?= Html::dropdownList('gender', $getGender, ['all'=>'All genders', 'male'=>'Male', 'female'=>'Female'], ['class'=>'form-control']) ?>
	<?= Html::dropdownList('country', $getCountry, ArrayHelper::map($countryList, 'code', 'name_en'), ['class'=>'form-control', 'prompt'=>'All countries']) ?>
	<?= Html::textInput('email', $getEmail, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Email']) ?>
	<?= Html::submitButton(Yii::t('mn', 'Go'), ['class' => 'btn btn-primary']) ?>
	</form>
	<? if (empty($theUsers)) { ?><p>No data found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<td width="40">ID</td>
					<th width="50"></th>
					<th colspan="2">Name</th>
					<th width="">Date of birth</th>
					<th width="">Email</th>
					<th width="">Phone</th>
					<th width="">Groups</th>
					<th>Cases / Tours</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theUsers as $li) { ?>
				<tr>
					<td class="text-muted text-center"><?= $li['id'] ?></td>
					<td>
						<? if ($li['gender'] == 'male') { ?><i class="fa fa-male" style="color:blue"></i><? } ?>
						<? if ($li['gender'] == 'female') { ?><i class="fa fa-female" style="color:brown"></i><? } ?>
						<? if ($li['country_code'] != '--') { ?><img src="<?=DIR?>images/flags/16x11/<?=$li['country_code']?>.png"><? } ?>
					</td>
					<td><?=Html::a($li['fname'], 'users/r/'.$li['id'])?></td>
					<td><?=Html::a($li['lname'], 'users/r/'.$li['id'])?>
					<?
					if (Yii::$app->user->id == 1 && $li['lname'] == '' && $li['fname'] != '') {
						$names = explode(' ', $li['fname']);
						if (count($names) == 2) {
							echo Html::a($names[0].'/'.$names[1], 'users/d/'.$li['id'].'?action=name&option=12');
							echo ' - ';
							echo Html::a($names[1].'/'.$names[0], 'users/d/'.$li['id'].'?action=name&option=21');
						}
					}
					?>
					</td>
					<td><?= $li->bday ?>/<?= $li->bmonth ?>/<?= $li->byear ?></td>
					<td><?= $li->email ?></td>
					<td><?= $li->phone ?></td>
					<td>
						<?
						$roles = [];
						foreach ($li->roles as $lir) {
							$roles[] = Html::a($lir->name, 'roles/r/'.$lir->id);
						}
						echo implode(', ', $roles);
						?>
					</td>
					<td>
						<?
						if ($li['cases']) {
							foreach ($li['cases'] as $case) {
								echo '<i class="text-muted fa fa-briefcase"></i> ';
								echo Html::a($case['name'], 'cases/r/'.$case['id']);
								echo '&nbsp; ';
							}
						}
						if ($li['bookings']) {
							foreach ($li['bookings'] as $booking) {
								echo '<i class="text-muted fa fa-truck text-success"></i> ';
								echo Html::a($booking['product']['op_code'], 'bookings/r/'.$booking['id'], ['class'=>'text-success', 'style'=>'font-weight:bold;']);
								echo '&nbsp; ';
							}
						}
						?>
					</td>
					<td class="muted td-n">
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>users/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>users/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
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
			'firstPageLabel'=>'<<',
			'prevPageLabel'=>'<',
			'nextPageLabel'=>'>',
			'lastPageLabel'=>'>>',
		]) ?>
	</div>
	<? } // if pages ?>
	<? } // if theUsers ?>
</div>
