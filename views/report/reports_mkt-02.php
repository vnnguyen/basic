<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = 'Danh sách khách liên hệ không mua tour theo năm ('.$y.')';
?>
<div class="col-md-12">
	<form class="form-inline">
		<?= Html::dropdownList('y', $y, [2016=>2016, 2015=>2015, 2014=>2014, 2013=>2013], ['class'=>'form-control', 'prompt'=>'Năm']) ?>
		<?= Html::dropdownList('l', $l, ['fr'=>'Francais', 'en'=>'English'], ['class'=>'form-control', 'prompt'=>'Ngôn ngữ']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>
	<br>

	<? if (empty($thePeople)) { ?>
	<div class="alert alert-danger">Không có dữ liệu</div>
	<? } else { ?>

	<? if ($pagination->pageSize < $pagination->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]) ?>
	</div>
	<? } ?>

	<div class="panel panel-default">
		<div class="table-responsive">
			<table class="table table-condensed table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Xem</th>
						<th>Ho</th>
						<th>Ten</th>
						<th>Gioi</th>
						<th>Tuoi</th>
						<th>Email</th>
						<th>Tel</th>
						<th>QG</th>
					</tr>
				</thead>
				<tbody>
					<? $cnt = 0; foreach ($thePeople as $person) { $cnt ++;?>
					<tr>
						<td class="text-center text-muted"><?= $cnt ?></td>
						<td class="text-center"><?= Html::a('Xem', '/users/r/'.$person['id'], ['target'=>'_blank']) ?></td>
						<td><?= $person['fname'] ?></td>
						<td><?= $person['lname'] ?></td>
						<td><?= $person['gender'] ?></td>
						<td><?= $person['age'] ?></td>
						<td><?= $person['email'] ?></td>
						<td><?= $person['phone'] ?></td>
						<td><?= strtoupper($person['country_code']) ?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
	</div>

	<? if ($pagination->pageSize < $pagination->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]) ?>
	</div>
	<? } ?>

	<? } ?>
</div>
