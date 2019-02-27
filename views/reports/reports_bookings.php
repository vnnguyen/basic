<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->params['breadcrumb'] = [
    ['Manager', '@web/manager'],
    ['Reports', '@web/reports'],
];

Yii::$app->params['body_class'] = 'sidebar-xs bg-white';
Yii::$app->params['page_title'] = 'Báo cáo doanh thu tour ('.count($theBookings).')';

?>

<div class="col-md-12">
    <form class="form-inline mb-20">
        <?= Html::dropdownList('viewby', $viewby, ['ketthuc'=>'Kết thúc', 'khoihanh'=>'Khởi hành', 'bantour'=>'Bán tour'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>'Tháng']) ?>
        <?= Html::dropdownList('seller', $seller, ArrayHelper::map($sellerList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Bán hàng']) ?>
        <?//= Html::dropdownList('currency', $currency, ['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND'], ['class'=>'form-control', 'prompt'=>'Loại tiền']) ?>
        <?= Html::dropdownList('fg', $fg, ['all'=>'fg', 'g'=>'Chỉ B2B', 'f'=>'Chỉ B2C'], ['class'=>'form-control']) ?>
        <?= Html::textInput('rates', $rates, ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), '@web/reports/bookings') ?>
    </form>
    <div class="alert alert-info">
        <strong>CHÚ Ý</strong>
        Tour bán bằng USD sẽ được quy đổi sang EUR theo tỉ giá 1 EUR = <?= $rates ?> USD (sửa được trong ô ở trên đây) và có ghi chú giá gốc bên cạnh.
        Các booking chưa có báo cáo tuy vẫn hiện ra nhưng không được tính vào tổng.
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-narrow table-framed">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>Tour</th>
                    <th width="40">Ngày cfm</th>
                    <th width="40"><?= $viewby == 'ketthuc' ? 'Kết thúc' : 'Khởi hành' ?></th>
                    <th width="40" class="text-center">Ngày</th>
                    <th width="40" class="text-center">Pax</th>
                    <th>N/pax</th>
                    <th>Tiền</th>
                    <th>D/thu</th>
                    <th>C/phí</th>
                    <th>Dt/np</th>
                    <th>Cp/np</th>
                    <th>L/gộp</th>
                    <th>DTQĐ</th>
                    <th>P%LG</th>
                    <th>HSBH</th>
                    <th>Note</th>
                    <th>DT / CP USD</th>
                </tr>
            </thead>
            <tbody>
                <?
                $sumNgay = 0;
                $sumPax = 0;
                $sumNgayPax = 0;
                $sumDoanhThu = 0;
                $sumChiPhi = 0;
                $avgDoanhThuNgayPax = 0;
                $avgChiPhiNgayPax = 0;
                $sumLaiGop = 0;
                $sumDoanhThuQuyDoi = 0;
                $avgPhanTramLaiGop = 0;

                $cnt = 0;
                
                foreach ($theBookings as $booking) {
                    if (1) {
                        $doanhThuUSD = 0;
                        $chiPhiUSD = 0;
                        if ($booking['report'] && $booking['product']['op_status'] != 'canceled') {
                            $ngay = $booking['report']['day_count'];
                            $pax = $booking['report']['pax_count'];
                            $ngayPax = $ngay * $pax;
                            if ($booking['report']['price_unit'] == 'USD') {
                                $doanhThu = $booking['report']['price'] / $rates;
                                $chiPhi = $booking['report']['cost'] / $rates;
                                $doanhThuUSD = $booking['report']['price'];
                                $chiPhiUSD = $booking['report']['cost'];
                            } else {
                                $doanhThu = $booking['report']['price'];
                                $chiPhi = $booking['report']['cost'];
                            }
                            $loaiTien = $booking['report']['price_unit'];
                        } else {
                            $ngay = $booking['product']['day_count'];
                            $pax = $booking['pax'];
                            $ngayPax = $ngay * $pax;
                            $doanhThu = 10;//$booking['price'];
                            $chiPhi = 10;//rand(890, 1280);
                            $loaiTien = $booking['currency'];
                        }

                        $doanhThuNgayPax = $ngayPax == 0 ? 0 : $doanhThu / $ngayPax;
                        $chiPhiNgayPax = $ngayPax == 0 ? 0 : $chiPhi / $ngayPax;
                        $laiGop = $doanhThu - $chiPhi;
                        $doanhThuQuyDoi = $laiGop * 5;
                        $phanTramLaiGop = $doanhThu == 0 ? 0 : 100 * $laiGop / $doanhThu;

                        if ($booking['report'] && $booking['product']['op_status'] != 'canceled') {
                            $sumNgay += $ngay;
                            $sumPax += $pax;
                            $sumNgayPax += $ngayPax;
                            $sumDoanhThu += $doanhThu;
                            $sumChiPhi += $chiPhi;
                        }
                        $cnt ++;
                ?>
                <tr class="<?= $booking['product']['op_finish'] == 'canceled' ? 'danger' : '' ?>">
                    <td class="text-muted text-center"><?= $cnt ?></td>
                    <td class="text-nowrap">
                        <? if (in_array(USER_ID, [1, $booking['created_by'], $booking['updated_by'], $booking['case']['owner_id']])) { ?>
                        <?= Html::a('<i class="fa fa-fw fa-edit"></i>', '@web/bookings/report/'.$booking['id'], ['title'=>'Edit', 'class'=>'text-muted']) ?>
                        <? } ?>
                        <? if ($booking['note'] != '') { ?>
                            <i class="fa fa-file-text-o pull-left text-muted popovers"
                                data-toggle="popover"
                                data-trigger="hover"
                                data-title="<?= $booking['product']['title'] ?>"
                                data-html="true"
                                data-content="<?= Html::encode($booking['note']) ?>"></i>
                        <? } ?>
                        <?= Html::a($booking['product']['op_code'], '@web/products/op/'.$booking['product']['id'], ['title'=>$booking['product']['op_name']]) ?>
                    </td>
                    <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($booking['status_dt'])) ?></td>
                    <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($viewby == 'ketthuc' ? $booking['end_date'] : $booking['start_date'])) ?></td>
                    <td class="text-center"><?= $ngay ?></td>
                    <td class="text-center"><?= $pax ?></td>
                    <td class="text-center"><?= $ngayPax ?></td>
                    <? if ($booking['product']['op_finish'] == 'canceled' || !$booking['report']) { ?>
                    <td colspan="8" class="text-center text-danger"><?= $booking['product']['op_finish'] == 'canceled' ? 'Tour canceled' : 'No data' ?></td>
                    <? } else { ?>
                    <td class="text-center">EUR<?//= $loaiTien ?></td>
                    <td class="text-right"><?= number_format($doanhThu, 0) ?></td>
                    <td class="text-right"><?= number_format($chiPhi, 0) ?></td>
                    <td class="text-right"><?= number_format($doanhThuNgayPax, 2) ?></td>
                    <td class="text-right"><?= number_format($chiPhiNgayPax, 2) ?></td>
                    <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($laiGop, 0) ?></td>
                    <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($doanhThuQuyDoi, 0) ?></td>
                    <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($phanTramLaiGop, 2) ?>%</td>
                    <? } ?>
                    <td>
                        <?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?>
                        <?= $booking['case']['owner']['name'] ?>
                    </td>
                    <td>
                        <? if ($booking['report']['note'] != '') { ?>
                        <i class="fa fa-info-circle text-warning pull-left text-muted popovers"
                            data-toggle="popover"
                            data-trigger="hover"
                            data-title="Note about report"
                            data-html="true"
                            data-content="<?= Html::encode($booking['report']['note']) ?>"></i>
                        <? } ?>
                    </td>
                    <td class="text-right"><?
                    if ($doanhThuUSD != 0) {
                        echo number_format($doanhThuUSD), ' / ', number_format($chiPhiUSD), ' USD'; 
                    }
                    ?>
                    </td>
                </tr>
                <?
                    } // is b2b
                } // for each bookings
                ?>
                <tr>
                    <th colspan="4">Total</th>
                    <th class="text-center"><?= $sumNgay ?></th>
                    <th class="text-center"><?= $sumPax ?></th>
                    <th class="text-center"><?= $sumNgayPax ?></th>
                    <th class="text-center">EUR</th>
                    <th class="text-right"><?= number_format($sumDoanhThu, 0) ?></th>
                    <th class="text-right"><?= number_format($sumChiPhi, 0) ?></th>
                    <th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumDoanhThu / $sumNgayPax, 2) ?></th>
                    <th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumChiPhi / $sumNgayPax, 2) ?></th>
                    <th class="text-right"><?= number_format($sumDoanhThu - $sumChiPhi, 0) ?></th>
                    <th class="text-right"><?= number_format(5 * ($sumDoanhThu - $sumChiPhi), 0) ?></th>
                    <th class="text-right"><?= $sumDoanhThu == 0 ? 0 : number_format(100 * ($sumDoanhThu - $sumChiPhi) / $sumDoanhThu, 2) ?>%</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style type="text/css">
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>
<?
$js = <<<TXT
$('.popovers').popover();
TXT;
$this->registerJs($js);
