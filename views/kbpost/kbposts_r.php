<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_kbposts_inc.php');
$this->title  = $theEntry['title'];

?>
<div class="col-md-8">
	<p style="margin-bottom:20px;">
		<img src="<?= $theEntry->author->image ?>" style="width:20px; height:20px; margin:0 10px 0 0; float:left;">
		<strong><?= $theEntry->author->nickname ?></strong> - <?=date_format(date_timezone_set(date_create($theEntry['online_from']), timezone_open('Asia/Saigon')), 'd-m-Y H:i')?>
		- last update <?=date_format(date_timezone_set(date_create($theEntry['updated_at']), timezone_open('Asia/Saigon')), 'd-m-Y H:i')?>
	</p>
	<?
		// Google Docs viewer
		$theEntry->body = str_replace(['[pdf]', '[/pdf]'], ['<iframe src="http://docs.google.com/viewer?url=', '&embedded=true" width="100%" height="780" style="border: none;"></iframe>'], $theEntry->body);
		//$theEntry->body = str_replace(['width="', 'height="'], ['xwidth="', 'xheight="'], $theEntry->body);
		// OK now
		echo $theEntry->body;
	?>
	<hr>
	<div id="aboutauthor" class="mb-1em clearfix">
		<img class="pull-right img-polaroid" src="/timthumb.php?w=100&h=100&src=<?= $theEntry->author->image ?>" style="margin-left:1em;">
		<p><strong>About the author: <?= $theEntry->author->nickname ?> (<?= $theEntry->author->fname ?> <?= $theEntry->author->lname ?>)</strong></p>
		<?= nl2br($theEntry->author->profileMember->intro) ?>
	</div>
	<div id="comments" class="mb-1em">
		<? foreach ($theEntry->comments as $comment) { ?>
		<hr>
		<div class="media" id="comment-id-<?= $comment->id ?>">
			<a class="pull-left" href="#"><img class="media-object" style="width:60px; height:60px;" src="<?= $comment->createdBy->image ?>" alt="Avatar"></a>
			<div class="media-body">
				<p><strong><?= $comment->createdBy->nickname ?></strong> <?=date_format(date_timezone_set(date_create($comment->created_at), timezone_open('Asia/Saigon')), 'd-m-Y H:i')?></p>
				<?= nl2br($comment->body) ?>
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
<div class="col-md-4">
<? include('_kbposts_sb.php') ?>
</div>
