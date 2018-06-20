<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_blogposts_inc.php');
$this->title  = $theEntry['title'];

$search = '';
?>
<style type="text/css">
#blogpost_body img {max-width:100%; height:auto!important;}
</style>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<img src="/timthumb.php?w=100&h=100&src=<?= $theEntry['author']['image'] ?>" style="width:20px; height:20px; margin:0 10px 0 0; float:left;">
			<strong><?= $theEntry['author']['nickname'] ?></strong> - <?= date_format(date_timezone_set(date_create($theEntry['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y H:i')?>
			 | CHỦ ĐỀ: <?
			if ($theEntry['cats'] != 0) {
				foreach (Yii::$app->params['acc1/blog/cats'] as $iCat) {
					if ($iCat['id'] == (int)$theEntry['cats']) {
						echo Html::a($iCat['name'], '@web/blog/posts?cat='.$iCat['id']);
					}
				}
			} ?> | TAGS: <?
			if ($theEntry['tags'] != '') {
				$tags = explode(',', $theEntry['tags']);
				$htmlTags = [];
				foreach ($tags as $tag) {
					$htmlTags[] = Html::a($tag, '@web/blog/posts?tag='.trim($tag));
				}
				echo implode(', ', $htmlTags);
			} ?> | SỐ LƯỢT XEM: <?= $theEntry['hits'] ?> | LAST UPDATE <?= date_format(date_timezone_set(date_create($theEntry['updated_at']), timezone_open('Asia/Saigon')), 'j/n/Y H:i') ?>
		</div>
		<div class="panel-body">
			<div id="blogpost_body">
	<?
		// Google Docs viewer
		//$theEntry->body = str_replace(['[pdf]', '[/pdf]'], ['<iframe src="http://docs.google.com/viewer?url=', '&embedded=true" width="100%" height="780" style="width:100%; height:780px; border: none;"></iframe>'], $theEntry->body);
		//$theEntry->body = str_replace(['width="', 'height="'], ['xwidth="', 'xheight="'], $theEntry->body);
		// OK now
		echo $theEntry->body;
	?>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">About the author: <?= $theEntry['author']['nickname'] ?> (<?= $theEntry['author']['fname'] ?> <?= $theEntry->author->lname ?>)</h6>
		</div>
		<div class="panel-body">
			<img class="pull-right img-polaroid" src="<?= $theEntry->author->image ?>" style="width:120px; margin-left:1em;">
			<p><?= nl2br($theEntry['author']['profileMember']['intro']) ?></p>
			<p><?= Html::a('Xem profile', '@web/members/r/'.$theEntry['author']['id']) ?> - <?= Html::a('Xem các bài viết của '.$theEntry['author']['name'], '@web/blog/posts?author='.$theEntry['author']['id']) ?></p>	
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Comments</h6>
		</div>
		<div class="panel-body">
			<?
			if (!empty($theEntry->comments)) {
				foreach ($theEntry->comments as $comment) { ?>
			<div class="media">
				<div class="media-left">
					<a href="#"><img src="<?= '/timthumb.php?w=100&h=100&src='.$comment['updatedBy']['image'] ?>" class="img-circle" alt=""></a>
				</div>

				<div class="media-body">
					<h6 class="media-heading"><?= $comment['createdBy']['name'] ?> <span class="media-annotation dotted"><?= Yii::$app->formatter->asRelativeTime($comment['created_at']) ?></span></h6>
					<?= nl2br(Html::encode($comment['body'])) ?>
				</div>
			</div>
			<?
				}
				echo '<hr>';
			}
			?>
			<? $form = ActiveForm::begin() ?>
			<div class="media">
				<div class="media-left">
					<a href="#"><img src="<?= Yii::$app->user->identity->image ?>" class="img-circle" alt=""></a>
				</div>
				<div class="media-body">
					<?= $form->field($postComment, 'body')->textArea(['rows'=>5])->label('Ý kiến của bạn') ?>
					<?= Html::submitButton('Post ý kiến', ['class'=>'btn btn-default']) ?>
				</div>
			</div>
			<? ActiveForm::end() ?>
		</div>
	</div>
</div>
<div class="col-md-4 hidden-print">
	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">SEARCH / TÌM KIẾM BÀI VIẾT</h6></div>
		<div class="panel-body">
			<p><form class="form-inline"><input type="text" class="form-control" name="search" value="<?= Html::encode($search) ?>" autocomplete="off" placeholder="Tìm kiếm..."><button type="submit" class="btn btn-primary">Go</button></form></p>
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

	<?
	// Lanh dao, DM, cong doan, nhan su
	if (in_array(MY_ID, [1,2,3,4,22447,24229,18598, $theEntry['author_id']]) && $theEntry['status'] == 'on') {
	?>
	<div class="panel panel-default">
		<div class="panel-body">
	<form method="post" action="" class="form">
		Gửi email thông báo về bài viết này đến địa chỉ:
		<div class="row">
			<div class="col-xs-10"><input type="text" class="form-control" name="email" value="group.amica@amicatravel.com"></div>
			<div class="col-xs-2"><?= Html::submitButton('Gửi', ['class'=>'btn btn-primary btn-block']) ?></div>
		</div>
	</form>
		</div>
	</div>
	<?
	}
	?>
</div>
<script type="text/javascript">
	//$('#blogpost_body iframe').wrap('<div class="video-container"></div>');
	//$('#blogpost_body img:not([src^="https://mail.google.com"])').addClass('img-responsive').attr('style', '');
</script>
<style type="text/css">
#aboutauthor {background-color:#f8e9e9; padding:1em;}
.video-container {position: relative; padding-bottom: 56.25%; padding-top: 30px; height: 0; overflow: hidden; }
.video-container iframe, .video-container object, .video-container embed {position: absolute; top: 0; left: 0; width: 100%; height: 100%;}
</style>