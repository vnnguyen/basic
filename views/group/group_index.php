<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_group_inc.php');

$this->title = 'Groups';
if (isset($stype)) {
    $this->params['breadcrumb'][] = [$groupTypeList[$stype], 'groups/'.$stype];
}

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"></h6>
        </div>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Name of group</th>
                    <th>Alias</th>
                    <th>Members</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($theGroups as $group) { ?>
                <tr>
                    <td class="text-center text-muted"><?= $group['id'] ?></td>
                    <td><?= Html::a($group['name'], '/groups/r/'.$group['id']) ?></td>
                    <td><?= $group['alias'] ?></td>
                    <td><?//= $group['stype'] ?></td>
                </tr>
                <? } ?>         
            </tbody>
        </table>
    </div>
</div>
