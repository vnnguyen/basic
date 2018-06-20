<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

include('_person_inc.php');

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
	<? if (empty($thePersons)) { ?>
	<p>No data found.</p>
	<? } else { ?>
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
				<? foreach ($thePersons as $person) { ?>
				<tr>
					<td class="text-muted text-center"><?= $person['id'] ?></td>
					<td class="text-nowrap">
						<? if ($person['gender'] == 'male') { ?><i class="fa fa-male" style="color:blue"></i><? } ?>
						<? if ($person['gender'] == 'female') { ?><i class="fa fa-female" style="color:brown"></i><? } ?>
						<? if ($person['country_code'] != '--') { ?><span class="flag-icon flag-icon-<?= $person['country_code'] ?>"></span><? } ?>
					</td>
					<td><?=Html::a($person['fname'], 'persons/r/'.$person['id'])?></td>
					<td><?=Html::a($person['lname'], 'persons/r/'.$person['id'])?>
					<?
					if (Yii::$app->user->id == 1 && $person['lname'] == '' && $person['fname'] != '') {
						$names = explode(' ', $person['fname']);
						if (count($names) == 2) {
							echo Html::a($names[0].'/'.$names[1], 'persons/d/'.$person['id'].'?action=name&option=12');
							echo ' - ';
							echo Html::a($names[1].'/'.$names[0], 'persons/d/'.$person['id'].'?action=name&option=21');
						}
					}
					?>
					</td>
					<td><?= $person->bday ?>/<?= $person->bmonth ?>/<?= $person->byear ?></td>
					<td><?= $person->email ?></td>
					<td><?= $person->phone ?></td>
					<td>
						<?
						$roles = [];
						foreach ($person->roles as $personr) {
							$roles[] = Html::a($personr->name, 'roles/r/'.$personr->id);
						}
						echo implode(', ', $roles);
						?>
					</td>
					<td>
						<?
						if ($person['cases']) {
							foreach ($person['cases'] as $case) {
								echo '<i class="text-muted fa fa-briefcase"></i> ';
								echo Html::a($case['name'], 'cases/r/'.$case['id']);
								echo '&nbsp; ';
							}
						}
						if ($person['bookings']) {
							foreach ($person['bookings'] as $booking) {
								echo '<i class="text-muted fa fa-truck text-success"></i> ';
								echo Html::a($booking['product']['op_code'], 'bookings/r/'.$booking['id'], ['class'=>'text-success', 'style'=>'font-weight:bold;']);
								echo '&nbsp; ';
							}
						}
						?>
					</td>
					<td class="muted td-n">
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>persons/u/<?=$person['id']?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>persons/d/<?=$person['id']?>"><i class="fa fa-trash-o"></i></a>
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
	<? } // if thePersons ?>
</div>
