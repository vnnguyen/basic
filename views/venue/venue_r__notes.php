<?
use yii\helpers\Html;
use yii\helpers\Markdown;
?>
<div class="col-lg-6 col-md-7">
    <ul class="media-list">
        <? foreach ($venueNotes as $li) { ?>
        <div style="padding:15px 0; margin:15px 0 0; border-top:1px solid #eee;">
            <a class="pull-left" style="margin-right:15px;" href="<?= DIR ?>users/r/<?= $li['updatedBy']['id'] ?>"><img style="width:64px; height:64px;" class="media-object img-circle" src="<?= DIR.'timthumb.php?w=100&h=100&src='.$li['updatedBy']['image'] ?>" alt="Avatar"></a>
            <?= Html::a('<i class="fa fa-trash-o"></i>', '@web/notes/d/'.$li['id'], ['class'=>'text-muted pull-right', 'title'=>'Delete']) ?>
            <?= Html::a('<i class="fa fa-edit"></i>', '@web/notes/u/'.$li['id'], ['class'=>'text-muted pull-right', 'title'=>'Edit']) ?>
            <h4 style="font-weight:bold; margin:0;"><?= Html::a($li['title'] == '' ? '( No title )' : $li['title'], '@web/notes/r/'.$li['id']) ?></h4>
            <div><?= $li['updatedBy']['name'] ?> <em><?= $li['uo'] ?></em></div>
            <div style="margin-left:80px" class="clear clearfix">
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