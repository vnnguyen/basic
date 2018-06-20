<?
use yii\helpers\Html;

$this->title  = 'Danh sách thành viên Amica ('.count($theUsers).')';
$this->params['icon'] = 'font';
$this->params['breadcrumb'] = [
	['Users', 'users'],
];

?>
<div class="col-md-12">
	<? if (empty($theUsers)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<tr>
					<th width="20"></th>
					<th colspan="2">Họ tên</th>
					<th>NS</th>
					<th>Công việc & Văn phòng</th>
					<th>Email</th>
					<th>Mobile</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theUsers as $li) { ?>
				<tr>
					<td>
						<? if ($li->image == '') { ?>
						<img src="http://www.gravatar.com/avatar/<?= md5($li->email) ?>?d=wavatar" style="width:20px;">
						<? } else { ?>
						<img src="<?= DIR ?>timthumb.php?w=100&h=100&zc=1&src=<?= $li->image ?>" style="width:20px;">
						<? } ?>
					</td>
					<td style="border-right:none;"><?= Html::a($li->fname, 'at/users/r/'.$li->id) ?></td>
					<td style="border-left:none;"><?= Html::a($li->lname, 'at/users/r/'.$li->id) ?></td>
					<td class="text-center"><?= $li->bday ?>/<?= $li->bmonth ?></td>
					<td><?= $li->profileMember->position ?>, <?= $li->profileMember->unit ?> (<?= $li->profileMember->location ?>)</td>
					<td><?= $li->email ?></td>
					<td><?= $li->phone ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>
</div>