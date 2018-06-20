<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_dvd_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', 'cp'];
Yii::$app->params['page_title'] = 'Edit definition';

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Edit condition definition</h6>
            <div class="heading-elements">
                <span class="heading-text"><a class="text-danger" href="/dvd/d/<?= $theDvd['id'] ?>">Delete</a></span>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Contract</label>
                        <p class="form-control-static"><?= Html::a($theDvd['dvc_id'], '/dvc/r/'.$theDvd['dvc_id']) ?></p>
                    </div>
                </div>
            </div>
            <? $form = ActiveForm::begin(); ?>
            <? if (USER_ID == 1) { ?>
        <div class="table-responsive mb-20">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="sel_all" value=""></th>
                        <th><?= Yii::t('dv', 'Service') ?></th>
                        <th><?= Yii::t('dv', 'Period') ?></th>
                        <th><?= Yii::t('dv', 'Conditions') ?></th>
                        <th><?= Yii::t('dv', 'Price') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theDvc['cp'] as $cp) { ?>
                    <tr>
                        <td><input class="sel" type="checkbox" name="sel[]" value="<?= $cp['id'] ?>" checked="checked"></td>
                        <td><?= $cp['dv']['name'] ?></td>
                        <td class="text-slate"><?= $cp['period'] ?></td>
                        <td class="text-pink"><?= $cp['conds'] ?></td>
                        <td class="text-right text-nowrap">
                            <?= Html::a(number_format($cp['price'], intval($cp['price']) == $cp['price'] ? 0 : 2), '/cp/u/'.$cp['id']) ?>
                            <span class="text-muted"><?= $cp['currency'] ?></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
            <? } ?>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theDvd, 'stype')->dropdownList(['date'=>'Use period', 'conds'=>'Condition'])->label('Type') ?></div>
                <div class="col-md-4"><?= $form->field($theDvd, 'code')->label('Code') ?></div>
            </div>
            <?= $form->field($theDvd, 'def')->label('Definition') ?>
            <?= $form->field($theDvd, 'desc')->label('Description') ?>
            <div>
                <?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?>
                or <?= Html::a('Cancel', '/dvc/r/'.$theDvd['dvc_id']) ?>
            </div>
            <? ActiveForm::end(); ?>            
        </div>
    </div>
</div>
<?
$js = <<<'TXT'
$('input[name="sel_all"]').click(function(){
    $('input:checkbox.sel').prop('checked', this.checked);
});
TXT;

$this->registerJs($js);