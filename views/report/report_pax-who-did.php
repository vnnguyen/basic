<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

for ($y = 2017; $y >= 2007; $y --) {
    $yearList[$y] = $y;
}
Yii::$app->params['page_title'] = 'Danh sách khách cũ chưa đi tour Lào (năm '.$year.')';
?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                Năm đi tour
                <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tour</th>
                        <th>Họ</th>
                        <th>Tên</th>
                        <th>Giới</th>
                        <th>Sinh</th>
                        <th>Nước</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $cnt = 0;
                    foreach ($tours as $tour) {
                        foreach ($tour['bookings'] as $booking) {
                            foreach ($booking['pax'] as $pax) {
                                $cnt ++;
                    ?>
                    <tr>
                        <td class="text-muted"><?= $cnt ?></td>
                        <td><?= $tour['op_code'] ?></td>
                        <td><?= $pax['fname'] ?></td>
                        <td><?= $pax['lname'] ?></td>
                        <td><?= $pax['gender'] ?></td>
                        <td><?= $pax['byear'] ?></td>
                        <td><?= strtoupper($pax['country_code']) ?></td>
                        <td><?= $pax['email'] ?></td>
                        <td><?= Html::a('View', '/users/r/'.$pax['id'], ['target'=>'_blank']) ?></td>
                    </tr>
                    <?
                            }

                        }
                        ?>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
