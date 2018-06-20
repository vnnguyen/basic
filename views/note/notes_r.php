<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_notes_inc.php');

Yii::$app->params['page_icon'] = 'sticky-note-o';

$this->title = $theNote['title'] == '' ? '( No title )' : $theNote['title'];
$this->params['breadcrumb'][] = ['View', 'notes/r/'.$theNote['id']];
$userAvatar = '/timthumb.php?w=100&h=100&src='.$theNote['from']['image'];

if ($theNote['rtype'] == 'case') {
	$theCase = $theNote['relatedCase'];
	$relName = $theCase['name'];
	$relLink = 'cases/r/'.$theCase['id'];
}
if ($theNote['rtype'] == 'tour') {
	$theTour = $theNote['relatedTour'];
	$relName = $theTour['code'].' - '.$theTour['name'];
	$relLink = 'tours/r/'.$theTour['id'];
}
if ($theNote['rtype'] == 'user') {
	$theTour = '';//$theNote['relatedTour'];
	$relName = 'NAME';//$theTour['code'].' - '.$theTour['name'];
	$relLink = 'x'; //'tours/r/'.$theTour['id'];
}

foreach ($mentionedPeople as $person) {
	$theNote['body'] = str_replace('@[user-'.$person['id'].']', Html::img(DIR.'timthumb.php?w=100&h=100&src='.$person['image'], ['style'=>'width:20px; height:20px;']).Html::a($person['name'], 'users/r/'.$person['id'], ['style'=>'font-weight:bold;']), $theNote['body']);	
}


?>
<div class="col-lg-6 col-md-8 col-sm-12">
	<? if (in_array($theNote['rtype'], ['case', 'tour'])) { ?>
	<div class="alert alert-info">
		<i class="fa fa-fw fa-info-circle"></i>
		This note is related to a <?= $theNote['rtype'] ?>: <?= Html::a($relName, DIR.$relLink, ['class'=>'alert-link']) ?>
	</div>
	<? } ?>
	<ul class="note-list">
		<li class="first note-list-item clearfix">
			<div class="note-avatar">
				<?= Html::a(Html::img($userAvatar, ['class'=>'note-author-avatar img-circle']), '@web/users/r/'.$theNote['from']['id']) ?>
			</div>
			<div class="note-content">
				<h5 class="note-heading">
					<?= Html::a($theNote['from']['name'], '@web/users/r/'.$theNote['from_id'], ['class'=>'note-author-name']) ?>
					:
					<?= Html::a($theNote['title'] == '' ? '( No title )' : $theNote['title'], '@web/notes/r/'.$theNote['id'], ['class'=>'note-title']) ?></strong>
					<?
					if ($theNote['to']) {
						echo ' <span class="text-muted">to</span> ';
						$cnt = 0;
						foreach ($theNote['to'] as $to) {
							$cnt ++;
							if ($cnt > 1) echo ', ';
							echo Html::a($to['name'], 'users/r/'.$to['id'], ['style'=>'color:purple;']);
						}
					}
					?>

				</h5>
				<div class="note-meta mb-1em">
					<?= \app\helpers\DateTimeHelper::format($theNote['co']) ?>
				</div>
				<div class="note-body">
					<?= str_replace(['font-size:', '<table>', '<p>&nbsp;</p>'], ['x:', '<table class="table table-condensed table-bordered">', ''], $theNote['body']) ?>
				</div>
				<div class="note-actions">
					<?= Html::a('Reply', '#', ['class'=>'text-muted']) ?>
					-
					<?= Html::a('Delete', '#', ['class'=>'text-muted']) ?>
				</div>
			</div>
		</li>

		<? foreach ($theNote['replies'] as $note) { ?>
		<li class="note-list-item clearfix">
			<div class="note-avatar">
				<?= Html::a(Html::img('/timthumb.php?w=100&h=100&src='.$note['updatedBy']['image'], ['class'=>'note-author-avatar img-circle']), '@web/users/r/'.$note['from']['id']) ?>
			</div>
			<div class="note-content">
				<h5 class="note-heading">
					<strong style="margin-bottom:4px;"><?= Html::a($note['title'] == '' ? '( No title )' : $note['title'], '@web/notes/r/'.$note['id'], ['class'=>'note-title']) ?></strong>
					<?= Html::a($note['from']['name'], '@web/users/r/'.$note['from_id'], ['class'=>'note-author-name']) ?>
					<?
					if ($note['to']) {
						echo ' <span class="text-muted">to</span> ';
						$cnt = 0;
						foreach ($note['to'] as $to) {
							$cnt ++;
							if ($cnt > 1) echo ', ';
							echo Html::a($to['name'], 'users/r/'.$to['id'], ['style'=>'color:purple;']);
						}
					}
					?>

				</h5>
				<div class="note-meta mb-1em">
					<?= \app\helpers\DateTimeHelper::format($note['co']) ?>
				</div>
				<div class="note-body">
					<?= str_replace(['font-size:', '<table>', '<p>&nbsp;</p>'], ['x:', '<table class="table table-condensed table-bordered">', ''], $note['body']) ?>
				</div>
			</div>
		</li>
		<? } // foreach replies ?>
		<li class="note-list-item clearfix">
			<div class="note-avatar">
				<?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'img-circle note-author-avatar']), '@web/users/r/'.Yii::$app->user->id, ['class'=>'media-left hidden-xs']) ?>
			</div>
			<div class="note-content">
			<?= $this->render('_editor.php') ?>
			</div>
		</li>
	</ul>
</div>
<?
//$this->render('//kase/_plupload_inc.php');
//$this->render('//kase/_redactor_inc.php');