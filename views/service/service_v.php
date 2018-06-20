<?
use yii\helpers\Html;
$this->title = Yii::t('app', 'Service detail #'. $theService['id']);
?>
<div class="col-md-6">
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
            <tr><td width="20%"><?= Yii::t('app', 'Tour code')?></td><td><?= $theService['code']?></td></tr>
            <tr><td><?= Yii::t('app', 'Service')?></td><td><?= $theService['sv']?></td></tr>
            <tr><td><?= Yii::t('app', 'Context')?></td><td><?= $theService['context']?></td></tr>
            <tr><td><?= Yii::t('app', 'Cost')?></td><td><?= $theService['cp']?></td></tr>
            <tr><td><?= Yii::t('app', 'Result')?></td><td><?= $theService['result']?></td></tr>
        </tbody>
    </table>
</div>
</div>