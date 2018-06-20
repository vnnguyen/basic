<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Markdown;

include('_dvo_inc.php');

$this->title = $theDvo['name'];
if ($theDvo['venue_id'] != 0) {
    $this->title .= ' @'.Html::a($theDvo['venue']['name'], '@web/venues/r/'.$theDvo['venue_id']);
}

?>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">The cost</h6>
        </div>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($theDvo as $k=>$v) { ?>
                <tr>
                    <td><?= $k ?></td>
                    <td><?
                    if ($k == 'company_id') {
                        if ($v == 0) {
                            echo $v;
                        } else {
                            echo Html::a($theDvo['company']['name'], '@web/companies/r/'.$v, ['rel'=>'external']);
                        }
                    } elseif ($k == 'venue_id') {
                        if ($v == 0) {
                            echo $v;
                        } else {
                            echo Html::a($theDvo['venue']['name'], '@web/venues/r/'.$v, ['rel'=>'external']);
                        }
                    } else {
                        echo is_array($v) ? implode(', ', []) : nl2br($v);
                    }
                     ?></td>                
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Prices</h6>
        </div>
        <table class="table table-xxs">
            <thead>
                <tr>
                    <th>Thời hạn áp dụng</th>
                    <th>Tên</th>
                    <th>Giá tiền</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($theDvo['cpo'] as $cpo) { ?>
                <tr>
                    <td><?= $cpo['from_dt'] ?> to <?= $cpo['until_dt'] ?></td>
                    <td><?= Html::a($cpo['name'], '@web/cpo/u/'.$cpo['id']) ?></td>
                    <td class="text-right">
                        <?= number_format($cpo['price'],2) ?>
                        <span class="text-muted"><?= $cpo['currency'] ?></span>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Related costs</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($relatedCpx as $cp) { ?>
                    <tr>
                        <td><?= $cp['stype'] ?></td>
                        <td><?= Html::a($cp['name'], '/cp/r/'.$cp['id']) ?></td>
                        <td><?= $cp['unit'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
