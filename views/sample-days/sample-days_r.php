<?php
use yii\helpers\Html;

include('_sample-days_inc.php');

Yii::$app->params['page_title'] = $theDay['title'];

if ($theDay['is_halfday'] == 'yes') {
    Yii::$app->params['page_small_title'] = ' - '.Yii::t('x', 'half day');
}

if ($theSegment) {
    Yii::$app->params['page_small_title'] = ' - '.count($theSegment['days']).' days';
}

if ($theSegment) { ?>
<div class="col-md-4 order-md-12">
    <div class="card">
        <div class="card-body">
            <p><strong><?= Yii::t('x', 'Summary') ?></strong><br><?= $theSegment['note'] ?></p>
            <p><strong><?= Yii::t('x', 'Days') ?></strong>
                <?php
                foreach ($theSegment['days'] as $cnt=>$day) {
                ?><br><?= ++ $cnt ?>. <?= $day['title'] ?><?php
                }
                ?>
            </p>
            <p><strong><?= Yii::t('x', 'Tags') ?></strong><br><?= $theSegment['tags'] ?></p>
            <p><strong><?= Yii::t('x', 'Updated') ?></strong><br><?= $theSegment['updatedBy']['name'] ?> @<?= Yii::$app->formatter->asDate($theSegment['updated_dt'], 'php:j/n/Y (l) H:i') ?>
        </div>
    </div>
</div>

<div class="col-md-8 order-md-1">
    <div class="card">
        <table class="table table-narrow table-bordered">
            <tbody>
                <?php foreach ($theSegment['days'] as $cnt=>$day) { ?>
                <tr>
                    <th class="text-center" style="vertical-align:top"><?= Yii::t('x', 'Day') ?><br><span style="font-size:150%"><?= ++ $cnt ?></span></th>
                    <td>
                        <h6><strong><?= Html::a($day['title'], '/sample-days/'.$day['id']) ?> <em class="alpha-pink"><?= $day['meals'] ?></em></strong></h4>
                        <div><?= $day['body'] ?></div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php } else {

$this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom mb-0 px-3">
    <li class="nav-item"><a class="nav-link active" href="#"><?= Yii::t('x', 'Itinerary') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="#"><?= Yii::t('x', 'Services') ?></a></li>
</ul><?php
$this->endBlock();
    ?>

<div class="col-md-4 order-12">
    <div class="card">
        <div class="card-body">
            <?php if (!empty($theDay['attachments'])) { ?>
            <p><strong><?= Yii::t('x', 'Attachments') ?></strong>
            <?php foreach ($theDay['attachments'] as $attachment) { ?>
            <div class="post-attachment">+ <a href="/attachments/<?= $attachment['id'] ?>"><?= $attachment['name'] ?></a> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($attachment['size'], 0) ?></span></div>
            <?php } ?>
            </p>
            <?php } ?>

            <p><strong><?= Yii::t('x', 'Language') ?></strong><br><?= strtoupper($theDay['language']) ?></p>

            <?php if ($theDay['note'] != '') { ?>
            <p><strong><?= Yii::t('x', 'Note') ?></strong><br><?= $theDay['note'] ?></p>
            <?php } ?>
            <?php if ($theDay['tags'] != '') { ?>
            <p><strong><?= Yii::t('x', 'Tags') ?></strong><br><?= $theDay['tags'] ?></p>
            <?php } ?>
            <?php if (!empty($theDay['segments'])) { ?>
            <p><strong><?= Yii::t('x', 'This day is part of the following multiple-day segment:') ?></strong></p>
            <?php foreach ($theDay['segments'] as $segment) { ?>
            <ul class="list-unstyled">
                <li><strong><?= Html::a($segment['title'], '/sample-days/'.$segment['id']) ?></strong>
                    <ol class="list-unstyled">
                        <?php foreach ($segment['days'] as $cnt=>$day) { ?>
                        <li><?= ++$cnt ?>. <?= $day['id'] == $theDay['id'] ? $day['title'] : Html::a($day['title'], '/sample-days/'.$day['id']) ?> <em><?= $day['meals'] ?></em></li>
                        <?php } ?>
                    </ol>
                </li>
            </ul>
            <?php } ?>
            <?php } // if segment ?>
            <p>
                <strong><?= Yii::t('x', 'Updated') ?></strong><br>
                <?php if (!empty($theDay['updated_dt'])) { ?>
                <?= $theDay['updatedBy']['name'] ?> @<?= Yii::$app->formatter->asDate($theDay['updated_dt'], 'php:j/n/Y (l) H:i') ?> UTC
                <?php } else { ?>
                <?= $theDay['createdBy']['name'] ?> @<?= Yii::$app->formatter->asDate($theDay['created_dt'], 'php:j/n/Y (l) H:i') ?> UTC
                <?php } ?>
            </p>
        </div>
    </div>
</div>
<div class="col-md-8 order-1">
    <?php if ($theDay['is_selectable'] == 'no') { ?>
    <div class="alert alert-warning">
        <i class="fa fa-info-circle"></i>
        <?= Yii::t('x', 'This day is not directly selectable by users to use in a tour program.') ?>
    </div>
    <?php } ?>

    <div class="card">
        <div class="card-body">
            <?php
            if ($theDay['image'] != '') echo '<img style="float:right; margin:0 0 20px 20px;" src="/upload/devis-days/'.$theDay['image'].'" width="150" height="113" />';
            echo str_replace(['class=', 'style='], ['c=', 's='], $theDay['body']);
                if ($theDay['summary'] != '') { ?>
                    <p><strong class="text-pink"><?= Yii::t('x', 'Highlight/Summary') ?></strong></p><?php
                    echo $theDay['summary'];
                }
            ?>
        </div>
        <table class="table table-narrow">
            <tbody>
                <tr><th class="text-nowrap alpha-brown" width="10%"><?= Yii::t('x', 'Meals') ?></th><td><?=$theDay['meals']?></td></tr>
                <tr><th class="text-nowrap alpha-brown"><?= Yii::t('x', 'Tour guide') ?></th><td><?=$theDay['guides']?></td></tr>
                <tr><th class="text-nowrap alpha-brown"><?= Yii::t('x', 'Transport') ?></th><td><?=$theDay['transport']?></td></tr>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>