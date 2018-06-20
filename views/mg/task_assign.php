<div style="width:600px;">
    <? if ($theTask['is_priority'] == 'yes') { ?>
    <p style="color:red">*Priority task!</p>
    <? } ?>
    <p>Task to do: <?= $theTask['description'] ?></p>
    <p>Assigned by: <?= Yii::$app->user->identity->nickname ?></p>
    <p>Related to: <a href="https://my.amicatravel.com/<?= $theTask['rtype'] ?>s/r/<?= $theTask['rid'] ?>"><?= $theTask['rtype'] ?>/<?= $theTask['rid'] ?></a></p>
    <p>Due date: <?= date('j/n/Y H:i', strtotime($theTask['due_dt'])) ?> (<?= Yii::$app->formatter->asRelativetime($theTask['due_dt']) ?>)</p>
    <p>Link to details: <a href="https://my.amicatravel.com/tasks">https://my.amicatravel.com/tasks</a></p>
    <p style="color:#ccc">--<br>DON'T REPLY TO THIS EMAIL | ĐỪNG VIẾT TRẢ LỜI EMAIL NÀY</p>
</div>
