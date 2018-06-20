<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_issue_inc.php');

Yii::$app->params['page_title'] = Yii::t('d', 'Issue').' #'.$theIssue['id'].' : '.$theIssue['name'];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= $theIssue['createdBy']['nickname'] ?> <small><?= Yii::$app->formatter->asRelativetime($theIssue['created_dt']) ?></small></h6>
            <div class="heading-elements">
                <ul class="heading-thumbnails">
                    <li><a href="#"><img src="/timthumb.php?w=100&h=100&src=<?= $theIssue['createdBy']['image'] ?>" alt=""></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-condensed">
            <tbody>
                <tr>
                    <th><?= Yii::t('d', 'Project') ?></th><td><?= $projectList[$theIssue['project_id']] ?? $theIssue['project_id'] ?></td>
                    <th><?= Yii::t('d', 'Milestone') ?></th><td><?= $theIssue['milestone'] ?></td>
                </tr>
                <tr>
                    <th><?= Yii::t('d', 'Type') ?></th><td><?= $categoryList[$theIssue['category']] ?? $theIssue['category'] ?></td>
                    <th><?= Yii::t('d', 'Assigned to') ?></th><td><?= $theIssue['assignedTo']['name'] ?></td>
                </tr>
                <tr>
                    <th><?= Yii::t('d', 'Start date') ?></th><td><?= date(DFM, strtotime($theIssue['start_date'])) ?></td>
                    <th><?= Yii::t('d', 'Due date') ?></th><td><?= date(DFM, strtotime($theIssue['due_date'])) ?></td>
                </tr>
                <tr>
                    <th><?= Yii::t('d', 'Status') ?></th><td><?= $theIssue['status'] == 'on' ? '<span class="text-success">'.Yii::t('d', 'Active').'</span>' : Yii::t('d', 'Inactive') ?></td>
                    <th><?= Yii::t('d', 'Last update') ?></th><td><?= date(DFM, strtotime($theIssue['updated_dt'])) ?></td>
                </tr>
            </tbody>
        </table>
        <div class="panel-body">
            <?= $theIssue['body'] ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('d', 'Notes & Comments') ?></h6>
        </div>
        <div class="panel-body">
            <ul class="media-list media-list-bordered">
            <?
            if (!empty($theMessages)) {
                foreach ($theMessages as $message) { ?>
                <li class="media">
                    <div class="media-left">
                        <a href="#"><img src="<?= $message['updatedBy']['image'] ?>" class="img-circle" alt=""></a>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading"><?= $message['updatedBy']['name'] ?> <span class="media-annotation dotted"><?= Yii::$app->formatter->asRelativeTime($message['uo']) ?></span></h6>
                        <?= $message['body'] ?>
                        <? if (in_array(USER_ID, [$message['uo'], $message['ub']])) { ?>
                        <ul class="list-inline mt-5">
                            <li>153 <a href="#"><i class="icon-arrow-up22 text-success"></i></a><a href="#"><i class="icon-arrow-down22 text-danger"></i></a></li>
                            <li><a href="#">Reply</a></li>
                            <li><a href="#">Edit</a></li>
                        </ul>
                        <? } ?>
                    </div>
                </li><?
                }
            } ?>
                <li class="media">
                    <div class="media-left">
                        <a href="#"><img src="<?= Yii::$app->user->identity->image ?>" class="img-circle" alt=""></a>
                    </div>
                    <div class="media-body">
                        <? $form = ActiveForm::begin() ?>
                        <?= $form->field($theMessage, 'body')->textArea(['rows'=>5])->label(Yii::t('d', 'Add your note/message')) ?>
                        <?= Html::submitButton(Yii::t('d', 'Post your note'), ['class'=>'btn btn-default']) ?>
                        <? ActiveForm::end() ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-4">
    
</div>
<?

\app\assets\CkeditorAsset::register($this);
\app\assets\CkfinderAsset::register($this);
$this->registerJs(\app\assets\CkeditorAsset::ckeditorJs('#message-body', 'basic'));