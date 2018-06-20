<?php

include('_tm_inc.php');

if (isset($_GET['action'], $_GET['ct'], $_GET['id']) && $_GET['action'] == 'day-add-nm-prepare') {
    Yii::$app->session->set('nm-prepare', 'yes');
    Yii::$app->session->set('nm-prepare-ct', $_GET['ct']);
    Yii::$app->session->set('nm-prepare-day', $_GET['id']);
    return \Yii::$app->response->redirect('/nm');
}

if (isset($_GET['action']) && $_GET['action'] == 'cancel-add-nm-after') {
    Yii::$app->session->remove('nm-prepare');
    Yii::$app->session->remove('nm-prepare-ct');
    Yii::$app->session->remove('nm-prepare-day');
    return \Yii::$app->response->redirect('/nm');
}

$getPrepare = Yii::$app->session->get('nm-prepare', 'no');
$getOwner = Yii::$app->session->get('owner', 'at');
if ($getPrepare == 'yes') {
    $getPrepareCt = Yii::$app->session->get('nm-prepare-ct', 0);
    $getPrepareDay = Yii::$app->session->get('nm-prepare-day', 0);
}

/*
$getType = fRequest::getValid('rtype', array('all', 'sample', 'ctr', 'tour'));
$getName = fRequest::get('name', 'string', '', true);
$getTag = fRequest::get('tag', 'string', '', true);
$getPage = fRequest::get('page', 'integer', 1, true);

$whereType = ' AND rid'.$getType == 'sample' ? '=0' : '!=0'; if ($getType == 'all') $whereType = '';
$whereName = ' AND LOCATE("'.$getName.'", name)!=0'; if ($getName == '') $whereName = '';
$whereTag = ' AND LOCATE("'.$getTag.'", note)!=0'; if ($getTag == '') $whereTag = '';

// Pages
$q = $db->query('SELECT COUNT(*) FROM at_days WHERE 1=1 '.$whereType.$whereName.$whereTag);
$pg = new hxPagination($q->fetchScalar(), '?rtype='.$getType.'&name='.$getName.'&tag='.$getTag.'&page=', $getPage, 20, 3);

// Get cases
$q = $db->query('SELECT * FROM at_days WHERE 1=1 '.$whereType.$whereName.$whereTag.' LIMIT '.$pg->limitFrom.', '.$pg->perPage);
$thePrograms = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$metaT = 'Ngày tour ('.$pg->itemCount.')';
$pageM = 'ct';
$pageB = array(
    anchor('days', 'Ngày tour'),
    // anchor('days/r/'.$theDay['id'], $theDay['name']),
    );
include('__hd.php'); */
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = 'Sample tour programs ('.$pagination->totalCount.')';

?>

<div class="col-md-12">
    <? if ($getPrepare == 'yes' && $getPrepareCt != 0) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Click the <i class="fa fa-plus text-pink"></i> icon to add a day to <a href="/products/r/<?= $getPrepareCt ?>">your tour program</a>. (<?= Html::a('Cancel', '/nm?action=cancel-add-nm-after')?>)
    </div>
    <? } ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('language', $language, $languageList, ['class'=>'form-control']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
                <?= Html::textInput('tags', $tags, ['class'=>'form-control', 'placeholder'=>'Search tags']) ?>
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
                        <th width="20">Days</th>
                        <th>Tags</th>
                        <? if (
                        (SEG2 == 'b2b' && in_array(USER_ID, [1, 3, 26052, 29013])) // Jonathan, Alain
                        || (SEG2 == '' && in_array(USER_ID, [1, 3, 28722])) // Hieu, Nguyen
                        ) { ?>
                        <th width="20">Edit</th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody>
                <? foreach ($thePrograms as $prog) { ?>
                <tr data-day="<?= $prog['id'] ?>">
                    <td>
                        <? if ($getPrepare == 'yes' && $getPrepareCt != 0) { ?>
                        <a title="Add day to program" href="/ct/rr/<?= $getPrepareCt ?>?action=day-add-nm-after&id=<?= $getPrepareDay ?>&nm=<?= $prog['id'] ?>" class="text-pink"><i class="fa fa-plus text-pink"></i></a>
                        <? } ?>
                        <a href="/tm/r/<?= $prog['id'] ?>"
                            class="popovers"
                            data-placement="right"
                            data-trigger="hover"
                            data-html="true"
                            data-title="<?= Html::encode($prog['title']) ?> (<?= $prog['id'] ?>)"
                            data-content="<?= Html::encode($prog['intro']) ?>"
                            ><?= $prog['title'] ?></a>
                    </td>
                    <td class="text-center"><?= $prog['day_count'] ?></td>
                    <td><?
                    $tags = explode(',', $prog['tags']);
                    $tagList = [];
                    foreach ($tags as $tag) {
                        $tagList[] = trim($tag);
                    }
                    sort($tagList);
                    $tagStrList = [];
                    foreach ($tagList as $tag) {
                        if (trim($tag) != '') {
                            $tagStrList[] = Html::a(trim($tag), DIR.URI.'?tags='.urlencode(trim($tag)));
                        }
                    }
                    echo implode(', ', $tagStrList);
                    ?></td>
                    <? if (
                        ($prog['owner'] == 'si' && in_array(USER_ID, [1, 3, 26052, 29013])) // Jonathan, Alain
                        || ($prog['owner'] == 'at' && in_array(USER_ID, [1, 3, 28722])) // Hieu, Nguyen
                        ) { ?>
                    <td class="text-nowrap">
                        <?= Html::a('<i class="fa fa-edit"></i>', '/tm/u/'.$prog['id'], ['class'=>'text-muted']) ?>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', '/tm/d/'.$prog['id'], ['class'=>'text-danger']) ?>
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