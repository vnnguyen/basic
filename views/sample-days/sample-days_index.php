<?php
use app\widgets\LinkPager;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;


include('_sample-days_inc.php');

Yii::$app->params['page_title'] = Yii::t('x', 'Sample tour days for B2C').' ('.$pagination->totalCount.')';

?>

<div class="col">
    <form class="form-inline mb-1">
        <?= Html::dropdownList('language', $language, $languageList, ['class'=>'form-control mb-1']) ?>
        <?= Html::dropdownList('type', $type, $dayTypeList, ['class'=>'form-control ml-1 mb-1', 'prompt'=>Yii::t('x', '- Select -')]) ?>
        <?= Html::textInput('name', $name, ['class'=>'form-control ml-1 mb-1', 'placeholder'=>'Search name']) ?>
        <?= Html::textInput('tags', $tags, ['class'=>'form-control ml-1 mb-1', 'placeholder'=>'Search tags']) ?>
        <?= Html::dropdownList('updatedby', $updatedby, ArrayHelper::map($updatedByList, 'id', 'name'), ['prompt'=>'Updated by', 'class'=>'form-control ml-1 mb-1']) ?>
        <?= Html::dropdownList('orderby', $orderby, ['name'=>'Order by name', 'updated'=>'Order by update'], ['class'=>'form-control ml-1 mb-1']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary ml-1 mb-1']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '/sample-days', ['class'=>'ml-1 mb-1']) ?>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table id="tbl-days" class="table -table-bordered table-striped table-narrow">
                <thead>
                    <tr>
                        <?php if (in_array(USER_ID, $this->context->allowList)) { ?>
                        <th width="10"></th>
                        <?php } ?>
                        <th><?= Yii::t('x', 'Name') ?></th>
                        <th><?= Yii::t('x', 'Tags') ?></th>
                        <th><?= Yii::t('x', 'Update') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($theDays as $day) { ?>
                <div class="modal fade modal-primary" id="nm<?= $day['id'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-full">
                        <div class="modal-content">
                            <div class="modal-header bg-white">
                                <h6 class="modal-title font-weight-semibold"><?= $day['title'] ?> (<?= $day['meals'] ?>)</h6>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div> 
                            <?php if ($day['note'] != '') { ?>
                            <div class="modal-body alpha-pink"><strong>NOTE:</strong><?= $day['note'] ?></div>
                            <?php } ?>
                            <div class="modal-body">
                                <p><button class="btn btn-default clipboard" data-clipboard-target="#nmbody<?= $day['id'] ?>"><i class="fa fa-copy"></i> Copy to clipboard</button>
                                or <?= Html::a('View detail', '/sample-days/'.$day['id']) ?>
                                </p>
                                <div id="nmbody<?= $day['id'] ?>">
                                    <?= $day['body'] ?>
                                </div>
                            </div>
                            <div class="modal-footer text-muted">
                                <i class="fa fa-clock-o"></i> <?= $day['updatedBy']['name'] ?> <?= Yii::$app->formatter->asRelativeTime($day['updated_dt']) ?>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <tr data-day="<?= $day['id'] ?>">
                    <?php if (in_array(USER_ID, $this->context->allowList)) { ?>
                    <td class="text-nowrap">
                        <?= Html::a('<i class="slicon-pencil"></i>', '/sample-days/'.$day['id'].'/u', ['class'=>'text-muted', 'title'=>Yii::t('x', 'Edit')]) ?>
                        <?php if (in_array(USER_ID, [1, 28722]) && $day['is_selectable'] != 'no' && strpos($day['tags'], 'nouse') === false) { ?>
                        <i data-day="<?= $day['id'] ?>" class="nouse cursor-pointer slicon-ban text-danger" title="Mark as NO_USE"></i>
                        <?php } ?>
                    </td>
                    <?php } ?>
                    <td>
                        <a data-href="/sample-days/<?= $day['id'] ?>"
                            class="x-popovers"
                            xdata-placement="right"
                            xdata-trigger="hover"
                            xdata-html="true"
                            data-title="<?= Html::encode($day['title']) ?> (<?= $day['meals'] ?>)"
                            data-content="<?= $day['note'] != '' ? '<div class=\'text-danger mb-2\'><strong>'.Yii::t('x', 'Note').':</strong> '.Html::encode($day['note']).'</div>' : '' ?><?= Html::encode($day['body']) ?>"
                            data-toggle="modal" data-target="#nm<?= $day['id'] ?>"
                            ><i class="slicon-info cursor-pointer"></i></a>
                            <?= Html::a($day['title'], '/sample-days/'.$day['id']) ?></a>
                        <?php if ($day['stype'] == 'segment') {
                        $segment = \common\models\SampleTourSegment::find()
                            ->where(['id'=>$day['id']])
                            ->with([
                                'days'=>function($q){
                                    return $q->select(['id', 'title']);
                                },
                            ])
                            ->asArray()
                            ->one();
                        echo '<em>', Yii::t('x', '{count} days', ['count'=>count($segment['days'])]), '</em>';
                        foreach ($segment['days'] as $cnt=>$segday) {
                            echo '<br><span class="">', Yii::t('x', 'Day'), ' ', ++$cnt, '. ', $segday['title'];
                        }
                        ?>
                        <?php } ?>
                        <em class="alpha-info"><?= $day['stype'] == 'day' ? $day['meals'] : '' ?></em>
                    </td>
                    <td>
                        <?php
                        if ($day['is_selectable'] == 'no') {
                            echo '<i class="slicon-tag text-warning"></i> ', Html::a(Yii::t('x', 'not selectable'), '?type=ns', ['class'=>'text-warning']), '</span> ';
                        }
                        if ($day['stype'] == 'segment') {
                            echo '<i class="slicon-tag text-info"></i> ', Html::a(Yii::t('x', 'multiple days'), '?type=2', ['class'=>'text-info font-weight-bold']), ' ';
                        }
                        if ($day['is_halfday'] == 'yes') {
                            echo '<i class="slicon-tag text-slate"></i> ', Html::a(Yii::t('x', 'half day'), '?type=5', ['class'=>'text-slate']), '</span> ';
                        }
                        if ($day['stype'] == 'day' && $day['id'] == 3507) {
                            echo '<i class="slicon-tag text-info"></i> ', Html::a(Yii::t('x', 'half day'), '?type=5', ['class'=>'text-info']), ' ';
                        }
                        if ($day['tags'] != '') {
                            $tags = explode(',', $day['tags']);
                            $tagList = [];
                            foreach ($tags as $tag) {
                                $tagList[] = trim($tag);
                            }
                            sort($tagList);
                            $tagStrList = [];
                            foreach ($tagList as $tag) {
                                if ($tag == 'nouse') {
                                    $class = 'text-danger';
                                    $tag = 'NO USE';
                                } elseif ($tag == '2015' || $tag == '2016' || $tag == '2017' || $tag == '2018') {
                                    $class = 'text-success';
                                } else {
                                    $class = 'text-normal';
                                }
                                $tagStrList[] = '<i class="slicon-tag text-muted"></i> '.Html::a(trim($tag), '?tags='.urlencode(trim($tag)), ['class'=>$class]);
                            }
                            echo implode(' ', $tagStrList);
                        }
                        ?>
                    </td>
                    <td>
                        <?= empty($day['updated_by']) ? $day['createdBy']['name'] : $day['updatedBy']['name'] ?>
                        <span class="text-muted text-nowrap"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativeTime($day['updated_dt']) ?></span>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pagination,
        'prevPageLabel'=>'<',
        'nextPageLabel'=>'>',
        'firstPageLabel'=>'<<',
        'lastPageLabel'=>'>>',
    ]) ?>

</div>

<?php

// include('_slider.php');

$js = <<<TXT
//new Clipboard('.clipboard-copy');
// $('.popovers').popover();

$('i.nouse').on('click', function(){
    var day = $(this).data('day');
    $.ajax({
        method: "POST",
        url: "/sample-days?xh",
        data: { action: "nouse", day: day }
    })
    .done(function() {
        $('tr[data-day="'+day+'"]').fadeOut(200);
    })
    .fail(function() {
        alert( "Error adding NO_USE tag " );
    });
});

// $('#tbl-days').on('click', 'a.x-popovers', function(e){
//     e.preventDefault()
//     var ref = $(this).data('ref')
//     var title = $(this).data('title')
//     var body = $(this).data('content')
//     var link = $(this).data('href')
//     $('#ks-title').html(title)
//     $('#ks-link').attr('href', link)
//     $('#ks-body').html(body)
//     $('.slide-block:eq(0)').addClass('ks-open')
// })

new Clipboard('.clipboard');
TXT;
$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.15/clipboard.min.js', ['depends'=>'yii\web\JqueryAsset']);
