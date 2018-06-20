<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_cpo_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', '#'];


Yii::$app->params['page_title'] = 'Giá các chi phí dịch vụ ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'dollar';

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::textInput('name', '', ['class'=>'form-control', 'placeholder'=>'Tìm theo tên cpo']) ?>
                <?= Html::textInput('dvo_id', $dvo_id, ['class'=>'form-control', 'placeholder'=>'Tìm theo cp']) ?>
                |
                <?//= Html::textInput('via', $via, ['class'=>'form-control', 'placeholder'=>'Tìm theo NPP']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/cpo') ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th>Chi phí</th>
                        <th>Đvị</th>
                        <th>Loại giá</th>
                        <th>Từ ngày</th>
                        <th>Đến ngày</th>
                        <th>Giá tiền</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theCpox as $cpo) { ?>
                    <tr>
                        <td>
                            <?= Html::a($cpo['dvo']['name'], '/dvo/r/'.$cpo['dvo']['id']) ?>
                            <? if ($cpo['dvo']['venue_id'] != 0) { ?><?= Html::a($cpo['dvo']['venue']['name'], '/venues/r/'.$cpo['dvo']['venue']['id'], ['class'=>'text-warning']) ?><? } ?>
                            <? if ($cpo['dvo']['by_company_id'] != 0) { ?><?= Html::a($cpo['dvo']['company']['name'], '/companies/r/'.$cpo['dvo']['company']['id'], ['class'=>'text-danger']) ?><? } ?>
                        </td>
                        <td><?= $cpo['dvo']['unit'] ?></td>
                        <td><?= $cpo['name'] ?></td>
                        <td><?= $cpo['from_dt'] ?></td>
                        <td><?= $cpo['until_dt'] ?></td>
                        <td class="text-right text-nowrap">
                            <?
                            $price = str_replace('.00', '', number_format($cpo['price'], 2));
                            //$price = rtrim($price, '0');
                            ?>
                            <?= Html::a($price, '/cpo/r/'.$cpo['id']) ?>
                            <span class="text-light text-muted"><?= $cpo['currency'] ?></span>
                        </td>
                        <td><?= $cpo['info'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>

        <? if ($pagination->pageSize < $pagination->totalCount) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]) ?>
        </div>
        <? } ?>

    </div>
</div>
