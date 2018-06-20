<?
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

include('_companies_inc.php');

$this->title = $theCompany['name'];

$this->params['small'] = $theCompany['search'];

$jsPeopleList = '';
foreach ($thePeople as $person) {
	$jsPeopleList .= "{key:'[".$person['name']."]', name:'".$person['fname']." ".$person['lname']."', nname:'".str_replace('.', '', strstr($person['email'], '@', true)).str_replace(['-', '_', ' '], ['', '', ''], \fURL::makeFriendly($person['fname'].$person['lname']))."', email:'".$person['email']."'},";
}
$jsPeopleList = trim($jsPeopleList, ',');


// Calculate the time of notes and emails
$myTimeZone = Yii::$app->user->identity->timezone;
if (!in_array($myTimeZone, ['UTC', 'Europe/Paris', 'Asia/Ho_Chi_Minh'])) {
	$myTimeZone = 'Asia/Ho_Chi_Minh';
}

$timeTable = [];
foreach ($theNotes as $note) {
	$time = DateTimeHelper::convert($note['co'], 'Y-m-d H:i:s', 'Asia/Ho_Chi_Minh', $myTimeZone);
	$timeTable[$time] = ['object'=>'note', 'id'=>$note['id'], 'title'=>$note['title']];
}
krsort($timeTable);

// File list
$allFileList = [];
foreach ($timeTable as $time=>$item) {
	$time = substr($time, 0, 16);
	if ($item['object'] == 'note') {
		foreach ($theNotes as $note) {
			if ($note['id'] == $item['id']) {
				if ($note['files']) {
					foreach ($note['files'] as $file) {
						$allFileList[] = [
							'name'=>$file['name'],
							'link'=>'@web/files/r/'.$file['id'],
							'size'=>$file['size'],
						];
					}
				}
			}
		}
	}
}

?>
<div class="col-md-8">
	<ul class="note-list">
		<li class="first note-list-item clearfix">
			<div class="note-avatar">
				<?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'img-circle note-author-avatar']), '@web/users/r/'.Yii::$app->user->id, ['class'=>'media-left hidden-xs']) ?>
			</div>
			<div class="note-content">
			<?= $this->render('_editor.php', ['theCompany'=>$theCompany]) ?>
			</div>
		</li>
