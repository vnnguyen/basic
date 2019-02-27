<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

include('_report_inc.php');

Yii::$app->params['page_icon'] = 'bar-chart';
Yii::$app->params['page_layout'] = '.s';
Yii::$app->params['page_title'] = 'Thống kê ngày tour và mức thưởng điều hành';

// \fCore::expose($result); exit;
$bonusList = [
    'vn-100'=>'Vietnam - 100% (normal) bonus',
    'vn-70'=>'Vietnam - 70% bonus',
    'vn-30'=>'Vietnam - 30% bonus',
    'vn-c'=>'Vietnam - An Hoa',
    'vn-200'=>'Vietnam - 200% bonus',
    'la-100'=>'Laos - 100% (normal) bonus',
    'la-s'=>'Laos - Southern Laos bonus (per tour)',
    'la-s30'=>'Laos - 30% Southern Laos bonus (per tour)',
    'kh-100'=>'Cambodia - normal bonus',
    ''=>'Bonus unknown or not specified',
];

$regionList = [
    'vn-n'=>[
        'name'=>'Vietnam - North',
        'ops'=>[8162, 24820, 42901, 46803, 29212, 15081, 118],
    ],
    'vn-c'=>[
        'name'=>'Vietnam - Central',
        'ops'=>[7915],
    ],
    'vn-s'=>[
        'name'=>'Vietnam - South',
        'ops'=>[37675, 27726, 46046],
    ],
    'la'=>[
        'name'=>'Laos',
        'ops'=>[30554, 9146, 34596, 25727],
    ],
    'kh'=>[
        'name'=>'Cambodia',
        'ops'=>[31399, 19371, 1906],
    ],
];

$codeList = [
    'f'=>'F tours',
    'g'=>'G tours',
];

$this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom border-0 mb-0">
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'dh-02' ? ' active' : '' ?>" href="/reports/dh-02">Số lượng tour</a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'dh-01' ? ' active' : '' ?>" href="/reports/dh-01">Số ngày tour</a></li>
</ul><?php
$this->endBlock();
?>
<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>'Tháng']) ?>
        <?= Html::dropdownList('code', $code, $codeList, ['class'=>'form-control', 'prompt'=>'Code']) ?>
        <? //Html::dropdownList('operator', $operator, $userList, ['class'=>'form-control', 'prompt'=>'Mọi điều hành']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2"><?= Yii::t('dh', 'Operator')?> \ <?= Yii::t('dh', 'Bonus')?></th>
                        <?php foreach ($bonusList as $k=>$v) { ?>
                        <th style="vertical-align:top; width:8%;" class="text-center"><?= strtoupper($k) ?> <sup class="text-info cursor-help" title="<?= $v ?>">?</sup></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($regionList as $region) { ?>
                    <tr class="info">
                        <td colspan="11"><strong><?= $region['name'] ?></strong></td>
                    </tr>
                    <?php
                        foreach ($result as $id=>$user) {
                            if (in_array($id, $region['ops'])) {
                    ?>
                    <tr>
                        <td class="text-nowrap"><?= $user['name'] ?></td>
                        <?php foreach ($bonusList as $k=>$v) { ?>
                        <td class="text-center"><?= isset($user[$k]) && $user[$k] != 0 ? Html::a($user[$k], '/tours?orderby=startdate&time='.$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'&operator='.$id) : '' ?></td>
                        <?php } ?>
                    </tr>
                    <?
                            } // if op
                        } // foreach result
                    } // foreach region
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Info</h4>
      </div>
      <div class="modal-body">
        <div class="content-div">
            <div class="table-responsive">
            <table class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th>Tour code</th>
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody id="modal_tbdy">
                </tbody>
            </table>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?

$js = <<<'TXT'
var data_on_modal = '';
$(document).on('click', '.detail', function(){
    data_on_modal = $(this).closest('tr').data('des');
    $("#myModal").modal('show');
});
$("#myModal").on('show.bs.modal', function () {
    $(this).find('#modal_tbdy').empty();
    var arr_data = data_on_modal.split('--');
    $.each(arr_data, function(i, item){
        var tr = '<tr>';
        var arr_c = item.split('/');

        tr += '<td>' + arr_c[0] + '</td>' + '<td>' + arr_c[1] + '</td>';
        $('#modal_tbdy').append(tr);
    });
    // $(this).find('.content-div').text(data_on_modal);
});


TXT;

$this->registerJs($js);