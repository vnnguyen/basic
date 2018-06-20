<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_tourguides_inc.php');

Yii::$app->params['page_title'] = 'Sửa mã NCC của các tour guide';
Yii::$app->params['page_icon'] = 'user';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tour guides', 'tourguides'],
    ['Mã NCC'],
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Tour guides</h6>
        </div>
        <div class="panel-body">
            <form class="form-inline" action="">
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Tên tour guide / mã NCC']) ?>
                <?= Html::textInput('region', $region, ['class'=>'form-control', 'placeholder'=>'Tên vùng miền']) ?>
                <?= Html::dropdownList('blank', $blank, [
                    ''=>'Mã NCC',
                    'yes'=>'Có mã NCC',
                    'no'=>'Không có mã NCC',
                ], ['class'=>'form-control']) ?>
                <?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?>
            </form>
            <? if (empty($theTourguides)) { ?>
            <p class="text-danger">No tourguides found.</p>
            <? } ?>
        </div>
        <? if (!empty($theTourguides)) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Họ hên</th>
                        <th>Giới</th>
                        <th>Birthday</th>
                        <th>Mobile</th>
                        <th class="text-nowrap">Mã NCC</th>
                        <th>Tiếng</th>
                        <th>Vùng</th>
                        <th>Loại tour</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theTourguides as $tourguide) { ?>
                    <tr>
                        <td class="text-nowrap"><?= Html::a($tourguide['fname'].' '.$tourguide['lname'], '/tourguides/r/'.$tourguide['id']) ?></td>
                        <td><?= $tourguide['gender'] ?></td>
                        <td><?= $tourguide['bday'] ?>/<?= $tourguide['bmonth'] ?>/<?= $tourguide['byear'] ?></td>
                        <td><?= $tourguide['phone'] ?></td>
                        <td class="text-nowrap"><a class="ma_ncc" data-name="ma_ncc" data-type="text" data-pk="<?= $tourguide['id'] ?>" data-url="/tourguides/ncc?action=ncc" data-title="Sửa mã NCC" title="Sửa mã NCC"><?= $tourguide['ma_ncc'] ?></a></td>
                        <td><?= $tourguide['languages'] ?></td>
                        <td><?= $tourguide['regions'] ?></td>
                        <td><?= $tourguide['tour_types'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <? if ($pagination->pageSize < $pagination->totalCount) { ?>
        <div class="panel-body text-center">
        <?=LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]);?>
        </div>
        <? } ?>
        <? } ?>
    </div>
</div>
<?

$js = <<<'TXT'
$('a.ma_ncc').editable();
TXT;

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);