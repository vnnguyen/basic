<?
use yii\helpers\Html;
use yii\helpers\Markdown;
?>
<div class="tab-pane" id="t-fb">
    <?php if (!empty($venueFeedbacks)) { ?>
    <h4><?= Yii::t('x', 'Customer feedback') ?></h4>
    <div class="table-responsive mb-20">
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Pax</th>
                    <th>How</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($venueFeedbacks as $feedback) { ?>
                <tr>
                    <td><?= Html::a($feedback['op_code'].' '.$feedback['op_name'], '/tours/feedback/'.$feedback['tour_id'], ['target'=>'_blank']) ?> <?= date('j/n/Y', strtotime($feedback['day_from'])) ?></td>
                    <td><?= $feedback['who'] ?></td>
                    <td><i class="fa-2x text-<?= $feedback['say'] == 'smile' ? 'success' : ($feedback['say'] == 'frown' ? 'danger' : '' ) ?> fa fa-<?= $feedback['say'] ?>-o"></i></td>
                    <td><?= $feedback['feedback'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>

    <h4><?= Yii::t('x', 'TripAdvisor feedback') ?></h4>
    <p><?= Yii::t('x', 'The following comments were posted on TripAdvisor.com') ?></p>
    <?= $fbTripadvisor ?>
    <style type="text/css">
    #t-fb .col1of2 {display:none;}
    #t-fb .col2of2 .quote {font-size:15px; font-weight:bold;}
    #t-fb .col2of2 .entry {margin-bottom:1em;}
    .mgrRspnInline .header {background-color:#ffc!important;}
    .inter_module, .photosInline {display:none;}
    .fw-b {font-weight: bold;}
    .xmedia, .reportProblem, .helpful, .partnerRvw, .rate.rate_s, .deckTools, .ratings_and_types, .memberBadging  {display:none;}
    .col2of2.composite {width:30%; margin-right:5%; float:left;}
    .trip_type {width:30%; float:left;}
    #SUMMARYBOX {width:30%; float:right;}
    .colTitle, .title .conceptsHeading {font-size:14px;}
    .rating {direction:ltr; font-size:13px;}
    </style>
</div>
