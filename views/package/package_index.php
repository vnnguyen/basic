<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_package_inc.php');

?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-condensed table-striped">
            <thead>
                <tr>
                    <th width="">Name</th>
                    <th width="40" class="text-nowrap">Link cpt</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody>
                <? if (empty($thePackages)) { ?><tr><td colspan="7">No data found.</td></tr><? } ?>
                <? foreach ($thePackages as $package) { ?>
                <tr>
                    <td><?= Html::a($package['name'], '@web/packages/r/'.$package['id'])?></td>
                    <td><?= $package['link'] == '' ? '' : Html::a('Link cpt', $package['link'])?></td>
                    <td><?= Html::a('Edit', '@web/packages/u/'.$package['id'])?></td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <div class="text-center">
    <?= LinkPager::widget(array(
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ));?>
    </div>
</div>
