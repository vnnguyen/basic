<? // All companies
use yii\widgets\LinkPager;

$this->title = 'Fix missing user search';
?>
<div class="col-xs-12">
	<div class="text-center">
		<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
		));?>
	</div>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>Fname</th>
				<th>Lname</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Search</th>
			</tr>
		</thead>
		<tbody>
			<? $cnt = 0; foreach ($models as $li) { $cnt ++; ?>
			<? //$db->execute('DELETE FROM at_search WHERE id=%i LIMIT 1', $li['id']); ?>
			<tr>
				<td><?= $li['id'] ?></td>
				<td><?= $li['fname'] ?></td>
				<td><?= $li['lname'] ?></td>
				<td><?= $li['email'] ?></td>
				<td><?= $li['phone'] ?></td>
				<td><?
				if (isset($li['search'])) {
					echo $li['search']['found'];
				}
				?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
	<div class="text-center">
		<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
		));?>
	</div>
</div>
