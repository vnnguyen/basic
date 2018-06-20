<?
use yii\helpers\Html;
use yii\helpers\Markdown;
?>
<div class="tab-pane" id="t-notes">
    <ul class="media-list">
        <? foreach ($venueNotes as $li) { ?>
        <div style="background-color:#fcfcfc; padding:15px; margin:0 0 15px; border:1px solid #ccc;">
            <a class="pull-left" style="margin-right:15px;" href="<?= DIR ?>users/r/<?= $li['updatedBy']['id'] ?>"><img style="width:64px; height:64px;" class="media-object" src="<?= DIR.'timthumb.php?w=100&h=100&src='.$li['updatedBy']['image'] ?>" alt="Avatar"></a>
            <?= Html::a('<i class="fa fa-trash-o"></i>', '@web/notes/d/'.$li['id'], ['class'=>'text-muted pull-right', 'title'=>'Delete']) ?>
            <?= Html::a('<i class="fa fa-edit"></i>', '@web/notes/u/'.$li['id'], ['class'=>'text-muted pull-right', 'title'=>'Edit']) ?>
            <h4 style="font-weight:bold;"><?= Html::a($li['title'] == '' ? '( No title )' : $li['title'], '@web/notes/r/'.$li['id']) ?></h4>
            <div><?= $li['updatedBy']['name'] ?> <em><?= $li['uo'] ?></em></div>
            <hr>
            <div style="xmargin-left:80px">
            <? if ($li['files']) { ?>
            <div class="list list-files mb-1em">
                <? foreach ($li['files'] as $file) { ?>
                <div>+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?></div>
                <? } ?>
            </div>
            <? } ?>
            <?= $li['body']?>
            </div>
        </div>
        <? } ?>
    </ul>
</div>