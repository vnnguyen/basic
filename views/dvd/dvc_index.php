<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_dvc_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', 'dv'];
Yii::$app->params['page_breadcrumbs'][] = ['Hợp đồng', 'dvc'];


Yii::$app->params['page_title'] = 'Các hợp đồng dịch vụ ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'file-text-o';

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::textInput('name', '', ['class'=>'form-control', 'placeholder'=>'Tìm theo tên dvc']) ?>
                <?= Html::textInput('venue_id', 0, ['class'=>'form-control', 'placeholder'=>'Tìm theo dv']) ?>
                |
                <?//= Html::textInput('via', $via, ['class'=>'form-control', 'placeholder'=>'Tìm theo NPP']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/dvc') ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th>Địa điểm / Nhà cung cấp</th>
                        <th>Tên HĐ</th>
                        <th>Số HĐ</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theDvcx as $dvc) { ?>
                    <tr>
                        <td><?= Html::a($dvc['venue']['name'], '/venues/r/'.$dvc['venue']['id']) ?></td>
                        <td><?= Html::a($dvc['name'], '/dvc/r/'.$dvc['id']) ?></td>
                        <td><?= $dvc['number'] ?></td>
                        <td><?= $dvc['note'] ?></td>
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
