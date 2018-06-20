<?php

//myID == 1 || die('HUAN');
include('_day_inc.php');
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
$theDays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$metaT = 'Ngày tour ('.$pg->itemCount.')';
$pageM = 'ct';
$pageB = array(
    anchor('days', 'Ngày tour'),
    // anchor('days/r/'.$theDay['id'], $theDay['name']),
    );
include('__hd.php'); */
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = 'Sample tour days ('.$pagination->totalCount.')';

Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Sample tour days'],
];

?>
<style>
.t_Content_light {font-size: 14px; line-height:1.5}
.t_Tooltip_light {width: 500px!important;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('language', $language, ['en'=>'English', 'fr'=>'Français'], ['class'=>'form-control']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
                <?= Html::textInput('tags', $tags, ['class'=>'form-control', 'placeholder'=>'Search tags']) ?>
                <?= Html::dropdownList('show', $show, ['all'=>'Show all tags', '2015'=>'Tag "2015" only'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('orderby', $orderby, ['name'=>'Order by name', 'updated'=>'Order by update'], ['class'=>'form-control']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), DIR.URI) ?>
            </form>
        </div>
        <div class="table-responsive">
            <table id="tbl-days" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Name & Content</th>
                        <th>Meals</th>
                        <th>Tags</th>
                        <? if (
                        (SEG2 == 'b2b' && in_array(USER_ID, [1, 3, 26052, 29013])) // Jonathan, Alain
                        || (SEG2 == '' && in_array(USER_ID, [1, 3, 28722])) // Hieu, Nguyen
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
                                <p><?= $day['body'] ?></p>
                            </div>
                            <div class="modal-footer text-muted">
                                <i class="fa fa-clock-o"></i> <?= $day['updatedBy']['nickname'] ?> <?= Yii::$app->formatter->asRelativeTime($day['updated_dt']) ?>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <tr data-day="<?= $day['id'] ?>">
                    <td>
                        <? if (in_array(USER_ID, [1, 28722]) && SEG2 != 'b2b') { ?>
                        <i data-day="<?= $day['id'] ?>" class="nouse cursor-pointer fa fa-ban text-danger" title="NoUse"></i>
                        <? } ?>
                        <a href="#/nm/r/<?= $day['id'] ?>" class="popovers"
                            data-placement="right"
                            data-trigger="hover"
                            data-html="true"
                            data-title="<?= Html::encode($day['title']) ?> (<?=$day['meals'] ?>)"
                            data-content="<?= Html::encode($day['body']) ?>"
                            data-toggle="modal" data-target="#nm<?= $day['id'] ?>"><?= $day['title'] ?></a>
                        <? if (USER_ID == 1) { ?>
                        <a title="Add day after" class="text-danger" href="/nm/c?after=<?= $day['id'] ?>">+</a>
                        <? } ?>
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
                        || ($day['owner'] == 'at' && in_array(USER_ID, [1, 3, 28722])) // Hieu, Nguyen
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
    </div>

    <? if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="text-center">
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
<style>
.popover {max-width:500px;}
</style>
<?
$js = <<<'TXT'
// Tipped.create('.tippedx',
//     function(element) {
//         var tipID = $(element).data('tipped');
//         return document.getElementById(tipID);
//     }, {
//     inline: true,
//     fadeIn: 0,
//     fadeOut: 0,
//     skin: 'light',
//     border: { size: 8, color: '#000', opacity: .4 },
//     radius: { size: 8, position: 'border' },
//     maxWidth: 460,
//     target: 'mouse',
//     fixed: true,
//     hook: { target: 'rightmiddle', tooltip: 'leftmiddle' },
//     shadow: false
//     }
// );

$('.qtip2').each(function() {
    $(this).qtip({
        content: 'My content',
        position: {
            my: 'top left',
            at: 'bottom right',
            target: 'mouse'
        }
        // content: {
        //     text: $(this).next('div')
        // }
    });
});


TXT;
//$this->registerJsFile('/assets/tipped_4.5.7/js/tipped/tipped.js', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerCssFile('/assets/tipped_4.5.7/css/tipped/tipped.css', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerJs($js);

//$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js', ['depends'=>'yii\web\JqueryAsset']);

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
TXT;
$this->registerJs($js);