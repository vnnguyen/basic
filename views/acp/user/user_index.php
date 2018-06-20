<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_user_inc.php');

?>
<style type="text/css">
.table-condensed td, .table-condensed th {padding:8px!important;}
</style>
<div class="col-md-12">
    <form class="form-inline panel-search">
    <?= Html::dropdownList('status', $status, ['on'=>Yii::t('a', 'Active'), 'off'=>Yii::t('a', 'Suspended')], ['class'=>'form-control']) ?>
    <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
    <?= Html::textInput('email', $email, ['class'=>'form-control', 'placeholder'=>'Search email']) ?>
    <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Reset'), '@web/acp/users') ?>
    </form>
    <div class="panel panel-default">
        
    <? if (empty($theUsers)) { ?><div class="panel-body"><?= Yii::t('app', 'No data found.') ?></div><? } else { ?>

    <div class="table-responsive">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('a', 'Name') ?></th>
                    <th><?= Yii::t('a', 'Teams') ?></th>
                    <th><?= Yii::t('a', 'Tel') ?></th>
                    <th><?= Yii::t('a', 'Email') ?></th>
                    <th><?= Yii::t('a', 'Edit') ?></th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($theUsers as $user) { ?>
                <tr>
                    <td>
                        <? if ($user['status'] == 'off') { ?><span class="pull-right label label-danger">Inactive</span><? } ?>
                        <?= Html::img('/timthumb.php?w=100&h=100&src='.$user['image'], ['class'=>'img-circle position-left', 'style'=>'width:42px; height:42px']) ?>
                        <?= Html::a($user['name'], '@web/acp/users/r/'.$user['id']) ?>
                    </td>
                    <td><?
                        $teams = [];
                        // foreach ($user['teams'] as $team) {
                        //     $teams[] = Html::a(Yii::t('team', $team['name']), '/acp/teams/r/'.$team['id']);
                        // }
                        echo implode(', ', $teams);
                     ?></td>
                    <td><?= $user['phone'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <!--td><?= $user['id'] ?></td-->
                    <td>
                        <?= Html::a(Yii::t('a', 'Edit'), '@web/acp/users/u/'.$user['id']) ?>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>

    <? if ($pagination->pageSize < $pagination->totalCount) { ?>
    <div class="panel-footer text-center">
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]) ?>
    </div>
    <? } ?>

    <? } ?>
    </div>
</div>
