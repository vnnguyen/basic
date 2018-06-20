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
            <table class="table table-xxs table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Địa điểm / Nhà cung cấp</th>
                        <th>Tên HĐ</th>
                        <th>File</th>
                        <th>Thời hạn</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theDvcx as $dvc) { ?>
                    <tr>
                        <td><?= Html::a($dvc['venue']['name'], '/venues/r/'.$dvc['venue']['id']) ?></td>
                        <td>

                            <?= Html::a($dvc['name'], '/dvc/r/'.$dvc['id']) ?>
                            <? if (in_array(USER_ID, [1, 9198])) { ?>
                            - <?= Html::a('Sửa', '/dvc/u/'.$dvc['id'], ['class'=>'text-muted']) ?>
                            <? } ?>                
                        </td>
                        <td><?
                        $uploadDir = Yii::getAlias('@webroot').'/upload/dvc/'.substr($dvc['created_dt'], 0, 7).'/'.$dvc['id'];
                        if (file_exists($uploadDir)) {
                            $uploadFiles = \yii\helpers\FileHelper::findFiles($uploadDir);
                            if (!empty($uploadFiles)) {
                                foreach ($uploadFiles as $file) {
                                    ?><?= Html::a('<i class="fa fa-paperclip text-muted"></i>', str_replace(Yii::getAlias('@webroot'), Yii::getAlias('@web'), $file).'#/dvc/r/'.$dvc['id'].'?action=download&file='.substr(strrchr($file, '/'), 1), ['title'=>substr(strrchr($file, '/'), 1)]) ?><?
                                }
                            }
                        }

                        ?></td>
                        <? if ($dvc['valid_from_dt'] == ZERO_DT || $dvc['valid_until_dt'] == ZERO_DT) { ?>
                        <td></td>
                        <? } else { ?>
                        <td><?= date('j/n/Y', strtotime($dvc['valid_from_dt'])) ?> - <?= date('j/n/Y', strtotime($dvc['valid_until_dt'])) ?></td>
                        <? } ?>
                        <td><?= $dvc['updatedBy']['name'] ?> <?= Yii::$app->formatter->asRelativetime($dvc['updated_dt']) ?></td>
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
