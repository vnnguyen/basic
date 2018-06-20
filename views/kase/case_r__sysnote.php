<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

?>
        <? if ($note['action'] == 'kase/create') { ?>
        <li class="note-list-item clearfix">
            <div class="note-content">
                <i class="fa fa-magic text-success"></i>
                <?= Html::a(Html::encode($note['user']['nickname']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                <?= Yii::t('k', 'created this case') ?>
                <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
            </div>
        </li>
        <? } // kase/create ?>

        <? if ($note['action'] == 'kase/assign') { ?>
        <li class="note-list-item clearfix">
            <div class="note-content">
                <i class="fa fa-hand-o-right text-warning"></i>
                <?= Html::a(Html::encode($note['user']['nickname']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                <?= Yii::t('k', 'assigned this case to') ?>
                <?
                if ($note['info'] == $theCase['owner_id']) {
                    $ownerName = $theCase['owner']['nickname'];
                } else {
                	$assignee = \common\models\User::find()->select(['nickname'])->where(['id'=>$note['info']])->asArray()->one();
                    $ownerName = !$assignee ? Yii::t('k', 'a consultant') : $assignee['nickname'];
                }
                echo Html::a($ownerName, '/users/r/'.$note['info']);
                ?>
                <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
            </div>
        </li>
        <? } // kase/assign ?>

        <? if ($note['action'] == 'proposal/c') { ?>
        <li class="note-list-item clearfix">
            <div class="note-content">
                <i class="fa fa-commenting-o"></i>
                <?= Html::a(Html::encode($note['user']['nickname']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                <?= Yii::t('k', 'proposed a tour program') ?>
                <? foreach ($theCase['bookings'] as $booking) { ?>
                    <? if ($booking['product']['id'] == $note['info']) { ?>
                <?= Html::a($booking['product']['title'], '/products/r/'.$booking['product']['id']) ?>
                    <? } ?>
                <? } ?>
                <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
            </div>
        </li>
        <? } // proposal/c ?>

        <? if ($note['action'] == 'kase/reopen') { ?>
        <li class="note-list-item clearfix">
            <div class="note-content">
                <i class="fa fa-unlock text-info"></i>
                <?= Html::a(Html::encode($note['user']['nickname']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                <?= Yii::t('k', 're-opened this case') ?>
                <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
            </div>
        </li>
        <? } // kase/reopen ?>


        <? if (!in_array($note['action'], ['kase/create', 'kase/assign', 'proposal/c', 'kase/reopen'])) { ?>
        <li class="note-list-item clearfix">
            <? if ($note['action'] == 'kase/close') { ?>
            <div class="note-content">
                <div class="note-heading">
                    <i class="fa fa-lock text-danger"></i>
                    <?= Html::a(Html::encode($note['user']['nickname']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                    <?= Yii::t('k', 'closed this case') ?>
                    <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                </div>
                <div class="note-body">
                    <?
                    foreach ($caseWhyClosedListAll as $_key=>$_val) {
                        $_len = strlen(' : '.$_key);
                        if (substr($note['info'], -$_len) == ' : '.$_key) {
                            echo '<strong>', $_val, '</strong><br>';
                            break;
                        }
                    }
                    ?>
                    <?= $note['info'] ?>
                </div>
            </div>
            <? } elseif ($note['action'] == 'kase/ana') { ?>
            <div class="note-avatar">
            <i class="fa fa-list-alt text-info fa-3x note-author-avatar"></i>
            </div>
            <div class="note-content">
                <h5 class="note-heading">
                    
                    <?= Html::a(Html::encode($note['user']['nickname']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                    <?= Yii::t('k', 'posted the result of an exit survey') ?>
                </h5>
                <div class="mb-1em">
                    <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                </div>
                <div class="note-body">
<?php

$parts = explode('[#QA]', $note['info']);

for ($i = 1; $i <= count($anaQuestions[$parts[0]]); $i ++) { ?>
        <div class="text-pink text-semibold"><?= $anaQuestions[$parts[0]][$i - 1] ?></div>
        <div><?= isset($parts[$i]) ? nl2br(trim($parts[$i])) : '' ?></div>
        <br>
<?php
}

?>
                </div>
            </div>

            <? } elseif ($note['action'] == 'kase/send-cpl') { ?>
            <div class="note-avatar">
                <i class="fa fa-link text-info fa-3x note-author-avatar"></i>
            </div>
            <div class="note-content">
                <h5 class="note-heading">
                    <i class="fa fa-envelope-o"></i>
                    <?= Html::a(Html::encode($note['user']['nickname']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                    sent the link to Client page to XXX
                </h5>
                <div class="mb-1em">
                    <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                </div>
                <div class="note-body"><?= nl2br($note['info']) ?></div>
            </div>
            <? } ?>
        </li>
        <? } ?>