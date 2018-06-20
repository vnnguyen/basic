<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

// BEGIN MAIL
?>
        <li class="note-list-item clearfix">
            <div class="note-avatar">
            <?
            $userAvatar = '//secure.gravatar.com/avatar/'.md5($mail['from_email']).'?s=100&d=wavatar';
            if ($mail['from_email'] == $theCase['owner']['email'] && $theCase['owner']['image'] != '') {
                $userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$theCase['owner']['image'];
            }
            ?> 
                <?= Html::a(Html::img($userAvatar, ['class'=>'img-circle note-author-avatar']), '#') ?>
            </div>
            <div class="note-content">
                <h5 class="note-heading">
                    <i class="fa fa-envelope-o"></i>
                    <?= Html::a($mail['from'], '@web/mails/r/'.$mail['id'], ['class'=>'note-author-name', 'rel'=>'external']) ?>:
                    <?= Html::a($mail['subject'] == '' ? '' : $mail['subject'], '@web/mails/r/'.$mail['id'], ['class'=>'note-title', 'rel'=>'external']) ?>
                    <small><a class="text-muted label" style="background-color:#ccc;" onclick="$('#mail-tbl-<?= $mail['id'] ?>').toggle(); return false;">&hellip;</a></small>
                </h5>
                <div class="mb-1em">
                    <span class="text-muted">
                    <?= date('j/n/Y H:i', strtotime($time)) ?>
                    <? if ($mail['created_at'] != $mail['updated_at'] && $mail['updated_by'] != 0) { ?>
                    edited
                    <? } ?>
                    </span>
                    <? if ($mail['attachment_count'] > 0) { ?>
                    - <i class="fa fa-paperclip"></i> <?= $mail['attachment_count'] ?>
                    <? } ?>

                    <? if ($mail['tags'] == 'op') { ?>
                    - <?= Html::a('Shared in tour', '@web/mails/u-op/'.$mail['id'], ['class'=>'label label-success', 'title'=>'Click to stop sharing']) ?>
                    <? } else { ?>
                    - <?= Html::a('Not shared', '@web/mails/u-op/'.$mail['id'], ['class'=>'text-muted', 'title'=>'Click to share in tour']) ?>
                    <? } ?>

                    <? if (in_array(Yii::$app->user->id, [1, 4432, 26435, $theCase['owner_id']])) { ?>
                    - <?= Html::a('Unlink', '@web/mails/unlink/'.$mail['id'], ['class'=>'text-muted', 'title'=>'Unlink this email from this case']) ?>
                    - <?= Html::a('Edit', '@web/mails/u/'.$mail['id'], ['class'=>'text-muted']) ?>
                    - <?= Html::a('Delete', '@web/mails/u/'.$mail['id'], ['class'=>'text-muted']) ?>
                    <? } ?>
                </div>
                <div id="mail-tbl-<?= $mail['id'] ?>" style="display:none;">
                    <table class="table table-condensed table-bordered bg-info">
                        <tbody>
                            <tr><td>Date</td><td><?= DateTimeHelper::convert($mail['sent_dt'], 'd-m-Y H:i O', 'UTC', Yii::$app->user->identity->timezone) ?></td></tr>
                            <tr><td>From</td><td><?= Html::encode($mail['from']) ?></td></tr>
                            <tr><td>To</td><td><?= Html::encode($mail['to']) ?></td></tr>
                            <? if ($mail['cc'] != '') { ?>
                            <tr><td>Cc</td><td><?= Html::encode($mail['cc']) ?></td></tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
                <? if ($mail['attachment_count'] > 0 && $mail['files'] != '') { $mail['files'] = @unserialize($mail['files']); ?>
                <div class="note-file-list">
                    <? foreach ($mail['files'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/mails/f/'.$mail['id'].'?name='.$file['name']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                    <? } ?>
                </div>
                <? } ?>
                <div class="note-body pre-scrollable">
                    <?
                        $sep = [
                            'anh.tho@amicatravel.com'=>'Anh Tho LUU',
                            'bearez.hoa@amicatravel.com'=>'<b>Correspondante - Amica Travel en France</b>',
                            'mai.phuong@amicatravel.com'=>'Amica Travel - Voyage sur mesure au Vietnam, au Laos, au Cambodge',
                            'phung.lien@amicatravel.com'=>'PHUNG Lien (Mme)',
                            'nguyen.ha@amicatravel.com'=>'<span>Hà NGUYEN</span>',
                            'ngoc.linh@amicatravel.com'=>'LY Ngoc Linh',
                            'ngo.hang@amicatravel.com'=>'Hang NGO',
                            'ngoc.anh@amicatravel.com'=>'Ngoc Anh NGUYEN',
                            'dinh.huyen@amicatravel.com'=>'DINH Thi Thuong Huyen',
                            'bui.ngoc@amicatravel.com'=>'Conseillère de voyage',
                            'nguyen.phuc@amicatravel.com'=>'Phuc NGUYEN',
                            'nguyen.thao@amicatravel.com'=>'Conseillère en voyage',
                            'hoa.nhung@amicatravel.com'=>'Nhung HOA (Mlle)',
                            'doan.ha@amicatravel.com'=>'Assistante du Chef de vente',
                            'xuan.nhi@amicatravel.com'=>'Xuan Nhi TRAN',
                            'le.giang@amicatravel.com'=>'NGUYEN Le Giang',
                            'nytennhu@amicatravel.com'=>'Thi Yen Nhu NGUYEN, Mlle',
                            'etienne.v@amicatravel.com'=>'Etienne Vallognes',
                        ];
                        if (isset($sep[$mail['from_email']])) {
                            $pos = strpos($mail['body'], $sep[$mail['from_email']]);
                            if (false !== $pos) {
                                $mail['body'] = substr($mail['body'], 0, $pos - 1);
                                $mail['body'] = HtmlPurifier::process($mail['body']);
                            }
                        }
                        echo str_ireplace(['<br><br><br>', '<br><br>', 'href="', 'src="'], ['<br>', '<br>', 'href="#', 'src="//my.amicatravel.com/assets/img/1x1.png" x="'], $mail['body']);
                    ?>
                </div>
            </div>
        </li>
<?
// END MAIL