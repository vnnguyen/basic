<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_message_inc.php');

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
if ($theNote['rtype'] == 'venue') {
    $theVenue = Yii::$app->db->createCommand('select id, name from venues where id=:id limit 1', [':id'=>$theNote['rid']])->queryOne();
    if (!$theVenue) {
        $relName = 'Venue';
    } else {
        $relName = $theVenue['name'];
    }
    $relLink = 'venues/r/'.$theNote['rid'];
}
if ($theNote['rtype'] == 'user') {
    $relName = 'User';//$theTour['code'].' - '.$theTour['name'];
    $relLink = 'users/r/'.$theNote['rid'];
}

Yii::$app->params['body_class'] = 'bg-white';

foreach ($mentionedPeople as $person) {
    $theNote['body'] = str_replace('@[user-'.$person['id'].']', Html::img(DIR.'timthumb.php?w=100&h=100&src='.$person['image'], ['style'=>'width:20px; height:20px;']).Html::a($person['name'], 'users/r/'.$person['id'], ['style'=>'font-weight:bold;']), $theNote['body']);   
}

\app\assets\CkeditorOnlyAsset::register($this);
$this->registerJs(\app\assets\CkeditorOnlyAsset::ckeditorJs('textarea#message-body', 'basic', false));
?>
<div class="col-md-8 col-sm-10 col-xs-12">
    <? if (in_array($theNote['rtype'], ['case', 'tour', 'venue', 'user'])) { ?>
    <div class="alert alert-info">
        <i class="fa fa-fw fa-info-circle"></i>
        This note is related <?= $theNote['rtype'] ?>: <?= Html::a($relName, DIR.$relLink, ['class'=>'alert-link']) ?>
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
                    <?= \app\helpers\DateTimeHelper::convert($theNote['co'], 'j/n/Y H:i', 'UTC', 'Asia/Ho_Chi_Minh') ?>
                    <? if ($theNote['co'] != $theNote['uo']) { ?> edited<? } ?>
                </div>
                <? if ($theNote['files']) { ?>
                <div class="note-file-list">
                    <? foreach ($theNote['files'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
                    <? } ?>
                </div>
                <? } ?>
                <div class="note-body">
                    <?= str_replace(['font-size:', '<table>', '<p>&nbsp;</p>'], ['x:', '<table class="table table-condensed table-bordered">', ''], $theNote['body']) ?>
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
                    <?= Html::a($note['from']['name'], '@web/users/r/'.$note['from_id'], ['class'=>'note-author-name']) ?> : 
                    <strong style="margin-bottom:4px;"><?= Html::a($note['title'] == '' ? '( No title )' : $note['title'], '@web/notes/r/'.$note['id'], ['class'=>'note-title']) ?></strong>
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
                <div class="clearfix">
                    <p>Your reply will be sent to: <?= implode(', ', $emailList) ?></p>
                    <? $form = ActiveForm::begin() ?>
                        <!--
                        <div id="files-list"></div>
                        <p id="files-container">
                            <a id="files-browse" href="javascript:;">Upload files</a>
                            <span id="files-console" class="text-danger"></span>
                        </p>
                        -->
                        <?= $form->field($theMessage, 'body')->textArea(['rows'=>5])->label(false) ?>
                        <div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
                    <? ActiveForm::end() ?>
                </div>
            </div>
        </li>
    </ul>
</div>
<?
//$this->render('//kase/_plupload_inc.php');
//$this->render('//kase/_redactor_inc.php');