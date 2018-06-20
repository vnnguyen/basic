<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_blogposts_inc.php');

$this->title = 'Tin tức Amica Travel';
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
if ($author != 0 && isset($theAuthor)) {
	$this->title .= ' - tác giả: '.$theAuthor['name'];
}
?>
<div class="col-md-12">
	<div class="panel panel-default">
		<? if (count($blogPosts) == 0) { ?>
		<p>Không có bài nào</p>
		<? } else { ?>
		<div class="table-responsive">
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Publish</th>
						<th>Title & status</th>
						<th>Author</th>
						<th>Category</th>
						<th>Tags</th>
						<th width="50">Views</th>
						<th width="50">Cmts</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($blogPosts as $entry) { ?>
					<tr>
						<td class="text-center"><?= date('j/n/Y', strtotime($entry['online_from'])) ?></td>
						<td><?= Html::a($entry['title'], '@web/blog/posts/r/'.$entry['id']) ?><?= $entry['status'] != 'on' ? ' ['.$entry['status'].']' : '' ?></td>
						<td><?= Html::a($entry['author']['name'], '@web/blog/manage?author='.$entry['author_id']) ?></td>
						<td class="text-nowrap"><?
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
						<td class="text-center"><?= $entry['hits'] ?></td>
						<td class="text-center"><?= $entry['comment_count'] ?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>

		<? if ($pagination->totalCount > $pagination->pageSize) { ?>
		<div class="panel-footer">
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
		</div>
		<? } ?>
	</div>
</div>