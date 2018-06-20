<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['Assign tour guides', 'tours/guides/'.$theTour['id']],
];

$this->params['icon'] = 'user';

$this->title = 'Tour guides: '.$theTour['op_code'];

$tourdayIds = explode(',', $theTour['day_ids']);
?>
<div class="col-md-12">
    <p><strong>CURRENT ASSIGNED TOUR GUIDES</strong> | <?= Html::a('+New', DIR.URI.'?action=add') ?></p>
    <? if (empty($tourGuides)) { ?>
    <p>No assigned tour guides.</p>
    <? } else { ?>
    <div class="table-responsive">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('tours_guides', 'Status');?></th>
                    <th><?= Yii::t('tours_guides', 'Guide');?></th>
                    <th width="150"><?= Yii::t('tours_guides', 'Service time');?></th>
                    <th><?= Yii::t('tours_guides', 'Points');?></th>
                    <th><?= Yii::t('tours_guides', 'Note');?></th>
                    <th width="20"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($tourGuides as $guide) { if ($guide['parent_id'] == 0) { ?>
                <tr>
                    <td><?= ucwords($guide['booking_status']) ?></td>
                    <td class="text-nowrap"><?
                    if ($guide['guide_user_id'] != 0 && $guide['namephone'] != '') {
                        echo '<i class="fa fa-user text-muted"></i> ', Html::a($guide['namephone'], '@web/tourguides/r/'.$guide['guide_user_id'], ['rel'=>'external']);
                    } else {
                        echo $guide['guide_name'];
                    }
                    echo ' - ', Html::a('Edit', DIR.URI.'?action=edit&item_id='.$guide['id']);
                    ?>
                    </td>
                    <td class="text-nowrap text-center">
                        <div>
                        <?= date('j/n/Y', strtotime($guide['use_from_dt'])) ?> - <?= date('j/n/Y', strtotime($guide['use_until_dt'])) ?>
                        <?= Html::a('+', DIR.URI.'?action=addtime&item_id='.$guide['id'], ['title'=>'+Service time']) ?>
                        </div>
                        <? foreach ($tourGuides as $item2) {
                            if ($item2['parent_id'] == $guide['id']) {
                                echo '<div>', date('j/n/Y', strtotime($item2['use_from_dt'])), ' - ', date('j/n/Y', strtotime($item2['use_until_dt']));
                                echo ' ', Html::a('<i class="fa fa-trash-o"></i>', DIR.URI.'?action=delete&item_id='.$item2['id'], ['title'=>'Delete', 'class'=>'text-muted']), '</div>';
                            }
                        }
                        ?>
                    </td>
                    <td class="text-center"><?= $guide['points'] ?></td>
                    <td><?= $guide['note'] ?></td>
                    <td class="text-muted">
                        <?= Html::a('<i class="fa fa-trash-o"></i>', DIR.URI.'?action=delete&item_id='.$guide['id'], ['class'=>'text-danger', 'title'=>'Delete']) ?>
                    </td>
                </tr>
                <? } } ?>
            </tbody>
        </table>
    </div>
    <? } // if empty ?>
    <hr>
</div>

<div class="col-md-6">
<? $form = ActiveForm::begin(); ?>
    <? if ($action == 'add') { ?>
    <p><strong><?= Yii::t('tours_guides', 'NEW TOUR GUIDE INFO');?></strong></p>
    <? } ?>

    <? if ($action == 'addtime') { ?>
    <p><strong><?= Yii::t('tours_guides', 'NEW SERVICE TIME FOR');?> <?= $theGuide['guide_name'] ?></strong></p>
    <? } ?>

    <? if ($action == 'edit') { ?>
    <p><strong><?= Yii::t('tours_guides', 'EDIT INFO');?></strong>
        <? if ($action == 'edit' && $theGuide['guide_user_id'] != 0) { ?>
        <em class="text-danger"><?= Yii::t('tours_guides', 'Note: You cannot edit guide\'s name. To replace guide, delete and then add new');?></em>
        <? } ?>
    </p>
    <? } ?>

    <? if ($action == 'delete') { ?>
    <p><strong><?= Yii::t('tours_guides', 'CONFIRM DELETION');?></strong></p>
    <? } ?>

    <? if (in_array($action, ['add', 'addtime', 'edit'])) { ?>
    <? if (in_array($action, ['add', 'edit'])) { ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theForm, 'guideCompany')->hiddenInput() ?></div>
        <div class="col-md-6"><?= $form->field($theForm, 'guideName', ['inputOptions'=>['class'=>'form-control', 'autocomplete'=>'off', ($action == 'edit' && $theGuide['guide_user_id'] != 0 ? 'disabled' : 'meh')=>'disabled']]) ?></div>
    </div>
    <? } ?>
    <div class="row">
        <div class="col-xs-6"><?= $form->field($theForm, 'useFromDt') ?></div>
        <div class="col-xs-6"><?= $form->field($theForm, 'useUntilDt') ?></div>
    </div>
    <? if (in_array($action, ['add', 'edit'])) { ?>
    <div class="row">
        <div class="col-xs-6"><?= $form->field($theForm, 'bookingStatus')->dropdownList($theForm::$bookingStatusList) ?></div>
        <div class="col-xs-6"><?= $form->field($theForm, 'points') ?></div>
    </div>
    <?= $form->field($theForm, 'note')->textArea(['rows'=>5]) ?>
    <? } ?>
    <div class="text-right"><?= Html::submitButton('Save', ['class'=>'btn btn-primary']) ?></div>
    <? } ?>
