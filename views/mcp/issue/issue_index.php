<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_issue_inc.php');


?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('project', '', $projectList, ['class'=>'form-control', 'prompt'=>'All projects']) ?>
                <?= Html::dropdownList('type', '', $categoryList, ['class'=>'form-control', 'prompt'=>'All types']) ?>
                <?= Html::dropdownList('status', '', $statusList, ['class'=>'form-control', 'prompt'=>'All status']) ?>
                <?= Html::dropdownList('assigned_to', '', [USER_ID=>'Me'], ['class'=>'form-control', 'prompt'=>'Assigned to']) ?>
                <?= Html::textInput('name', '', ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
                <?= Html::dropdownList('duedate', '', ['a'=>'Overdue'], ['class'=>'form-control', 'prompt'=>'All due dates']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/mcp/issues') ?>
            </form>
        </div>
        <? if (empty($theIssues)) { ?>
        <table class="table table-bordered table-condensed">
            <tr><td class="text-danger">No data found.</td></tr>
        </table>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table xtable-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Subject</th>
                        <th>Sent by</th>
                        <th>Due date</th>
                        <th>%</th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    foreach ($theIssues as $issue) { ?>
                    <tr>
                        <td class="text-muted"><?= $issue['id'] ?>  </td>
                        <td><?= $projectList[$issue['project_id']] ?? $issue['project_id'] ?></td>
                        <td><?= $categoryList[$issue['category']] ?? $issue['category'] ?></td>
                        <td><?= Html::a($statusList[$issue['status']] ?? $issue['status'], '/mcp/issues?status='.$issue['status']) ?></td>
                        <td>
                            <? if ($issue['is_priority'] == 'yes') { ?><i class="fa fa-star text-pink"></i><? } ?>
                            <?= Html::a($issue['name'], '/mcp/issues/r/'.$issue['id']) ?>
                            <i class="fa fa-info-circle popovers text-muted" data-trigger="hover" data-title="" data-placement="right" data-html="true" data-content="<?= Html::encode($issue['body']) ?>" data-original-title="" title=""></i>
                        </td>
                        <td class="text-center"><?= $issue['createdBy']['name'] ?></td>
                        <td class="text-center"><?= date(DFM, strtotime($issue['due_date'])) ?></td>
                        <td class="text-center"><?= $issue['pct_complete'] ?>%</td>
                        <td><?= Html::a('<i class="fa fa-edit"></i>', '/mcp/issues/u/'.$issue['id'], ['class'=>'text-muted']) ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>

        <? if ($pagination->pageSize < $pagination->totalCount) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]) ?>
        </div>
        <? } ?>

        <? } // empty fields ?>
    </div>
</div>
