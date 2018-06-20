<?
use app\helpers\DateTimeHelper;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Chi phí tour đã thanh toán';
Yii::$app->params['page_small_title'] = 'ghi nhận theo phương pháp mới (160111)';

Yii::$app->params['page_breadcrumbs'] = [
    ['Chi phí tour', 'cpt'],
    ['Đã thanh toán'],
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::textInput('date', $date, ['class'=>'form-control', 'placeholder'=>'Ngày TT (yyyy-mm-dd)', 'autocomplete'=>'off']) ?>
                <?= Html::textInput('tour', $tour, ['class'=>'form-control', 'placeholder'=>'Tour code/ID hoặc tên DV', 'autocomplete'=>'off']) ?>
                <?= Html::dropdownList('updatedby', $updatedby, ArrayHelper::map($updatedbyList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Người nhập']) ?>
                <?= Html::dropdownList('unitc', $unitc, ArrayHelper::map($currencyList, 'currency', 'currency'), ['class'=>'form-control', 'prompt'=>'CP bằng']) ?>
                <?= Html::dropdownList('currency', $currency, ArrayHelper::map($currencyList, 'currency', 'currency'), ['class'=>'form-control', 'prompt'=>'TT bằng']) ?>
                <?= Html::textInput('tkgn', $tkgn, ['class'=>'form-control', 'placeholder'=>'TKGN', 'autocomplete'=>'off']) ?>
                <?= Html::textInput('mp', $mp, ['class'=>'form-control', 'placeholder'=>'MP', 'autocomplete'=>'off']) ?>
                <?= Html::dropdownList('check', $check, ['all'=>'Check', 'yes'=>'Check ON', 'no'=>'Check OFF', ], ['class'=>'form-control']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/cpt/da-thanh-toan') ?>
            </form>
        </div>
        <div class="table-responsive">
            <table id="tbl-mtt" class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th>Ngày TT</th>
                        <th>Tour</th>
                        <th>Ngày sử dụng, Dịch vụ, Nhà cung cấp</th>
                        <th>Thành tiền</th>
                        <th>TT số tiền</th>
                        <th>Bằng</th>
                        <th>CHK</th>
                        <th>Tỉ giá</th>
                        <th>TKGN</th>
                        <th>MP</th>
                        <th>Ghi chú</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $cnt = 0;
                    $dueTotal = [
                        'EUR'=>0,
                        'USD'=>0,
                        'VND'=>0,
                        'LAK'=>0,
                        'KHR'=>0,
                        'THB'=>0,
                    ];
                    $paidTotal = [
                        'EUR'=>0,
                        'USD'=>0,
                        'VND'=>0,
                        'LAK'=>0,
                        'KHR'=>0,
                        'THB'=>0,
                    ];
                    foreach ($theMttx as $mtt) {
                        if ($mtt['check'] != '') {
                            $checkClass = 'success';
                        } else {
                            $checkClass = 'default';
                        }
                        $due = $mtt['cpt']['qty'] * $mtt['cpt']['price'];

                        if ($mtt['cpt']['plusminus'] == 'minus') {
                            $dueTotal[$mtt['cpt']['unitc']] -= $due;
                            $paidTotal[$mtt['cpt']['unitc']] -= $mtt['amount'];
                        } else {
                            $dueTotal[$mtt['cpt']['unitc']] += $due;
                            $paidTotal[$mtt['cpt']['unitc']] += $mtt['amount'];
                        }

                        ?>
                    <tr>
                        <td class="text-center">
                            <?
                            $thisYear = date('Y');
                            
                            if (substr($mtt['payment_dt'], 0, 4) == $thisYear) {
                                $payDt = DateTimeHelper::format($mtt['payment_dt'], 'j/n');
                            } else {
                                $payDt = DateTimeHelper::format($mtt['payment_dt'], 'j/n/Y');
                            }
                            echo Html::a($payDt, '?date='.substr($mtt['payment_dt'], 0, 10), ['title'=>'Xem các TT trong ngày này']);
                            ?>
                        </td>
                        <td><?= Html::a($mtt['cpt']['tour']['code'], '?tour='.$mtt['cpt']['tour']['id']) ?></td>
                        <td>
                            <span class="text-muted"><?= date('j/n', strtotime($mtt['cpt']['dvtour_day'])) ?></span>
                            <?= Html::a($mtt['cpt']['dvtour_name'], '?tour='.$mtt['cpt']['dvtour_name']) ?>
                            <?= $mtt['cpt']['oppr'] != '' ? '<em>'.$mtt['cpt']['oppr'].'</em>' : '' ?>
                            <? if ($mtt['cpt']['venue_id'] != 0) { ?>   @<?= Html::a($mtt['cpt']['venue']['name'], '/venues/r/'.$mtt['cpt']['venue_id'], ['target'=>'_blank']) ?><? } ?>
                            <? if ($mtt['cpt']['by_company_id'] != 0) { ?> (<?= $mtt['cpt']['company']['name'] ?>)<? } ?>
                            <? if ($mtt['cpt']['via_company_id'] != 0) { ?> (<?= $mtt['cpt']['viaCompany']['name'] ?>)<? } ?>
                            <? if ($mtt['cpt']['venue_id'] == 0 && $mtt['cpt']['by_company_id'] == 0 && $mtt['cpt']['via_company_id'] == 0) { ?> <span class="text-muted"><?= $mtt['cpt']['oppr'] ?></span><? } ?>
                        </td>
                        <td class="text-right text-nowrap">
                            <?= $mtt['cpt']['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($due, intval($due) == $due ? 0 : 2) ?> <span class="text-muted"><?= $mtt['cpt']['unitc'] ?></span>
                        </td>
                        <td class="text-right text-nowrap">
                            <?= $mtt['cpt']['plusminus'] == 'minus' ? '-' : '' ?><?= Html::a(number_format($mtt['amount'], intval($mtt['amount']) == $mtt['amount'] ? 0 : 2), '/cpt/r/'.$mtt['cpt_id'], ['class'=>$mtt['amount'] != $due ? 'text-warning' : '', 'title'=>'Xem cpt']) ?> <span class="text-muted"><?= $mtt['cpt']['unitc'] ?></span>
                        </td>
                        <td class="text-center"><?= $mtt['currency'] ?></td>
                        <td><a href="#" class="ajax label label-<?= $checkClass ?>" data-tour_id="<?= $mtt['cpt']['tour_id'] ?>" data-dvtour_id="<?= $mtt['cpt']['dvtour_id'] ?>" data-mtt_id="<?= $mtt['id'] ?>">CHK</a></td>
                        <td class="text-center"><?= number_format($mtt['xrate']) ?></td>
                        <td class="text-center"><?= $mtt['tkgn'] ?></td>
                        <td class="text-center"><?= $mtt['mp'] ?></td>
                        <td class="text-right"><?= $mtt['note'] ?></td>
                        <td class="text-nowrap">
                            <i class="fa fa-clock-o text-muted" title="<?= Yii::$app->formatter->asRelativeTime($mtt['updated_dt']) ?>"></i>
                            <?= $mtt['updatedBy']['name'] ?>
                        </td>
                    </tr>
                    <? } ?>
                    <tr>
                        <td colspan="3" class="text-right">TỔNG TRÊN TRANG NÀY:</td>
                        <td class="text-right">
                        <?
                        foreach ($dueTotal as $currency=>$total) {
                            if ($total != 0) {
                        ?>
                            <div>
                                <span class="text-bold"><?= number_format($total, intval($total) == $total ? 0 : 2) ?></span>
                                <span><?= $currency ?></span>
                            </div>
                        <?
                            }
                        }
                        ?>
                        <td class="text-right">
                        <?
                        foreach ($paidTotal as $currency=>$total) {
                            if ($total != 0) {
                        ?>
                            <div>
                                <span class="text-bold"><?= number_format($total, intval($total) == $total ? 0 : 2) ?></span>
                                <span><?= $currency ?></span>
                            </div>
                        <?
                            }
                        }
                        ?>
                        </td>
                        <td colspan="7">
                        <?
                        foreach ($paidTotal as $currency=>$total) {
                            if ($total != 0) {
                        ?>
                            <div><?= Yii::$app->formatter->asSpellout($total) ?> <?= $currency ?></div>
                        <?
                            }
                        }
                        ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <? if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="text-center">
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
<?
$js = <<<'TXT'
// Check / Uncheck payment
$('#tbl-mtt').on('click', 'a.ajax', function(event){
    event.preventDefault();
    var span = $(this);
    var action = 'check-mtt';
    var tour_id = $(this).data('tour_id');
    var dvtour_id = $(this).data('dvtour_id');
    var mtt_id = $(this).data('mtt_id');
    var jqxhr = $.post('/cpt/ajax?xh', {action:action, tour_id:tour_id, dvtour_id:dvtour_id, mtt_id:mtt_id})
    .done(function(data) {
        if (data['code'] == 200) {
            cssClass = span.hasClass('label-success') ? 'label-default' : 'label-success';
            span.removeClass('label-default label-success');
            span.addClass(cssClass);
        } else {
            alert(data['message']);
        }
    }, 'json')
    .fail(function(data) {
        if (data['message']) {
            alert(data['message']);
        } else {
            alert('Error updating CPT!');
        }
    });
});
TXT;

$this->registerJs($js);
