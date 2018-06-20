<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_group_inc.php');

$this->title = 'Group: '.$theGroup['name'];
$this->params['breadcrumb'][] = ['User groups', 'groups/user'];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">About this group</h6>
        </div>
        <div class="panel-body">
            
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Members of this group</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theGroup['members'] as $user) { ?>
                    <tr>
                        <td><?= $user['fname'] ?> <?= $user['lname'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= Html::a('Remove', '?action=remove&member='.$user['id']) ?></td>
                    </tr>
                    <? } ?>
        
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <?= Html::a('Add more members', '#') ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Group permissions</h6>
        </div>
        <div class="panel-body">
            <p>People in this group can:</p>
            <ul>
                <li>Do this</li>
                <li>Do that...</li>
            </ul>
        </div>
    </div>
</div>