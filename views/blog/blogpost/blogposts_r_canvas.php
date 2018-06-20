<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_blogposts_inc.php');
$this->title  = $model['title'];

?>
<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="row">
				<div class="col-md-8 bottommargin">
					<div class="entry-title"><h2><?= $model['title'] ?></h2></div>
					<ul class="entry-meta clearfix">
						<li><i class="icon-calendar3"></i> 10th July 2014</li>
						<li><a href="#"><i class="icon-user"></i> admin</a></li>
						<li><i class="icon-folder-open"></i> <a href="#">General</a>, <a href="#">Media</a></li>
						<li><a href="#"><i class="icon-comments"></i> 43 Comments</a></li>
						<li><a href="#"><i class="icon-camera-retro"></i></a></li>
					</ul>

	<p style="margin-bottom:20px;">
		<img src="/timthumb.php?w=100&h=100&src=<?= $model->author->image ?>" style="width:20px; height:20px; margin:0 10px 0 0; float:left;">
		<strong><?= $model->author->nickname ?></strong> - <?= date_format(date_timezone_set(date_create($model['created_at']), timezone_open('Asia/Saigon')), 'd-m-Y H:i')?>
		- last update <?=date_format(date_timezone_set(date_create($model['updated_at']), timezone_open('Asia/Saigon')), 'd-m-Y H:i')?>
	</p>
	<div id="blogpost_body">
	<?
		// Google Docs viewer
		//$model->body = str_replace(['[pdf]', '[/pdf]'], ['<iframe src="http://docs.google.com/viewer?url=', '&embedded=true" width="100%" height="780" style="width:100%; height:780px; border: none;"></iframe>'], $model->body);
		//$model->body = str_replace(['width="', 'height="'], ['xwidth="', 'xheight="'], $model->body);
		// OK now
		echo $model->body;
	?>
	</div>
	<hr>
	<div id="aboutauthor" class="mb-1em clearfix">
		<img class="pull-right img-polaroid" src="<?= $model->author->image ?>" style="width:120px; margin-left:1em;">
		<p><strong>About the author: <?= $model->author->nickname ?> (<?= $model['author']['fname'] ?> <?= $model->author->lname ?>)</strong></p>
		<?= nl2br($model['author']['profileMember']['intro']) ?>
	</div>
	<div id="comments" class="mb-1em">
		<? foreach ($model->comments as $li) { ?>
		<hr>
		<div class="media" id="comment-id-<?= $li->id ?>">
			<a class="pull-left" href="#"><img class="media-object" style="width:60px; height:60px;" src="<?= $li->createdBy->image ?>" alt="Avatar"></a>
			<div class="media-body">
				<p><strong><?= $li->createdBy->nickname ?></strong> <?=date_format(date_timezone_set(date_create($li->created_at), timezone_open('Asia/Saigon')), 'd-m-Y H:i')?></p>
				<?= nl2br($li->body) ?>
			</div>
		</div>
		<? } ?>
	</div>
	<? $form = ActiveForm::begin() ?>
	<div id="comment" class="mb-1em">
		<hr>
		<div class="media">
			<a class="pull-left" href="#"><img class="media-object" style="width:60px; height:60px;" src="<?= Yii::$app->user->identity->image ?>" alt="Avatar"></a>
			<div class="media-body">
				<?= $form->field($postComment, 'body')->textArea(['rows'=>5, 'class'=>'form-control']) ?>
				<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Post comment'), ['class' => 'btn btn-primary']); ?></div>
			</div>
		</div>
	</div>
	<? ActiveForm::end() ?>

				</div>
				<div class="col-md-4 bottommargin">
	<p><strong>MOST COMMENTED POSTS</strong></p>
	<p><strong>MOST POPULAR POSTS</strong></p>
	<p><strong>TOP RATED POSTS</strong></p>
				</div>
			</div>
		</div>
	</div>
</section>

