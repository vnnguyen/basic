<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_sample-days_inc.php');

if ($theSegment->isNewRecord) {
    Yii::$app->params['page_title'] = 'New sample segment';
} else {
    Yii::$app->params['page_title'] = 'Edit sample segment: '.$theSegment['title'];
}

?>
<div class="col-md-8">
    <?php if ($theDay->isNewRecord) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        <?= Yii::t('x', 'You are creating a multiple-day segment.') ?> <?= Html::a(Yii::t('x', 'Click here if you want to create a single day.'), '?', ['class'=>'alert-link']) ?>
    </div>
    <?php } ?>

    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <fieldset>
                <div class="row">
                    <div class="col-md-8"><?= $form->field($theSegment, 'title')->label(Yii::t('x', 'Name of this segment')) ?></div>
                    <div class="col-md-4"><?= $form->field($theSegment, 'language')->dropdownList($languageList)->label(Yii::t('x', 'Language')) ?></div>
                </div>
                <?= $form->field($theSegment, 'note')->textArea(['rows'=>5])->label(Yii::t('x', 'Summary')) ?>
                <?= $form->field($theSegment, 'tags')->label(Yii::t('x', 'Tags')) ?>
            </fieldset>

            <fieldset>
                <label><?= Yii::t('x', 'Days in this sample tour segment') ?></label>
                <div class="mb-2 segment-day-list" id="ahihi">
                    <div class="text-danger d-none segment-day-list-no-days"><?= Yii::t('x', 'No days. Add days using the from below.') ?></div>
                    <?php foreach ($theSegment['days'] as $cnt=>$day) { ?>
                    <div class="mb-1 segment-day-list-item">
                        <input type="hidden" name="day_id[]" value="<?= $day['id'] ?>">
                        <i data-dayid="<?= $day['id'] ?>" class="action-del-day slicon-trash text-danger cursor-pointer"></i>
                        Day <span class="segment-day-list-item-count"><?= ++ $cnt ?></span>.
                        <a href="/sample-days/<?= $day['id'] ?>" target="_blank"><?= $day['title'] ?></a>
                        <em><?= $day['meals'] ?></em>
                    </div>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label><?= Yii::t('x', 'Select from sample tour days') ?></label>
                    <div class="row">
                        <div class="col-sm-9">
                            <input type="hidden" name="add-day-id" id="add-day-id" value="0">
                            <input id="day-search" type="text" class="form-control" name="" value="" placeholder="Type ID or name to search from the sample days" />
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-block btn-info action-add-day" href="#">+<?= Yii::t('x', 'Add day') ?></a>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div><?= Html::submitButton(Yii::t('x', 'Save changes'), ['class' => 'btn btn-primary']) ?></div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="d-none" id="ehehe">
    <div class="mb-1">
        <input type="hidden" name="day_id[]" value="ID">
        <i data-dayid="" class="action-del-day slicon-trash text-danger cursor-pointer"></i>
        Day <span class="segment-day-list-item-count">CNT</span>.
        <a href="/sample-days/ID" target="_blank"></a>
        <em>EM</em>
    </div>
</div>
<script>
var segmentid = '<?= $theSegment['id'] ?>';
var dayid = 0;
</script>
<?php
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.4.3/jquery.autocomplete.min.js', ['depends'=>'yii\web\JqueryAsset']);

$js = <<<'TXT'
$('.segment-day-list').on('click', '.action-del-day', function(){
    var dayid = $(this).data('dayid')
    if (confirm('Delete this?')) {
        $(this).parents('.segment-day-list-item').remove()
        $('.segment-day-list-item-count').each(function(i) {
            $(this).text(1 + i)
        })
        if ($('.segment-day-list-item').length > 0) {
            $('.segment-day-list-no-days').addClass('d-none')
        } else {
            $('.segment-day-list-no-days').removeClass('d-none')
        }
    }
});

$('.action-add-day').on('click', function(e){
    e.preventDefault()
    var dayid = $('#add-day-id').val()
    if (dayid == 0) {
        alert('Not OK')
        return false
    }
    $('#ehehe').find('div.mb-1').clone(true, true).addClass('segment-day-list-item').appendTo('#ahihi')
    $('#day-search').val('')
    $('.segment-day-list-item-count').each(function(i) {
        $(this).text(1 + i)
    })
})

$('#day-search').autocomplete({
    serviceUrl: '/sample-days/ajax?action=searchday',
    onSelect: function(suggestion){
        $(this).val(suggestion.name)
        $('#add-day-id').val(suggestion.id)
        var div = $('#ehehe').find('div.mb-1:eq(0)')
        div.find('input').val(suggestion.id)
        div.find('i').attr('data-dayid', suggestion.id)
        div.find('em').val(suggestion.meals)
        div.find('a').attr('href', '/sample-days/' + suggestion.id).text(suggestion.name)
    }
});

// $('#sampletoursegment-body').ckeditor({
//     allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
//     entities: false,
//     entities_greek: false,
//     entities_latin: false,
//     uiColor: '#ffffff',
//     height:400,
//     contentsCss: '/assets/css/style_ckeditor.css'
// });
// $('#sampletoursegment-summary').ckeditor({
//     allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
//     entities: false,
//     entities_greek: false,
//     entities_latin: false,
//     uiColor: '#ffffff',
//     height:200,
//     contentsCss: '/assets/css/style_ckeditor.css'
// });
TXT;
$this->registerJs($js);

// $this->registerJsFile('https://cdn.ckeditor.com/4.11.3/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile('https://cdn.ckeditor.com/4.11.3/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