<? ActiveForm::end(); ?>
</div>
<div class="col-md-6">
<style type="text/css">
.day-list-item { margin-bottom:8px;}
.day-list-item-title {border:1px dotted #ccc; padding:8px; background-color:#eceff1;}
.day-list-item-body {border:1px dotted #ccc; padding:8px; border-top:0;}
.day-number {padding:0 8px; border-right:1px solid #ddd; font-weight:bold; display:inline-block; float:left;}
.day-date {padding:0 8px; border-right:1px solid #ddd; display:inline-block; float:left;}
.day-name {padding:0 8px; border-right:1px solid #ddd; font-weight:bold; display:inline-block; float:left;}
.day-meals {padding:0 8px; color:#757575;}
</style>
    <p>
        <strong><?= Yii::t('tours_guides', 'ITINERARY');?></strong> <?= Yii::t('tours_guides', 'Last update');?> <?= $theTour['updated_at'] ?>
        <a href="#" class="a-show-all"><?= Yii::t('tours_guides', 'Show all');?></a>
        <a href="#" class="a-hide-all"><?= Yii::t('tours_guides', 'Hide all');?></a>
    </p>
    <div class="itinerary day-list">
<?
$cnt = 0;
foreach ($tourdayIds as $id) {
    foreach ($theTour['days'] as $day) {
        if ($day['id'] == $id) {
            $date = strtotime('+ '.$cnt.' days', strtotime($theTour['day_from']));
            $dmY = date('j/n/Y', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
            $dm = date('j/n', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
?>
    <div class="day-list-item">
        <div class="day-list-item-title">
            <span class="day-number"><?= ++$cnt ?></span>
            <span class="day-date"><?= $dmY ?></span>
            <span class="day-name"><?= $day['name'] ?></span>
            <em class="day-meals"><?= $day['meals'] ?></em>
        </div>
        <div class="day-list-item-body">
            <?= Markdown::process($day['body']) ?>
        </div>
    </div>
<?
        }
    }
}
?>
    </div>
</div>

<?

$js = <<<'TXT'
$('#tourguideform-usefromdt, #tourguideform-useuntildt').daterangepicker({
    locale: {
        firstDay: 1,
        format: 'YYYY-MM-DD HH:mm'
    },
    timePicker: true,
    timePickerIncrement: 5,
    timePicker24Hour: true,
    singleDatePicker: true,
    showDropdowns: true
});

var guides = [$theGuides];

var guides = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: guides
});

$('#tourguideform-guidename').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
    },{
    name: 'guides',
    source: guides
});
TXT;

$guideList = [];
foreach ($theGuides as $guide) {
    $guideList[] = '"'.$guide['namephone'].'"';
}
$js = str_replace('$theGuides', implode(', ', $guideList), $js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.0/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJsFile(DIR.'assets/typeahead.js_0.11.1/typeahead.bundle.min.js', ['depends'=>'app\assets\MainAsset']);

$this->registerJs($js);



$js = <<<'TXT'
$('.day-list-item-body').hide();

$('.a-show-all').click(function(){
    $('.day-list-item-body').slideDown(300);
    return false;
});
$('.a-hide-all').click(function(){
    $('.day-list-item-body').slideUp(300);
    return false;
});
$('.day-list-item-title').click(function(){
    var body = $(this).parent().find('.day-list-item-body');
    body.addClass('clicked');
    if (body.is(':visible')) {
        body.slideUp(300);
        //$('.day-list-item-body:not(.clicked)').slideDown(300);
    } else {
        body.slideDown(300);
        $('.day-list-item-body:not(.clicked)').slideUp(300);
    }
    body.removeClass('clicked');
});
TXT;
$this->registerJs($js);