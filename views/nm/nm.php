<?php

include('_nm_inc.php');

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;

if (USER_ID == 1444) {

$dataProvider = new ActiveDataProvider([
    'query' => \common\models\Nm::find()->where(['owner'=>'at']),
    'pagination' => [
        'pageSize' => 20,
    ],
]);

Pjax::begin([
    // PJax options
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        // [	'class' => 'yii\grid\SerialColumn'],
        // Simple columns defined by the data contained in $dataProvider.
        // Data from the model's column will be used.
        'title',
        'tags',
        // More complex one.
        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) {
                return $data->created_dt; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],
    ],
]);
Pjax::end();
} else {


$sessAction = Yii::$app->session->get('action', '');
$sessTo = Yii::$app->session->get('to', 0);
$sessAt = Yii::$app->session->get('at', 0);

Yii::$app->params['page_title'] = 'Sample days ('.$pagination->totalCount.')';

?>

<div class="col-md-12">
    <? if ($sessAction == 'prepare-add-day' && $sessTo != 0) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Click the <i class="fa fa-plus text-pink"></i> icon to add a day to <a href="/products/r/<?= $sessTo ?>">your tour program</a>. (<?= Html::a('Cancel', '/nm?action=cancel-add-day')?>)
    </div>
    <? } ?>

    <? if ($sessAction == 'prepare-add-day-sample' && $sessTo != 0) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Click the <i class="fa fa-plus text-pink"></i> icon to add a day to <a href="/tm/r/<?= $sessTo ?>">your sample program</a>. (<?= Html::a('Cancel', '/nm?action=cancel-add-day-sample')?>)
    </div>
    <? } ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('language', $language, $languageList, ['class'=>'form-control']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
                <?= Html::textInput('tags', $tags, ['class'=>'form-control', 'placeholder'=>'Search tags']) ?>
                <?= Html::dropdownList('show', $show, ['all'=>'B2C / all tags', '2015'=>'B2C / "2015" only', 'b2b'=>'B2B only'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('updatedby', $updatedby, ArrayHelper::map($updatedByList, 'id', 'name'), ['prompt'=>'Updated by', 'class'=>'form-control']) ?>
                <?= Html::dropdownList('orderby', $orderby, ['name'=>'Order by name', 'updated'=>'Order by update'], ['class'=>'form-control']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), DIR.URI) ?>
            </form>
        </div>
        <div class="table-responsive">
            <table id="tbl-days" class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Name & Content</th>
                        <th>Meals</th>
                        <th>Tags</th>
                        <? if (
                        ($show == 'b2b' && in_array(USER_ID, [1, 3, 26052, 29013])) // Jonathan, Alain
                        || ($show != 'b2b' && in_array(USER_ID, $this->context->allowList)) // Hieu, Nguyen
                        ) { ?>
                        <th>Edit</th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody>
                <? foreach ($theDays as $day) { ?>
                <div class="modal fade modal-primary" id="nm<?= $day['id'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h6 class="modal-title text-pink text-semibold"><?= $day['title'] ?> (<?= $day['meals'] ?>)</h6>
                            </div> 
                            <div class="modal-body">
                                <p><button class="btn btn-default clipboard" data-clipboard-target="#nmbody<?= $day['id'] ?>"><i class="fa fa-copy"></i> Copy to clipboard</button>
                                or <?= Html::a('View detail', '/nm/r/'.$day['id']) ?>
                                </p>
                                <div id="nmbody<?= $day['id'] ?>">
                                    <?= $day['body'] ?>
                                </div>
                            </div>
                            <div class="modal-footer text-muted">
                                <i class="fa fa-clock-o"></i> <?= $day['updatedBy']['nickname'] ?> <?= Yii::$app->formatter->asRelativeTime($day['updated_dt']) ?>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <tr data-day="<?= $day['id'] ?>">
                    <td>
                        <? if ($sessAction == 'prepare-add-day-sample') { ?>
                        <a title="Add day to program" href="/tm/r/<?= $sessTo ?>?action=add-day-sample&at=<?= $sessAt ?>&add=<?= $day['id'] ?>" class="text-pink"><i class="fa fa-plus text-pink"></i></a>
                        <? } elseif ($sessAction == 'prepare-add-day') { ?>
                        <a title="Add day to program" href="/ct/rr/<?= $sessTo ?>?action=day-add-nm-after&id=<?= $sessAt ?>&nm=<?= $day['id'] ?>" class="text-pink"><i class="fa fa-plus text-pink"></i></a>
                        <? } else { ?>
                            <? if (in_array(USER_ID, [1, 28722]) && $show != 'b2b') { ?>
                        <i data-day="<?= $day['id'] ?>" class="nouse cursor-pointer fa fa-ban text-danger" title="NoUse"></i>
                            <? } ?>
                        <? } ?>
                        <a data-href="/nm/r/<?= $day['id'] ?>"
                            class="popovers"
                            data-placement="right"
                            data-trigger="hover"
                            data-html="true"
                            data-title="<?= Html::encode($day['title']) ?> (<?= $day['meals'] ?>)"
                            data-content="<?= Html::encode($day['body']) ?>"
                            data-toggle="modal" data-target="#nm<?= $day['id'] ?>"
                            ><?= $day['title'] ?></a>
                    </td>
                    <td><?=$day['meals'] ?></td>
                    <td><?
                    $tags = explode(',', $day['tags']);
                    $tagList = [];
                    foreach ($tags as $tag) {
                        $tagList[] = trim($tag);
                    }
                    sort($tagList);
                    $tagStrList = [];
                    foreach ($tagList as $tag) {
                        if ($tag == '2015' || $tag == '2016') {
                            $class = 'text-warning';
                        } else {
                            $class = '';
                        }
                        $tagStrList[] = Html::a(trim($tag), DIR.URI.'?tags='.urlencode(trim($tag)), ['class'=>$class]);
                    }
                    echo implode(', ', $tagStrList);
                    ?></td>
                    <? if (
                        ($day['owner'] == 'si' && in_array(USER_ID, [1, 3, 26052, 29013])) // Jonathan, Alain
                        || ($day['owner'] == 'at' && in_array(USER_ID, $this->context->allowList)) // Hieu, Nguyen
                        ) { ?>
                    <td class="text-nowrap">
                        <?= Html::a('<i class="fa fa-edit"></i>', '/nm/u/'.$day['id']) ?>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', '/nm/d/'.$day['id'], ['class'=>'text-danger']) ?>
                    </td>
                    <? } ?>
                </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
        <? if ($pagination->totalCount > $pagination->pageSize) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget(array(
            'pagination' => $pagination,
            'prevPageLabel'=>'<',
            'nextPageLabel'=>'>',
            'firstPageLabel'=>'<<',
            'lastPageLabel'=>'>>',
        ));?>
        </div>
        <? } ?>
    </div>
</div>
<style>
.popover {max-width:500px;}
</style>
<?

$js = <<<TXT
//new Clipboard('.clipboard-copy');
$('.popovers').popover();

$('i.nouse').on('click', function(){
    var day = $(this).data('day');
    $.ajax({
        method: "POST",
        url: "/sample-tour-days?xh",
        data: { action: "nouse", day: day }
    })
    .done(function() {
        $('tr[data-day="'+day+'"]').fadeOut(200);
    })
    .fail(function() {
        alert( "Error adding NoUse tag " );
    });
});

new Clipboard('.clipboard');
TXT;
$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.15/clipboard.min.js', ['depends'=>'yii\web\JqueryAsset']);
}// not Huan