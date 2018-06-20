<?
use yii\widgets\LinkPager;
$this->title  = 'Special lists';
$this->params['icon'] = 'list';
$this->params['breadcrumb'] = [
	['Community', 'community'],
	['Knowledge base', 'kb'],
	['Lists', 'kb/lists'],
];
$this->params['active'] = 'kb';
$this->params['active2'] = 'kblist';

?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-condensed table-hover table-striped">
			<thead>
				<tr>
					<th width="20"></th>
					<th>Title</th>
					<th>Author</th>
					<th>Updated</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($kbPosts as $li) { ?>
				<tr>
					<td><img src="<?=DIR?>upload/user-avatars/user-<?=$li['author_id']?>.jpg" style="width:20px; height:20px;"></td>
					<td>
						<? if ($li['status'] != 'on') { ?><span class="label label-default status-<?=$li['status']?>"><?=$li['status']?></span><? } ?>
						<a href="<?=DIR?>kb/lists/<?=$li['alias']?>"><?=$li['title']?></a>
					</td>
					<td><?=$li['author']['name']?></td>
					<td><?=date('d-m-Y', strtotime($li['created_at']))?></td>
					<td>
						<a title="Edit" class="muted td-n" href="<?=DIR?>kb/lists/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a title="Delete" class="muted td-n" href="<?=DIR?>kb/lists/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'pagination' => $pages,
		'lastPageLabel' => '>>',
		'nextPageLabel' => '>',
	));?>
	</div>
</div>