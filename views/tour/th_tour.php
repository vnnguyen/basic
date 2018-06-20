<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

if ($month == 'next30days') {
    $monthText = 'trong 30 ngày tới';
} elseif ($month == 'last30days') {
    $monthText = 'trong 30 vừa qua';
} else {
    $monthText = 'trong tháng '.date('n/Y', strtotime($month));
}
$selectText = 'Tour kết thúc ';

Yii::$app->params['page_title'] = $selectText.$monthText.' ('.number_format(count($theTours)).' tour)';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tours', '@web/tours'],
    [$month, '@web/tours?month='.$month],
];

$newMonthList = [''=>'Tháng này'];
$newMonthList['next30days'] = '30 ngày tới';
$newMonthList['last30days'] = '30 ngày qua';
foreach ($monthList as $mo) {
    $newMonthList[$mo['ym']] = $mo['ym'].' ('.$mo['total'].')';
    $newMonthList[$mo['ym']] = $mo['ym'];
}

$statusList = [
    'active'=>'Active',
    'canceled'=>'Canceled',
];
$kq = Yii::$app->request->get('qhkh_kq');
$kt = Yii::$app->request->get('qhkh_kt');
$point = Yii::$app->request->get('qhkh_diem');
$option1 = [
    'nc' => Yii::t('t', 'Good review'),
    'csk' => Yii::t('t', 'Complaned, good review'),
    'csn' => Yii::t('t', 'Complaned, bad review'),
    'csu' => Yii::t('t', 'Complaned, average'),
];
$option2 = [
    1 => 'Tem',
    2 => 'Blog 360',
    3 => 'Forum',
    4 => 'Retour',
    5 => 'Rechezpa',
    6 => 'Rechezich',
    7 => 'Recheznguyen',
    8 => 'Rechez thanh',
    9 => 'Rechez tap'
];
$option3 = [
    1 => 1,
    2 => 2,
    3 => 3,
    4 => 4,
    5 => 5,
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('month', $month, $newMonthList, ['class'=>'form-control']) ?>
                <?= Html::dropdownList('fg', $fg, ['f'=>'F tours', 'g'=>'G tours'], ['class'=>'form-control', 'prompt'=>'F/G tours']) ?>
                <?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control', 'prompt'=>'Status']) ?>
                <?= Html::dropdownList('qhkh_kq', $kq, $option1, ['class'=>'form-control', 'prompt'=>'KQ']) ?>
                <?= Html::dropdownList('qhkh_kt', $kt, $option2, ['class'=>'form-control', 'prompt'=>'KT']) ?>
                <?= Html::dropdownList('qhkh_diem', $point, $option3, ['class'=>'form-control', 'prompt'=>'Point']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), '@web/tours/th_tour') ?>
            </form>
        </div>
    <div class="table-responsive">
        <table id="tourlist" class="table table-xxs xtable-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="min-width:280px">Code - Tên tour - <!--a class="fw-n" href="#" onclick="$('tr.paxLine').toggleClass('hide'); return false;">Ẩn / hiện danh sách khách</a--></th>
                    <th class="text-center">From date - To date</th>
                    <th class="text-center">Email of pax</th>
                    <th class="text-center">KQ</th>
                    <th class="text-center">KT</th>
                    <th class="text-center">Point</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
<?
$dayIn = '';
$cnt = 0;

