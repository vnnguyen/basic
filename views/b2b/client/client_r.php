<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use app\helpers\DateTimeHelper;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;


include('_client_inc.php');

Yii::$app->params['page_icon'] = 'slicon-paper-plane';
Yii::$app->params['page_title'] = $theClient['name'];

// Sort tours
$tourList = [];
foreach ($theClient['cases'] as $case) {
    foreach ($case['bookings'] as $booking) {
        $tour = $booking['product'];
        $tourList[$tour['day_from']] = $tour;
    }
}
krsort($tourList);

$infoMetaNames = ['info_type_of_cooperation', 'info_client_service', 'info_tour_operation',
        'info_payment_conditions', 'info_bank_accounts', 'info_urgent_contact', 'info_debt'];
foreach ($infoMetaNames as $metaName) {
    $clientMeta[$metaName] = '';
}
foreach ($theClient['metas'] as $meta) {
    if (in_array($meta['name'], $infoMetaNames)) {
        $clientMeta[$meta['name']] = $meta['value'];
    }
}

$theClient['products'] = \app\models\Product::find()
    ->select(['id', 'updated_by', 'title', 'op_status', 'op_code', 'op_name', 'op_finish', 'day_from', 'client_series'])
    ->with([
        'updatedBy'=>function($q){
            return $q->select(['id', 'name']);
        },
    ])
    ->where(['client_id'=>$theClient['id']])
    ->orderBy('client_series, day_from DESC')
    ->asArray()
    ->all();

?>
<div class="col-md-8">
    <div class="card">
        <div class="card-header bg-light pb-0 pt-2">
            <ul class="nav nav-tabs nav-tabs-highlight card-header-tabs" id="client-tabs">
                <li class="nav-item">
                    <a href="#client-tab-content-overview" class="nav-link active" data-toggle="tab">
                        <?= Yii::t('x', 'Overview') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#client-tab-content-discussion" class="nav-link" data-toggle="tab">
                        <?= Yii::t('x', 'Discussion') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#client-tab-content-products" class="nav-link" data-toggle="tab">
                        <?= Yii::t('x', 'Products') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#client-tab-content-cases" class="nav-link" data-toggle="tab">
                        <?= Yii::t('x', 'Files') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#client-tab-content-tours" class="nav-link" data-toggle="tab">
                        <?= Yii::t('x', 'Tours') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane active card-body" id="client-tab-content-overview">
                <?php if ($theClient['image'] != '') { ?><img src="<?= $theClient['image'] ?>" style="max-width:400px; max-height:400px" class="pull-right ml-2 mb-2" alt="<?= Yii::t('x', 'Logo') ?>"><?php } ?>

                <div><?= $theClient['body'] ?></div>

                <?php if ($clientMeta['info_debt'] != '') { ?>
                <div class="mb-20">
                    <h4><strong><?= Yii::t('x', 'Note about outstanding balance') ?></strong></h4>
                    <div class="text-danger"><?= nl2br($clientMeta['info_debt']) ?></div>
                </div>
                <?php } ?>

                <div class="mb-20">
                    <h4><strong><?= Yii::t('x', 'Note about sales') ?></strong></h4>
                    <div><?= $clientMeta['info_type_of_cooperation'] ?></div>
                </div>

                <div class="mb-20">
                    <h4><strong><?= Yii::t('x', 'Note about client services') ?></strong></h4>
                    <div><?= $clientMeta['info_client_service'] ?></div>
                </div>

                <div class="mb-20">
                    <h4><strong><?= Yii::t('x', 'Note about tour operation') ?></strong></h4>
                    <div><?= $clientMeta['info_tour_operation'] ?></div>
                </div>

                <div class="mb-20">
                    <h4><strong><?= Yii::t('x', 'Conditions of payment') ?></strong></h4>
                    <div><?= $clientMeta['info_payment_conditions'] ?></div>
                </div>

                <div class="mb-20">
                    <h4><strong><?= Yii::t('x', 'Bank accounts') ?></strong></h4>
                    <div><?= $clientMeta['info_bank_accounts'] ?></div>
                </div>

                <div class="mb-20">
                    <h4><strong><?= Yii::t('x', 'Urgent contact') ?></strong></h4>
                    <div><?= $clientMeta['info_urgent_contact'] ?></div>
                </div>

                <h4><strong><?= Yii::t('x', 'Note') ?></strong></h4>
                <div><?= $theClient['note'] ?></div>                
            </div>

            <div class="tab-pane card-body" id="client-tab-content-discussion">
<?php
$venuePosts = \app\models\Post::find()
    ->where(['rtype'=>'company', 'rid'=>$theClient['id']])
    ->andWhere('rid!=0')
    ->with([
        'from',
        'to',
        'replies',
        'attachments',
    ])
    ->orderBy('created_dt DESC')
    ->asArray()
    ->all();

