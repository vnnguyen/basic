<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_dvg_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', '#'];


Yii::$app->params['page_title'] = 'Các chi phí dịch vụ ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'dollar';

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::textInput('name', '', ['class'=>'form-control', 'placeholder'=>'Tìm theo tên dvg']) ?>
                <?= Html::textInput('dv_id', $dv_id, ['class'=>'form-control', 'placeholder'=>'Tìm theo dv']) ?>
                |
                <?//= Html::textInput('via', $via, ['class'=>'form-control', 'placeholder'=>'Tìm theo NPP']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/dvg') ?>
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
                    <? foreach ($theDvgx as $dvg) { ?>
                    <tr>
                        <td>
                            <?= Html::a($dvg['dv']['name'], '/dv/r/'.$dvg['dv']['id']) ?>
                            <? if ($dvg['dv']['venue_id'] != 0) { ?><?= Html::a($dvg['dv']['venue']['name'], '/venues/r/'.$dvg['dv']['venue']['id'], ['class'=>'text-warning']) ?><? } ?>
                            <? if ($dvg['dv']['by_company_id'] != 0) { ?><?= Html::a($dvg['dv']['company']['name'], '/companies/r/'.$dvg['dv']['company']['id'], ['class'=>'text-danger']) ?><? } ?>
                        </td>
                        <td><?= $dvg['dv']['unit'] ?></td>
                        <td><?= $dvg['name'] ?></td>
                        <td><?= $dvg['from_dt'] ?></td>
                        <td><?= $dvg['until_dt'] ?></td>
                        <td class="text-right text-nowrap">
                            <?
                            $price = str_replace('.00', '', number_format($dvg['price'], 2));
                            //$price = rtrim($price, '0');
                            ?>
                            <?= Html::a($price, '/dvg/r/'.$dvg['id']) ?>
                            <span class="text-light text-muted"><?= $dvg['currency'] ?></span>
                        </td>
                        <td><?= $dvg['info'] ?></td>
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
