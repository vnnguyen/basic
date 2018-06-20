<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_qhkh_inc.php');

Yii::$app->params['page_title'] = 'Chi tiết thu Quỹ Quan hệ khách hàng trong tháng';

// $tongThu = 0;
// foreach ($thuQuyQhkh as $thu) {
//     $tongThu += $thu['tong'];
// }
?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Thông tin</h6>
        </div>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <th>TT</th>
                <th class="text-nowrap">Code tour</th>
                <th class="text-nowrap">Tên tour</th>
                <th class="text-nowrap">Khởi hành</th>
                <th class="text-nowrap">S/Ngày</th>
                <th class="text-nowrap">S/Pax</th>
                <th class="text-nowrap">Thu quỹ</th>
                <th>Update</th>
            </thead>
            <tbody>
                <?
                foreach ($theTours as $i=>$tour) {
                    $quyTour = 0;
                    foreach ($tour['bookings'] as $booking) {
                        $quyTour += $booking['quy_qhkh'];
                    }
                    ?>
                <tr>
                    <td class="text-center"><?= ++ $i ?></td>
                    <td class="text-nowrap"><?= Html::a($tour['op_code'], '/products/op/'.$tour['id']) ?></td>
                    <td class="text-nowrap"><?= $tour['op_name'] ?></td>
                    <td class="text-center"><?= date('j/n/Y', strtotime($tour['day_from'])) ?></td>
                    <td class="text-center"><?= $tour['day_count'] ?></td>
                    <td class="text-center"><?= $tour['pax'] ?></td>
                    <td class="text-center"><?= number_format($quyTour) ?> USD</td>
                    <td></td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>
</div>