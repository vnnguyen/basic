<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

include('_message_inc.php');

$this->title = 'Messages ('.number_format($pagination->totalCount).')';

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                Month <?= Html::dropdownList('month', $month, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'All months']) ?>
                <? if ($viewAll) { ?>
                From <?= Html::dropdownList('from', $from, $fromList, ['class'=>'form-control']) ?>
                To <?= Html::dropdownList('to', $to, $toList, ['class'=>'form-control']) ?>
                <? } else { ?>
                From <?= Html::dropdownList('from', $from, [0=>'Anybody', Yii::$app->user->id=>'Me'], ['class'=>'form-control']) ?>
                To <?= Html::dropdownList('to', $to, [0=>'Anybody', Yii::$app->user->id=>'Me'], ['class'=>'form-control']) ?>
                <? } ?>
                Via <?= Html::dropdownList('via', $via, $viaList, ['class'=>'form-control', 'prompt'=>'All']) ?>
                Title <?= Html::textInput('title', $title, ['class'=>'form-control']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/messages') ?>
            </form>
            <? if (empty($theNotes)) { ?>
            <p>No data found.</p>
        </div>
    <? } else { ?>
        </div>
    <div class="table-responsive">
        <table class="table table-xxs table-striped">
            <thead>
                <tr>
                    <th width="80">Time</th>
                    <th>From, Title, To</th>
                    <th>Related to</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($theNotes as $li) { ?>
                <tr>
                    <td class="text-nowrap text-muted"><?= \app\helpers\DateTimeHelper::convert($li['uo'], 'j/n/Y H:i') ?></td>
                    <td>
                        <i class="fa fa-info-circle popovers position-left text-muted"
                            data-trigger="hover"
                            data-title="<?= Html::encode($li['title']) ?>"
                            data-placement="right"
                            data-html="true"
                            data-content="<?= Html::encode($li['body']) ?>"></i>
                        <? if ($li['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
                        <? if ($li['via'] == 'form') { ?><i class="fa fa-desktop"></i><? } ?>
                        <?= Html::a($li['from']['name'], '/users/r/'.$li['from']['id'], ['class'=>'text-semibold text-brown'])?>
                        <?= Html::a($li['title'] == '' ? '( No title )' : $li['title'], '/messages/r/'.$li['id']) ?>
                        <?= count($li['files']) != 0 ? '<i class="fa fa-paperclip"></i>'.count($li['files']) : '' ?>
                        <?
                        if ($li['to']) {
                            echo ' &rarr; ';
                            $cnt = 0;
                            foreach ($li['to'] as $to) {
                                $cnt ++;
                                if ($cnt != 1) echo ', ';
                                echo Html::a($to['name'], '/users/r/'.$to['id'], ['class'=>'text-purple']);
                            }
                        }
                        ?>
                    </td>
                    <td class="text-nowrap"><?
                    if ($li['rtype'] == 'case' && $li['relatedCase']) {
                        echo '<i class="text-muted fa fa-briefcase"></i> '.Html::a($li['relatedCase']['name'], 'cases/r/'.$li['relatedCase']['id']);
                    }
                    if ($li['rtype'] == 'tour' && $li['relatedTour']) {
                        echo '<i class="text-muted fa fa-truck"></i> '.Html::a($li['relatedTour']['code'].' - '.$li['relatedTour']['name'], 'tours/r/'.$li['relatedTour']['id']);
                    }
                    ?></td>
                    <td class="text-nowrap">
                        <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="/messages/u/<?= $li['id'] ?>"><i class="fa fa-edit"></i></a>
                        <a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="/messages/d/<?= $li['id'] ?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
            <? if ($pagination->totalCount > $pagination->pageSize) { ?>
            <div class="panel-body text-center">
            <?= LinkPager::widget([
                'pagination' => $pagination,
                'firstPageLabel' => '<<',
                'prevPageLabel' => '<',
                'nextPageLabel' => '>',
                'lastPageLabel' => '>>',
                ]
            )
            ?>
            <? } ?>
            </div>
    <? } ?>
    </div>
</div>
<style type="text/css">
.popover {max-width:600px;}
</style>