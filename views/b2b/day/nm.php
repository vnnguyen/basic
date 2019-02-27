<?php

use yii\helpers\Html;
use app\widgets\LinkPager;

include('_nm_inc.php');

Yii::$app->params['page_title'] = 'B2B - Sample days ('.$pagination->totalCount.')';

?>

<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('language', $language, $languageList, ['class'=>'form-control']) ?>
        <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
        <?= Html::textInput('tags', $tags, ['class'=>'form-control', 'placeholder'=>'Search tags']) ?>
        <?= Html::dropdownList('orderby', $orderby, ['name'=>'Order by name', 'updated'=>'Order by update'], ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), DIR.URI) ?>
    </form>

    <div class="card mb-2">
        <div class="table-responsive">
            <table id="tbl-days" class="table table-bordered table-narrow mb-0">
                <thead>
                    <tr>
                        <th>Name & Content</th>
                        <th>Meals</th>
                        <th>Tags</th>
                        <?php if (in_array(USER_ID, [1, 3, 26052, 29013, 40399])) { ?>
                        <th>Edit</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($theDays as $day) { ?>
                <div class="modal fade modal-primary" id="nm<?= $day['id'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-full">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title fomt-weight-semibold"><?= $day['title'] ?> (<?= $day['meals'] ?>)</h6>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div> 
                            <div class="modal-body">
                                <p><button class="btn btn-default clipboard" data-clipboard-target="#nmbody<?= $day['id'] ?>"><i class="fa fa-copy"></i> Copy to clipboard</button>
                                or <?= Html::a('View detail', '/b2b/days/r/'.$day['id']) ?>
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
                        <a data-href="/b2b/days/r/<?= $day['id'] ?>"
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
                    <?php if (in_array(USER_ID, [1, 3, 26052, 29013, 40399])) { ?>
                    <td class="text-nowrap">
                        <?= Html::a('<i class="fa fa-edit"></i>', '/b2b/days/u/'.$day['id']) ?>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', '/b2b/days/d/'.$day['id'], ['class'=>'text-danger']) ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?= LinkPager::widget(array(
        'pagination' => $pagination,
        'prevPageLabel'=>'<',
        'nextPageLabel'=>'>',
        'firstPageLabel'=>'<<',
        'lastPageLabel'=>'>>',
    )) ?>

</div>
<style>
.popover {max-width:500px;}
</style>
<?

$js = <<<TXT
$('.popovers').popover();

new Clipboard('.clipboard');
TXT;
$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.15/clipboard.min.js', ['depends'=>'yii\web\JqueryAsset']);