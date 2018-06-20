<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_kbposts_inc.php');
$this->title  = 'Knowledge base - all posts';

if ($tag != '') {
	$this->title .= ' - tag: '.Html::encode($tag);
}
if ($cat != 0) {
	foreach (Yii::$app->params['amica/kb/cats'] as $pcat) {
		if ($pcat['id'] == $cat) {
			$this->title .= ' - chủ đề: '.Html::encode($pcat['name']);
		}
	}
}
if ($author != 0 && isset($theAuthor)) {
	$this->title .= ' - tác giả: '.$theAuthor['name'];
}

?>
<div class="col-md-8">
	<div class="table-responsive">
		<table class="table table-condensed table-hover table-bordered">
			<thead>
				<tr>
					<th width="20"></th>
					<th>Title</th>
					<th>Author</th>
					<th>Updated</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($kbPosts as $entry) { ?>
				<tr>
					<td><img src="<?= $entry['author']['image'] ?>" style="width:20px; height:20px;"></td>
					<td>
						<? if ($entry['status'] != 'on') { ?><span class="label label-default status-<?=$entry['status']?>"><?=$entry['status']?></span><? } ?>
						<a href="<?=DIR?>kb/posts/r/<?=$entry['id']?>"><?=$entry['title']?></a>
					</td>
					<td><?=$entry['author']['name']?></td>
					<td><?=date('d-m-Y', strtotime($entry['online_from']))?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'pagination' => $pagination,
		'lastPageLabel' => '>>',
		'nextPageLabel' => '>',
	));?>
	</div>
</div>
<div class="col-md-4">
<? include('_kbposts_sb.php') ?>
</div>
