<?php
use app\models\Contact;

use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

if (!isset($mailMessageIdList)) {
    $mailMessageIdList = [];
}
if (!in_array($mail['message_id'], $mailMessageIdList)) {
    $mailMessageIdList[] = $mail['message_id'];
    $mailBody = $mail['body']['body'];
    $mailBody = str_replace(['http://www.amica-travel.com'], ['https://www.amica-travel.com'], $mailBody);

    // Search for user
    $mailSenderId = \common\models\Meta::find()
        ->select(['rid'])
        ->where(['format'=>'email', 'rtype'=>'user', 'value'=>$mail['from_email']])
        ->scalar();
    if ($mailSenderId) {
        $mailSender = Contact::find()
            ->select(['id', 'name', 'image'])
            ->where(['id'=>$mailSenderId])
            ->asArray()
            ->one();
    }

    if (isset($mailSender)) {
        $fromAvatar = '/timthumb.php?w=100&h=100&src='.($mailSender['image'] == '' ? '/assets/img/placeholder.jpg' : $mailSender['image']);
        $fromName = $mailSender['name'];
    } else {
        $fromAvatar = '/timthumb.php?w=100&h=100&src=/assets/img/placeholder.jpg';
        $fromName = $mail['from'];
    }

    $mailBody = str_replace(['<table ', '<table>'], ['<table class="table table-narrow table-bordered" ', '<table class="table table-narrow table-bordered">'], $mailBody);
?>
        <!-- MAIL BEGIN -->
<hr id="hr-mail-<?= $mail['id'] ?>">
<div class="media div-mail" id="div-mail-<?= $mail['id'] ?>">
    <?= Html::a(Html::img($fromAvatar, ['class'=>'align-self-start post-avatar mr-3 rounded-circle']), isset($mailSender) ? '/contacts/'.$mailSender['id'] : '#' ) ?>
    <div class="media-body post-content">
        <h5 class="post-header my-0 py-0">
            <i class="fa fa-envelope-o"></i>
            <?= Html::a($fromName, isset($mailSender) ? '/contacts/'.$mailSender['id'] : '#', ['class'=>'text-brown']) ?>:
            <?= Html::a($mail['subject'] == '' ? Yii::t('x', '(No subject)') : $mail['subject'], '@web/mails/'.$mail['id'], ['class'=>'post-title font-weight-bold', 'target'=>'_blank']) ?>
        </h5>
        <div id="mail-tbl-<?= $mail['id'] ?>">
            <ul class="text-muted list-unstyled pt-1">
                <!-- <li><strong>Date:</strong> <?= DateTimeHelper::convert($mail['sent_dt'], 'd-m-Y H:i O', 'UTC', Yii::$app->user->identity->timezone) ?></li> -->
                <!-- li><strong>From:</strong> <?= Html::encode($mail['from']) ?></li -->
                <li><strong>To:</strong> <?= Html::encode($mail['to']) ?></li>
                <?php if ($mail['cc'] != '') { ?>
                <li><strong>Cc:</strong> <?= Html::encode($mail['cc']) ?></li>
                <?php } ?>
            </ul>
        </div>
        <div class="post-meta mt-1">
            <span class="post-time text-muted">
                <span title="<?= Yii::$app->formatter->asRelativetime($mail['created_at']) ?>"><i class="fa fa-clock-o"></i> <?= DateTimeHelper::convert($mail['created_at'], 'j/n/Y H:i') ?></span>
                <?php if ($mail['created_at'] != $mail['updated_at'] && $mail['updated_by'] != 0) { ?><?= Yii::t('x', 'edited') ?><?php } ?>
            </span>
            <?php if ($mail['attachment_count'] > 0) { ?><span class="post-attachment-count ml-1"><i class="fa fa-paperclip"></i> <?= $mail['attachment_count'] ?></span><?php } ?>
            <span class="post-shared text-success text-uppercase ml-1 <?= strpos($mail['tags'], 'share') === false ? 'd-none' : '' ?>"><?= Yii::t('x', 'Shared') ?></span>
            <?php if (isset($theCase) && in_array(USER_ID, [1, $theCase['owner_id']])) { ?>
            <a class="text-muted dropdown-toggle ml-2" href="#" data-toggle="dropdown"><?= Yii::t('x', 'Edit/Share') ?></a>
            <div class="dropdown-menu">
                <?php if (isset($theCase) && in_array(USER_ID, [1, $theCase['owner_id']])) { ?>
                <?= Html::a(Yii::t('x', 'Share email in tour'), '#', ['class'=>'dropdown-item action-share-mail', 'data-id'=>$mail['id']]) ?>
                <?= Html::a(Yii::t('x', 'Share attachments in tour'), '#', ['class'=>'dropdown-item action-share-mail share-attachments-only', 'data-id'=>$mail['id']]) ?>
                <?= Html::a(Yii::t('x', 'Stop sharing in tour'), '#', ['class'=>'dropdown-item action-share-mail stop-sharing', 'data-id'=>$mail['id']]) ?>
                <?= Html::a(Yii::t('x', 'Edit'), '@web/mails/'.$mail['id'].'/u', ['class'=>'dropdown-item']) ?>
                <?php } ?>
                <?php if (in_array(USER_ID, [1])) { ?>
                <?= Html::a(Yii::t('x', 'Delete'), '@web/mails/'.$mail['id'].'/d', ['class'=>'dropdown-item action-delete-mail', 'data-id'=>$mail['id']]) ?>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <?php if ($mail['attachment_count'] > 0 && $mail['files'] != '') { $mail['files'] = unserialize($mail['files']); ?>
        <div class="post-attachments mt-3 pl-3">
            <?php foreach ($mail['files'] as $file) { ?>
            <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/mails/'.$mail['id'].'/f?name='.urlencode($file['name'])) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
            <?php } ?>
        </div>
        <?php } ?>
        <?php if (isset($theTour) && strpos($mail['tags'], 'share-attachments') !== false) { ?>
        <div class="post-body mt-3">
            <span class="text-muted"><?= Yii::t('x', '(Email body not displayed)') ?></span>
        </div>
        <?php } else { ?>
            <?php if ($mail['created_at'] != $mail['updated_at'] && $mail['updated_by'] != 0) { // edited mail means content OK ?>
        <div class="post-body mt-3">
            <?php } else { ?>
        <div class="post-body mt-3" style="max-height:340px; overflow-y:scroll; overflow-x:hidden; border-right:1px solid #eee;">
            <?php } ?>
            <?php
                $mailBody = str_replace(['font-size:', 'font-family:'], ['x:', 'x:'], $mailBody);

                $sep = [
                    'bearez.hoa@amicatravel.com'=>'<b>Correspondante - Amica Travel en France</b>',
                    'mai.phuong@amicatravel.com'=>'Amica Travel - Voyage sur mesure au Vietnam, au Laos, au Cambodge',
                    'phung.lien@amicatravel.com'=>'PHUNG Lien (Mme)',
                    'nguyen.ha@amicatravel.com'=>'<span>Hà NGUYEN</span>',
                    'ngoc.linh@amicatravel.com'=>'LY Ngoc Linh',
                    'ngo.hang@amicatravel.com'=>'Hang NGO',
                    'ngoc.anh@amicatravel.com'=>'Ngoc Anh NGUYEN',
                    'dinh.huyen@amicatravel.com'=>'DINH Thi Thuong Huyen',
                    'bui.ngoc@amicatravel.com'=>'Conseillère de voyage',
                    'nguyen.thao@amicatravel.com'=>'Conseillère en voyage',
                    'hoa.nhung@amicatravel.com'=>'Nhung HOA (Mlle)',
                    'doan.ha@amicatravel.com'=>'Assistante du Chef de vente',
                ];
                if (isset($sep[$mail['from_email']])) {
                    $pos = strpos($mailBody, $sep[$mail['from_email']]);
                    if (false !== $pos) {
                        $mailBody = substr($mailBody, 0, $pos - 1);
                        $mailBody = HtmlPurifier::process($mailBody);
                    }
                }
                // Not [cs]
                if (substr($mail['subject'], 0, 4) != '[cs]') {
                    echo str_ireplace(['<br><br><br>', '<br><br>', 'href="', 'src="'], ['<br>', '<br>', 'href="#', 'src="//my.amicatravel.com/assets/img/1x1.png" x="'], $mailBody);
                } else {
                    ?><span class="text-muted"><?= Yii::t('x', '(Email body not displayed)') ?></span><?php
                }
            ?>
        </div>
        <?php } ?>
    </div>
</div>
<!-- MAIL END -->

<?php 
}