<?
		foreach ($timeTable as $time=>$item) {
			$time = substr($time, 0, 16);
			if ($item['object'] == 'note') {
				foreach ($theNotes as $note) {
					if ($note['id'] == $item['id']) {
						// BEGIN NOTE
						$userAvatar = '//secure.gravatar.com/avatar/'.md5($note['from']['id']).'?s=100&d=wavatar';
						if ($note['from']['image'] != '') {
							$userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$note['from']['image'];
						}
						//$note->from->image != '' ? DIR.'timthumb.php?src='.$note->from->image.'&w=300&h=300&zc=1' : 'http://0.gravatar.com/avatar/'.md5($li->from_id).'.jpg?s=64&d=wavatar';;

?>
		<li class="note-list-item clearfix">
			<div class="note-avatar">
			<?= Html::a(Html::img($userAvatar, ['class'=>'img-circle note-author-avatar']), '@web/users/r/'.$note['from']['id'], ['class'=>'media-left hidden-xs']) ?>
			</div>
			<?
			$title = $note['title'];
			$body = $note['body'];
			/*
			// Name mentions
			$toEmailList = [];
			foreach ($thePeople as $person) {
				$mention = '@[user-'.$person['id'].']';
				if (strpos($body, $mention) !== false) {
					$body = str_replace($mention, '@'.Html::a($person['name'], '@web/users/r/'.$person['id'], ['style'=>'font-weight:bold;']), $body);
					$toEmailList[] = $person['email'];
				}
			}
			$toEmailList = array_unique($toEmailList);
			*/
			$body = str_replace(['width:', 'height:', 'font-size:', '<table ', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<table class="table table-condensed table-bordered" ', ''], $body);
			$body = HtmlPurifier::process($body);
			?>
			<div class="note-content">
				<h5 class="note-heading">
					<? if ($note['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
					<?= Html::a($note['from']['name'], '@web/users/r/'.$note['from_id'], ['class'=>'note-author-name']) ?>
					:
					<? if (substr($note['priority'], 0, 1) == 'C') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#important</strong><? } ?>
					<? if (substr($note['priority'], -1) == '3') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#urgent</strong><? } ?>

					<?= Html::a($title, '@web/notes/r/'.$note['id'], ['class'=>'note-title']) ?>
					<?
					if ($note['to']) {
						echo ' <span class="text-muted">to</span> ';
						$cnt = 0;
						foreach ($note['to'] as $to) {
							$cnt ++;
							if ($cnt > 1) echo ', ';
							echo Html::a($to['name'], '@web/users/r/'.$to['id'], ['style'=>'color:purple;']);
						}
					}
					?>
				</h5>
				<div class="note-meta mb-1em">
					<span class="text-muted timeago" title="<?= date('Y-m-d\TH:i:s', strtotime($note['co'])) ?>+07"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
					- <?= Html::a('Edit', '@web/notes/u/'.$note['id']) ?>
					- <?= Html::a('Delete', '@web/notes/d/'.$note['id']) ?>
				</div>
				<? if ($note['files']) { ?>
				<div class="note-file-list">
					<? foreach ($note['files'] as $file) { ?>
					<div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
					<? } ?>
				</div>
				<? } ?>
				<div class="note-body">
					<?= $body ?>
				</div>
			</div>
		</li>
<?
					}
				}
				// END NOTE
			}
		} // foreach timeTable
?>
	</ul>
	<? \fCore::expose($_SESSION) ?>
	<? \fCore::expose(Yii::getAlias('@www')) ?>
</div>
<div class="col-lg-4">
	<p><strong>CONTACT INFORMATION</strong></p>
	<ul>
		<? sort($theCompany['metas']); foreach ($theCompany['metas'] as $theMeta) { ?>
		<li><?=$theMeta['k']?>: <?=$theMeta['v']?></li>
		<? } ?>
	</ul>
	<p><strong>GENERAL INFORMATION</strong></p>
	<div>
	<?=nl2br($theCompany['info'])?>
	</div>
	<p><strong>TAX INFORMATION</strong></p>
	<div>
	<?=nl2br($theCompany['tax_info'])?>
	</div>
	<p><strong>BANK INFORMATION</strong></p>
	<div>
	<?=nl2br($theCompany['bank_info'])?>
	</div>
	<p><strong><i class="fa fa-file-o"></i> ALL FILES</strong></p>
	<div>
		<? foreach ($allFileList as $file) { ?>
		<div>+ <?= Html::a($file['name'], $file['link']) ?> <em class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></em></div>
		<? } ?>
	</div>
</div>
<?
$js = <<<'TXT'
	var names = [
		{{jsPeopleList}}
	];

    var tags = ['important', 'urgent', 'rpsv'];
    var tags = $.map(tags, function(value, i) {return {key: value, name:value}});

    var at_config = {
      at: "@",
      data: names,
      search_key: 'nname',
      limit: 10,
      tpl: "<li data-value='@${key}'>${name} <small>${email}</small></li>",
      show_the_at: true
    }
    var tag_config = {
      at: '#',
      data: tags,
      tpl: '<li data-value="#${name}">${name}</li>',
      show_the_at: true
    }
	$('#to').atwho(at_config);
	$('#title').atwho(tag_config);
TXT;

$js = str_replace(['{{jsPeopleList}}'], [$jsPeopleList], $js);

$this->registerCssFile(DIR.'assets/at.js_0.4.12/css/jquery.atwho.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/at.js_0.4.12/js/jquery.caret.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/at.js_0.4.12/js/jquery.atwho.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);
