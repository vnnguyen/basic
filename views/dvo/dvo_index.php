<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_cp_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_breadcrumbs'][] = ['Chi phí'];

Yii::$app->params['page_title'] = 'Chi phí dịch vụ ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'dollar';

$ppList = [
    25=>'25/trang',
    50=>'50/trang',
    100=>'100/trang',
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('status', $status, $cpStatusList, ['class'=>'form-control', 'prompt'=>'- Trạng thái -']) ?>
                <?= Html::dropdownList('type', $type, $cpTypeList, ['class'=>'form-control', 'prompt'=>'- Loại cp -']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Tìm theo tên cp']) ?>
                <?= Html::textInput('venue', $venue, ['class'=>'form-control', 'placeholder'=>'Tìm theo điểm/NCC']) ?>
                <?= Html::textInput('tk', $tk, ['class'=>'form-control', 'placeholder'=>'Tìm theo TK']) ?>
                <?= Html::dropdownList('per-page', $pp, $ppList, ['class'=>'form-control']) ?>
                
                <?//= Html::textInput('via', $via, ['class'=>'form-control', 'placeholder'=>'Tìm theo NPP']) ?>
                <?//= Html::textInput('date', $date, ['class'=>'form-control', 'placeholder'=>'yyyy-mm-dd']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/cp') ?>
            </form>
        </div>
        <form method="post" class="form-inline">
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectall"></th>
                        <th>L</th>
                        <th>Tên</th>
                        <th>Đvị</th>
                        <th>Search</th>
                        <th>TK</th>
                        <th>Đặt</th>
                        <th>Trả</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theCpx as $cp) {
                        /*
                        $cp['name'] = str_replace([
                            '(( D ))', '(( C ))', '(( P ))'
                            ], [
                            Html::a($cp['venue']['name'], '/venues/r/'.$cp['venue']['id']),
                            Html::a($cp['byCompany']['name'], '/companies/r/'.$cp['byCompany']['id']),
                            Html::a($cp['viaCompany']['name'], '/companies/r/'.$cp['viaCompany']['id']),
                            ], $cp['name']
                            );*/
                        $cp['name'] = str_replace([
                            '{d}', '{p}'
                            ], [
                            '<span class="text-info">'.$cp['venue']['name'].'</span>',
                            '<span class="text-warning">'.$cp['byCompany']['name'].'</span>',
                            ], $cp['name']
                            );
                        ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="cpid" name="cpid[]" value="<?= $cp['id'] ?>">
                        </td>
                        <td><i title="<?= $cpTypeList[$cp['stype']] ?? 'Unknown' ?>" class="text-muted fa fa-fw fa-<?= $cpTypeIconList[$cp['stype']] ?? 'dollar' ?>"></i></td>
                        <td>
                            <? if ($cp['is_dependent'] == 'yes') { ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <? } ?>
                            <i class="fa fa-circle <?= $cp['status'] == 'on' ? 'text-success' : 'text-muted' ?>"></i>
                            <? if ($cp['note'] != '') { ?>
                            <i class="fa fa-info-circle pull-right" title="<?= Html::encode($cp['note']) ?>"></i>
                            <? } ?>
                            <?= $cp['grouping'] != '' ? $cp['grouping'].' &middot; ' : '' ?>
                            <?= Html::a(Yii::t('cp', $cp['name']), '@web/cp/r/'.$cp['id']) ?>
                            <? if ($cp['venue_id'] != 0) { ?><?= Html::a($cp['venue']['name'], '/venues/r/'.$cp['venue']['id'], ['class'=>'text-warning']) ?><? } ?>
                            <? if ($cp['venue_id'] == 0 && $cp['by_company_id'] != 0) { ?><?= Html::a($cp['byCompany']['name'], '/companies/r/'.$cp['byCompany']['id'], ['class'=>'text-danger']) ?><? } ?>
                        </td>
                        <td><?= $cp['unit'] ?></td>
                        <td><?= $cp['search'] ?></td>
                        <td><?= $cp['tk'] ?></td>
                        <td class="text-nowrap">
                            <?= $cp['booking_conds'] ?>
                        </td>
                        <td><?= $cp['payment_conds'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            Với (<span id="countselected">0</span>) mục đã chọn:
            <select class="form-control" name="status" style="width:150px;">
                <option value="">- Trạng thái -</option>
                <option value="on">OK dữ liệu</option>
                <option value="draft">Chưa OK dữ liệu</option>
                <option value="off">Không dùng</option>
                <option value="deleted">Xoá</option>
            </select>
            <?= Html::dropdownList('stype', '', $cpTypeList, ['class'=>'form-control', 'style'=>'width:150px', 'prompt'=>'- Loại cp -']) ?>
            <input type="text" class="form-control" name="grouping" style="width:100px;" value="" placeholder="Nhóm">
            <input type="text" class="form-control" name="unit" style="width:100px;" value="" placeholder="Đơn vị">
            <input type="text" class="form-control" name="tk" style="width:100px;" value="" placeholder="Tài khoản">
            <?= Html::submitButton('Ghi', ['class'=>'btn btn-primary']) ?>
        </div>
        </form>

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
<?
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$js = <<<'TXT'
$('input[name="date"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD',
      cancelLabel: 'Clear',
    },
    singleDatePicker: true,
    showDropdowns: true
});

$("input.cpid").on('click', function(){
    $("#countselected").html($('input.cpid:checked').length);
});

$("#selectall").on('click', function(){
    $('input.cpid').not(this).prop('checked', this.checked);
    $("#countselected").html($('input.cpid:checked').length);
});

TXT;
$this->registerJs($js);