foreach ($theTours as $tour) {
    // FG
    $fgOK = true;
    if (in_array($fg, ['f', 'g']) && substr($tour['tour']['code'], 0, 1) != strtoupper($fg)) {
        $fgOK = false;
    }

    // Status
    $statusOK = true;
    if (($status == 'active' && $tour['tour']['status'] == 'deleted') || ($status == 'canceled' && $tour['tour']['status'] != 'deleted')) {
        $statusOK = false;
    }
    if ($fgOK && $statusOK) {
?>
                <tr class="tour-list-item
                    <? foreach ($tour['bookings'] as $booking) echo 'role-se-',$booking['created_by']; ?>
                    tour <?= $tour['tour']['status'] == 'deleted' ? 'danger' : '' ?>">
                    <td class="text-center text-muted"><?= ++ $cnt ?></td>
                    <td>
                        <?
                        $flag = $tour['language'];
                        if ($tour['language'] == 'en') $flag = 'us';
                        if ($tour['language'] == 'vi') $flag = 'vn';
                        echo '<span class="flag-icon flag-icon-', $flag,'"></span>';
                        ?>
                        <?= $tour['offer_type'] == 'combined2016' ? '<span class="text-uppercase text-light" style="background-color:#cff; color:#148040; padding:0 3px" title="Combined">C</span> ' : ''?>
                        <?= $tour['tour']['status'] == 'deleted' ? '<strong style="color:#c00;">(CXL)</strong> ' : ''?>
                        <?= Html::a($tour['tour']['code'].' - '.$tour['tour']['name'], '@web/tours/r/'.$tour['tour']['id']) ?>
                        <?
                        $returning = false;
                        foreach ($tour['pax'] as $pax) {
                            if ($pax['is_repeating'] == 'yes') {
                                $returning = true;
                                echo '<i title="Returning customer" class="fa fa-refresh text-info"></i>';
                                break;
                            }
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <?php if (date('Y', strtotime($tour['day_from'])) == date('Y', strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days'))){ ?>
                            <?= date('j/n', strtotime($tour['day_from'])) . ' - '. date('j/n/Y', strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days'))?>
                        <?php } else {?>
                                <?= date('j/n/Y', strtotime($tour['day_from'])) . ' - '. date('j/n/Y', strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days'))?>
                        <?php } ?>
                    </td>
                    <td class="text-center"></td>
                    <td class="text-center"><?= ($tour['tourStats']['qhkh_ketthuc'] != '') ? $option1[$tour['tourStats']['qhkh_ketthuc']] : '' ?></td>
                    <td class="text-center"><?php
                    if ($tour['tourStats']['qhkh_khaithac'] != '') {
                        foreach (explode(',', $tour['tourStats']['qhkh_khaithac']) as $k=>$item) {
                            if ($k == count(explode(',', $tour['tourStats']['qhkh_khaithac']))-1) {
                                echo $option2[$item];
                            } else {
                                echo $option2[$item]. ' | ';
                            }
                        }
                    }
                    ?></td>
                    <td class="text-center"><?=($tour['tourStats']['qhkh_diem'] > 0) ? $tour['tourStats']['qhkh_diem']: ''?></td>
                    <td class="text-center">
                        <?php
                            if (strtotime(NOW) > strtotime(date('Y/m/d', strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days')))) {
                                echo Html::a('link', '', ['class' => 'btn_edit', 'data-tourstats' => $tour['tourStats'], 'data-tour_id' => $tour['id']]);
                            }
                         ?>
                    </td>
                </tr>
                <?
                    } // if hidden
                } // foreach
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style type="text/css" media="screen">
    .wrap-question label { width: 100%; padding-left: 10px;}
    .modal-body p{font-weight: bold;}
    .wrap-point label {display: inline-block; padding: 0 15px; }
</style>
<!-- Modal -->
<div class="modal fade" id="editModal" role="dialog">
    <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content">
            <form method="POST" accept-charset="utf-8">
                <?= Html::input('hidden','tour_id', '');?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= Yii::t('p', 'Update info')?></h4>
                </div>
                <div class="modal-body">
                    <div id="wrap_fieldset">
                        <div class="wrap-question">
                            <p><?= Yii::t('t', '1. Response of tour');?></p>
                            <?= Html::radioList('kq', [], $option1, ['encode' => false]);?>
                        </div>
                        <div class="wrap-question">
                            <p><?= Yii::t('t', '2. For marketing');?></p>
                            <?= Html::checkboxList('kt', [], $option2, ['encode' => false]);?>
                        </div>
                        <div class="wrap-point">
                            <p><?= Yii::t('t', '3. Point of satisfaction');?></p>
                            <?= Html::radioList('point', [], $option3, ['encode' => false]);?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="save_user">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?

$js = <<<'TXT'
var TOURSTATS = null;
    $('.btn_edit').click(function(e){
        var tour_id = $(this).data('tour_id');
        TOURSTATS = $(this).data('tourstats');
        $('#editModal').find('[name="tour_id"]').val(tour_id);
        $('#editModal').modal('show');
        return false;
    });
    $("#editModal").on('show.bs.modal', function () {
        if (TOURSTATS != null) {
            $.each($('#editModal').find('[name="kq"]'), function(index, item){
                if ($(item).val() == TOURSTATS.qhkh_ketthuc) {
                    $(item).prop('checked', true);
                } else {
                    $(item).prop('checked', false);
                }
            });
            $.each($('#editModal').find('[name="kt[]"]'), function(index, item){
                if (TOURSTATS.qhkh_khaithac != null && TOURSTATS.qhkh_khaithac.indexOf($(item).val()) !== -1) {
                    $(item).prop('checked', true);
                } else {
                    $(item).prop('checked', false);
                }
            });
            $.each($('#editModal').find('[name="point"]'), function(index, item){
                if ($(item).val() == TOURSTATS.qhkh_diem) {
                    $(item).prop('checked', true);
                } else {
                    $(item).prop('checked', false);
                }
            });
            TOURSTATS = null;
        }
    });
TXT;
$this->registerJs($js);
