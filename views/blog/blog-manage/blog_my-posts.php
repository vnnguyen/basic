<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_blogposts_inc.php');

$this->title = 'Tin tức Amica Travel - các bài viết của tôi';
if ($tag != '') {
	$this->title .= ' - tag: '.Html::encode($tag);
}
if ($cat != 0) {
	foreach (Yii::$app->params['acc1/blog/cats'] as $pcat) {
		if ($pcat['id'] == $cat) {
			$this->title .= ' - chủ đề: '.Html::encode($pcat['name']);
		}
	}
}
?>
<div class="col-md-12">
	<? if (count($blogPosts) == 0) { ?>
	<p>Không có bài nào</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Title & status</th>
					<th>Publish date</th>
					<th>Category</th>
					<th>Tags</th>
					<th width="50">Views</th>
					<th width="50">Cmts</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($blogPosts as $entry) { ?>
				<tr>
					<td><?= Html::a($entry['title'], '@web/blog/posts/r/'.$entry['id']) ?><?= $entry['status'] != 'on' ? '['.$entry['status'].']' : '' ?></td>
					<td><?= date('j/n/Y', strtotime($entry['online_from'])) ?></td>
					<td><?
				if ($entry['cats'] != 0) {
					foreach (Yii::$app->params['acc1/blog/cats'] as $cat) {
						if ($cat['id'] == (int)$entry['cats']) {
							echo Html::a($cat['name'], '@web/blog/my-posts?cat='.$cat['id']);
						}
					}
				}
					?></td>
					<td><?
				if ($entry['tags'] != '') {
					$tags = explode(',', $entry['tags']);
					$htmlTags = [];
					foreach ($tags as $tag) {
						$htmlTags[] = Html::a($tag, '@web/blog/my-posts?tag='.$tag);
					}
					echo implode(', ', $htmlTags);
				}
					?></td>
					<td><?= $entry['hits'] ?></td>
					<td><?= $entry['comment_count'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>

	<? if ($pagination->totalCount == $pagination->pageSize) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'pagination' => $pagination,
		'lastPageLabel' => '>>',
		'nextPageLabel' => '>',
	]) ?>
	</div>
	<? } ?>

	<? } ?>
</div>