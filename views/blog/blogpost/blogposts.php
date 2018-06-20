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
if (strlen($year) == 4) {
	$this->title .= ' - năm: '.$year;
}
if ($search != '') {
	$this->title .= ' - tìm kiếm: '.Html::encode($search);
}

?>
<div class="col-md-8">
	<? foreach ($blogPosts as $entry) { ?>
	<div class="panel panel-default">
		<div class="panel-body">
	<div class="row clearfix">
		<? if ($entry['image'] != '') { ?>
		<div class="col-md-4 mb-1em">
			<img src="<?= $entry->image ?>" alt="Thumb" class="img-polaroid" style="width:100%;">
		</div>
		<div class="col-md-8">
		<? } else { ?>
		<div class="col-md-12">
		<? } ?>
			<h3 style="margin-top:0">
				<? if ($entry['is_sticky'] == 'yes') { ?><i title="Bài nổi bật" class="fa fa-star text-danger"></i><? } ?>
				<?=Html::a($entry['title'], '@web/blog/posts/r/'.$entry['id'])?>
			</h3>
			<div class="pull-right text-muted">
				<i class="fa fa-fw fa-eye"></i><span><?= $entry['hits'] ?></span>
				<i class="fa fa-fw fa-comments"></i><span><?= $entry['comment_count'] ?></span>
				<!--i class="fa fa-fw fa-thumbs-up"></i><span><?= $entry['like_count'] ?></span-->
			</div>
			<p>
				<img src="<?= $entry->author->image ?>" style="width:20px; height:20px;">
				<strong><?= $entry['author']['nickname'] ?></strong>
				<?=date('j/n/Y', strtotime($entry['online_from']))?>
				<? if ($entry['status'] != 'on') { ?><span class="label label-default status-<?= $entry['status'] ?>"><?= $entry['status'] ?></span><? } ?>
			</p>
			<div class="mb-1em"><?= $entry['summary'] ?></div>
			<p>
				<?
				if ($entry['cats'] != 0) {
					echo 'CHỦ ĐỀ: '; 
					foreach (Yii::$app->params['acc1/blog/cats'] as $cat) {
						if ($cat['id'] == (int)$entry['cats']) {
							echo Html::a($cat['name'], '@web/blog/posts?cat='.$cat['id']);
						}
					}
					echo ' | ';
				}
				if ($entry['tags'] != '') {
					echo 'TAG: ';
					$tags = explode(',', $entry['tags']);
					$htmlTags = [];
					foreach ($tags as $tag) {
						$htmlTags[] = Html::a($tag, '@web/blog/posts?tag='.$tag);
					}
					echo implode(', ', $htmlTags);
					echo ' | ';
				}
				echo Html::a('Đọc tiếp &rarr;', '@web/blog/posts/r/'.$entry['id']);
				?>
			</p>
		</div>
	</div>
		</div>
	</div>
	<? } ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'pagination' => $pagination,
		'lastPageLabel' => '>>',
		'nextPageLabel' => '>',
	]) ?>
	</div>
</div>
<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">SEARCH / TÌM KIẾM BÀI VIẾT</h6></div>
		<div class="panel-body">
			<p><form class="form-inline"><input type="text" class="form-control" name="search" value="<?= Html::encode($search) ?>" autocomplete="off" placeholder="Tìm kiếm..."><button type="submit" class="btn btn-primary">Go</button></form></p>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">BÌNH LUẬN MỚI NHẤT / LATEST COMMENTS</h6></div>
		<div class="panel-body">
			<ul class="list-unstyled">
				<? foreach ($latestComments as $comment) { ?>
				<li>
					<?= substr($comment['created_at'], 0, 10) ?>: <?= Html::a($comment['createdBy']['name'], '@web/users/r/'.$comment['created_by']) ?> <em>comment trong bài</em>
					<?= Html::a($comment['blogpost']['title'], '@web/blog/posts/r/'.$comment['rid']) ?>
				</li>
				<? } ?>
			</ul>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">CHỦ ĐỀ / CATEGORIES</h6></div>
		<div class="panel-body">
			<ul>
				<? foreach (Yii::$app->params['acc1/blog/cats'] as $cat) { ?>
				<li><?= Html::a($cat['name'], '@web/blog/posts?cat='.$cat['id']) ?></li>
				<? } ?>
			</ul>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">NHÃN / TAGS</h6></div>
		<div class="panel-body">
			<?
foreach ($allTagList as $iTag) {
	echo Html::a($iTag, '@web/blog/posts?tag='.Html::encode($iTag)), ', ';
} ?>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">TÁC GIẢ / POSTS BY AUTHOR</h6></div>
		<div class="panel-body">
<? foreach ($allAuthorList as $iAuthor) {
		echo Html::a($iAuthor['name'], '@web/blog/posts?author='.$iAuthor['id']), ', ';
		} ?>
		</div>
	</div>
</div>
