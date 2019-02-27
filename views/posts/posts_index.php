<?php
use app\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

include('_posts_inc.php');

Yii::$app->params['page_title'] = 'Messages ('.number_format($pagination->totalCount).')';

?>
<div class="col-md-12">
    <form class="form-inline mb-2">
        Month <?= Html::dropdownList('month', $month, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'All months']) ?>
        <?php if ($viewAll) { ?>
        From <?= Html::dropdownList('from', $from, $fromList, ['class'=>'form-control']) ?>
        To <?= Html::dropdownList('to', $to, $toList, ['class'=>'form-control']) ?>
        <?php } else { ?>
        From <?= Html::dropdownList('from', $from, [0=>'Anybody', Yii::$app->user->id=>'Me'], ['class'=>'form-control']) ?>
        To <?= Html::dropdownList('to', $to, [0=>'Anybody', Yii::$app->user->id=>'Me'], ['class'=>'form-control']) ?>
        <?php } ?>
        Via <?= Html::dropdownList('via', $via, $viaList, ['class'=>'form-control', 'prompt'=>'All']) ?>
        Title <?= Html::textInput('title', $title, ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <?php if (empty($thePosts)) { ?>
    <div class="text-danger"><?= Yii::t('x', 'No data found.') ?></div>
    <?php } else { ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="80">Time</th>
                        <th>From, Title, To</th>
                        <th>Related to</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($thePosts as $post) { ?>
                    <tr>
                        <td class="text-nowrap text-muted"><?= \app\helpers\DateTimeHelper::convert($post['uo'], 'j/n/Y H:i') ?></td>
                        <td>
                            <i class="fa fa-info-circle popovers position-left text-muted"
                                data-trigger="hover"
                                data-title="<?= Html::encode($post['title']) ?>"
                                data-placement="right"
                                data-html="true"
                                data-content="<?= Html::encode($post['body']) ?>"></i>
                            <?php if ($post['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><?php } ?>
                            <?php if ($post['via'] == 'form') { ?><i class="fa fa-desktop"></i><?php } ?>
                            <?= Html::a($post['from']['name'], '/users/'.$post['from']['id'], ['class'=>'text-semibold text-brown'])?>
                            <?= Html::a($post['title'] == '' ? '( No title )' : $post['title'], '/posts/'.$post['id']) ?>
                            <?= count($post['files']) != 0 ? '<i class="fa fa-paperclip"></i>'.count($post['files']) : '' ?>
                            <?
                            if ($post['to']) {
                                echo ' &rarr; ';
                                $cnt = 0;
                                foreach ($post['to'] as $to) {
                                    $cnt ++;
                                    if ($cnt != 1) echo ', ';
                                    echo Html::a($to['name'], '/users/'.$to['id'], ['class'=>'text-purple']);
                                }
                            }
                            ?>
                        </td>
                        <td class="text-nowrap"><?
                        if ($post['rtype'] == 'case' && $post['relatedCase']) {
                            echo '<i class="text-muted fa fa-briefcase"></i> '.Html::a($post['relatedCase']['name'], 'cases/r/'.$post['relatedCase']['id']);
                        }
                        if ($post['rtype'] == 'tour' && $post['relatedTour']) {
                            echo '<i class="text-muted fa fa-truck"></i> '.Html::a($post['relatedTour']['code'].' - '.$post['relatedTour']['name'], 'tours/r/'.$post['relatedTour']['id']);
                        }
                        ?></td>
                        <td class="text-nowrap">
                            <a title="<?=Yii::t('x', 'Edit')?>" class="text-muted" href="/posts/<?= $post['id'] ?>/u"><i class="fa fa-edit"></i></a>
                            <a title="<?=Yii::t('x', 'Delete')?>" class="text-danger" href="/posts/<?= $post['id'] ?>/d"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="center-h">
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
        ]
    )
    ?>
    </div>
    <?php } ?>

    <?php } // if empty messages ?>
</div>
<style type="text/css">
.popover {max-width:600px;}
</style>