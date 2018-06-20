<?
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

include('_supplier_inc.php');

Yii::$app->params['page_title'] = $theSupplier['name'];
Yii::$app->params['page_small_title'] = $theSupplier['search'];


?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Services by this supplier</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>ID</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theSupplier['dv'] as $dv) { ?>
                    <?
                    $dv['name'] = str_replace(
                        [
                            '[', ']',
                            '_s',
                        ], [
                            '<span class="text-pink">', '</span>',
                            '',
                        ], $dv['name']);
                    ?>
                    <tr>
                        <td><?= Html::a($dv['name'], '/dv/r/'.$dv['id']) ?></td>
                        <td><?= $dv['search'] ?></td>
                        <td><?= $dv['id'] ?></td>
                        <td><?= $dv['stype'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-body">
            <p><strong>CONTACT INFORMATION</strong></p>
            <p><?= $theSupplier['name_full'] ?></p>
            <ul>
                <? sort($theSupplier['metas']); foreach ($theSupplier['metas'] as $theMeta) { ?>
                <li><?=$theMeta['k']?>: <?=$theMeta['v']?></li>
                <? } ?>
            </ul>
            <p><strong>GENERAL INFORMATION</strong></p>
            <p><?= nl2br($theSupplier['info'])?></p>

            <p><strong>TAX INFORMATION</strong></p>
            <p><?= nl2br($theSupplier['tax_info'])?></p>

            <p><strong>BANK INFORMATION</strong></p>
            <p><?= nl2br($theSupplier['bank_info'])?></p>
        </div>
    </div>
</div>
