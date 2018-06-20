<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_dvc_inc.php');

Yii::$app->params['page_title'] = 'Hợp đồng dịch vụ: '.$theDvc['name'].' / '.$theDvc['venue']['name'];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Service contract') ?></h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-xxs">
                <thead>
                    <tr><th width="25%"><?= Yii::t('dv', 'Code') ?></th><td><?= $theDvc['name'] ?></td></tr>
                </thead>
                <tbody>
                    <tr><th><?= Yii::t('dv', 'Number') ?></th><td><?= $theDvc['number'] ?></td></tr>
                    <tr><th><?= Yii::t('dv', 'Date') ?></th><td><?= $theDvc['id'] ?></td></tr>
                    <tr><th><?= Yii::t('dv', 'Service supplier') ?></th><td><?= Html::a($theDvc['venue']['name'], '/venues/r/'.$theDvc['venue']['id']) ?></td></tr>
                    <tr><th><?= Yii::t('dv', 'Validity') ?></th><td><?= $theDvc['id'] ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Definitions') ?></h6>
            <div class="heading-elements">
                <span class="heading-text"><a href="#" class="dvd_add_toggle">Add</a></span>
            </div>
        </div>
        <div class="panel-body dvd_add" style="display:none;">
            <form method="post" action="">
                <?= Html::hiddenInput('action', 'dvd_add') ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('dv', 'Type') ?></label>
                            <?= Html::dropdownList('type', '', ['date'=>'Date', 'conds'=>'Conditions'], ['class'=>'form-control']) ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('dv', 'Code') ?></label>
                            <?= Html::textInput('code', '', ['class'=>'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?= Yii::t('dv', 'Definition') ?></label>
                    <?= Html::textInput('def', '', ['class'=>'form-control']) ?>
                </div>
                <div class="form-group">
                    <label class="control-label"><?= Yii::t('dv', 'Description') ?></label>
                    <?= Html::textInput('desc', '', ['class'=>'form-control']) ?>
                </div>
                <?= Html::submitButton('Add', ['class'=>'btn btn-primary']) ?>
                or <?= Html::a('Cancel', '#', ['class'=>'dvd_add_cancel']) ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-xxs">
                <thead>
                    <tr>
                        <th><?= Yii::t('dv', 'Code') ?></th>
                        <th><?= Yii::t('dv', 'Definition') ?></th>
                        <th><?= Yii::t('dv', 'Description') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theDvc['dvd'] as $dvd) { ?>
                    <tr>
                        <td><?= Html::a($dvd['code'], '/dvd/u/'.$dvd['id']) ?></td>
                        <td><?= $dvd['def'] ?></td>
                        <td><?= $dvd['desc'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Service prices') ?></h6>
            <div class="heading-elements">
                <span class="heading-text"><a href="/cp/c?venue_id=<?= $theDvc['venue']['id'] ?>&dvc_id=<?= $theDvc['id'] ?>">Add</a></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-xxs">
                <thead>
                    <tr>
                        <th><?= Yii::t('dv', 'Service') ?></th>
                        <th><?= Yii::t('dv', 'Period') ?></th>
                        <th><?= Yii::t('dv', 'Conditions') ?></th>
                        <th><?= Yii::t('dv', 'Price') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theDvc['cp'] as $cp) { ?>
                    <tr>
                        <td><?= $cp['dv']['name'] ?></td>
                        <td><?= $cp['period'] ?></td>
                        <td><?= $cp['conds'] ?></td>
                        <td class="text-right text-nowrap">
                            <?= Html::a(number_format($cp['price'], intval($cp['price']) == $cp['price'] ? 0 : 2), '/cp/u/'.$cp['id']) ?>
                            <span class="text-muted"><?= $cp['currency'] ?></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('dv', 'Related contracts') ?></h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th><?= Yii::t('dv', 'Name') ?></th>
                        <th><?= Yii::t('dv', 'Description') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($relatedDvcx as $dvc) { ?>
                    <tr>
                        <td><?= Html::a($dvc['name'], '/dvc/r/'.$dvc['id'], ['class'=>$dvc['id'] == $theDvc['id'] ? 'text-bold' : '' ]) ?></td>
                        <td><?= $dvc['description'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?
$js = <<<'TXT'
$('a.dvd_add_toggle, a.dvd_add_cancel').on('click', function(){
    $('div.dvd_add').toggle();
    return false;
})
TXT;
$this->registerJs($js);