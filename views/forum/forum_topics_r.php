<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

include('_forum_topics_inc.php');

$this->title = $theTopic['title'];

// List of users to notify of replies

$peopleToNotify = [];
if ($theTopic['author']['id'] != Yii::$app->user->id) {
	$peopleToNotify[$theTopic['author']['email']] = $theTopic['author']['name'];
}
foreach ($theReplies as $reply) {
	if ($reply['author']['id'] != Yii::$app->user->id) {
		$peopleToNotify[$reply['author']['email']] = $reply['author']['name'];
	}
}

$jsPeopleList = '';
foreach ($thePeople as $person) {
	$jsPeopleList .= "{key:'[".$person['name']."]', name:'".$person['fname']." ".$person['lname']."', nname:'".str_replace('.', '', strstr($person['email'], '@', true)).str_replace(['-', '_', ' '], ['', '', ''], \fURL::makeFriendly($person['fname'].$person['lname']))."', email:'".$person['email']."'},";
}
$jsPeopleList = trim($jsPeopleList, ',');

$topicTags = [];
if ($theTopic['tags'] != '') {
	$tags = explode(',', $theTopic['tags']);
	foreach ($tags as $tag) {
		$topicTags[] = Html::a(trim($tag), 'forum/topics/tags?tag='.urlencode(trim($tag)));
	}
}

?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-3">
			<?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$theTopic['author']['image'], ['class'=>'img-thumbnail hidden-sm hidden-xs']) ?>
			<p>
				<strong><?= Html::a($theTopic['author']['name'], 'users/r/'.$theTopic['author']['id']) ?></strong>
				started this topic
				<br>
				<span class="text-muted"><?= date('d-m-Y H:i', strtotime($theTopic['updated_at'])) ?> UTC</span>
			</p>
		</div>
		<div class="col-md-9">
			<p><strong>Category:</strong> <?= isset($forumCatList[$theTopic['cats']]) ? Html::a($forumCatList[$theTopic['cats']], 'forum/topics/cats/'.$theTopic['cats']) : 'No category' ?> | <strong>Tags:</strong> <?= implode(', ', $topicTags) ?></p>
			<div class="mb-1em">
			<?= Markdown::process($theTopic['body']) ?>
			</div>
		</div>
	</div><!-- .row -->
	<hr>
	<? if (!empty($theReplies)) { ?>
	<? foreach ($theReplies as $reply) { ?>
	<div class="row">
		<div class="col-md-3">
			<?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$reply['author']['image'], ['class'=>'img-thumbnail hidden-sm hidden-xs']) ?>
			<p>
				<strong><?= Html::a($reply['author']['name'], 'users/r/'.$reply['author']['id']) ?></strong>
				replied
				<br>
				<span class="text-muted"><?= date('d-m-Y H:i', strtotime($reply['updated_at'])) ?> UTC</span>
			</p>
		</div>
		<div class="col-md-9">
			<div class="mb-1em">
			<?= Markdown::process($reply['body']) ?>
			</div>
		</div>
	</div><!-- .row -->
	<hr>
	<? } // foreach replies ?>
	<? } else { ?>
	<p>No replies found. Please write your reply below.</p>
	<hr>
	<? } ?>
	<div class="row">
		<div class="col-md-3">
			<?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'img-thumbnail hidden-sm hidden-xs']) ?>
			<p>POST YOUR REPLY</p>
		</div>
		<div class="col-md-9">
			<p>Your reply will be emailed to:
			<? foreach ($peopleToNotify as $email=>$name) { ?>
			<?= $name ?>(<?= $email ?>), 
			<? } ?>
			</p>
			<? $form = ActiveForm::begin(); ?>
			<?= $form->field($theReply, 'body')->textArea(['rows'=>15])->label(false) ?>
			<div class="text-right"><?= Html::submitButton('Post your reply', ['class'=>'btn btn-primary']) ?></div>
			<? ActiveForm::end(); ?>
		</div>
	</div><!-- .row -->
</div>
<?

$js = <<<'TXT'
var names = [
	{{jsPeopleList}}
];

var at_config = {
  at: "@",
  data: names,
  search_key: 'nname',
  limit: 10,
  tpl: "<li data-value='@${key}'>${name} <small>${email}</small></li>",
  show_the_at: true
}

$('#forumpost-body').atwho(at_config);
TXT;
$js = str_replace(['{{jsPeopleList}}'], [$jsPeopleList], $js);

//$this->registerCssFile('https://ichord.github.io/At.js/dist/css/jquery.atwho.css');
//$this->registerJsFile('https://ichord.github.io/At.js/dist/js/jquery.atwho.js', ['yii\web\JqueryAsset']);
//$this->registerJs($js);