?>

                <ul class="note-list">
                    <li class="first note-list-item clearfix">
                        <div class="note-avatar"><?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'note-author-avatar rounded-circle']), '@web/contacts/'.USER_ID) ?></div>
                        <div class="note-content">
                            <?= $this->render('_editor_new.php', ['theClient'=>$theClient]) ?>
                        </div>
                    </li>


<?php

foreach ($venuePosts as $note) {
    $time = DateTimeHelper::convert($note['created_dt'], 'j.n.Y H:i', 'UTC', Yii::$app->user->identity->timezone);
// BEGIN NOTE
$userAvatar = '//secure.gravatar.com/avatar/'.md5($note['from']['id']).'?s=100&d=wavatar';
if ($note['from']['image'] != '') {
    $userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$note['from']['image'];
}
//$note->from->image != '' ? DIR.'timthumb.php?src='.$note->from->image.'&w=300&h=300&zc=1' : 'http://0.gravatar.com/avatar/'.md5($li->from_id).'.jpg?s=64&d=wavatar';;

?>
        <li class="note-list-item clearfix">
            <a name="anchor-note-<?= $note['id'] ?>"></a>
            <div class="note-avatar">
            <?= Html::a(Html::img($userAvatar, ['class'=>'rounded-circle note-author-avatar']), '@web/contacts/'.$note['from']['id']) ?>
            </div>
            <?php
            if ($note['n_id'] != 0) {
                $title = Yii::t('x', 'replied');
            } else {
                $title = $note['title'] == '' ? Yii::t('x', '(No title)') : $note['title'];
            }
            $body = $note['body'];
            /*
            // Name mentions
            $toEmailList = [];
            foreach ($thePeople as $person) {
                $mention = '@[user-'.$person['id'].']';
                if (strpos($body, $mention) !== false) {
                    $body = str_replace($mention, '@'.Html::a($person['name'], '@web/contacts/'.$person['id'], ['style'=>'font-weight:bold;']), $body);
                    $toEmailList[] = $person['email'];
                }
            }
            $toEmailList = array_unique($toEmailList);
            */
            $body = str_replace(['<a href="/mentions/', '?mention=user">@'], ['<span class="text-muted">@</span><a class="text-pink font-weight-bold" href="/mentions/', '">'], $body);
            $body = str_replace(['width:', 'height:', 'font-size:', '<table ', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<table class="table table-narrow table-bordered" ', ''], $body);
            $body = @HtmlPurifier::process($body);
            ?>
            <div class="note-content">
                <h5 class="note-heading">
                    <?php if ($note['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><?php } ?>
                    <?php if ($note['from_id'] == 36386) { echo Html::a('Violette (Pacific Voyages)', '/contacts/r/36386', ['class'=>'note-author-name']), ': '; } else { ?>
                    <?= Html::a($note['from']['nickname'], '@web/contacts/'.$note['from_id'], ['class'=>'note-author-name']) ?>: 
                    <?php } ?>
                    <?php if (substr($note['priority'], 0, 1) == 'C') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#important</strong><?php } ?>
                    <?php if (substr($note['priority'], -1) == '3') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#urgent</strong><?php } ?>

                    <?= Html::a($title, '@web/posts/'.$note['id'], ['class'=>'note-title']) ?>
                    <?
                    if ($note['to']) {
                        echo ' <i class="fa fa-caret-right text-muted"></i> ';
                        $cnt = 0;
                        foreach ($note['to'] as $to) {
                            $cnt ++;
                            if ($cnt > 1) echo ', ';
                            echo Html::a($to['nickname'], '@web/contacts/'.$to['id'], ['class'=>'note-recipient-name']);
                        }
                    }
                    ?>
                </h5>
                <div class="note-meta mb-1em">
                    <span class="text-muted timeago" title="<?= date('Y-m-d\TH:i:s', strtotime($note['created_dt'])) ?>+07"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                    - <?= Html::a(Yii::t('x', 'Edit'), '@web/posts/'.$note['id'].'/u') ?>
                    - <?= Html::a(Yii::t('x', 'Delete'), '@web/posts/'.$note['id'].'/d') ?>
                </div>
                <?php if ($note['attachments']) { ?>
                <div class="note-file-list">
                    <?php foreach ($note['attachments'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/attachments/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
                    <?php } ?>
                </div>
                <?php } ?>
                <div class="note-body">
                    <?= $body ?>
                </div>
                <?php
                $replies = [];
                foreach ($note['replies'] as $reply) {
                    if ($reply['n_id'] == $note['id']) {
                        $replies[] = $reply;
                    }
                }
                $replies = array_reverse($replies);

                foreach ($replies as $reply) {
                ?>
                <div class="mt-10">
                    <?= Html::img('/timthumb.php?zc=1&w=20&h=20&src='.$reply['from']['image'], ['class'=>'img-circle']) ?>
                    <a href="#anchor-note-<?= $reply['id'] ?>">
                        <?= $reply['from']['nickname'] ?> <?= Yii::t('op', 'replied') ?> <?= Yii::$app->formatter->asRelativetime($reply['co']) ?>
                    </a>
                </div>
                <?
                }
                ?>
            </div>
        </li>
<?php
                // END NOTE
}
?>  
                </ul>

            </div>

            <div class="table-responsive tab-pane" id="client-tab-content-products">
                <table class="table table-narrow">
                    <thead>
                        <tr>
                            <th><?= Yii::t('x', 'Name of program') ?></th>
                            <th><?= Yii::t('x', 'Date of dep.') ?></th>
                            <th><?= Yii::t('x', 'Oparation') ?></th>
                            <th><?= Yii::t('x', 'Updated by') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // All serries
                        $allSeries = \common\models\TourSeries::find()->where(['b2b_client_id'=>$theClient['id']])->indexBy('series_name')->asArray()->all();
                        $seriesName = 'Unknown';
                        foreach ($theClient['products'] as $program) {
                            if ($seriesName != $program['client_series']) {
                                $seriesName = $program['client_series'];
                                $seriesNameLabel = $program['client_series'] == '' ? Yii::t('x', 'Non-series programs') : Yii::t('x', 'Series').': '.$program['client_series']; ?>
                        <tr>
                            <th colspan="5" class="alpha-indigo">
                                <?= $seriesNameLabel ?>
                            </th>
                        </tr><?php
                                if (isset($allSeries[$seriesName]) && $allSeries[$seriesName]['description'] != '') { ?>
                        <tr>
                            <td colspan="5" class="bg-light"><?= nl2br($allSeries[$seriesName]['description']) ?></td>
                        </tr>
                        <?php
                                }
                            }
                            ?>
                        <tr>
                            <td><?= Html::a($program['title'], '/b2b/programs/r/'.$program['id']) ?></td>
                            <td class="text-nowrap"><?= date('j/n/Y', strtotime($program['day_from'])) ?></td>
                            <td>
                                <?php if ($program['op_status'] == 'op') { ?>
                                <span class="text-success" title="<?= $program['op_name'] ?>"><?= Html::a($program['op_code'], '/products/op/'.$program['id']) ?></span>
                                <?php } else { ?>
                                <span class="text-muted"><?= Yii::t('x', 'No') ?></span>
                                <?php } ?>
                            </td>
                            <td><?= $program['updatedBy']['name'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>                
            </div>

            <div class="table-responsive tab-pane" id="client-tab-content-cases">
                <table class="table table-narrow">
                    <thead>
                        <tr>
                            <th>Created</th>
                            <th>Case name</th>
                            <th>Status</th>
                            <th>Owner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($theClient['cases'] as $case) { ?>
                        <tr>
                            <td class="text-center"><?= date('j/n/Y', strtotime($case['created_at'])) ?></td>
                            <td>
                                <?php if (in_array($case['is_priority'], [1,2,3,4])) { ?><?= str_repeat('<i class="fa fa-caret-right text-orange-600"></i>', $case['is_priority']) ?><?php } ?>
                                <?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?>
                            </td>
                            <td>
                                <?= $case['deal_status'] == 'won' ? '<i title="'.Yii::t('x', 'Won').'" class="fa fa-dollar text-success"></i>' : '' ?>
                                <?= $case['deal_status'] == 'lost' ? '<i title="'.Yii::t('x', 'Lost').'" class="fa fa-dollar text-danger"></i>' : '' ?>

                                <?= $case['status'] == 'closed' ? '<i title="'.Yii::t('x', 'Closed').'" class="fa fa-lock text-muted"></i>' : '' ?>
                                <?= $case['status'] == 'onhold' ? '<i title="'.Yii::t('x', 'Onhold').'" class="fa fa-clock-o text-warning"></i>' : '' ?>
                            </td>
                            <td class="text-nowrap"><?= $case['owner']['name'] ?></td>
                        </tr>
                        <?php
                        if (!empty($case['bookings'])) {
                            echo '<tr><td class="text-right">TOURS:</td><td colspan="3" style="background-color:#f3f3f3">';
                            foreach ($case['bookings'] as $booking) {
                                echo '<div>', Html::a($booking['product']['op_code'], '@web/products/op/'.$booking['product']['id']);
                                echo ' - ', $booking['product']['pax'], 'p ', $booking['product']['day_count'], 'd ', date('j/n', strtotime($booking['product']['day_from']));
                                if ($booking['product']['op_finish'] == 'canceled') {
                                    echo ' <small class="text-danger">(CXL)</small>';
                                }
                                echo '</div>';
                            }
                            echo '</td></tr>';
                        }
                        ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive tab-pane" id="client-tab-content-tours">
                <table class="table table-narrow">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th class="text-center"><?= Yii::t('x', 'Arrival') ?></th>
                            <th><?= Yii::t('x', 'Client Ref.') ?></th>
                            <th><?= Yii::t('x', 'Amica/Secret Indochina Ref.') ?></th>
                            <th><?= Yii::t('x', 'Days') ?></th>
                            <th><?= Yii::t('x', 'Pax') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $year = 0;
                        $cnt = 0;
                        foreach ($tourList as $date=>$tour) {
                            $cnt ++;
                            if (substr($date, 0, 4) != $year) {
                                $year = substr($date, 0, 4);
                        ?>
                        <tr>
                            <th colspan="6"><?= $year ?></th>
                        </tr>
                        <?php
                            }
                        ?>
                        <tr>
                            <td class="text-center text-muted"><?= $cnt ?></td>
                            <td class="text-center <?= strtotime($date) > strtotime(NOW) ? 'font-weight-bold' : '' ?>"><?= date('j/n/Y', strtotime($date)) ?></td>
                            <td><?= Html::a($tour['client_ref'] == '' ? '(None)' : $tour['client_ref'], '@web/products/ref/'.$tour['id']) ?></td>
                            <td><?= $tour['op_finish'] == 'canceled' ? '<span class="text-danger">(CXL)</span>' : '' ?> <?= Html::a($tour['op_code'], '@web/products/op/'.$tour['id']) ?> <?= $tour['op_name'] ?></td>
                            <td class="text-center"><?= $tour['day_count'] ?></td>
                            <td class="text-center"><?= $tour['pax'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card mb-3">
        <div class="card-header bg-white">
            <h6 class="card-title"><?= Yii::t('x', 'Information') ?></h6>
        </div>
        <table class="table table-narrow">
            <tbody>
                <tr>
                    <th><?= Yii::t('x', 'Account owner') ?></th>
                    <td><?= $theClient['owner']['name'] ?></td>
                </tr>
                </tr>
                <tr>
                    <th><?= Yii::t('x', 'Login') ?></th>
                    <td><?= $theClient['login'] != '' ? $theClient['login'] : '('.Yii::t('x', 'None').')' ?> - <?= Html::a(Yii::t('x', 'Edit'), '/b2b/clients/login/'.$theClient['id']) ?></td>
                </tr>
                <?php foreach ($theClient['metas'] as $meta) { if (!in_array($meta['name'], $infoMetaNames)) { ?>
                <tr>
                    <th><?= Yii::t('x', ucfirst($meta['name'])) ?></th>
                    <td><?= $meta['value'] ?>
                        <?php if ($meta['note'] != '') { ?> <span class="text-muted text-italic"><?= $meta['note'] ?></span><?php } ?>
                    </td>
                </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white">
            <h6 class="card-title"><?= Yii::t('x', 'Contacts') ?></h6>
        </div>
        <table class="table table-narrow">
            <tbody>
                <?php if (empty($theClient['contacts'])) { ?>
                <tr><td  colspan="2"><?= Yii::t('x', 'No contacts found.') ?></td></tr>
                <?php } ?>
                <?php foreach ($theClient['contacts'] as $person) { ?>
                <tr>
                    <td width="60" class="no-padding-right"><img src="<?= $person['image'] == '' ? '/assets/img/placeholder.jpg' : '/timthumb.php?w=100&h=100&src='.$person['image'] ?>" class="img img-responsive rounded-circle img-lg" style="width:60px;"></td>
                    <td>
                        <?php if ($person['gender'] != '') { ?><i class="fa fa-<?= $person['gender'] ?>"></i><?php } ?>
                        <span class="flag-icon flag-icon-<?= $person['country_code'] ?>"></span>
                        <?= Html::a($person['name'], '/persons/r/'.$person['id']) ?>
                        <?php if ($person['byear'] != 0) { ?><em><?= date('Y') - $person['byear'] ?></em><?php } ?>
                        <div class="text-size-small text-muted"><?= $person['email'] ?></div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <p><i class="fa fa-info-circle text-muted"></i> <?= Yii::t('x', 'Last update {time} by {user}.', ['time'=>Yii::$app->formatter->asRelativetime($theClient['updated_dt']), 'user'=>$theClient['updatedBy']['name']]) ?></p>
</div>
<script>
var image = "<?= $theClient['image'] ?>"
</script>

<?php
$js = <<<'JS'
if (image != '') {
    // $('.page-title.d-flex >div').prepend('<img src="'+image+'" style="padding:10px; max-height:100px; float:right; display:inline-block;"><div class="clearfix visible-xs-block"></div>')
}
JS;

$this->registerJs($js);

