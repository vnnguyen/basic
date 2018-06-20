<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
	['Manager', 'manager'],
	['Customers', 'manager/customers'],
];

$this->title = 'Customers ('.$pages->totalCount.')';

?>
<div class="col-lg-12">
	<form method="get" action="" class="well well-sm">
		<div class="row mb-1em">
			<div class="col-md-2">
				<select class="form-control" name="month">
					<option value="all">Gender</option>
				</select>
			</div>
			<div class="col-md-2">
				<select class="form-control" name="month">
					<option value="all">Age group</option>
				</select>
			</div>
			<div class="col-md-3">
				<select class="form-control" name="month">
					<option value="all">Nationality</option>
				</select>
			</div>
			<div class="col-md-2">
				<select class="form-control" name="month">
					<option value="all">Booking types</option>
					<option>Private tour</option>
					<option>GIT tour</option>
					<option>VPC tour</option>
					<option>TCG tour</option>
					<option>Amica Travel tour</option>
				</select>
			</div>
			<div class="col-md-3">
				<select class="form-control" name="found">
					<option value="all">How customer knew about us</option>
				</select>
			</div>
			<div class="col-md-2">
				<input type="text" class="form-control" name="name" value="<?= $getName ?>" placeholder="Search name">
			</div>
			<div class="col-md-1">
				<button class="btn btn-block btn-primary">Go</button>
			</div>
		</div>
	</form>
	<? if (empty($models)) { ?><p>No customers found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?= $li['fname'] ?></td>
					<td><?= $li['lname'] ?></td>
					<td><?= $li['gender'] ?></td>
					<td><?= $li['bday'] ?> / <?= $li['bmonth'] ?> / <?= $li['byear'] ?></td>
					<td><?= strtoupper($li['country_code']) ?></td>
					<td><?= $li['email'] ?></td>
					<td><?= $li['id'] ?></td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>cases/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>cases/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
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
