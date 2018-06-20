<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$this->params['actions'] = [
    [
        ['icon'=>'plus', 'label'=>'New service', 'link'=>'service/c', 'active'=>SEG2 == 'c'],
    ],
];
$this->title = Yii::t('app', 'Services plus');

?>
<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-narrow table-striped">
            <thead>
                <tr>
                    <th width="150"><?= Yii::t('app', 'Tour code')?></th>
                    <th><?= Yii::t('app', 'Service')?></th>
                    <th><?= Yii::t('app', 'Cost')?></th>
                    <th><?= Yii::t('app', 'Result')?></th>
                    <th width="150"><?= Yii::t('app', 'Actions')?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($theServices as $theService) { ?>
                <tr>
                    <td><?= $theService['code']?></td>
                    <td><?= $theService['sv']?></td>
                    <td><?= $theService['cp']?></td>
                    <td><?= $theService['result']?></td>
                    <td>
                        <?= Html::a('edit', '/service/u/'. $theService['id']);?>
                        <?= Html::a('delete', '/service/d/'. $theService['id']);?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>
<? if ($pages->pageSize < $pages->totalCount) { ?>
    <div class="panel-footer text-center">
    <?= LinkPager::widget([
        'pagination' => $pages,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]);?>
    </div>
<? } ?>