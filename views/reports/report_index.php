<?php
use yii\helpers\Html;

// include('_report_inc.php');
Yii::$app->params['page_title'] = Yii::t('x', 'Reports');

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'Reports')],
];

Yii::$app->params['page_icon'] = 'area-chart';

$totalPaymentInVnd = 0;
foreach ($monthPayments as $payment) {
    $totalPaymentInVnd += $payment['xrate'] * $payment['amount'];
}


$this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom border-0 mb-0">
    <li class="nav-item"><a class="nav-link active" href="#tab-com" data-toggle="tab"><?= Yii::t('x', 'Company') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-ps" data-toggle="tab"><?= Yii::t('x', 'Products') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-b2c" data-toggle="tab"><?= Yii::t('x', 'B2C') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-b2b" data-toggle="tab"><?= Yii::t('x', 'B2B') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-to" data-toggle="tab"><?= Yii::t('x', 'Tour operation') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-hr" data-toggle="tab"><?= Yii::t('x', 'HR') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-ot" data-toggle="tab"><?= Yii::t('x', 'Other') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="/me/my-reports"><?= Yii::t('x', 'My reports') ?></a></li>
</ul><?php
$this->endBlock();
?>
<div class="col-md-12">
    <div class="tab-content">
        <div class="tab-pane active" id="tab-com">
            <p><strong><?= Yii::t('x', 'In this month') ?></strong></p>
            <div class="row">
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="mr-3 align-self-center">
                                <i class="fa fa-briefcase fa-3x text-slate-400"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="font-weight-semibold mb-0">+<?= $monthCaseCount ?></h3>
                                <span class="text-uppercase font-size-sm text-muted"><?= Yii::t('x', 'sales cases') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="mr-3 align-self-center">
                                <i class="fa fa-star fa-3x text-success-400"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="font-weight-semibold mb-0">+<?= $monthNewTourCount ?></h3>
                                <span class="text-uppercase font-size-sm text-muted"><?= Yii::t('x', 'won deals') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h3 class="font-weight-semibold mb-0">+<?= number_format($totalPaymentInVnd) ?> <span class="text-muted">VND</span></h3>
                                <span class="text-uppercase font-size-sm text-muted"><?= Yii::t('x', 'paid from customner') ?></span>
                            </div>
                            <div class="ml-3 align-self-center">
                                <i class="fa fa-dollar fa-3x text-info-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h3 class="font-weight-semibold mb-0">+<?= $monthTourCount ?></h3>
                                <span class="text-uppercase font-size-sm text-muted"><?= Yii::t('x', 'tours departing') ?></span>
                            </div>
                            <div class="ml-3 align-self-center">
                                <i class="fa fa-flag fa-3x text-pink-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p><strong><?= Yii::t('x', 'New won cases in month') ?></strong></p>
                            <div id="piechart" style="width:100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p><strong><?= Yii::t('x', 'Won cases by month') ?></strong></p>
                            <div id="chart2" style="width:100%; height:200px;"></div>
                            <br>
                            <p><strong><?= Yii::t('x', 'Departure of tours by month') ?></strong></p>
                            <div id="chart3" style="width:100%; height:200px;"></div>
                        </div>
                    </div>
                </div>
            </div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart1);
    google.setOnLoadCallback(drawChart2);
    google.setOnLoadCallback(drawChart3);
    function drawChart1() {
        var data = google.visualization.arrayToDataTable([
        ['Seller', 'Won'],
        <? $cnt = 0; foreach ($wonCasesBySeller as $li) { $cnt ++; ?>
        ['<?= $li['name']?>', <?= $li['total'] ?>]<?= $cnt != count($wonCasesBySeller) ? ',' : '' ?>
        <? } ?>
        ]);

        var options = {
            pieHole: 0.4,
            chartArea:{left:0,top:0,width:"100%",height:"100%"},
            legend:{position:"left"},
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }

    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Won cases'],
            <?
            $t24 = strtotime('-24 month');
            $t1 = strtotime('+1 month');
            $cnt = 0;
            foreach ($last12moWonCases as $li) {
                $t = strtotime($li['ym'].'-01');
                if ($t24 < $t && $t1 > $t) {
                    $cnt ++;
                    if ($cnt != 1) echo ', ';
            ?>
            ['<?= $li['ym'] ?>', <?= $li['total'] ?>]
            <?
                }
            }
            ?>
        ]);

        var options = {
            // hAxis: {title: 'Tháng'},
            chartArea:{left:0,top:0,width:"100%",height:"100%"},
            legend:{position: 'none'},
            hAxis:{textPosition: 'none'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart2'));
        chart.draw(data, options);
    }

    function drawChart3() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Tours'],
            <?
            $t24 = strtotime('-24 month');
            $t1 = strtotime('+1 month');
            $cnt = 0;
            foreach ($last12moTours as $li) {
                $t = strtotime($li['ym'].'-01');
                if ($t24 < $t && $t1 > $t) {
                    $cnt ++;
                    if ($cnt != 1) echo ', ';
            ?>
            ['<?= $li['ym'] ?>', <?= $li['total'] ?>]
            <?
                }
            }
            ?>
        ]);

        var options = {
            // hAxis: {title: 'Tháng'},
            chartArea:{left:0,top:0,width:"100%",height:"80%"},
            legend:{position: 'none'},
            hAxis:{textPosition: 'none'},
            colors:['purple'],
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart3'));
        chart.draw(data, options);
    }
</script>

            <div class="table-responsive d-none">
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?= Yii::t('x', 'Name of report') ?></th>
                            <th><?= Yii::t('x', 'Description') ?></th>
                            <th><?= Yii::t('x', 'Update') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><?= Html::a('Tỉ lệ thành công', '@web/reports/b2c-conversion-rate') ?></td><td>Tỉ lệ các hồ sơ bán được, không bán được, đang bán</td><td>4/7/2018</td></tr>
                        <tr><td><span class="badge badge-info">New</span> <?= Html::a('Các chỉ số bán hàng B2C theo tháng', '@web/reports/b2c') ?></td><td>Các chỉ số bán hàng theo tháng trong năm, có thể so sánh với năm khác</td></tr>
                        <tr><td><?= Html::a('Doanh thu tour', '@web/reports/bookings') ?></td><td>Các booking tour, giá thành, chi phí và tỉ lệ lãi dự tính</td></tr>
                        <tr><td><?= Html::a('Hồ sơ không thành công', '@web/reports/lost-cases') ?></td><td>Các hồ sơ đóng và không bán được tour, nguyên nhân</td></tr>
                        <tr><td><span class="badge badge-info">New</span> <?= Html::a('Doanh thu theo tháng tour kết thúc', '@web/reports/b2c-one') ?></td><td>Số tour, doanh thu và lợi nhuận đội bán hàng theo từng tháng trong năm</td></tr>
                        <tr><td><?= Html::a('Kết quả bán hàng chung', '@web/manager/sales-results') ?></td><td>Tỉ lệ bán được, bán không được và bán được tuyệt đối của đội bán hàng theo từng tháng</td></tr>
                        <tr><td><?= Html::a('Kết quả bán hàng theo nguồn đến', '@web/manager/sales-results-sources') ?></td><td>Tỉ lệ bán được của đội bán hàng theo nguồn khách theo từng tháng</td></tr>
                        <tr><td><?= Html::a('Hồ sơ bán được / không được theo tháng', '@web/manager/sales-results-changes') ?></td><td>Số lượng hồ sơ bán được và không bán được trong tháng của đội bán hàng theo từng tháng</td></tr>
                        <tr><td><?= Html::a('Hồ sơ giao theo tháng', '@web/manager/sales-results-assignments') ?></td><td>Số lượng hồ sơ giao cho người bán theo từng tháng qua các năm</td></tr>
                        <tr><td><?= Html::a('Hồ sơ giao theo ngày', '@web/manager/sellers-cases') ?></td><td>Số lượng hồ sơ giao cho người bán theo từng ngày trong tháng</td></tr>
                        <tr><td><?= Html::a('Hồ sơ và nhiệm vụ', '@web/manager/sellers-tasks') ?></td><td>Các hồ sơ bán hàng và nhiệm vụ tính theo mỗi người bán</td></tr>

                        <tr><!-- <td><strong>Tiền tour</strong></td> --><td><?= Html::a('Tiền thanh toán tour theo năm', '@web/reports/kqkdtour') ?></td><td>Số tiền cần thu và đã thu của các tour khởi hành trong năm</td></tr>
                        <tr><td><?= Html::a('Lịch thanh toán tour', '@web/reports/lichtttour') ?></td><td>Số tiền tour dự tính sẽ thu theo từng tuần trong năm</td></tr>
                        <tr><td><?= Html::a('Tiền tour đã thu', '@web/payments') ?></td><td>Số tiền tour đã thu được theo từng tháng</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card tab-pane" id="tab-ps">
            
        </div>
        <div class="card tab-pane" id="tab-b2c">
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?= Yii::t('x', 'Name of report') ?></th>
                            <th><?= Yii::t('x', 'Description') ?></th>
                            <th><?= Yii::t('x', 'Update') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><th colspan="3"><?= Yii::t('x', 'Marketing') ?></th></tr>
                        <tr><td><?= Html::a('Phân bổ chi phí QC', '@web/reports/mkt-05') ?></td><td>Phân bổ chi phí quảng cáo theo thời gian mở HS, loại khách và kênh liên hệ</td><td>17/9/2018</td></tr>

                        <tr><th colspan="3"><?= Yii::t('x', 'Sales') ?></th></tr>
                        <tr><td><?= Html::a('Tỉ lệ thành công', '@web/reports/b2c-conversion-rate') ?></td><td>Tỉ lệ các hồ sơ bán được, không bán được, đang bán</td><td>4/7/2018</td></tr>
                        <tr><td><?= Html::a('Tỉ trọng ngày tour', '@web/reports/tour-length') ?></td><td>Tỉ lệ độ dài tour</td></tr>
                        <tr><td><span class="badge badge-info">New</span> <?= Html::a('Các chỉ số bán hàng B2C theo tháng', '@web/reports/b2c') ?></td><td>Các chỉ số bán hàng theo tháng trong năm, có thể so sánh với năm khác</td></tr>
                        <tr><td><?= Html::a('Doanh thu tour', '@web/reports/bookings') ?></td><td>Các booking tour, giá thành, chi phí và tỉ lệ lãi dự tính</td></tr>
                        <tr><td><?= Html::a('Hồ sơ không thành công', '@web/reports/lost-cases') ?></td><td>Các hồ sơ đóng và không bán được tour, nguyên nhân</td></tr>
                        <tr><td><span class="badge badge-info">New</span> <?= Html::a('Doanh thu theo tháng tour kết thúc', '@web/reports/b2c-one') ?></td><td>Số tour, doanh thu và lợi nhuận đội bán hàng theo từng tháng trong năm</td></tr>
                        <tr><td><?= Html::a('Kết quả bán hàng chung', '@web/manager/sales-results') ?></td><td>Tỉ lệ bán được, bán không được và bán được tuyệt đối của đội bán hàng theo từng tháng</td></tr>
                        <tr><td><?= Html::a('Kết quả bán hàng theo nguồn đến', '@web/manager/sales-results-sources') ?></td><td>Tỉ lệ bán được của đội bán hàng theo nguồn khách theo từng tháng</td></tr>
                        <tr><td><?= Html::a('Hồ sơ bán được / không được theo tháng', '@web/manager/sales-results-changes') ?></td><td>Số lượng hồ sơ bán được và không bán được trong tháng của đội bán hàng theo từng tháng</td></tr>
                        <tr><td><?= Html::a('Hồ sơ giao theo tháng', '@web/manager/sales-results-assignments') ?></td><td>Số lượng hồ sơ giao cho người bán theo từng tháng qua các năm</td></tr>
                        <tr><td><?= Html::a('Hồ sơ giao theo ngày', '@web/manager/sellers-cases') ?></td><td>Số lượng hồ sơ giao cho người bán theo từng ngày trong tháng</td></tr>
                        <tr><td><?= Html::a('Hồ sơ và nhiệm vụ', '@web/manager/sellers-tasks') ?></td><td>Các hồ sơ bán hàng và nhiệm vụ tính theo mỗi người bán</td></tr>

                        <tr><!-- <td><strong>Tiền tour</strong></td> --><td><?= Html::a('Tiền thanh toán tour theo năm', '@web/reports/kqkdtour') ?></td><td>Số tiền cần thu và đã thu của các tour khởi hành trong năm</td></tr>
                        <tr><td><?= Html::a('Lịch thanh toán tour', '@web/reports/lichtttour') ?></td><td>Số tiền tour dự tính sẽ thu theo từng tuần trong năm</td></tr>
                        <tr><td><?= Html::a('Tiền tour đã thu', '@web/payments') ?></td><td>Số tiền tour đã thu được theo từng tháng</td></tr>

                        <tr><th colspan="3"><?= Yii::t('x', 'Customer Relations') ?></th></tr>
                        <tr><td><?= Html::a('Report 01', '@web/reports/qhkh-01') ?></td><td><?= Yii::t('x', 'Nguồn khách tour (tour code F, không huỷ)') ?></td><td></td></tr>
                        <tr><td><?= Html::a('Report 02', '@web/reports/qhkh-02') ?></td><td><?= Yii::t('x', 'HSBH hàng của khách được giới thiệu, theo tháng mở HS') ?></td><td></td></tr>
                        <tr><td><?= Html::a('Report 03', '@web/reports/qhkh-03') ?></td><td><?= Yii::t('x', 'Thư hồi âm và quà Club Ami Amica') ?></td><td></td></tr>
                        <tr><td><?= Html::a('Report 04', '@web/reports/qhkh-04') ?></td><td><?= Yii::t('x', 'Phân bổ tour cho QHKH theo nước khởi hành theo tháng') ?></td><td></td></tr>
                        <tr><td><?= Html::a('Khách hàng đi nhiều tour', '@web/reports/pax-tours') ?></td><td>Danh sách khách đã đi nhiều hơn 1 lần và tour tương ứng</td><td></td></tr>
                        <tr><td><?= Html::a('Khách hàng ở các nhà dân', '@web/reports/customers-hotel') ?></td><td>Danh sách khách đã ở các nhà dân, tính theo năm</td><td></td></tr>
                        <tr><td><?= Html::a('Số lượng khách tour các năm', '@web/reports/customers-tours') ?></td><td>Tổng số khách tour (không tính tour huỷ) theo từng tháng của từng năm</td><td></td></tr>
                        <tr><td><?= Html::a('Khách hàng còn credit chưa dùng', '@web/reports/mkt03') ?></td><td>Download</td><td></td></tr>
                        <tr><td><?= Html::a('Khách hàng được cập nhật theo tháng', '@web/reports/mkt04') ?></td><td>Dùng để lấy danh sách email mới nhất</td><td></td></tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="card tab-pane" id="tab-b2b">
            
        </div>
        <div class="card tab-pane" id="tab-to">
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?= Yii::t('x', 'Name of report') ?></th>
                            <th><?= Yii::t('x', 'Description') ?></th>
                            <th><?= Yii::t('x', 'Update') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><?= Html::a(Yii::t('x', 'Phân công điều hành tour theo số ngày'), 'https://my.amicatravel.com/reports/dh-01') ?></td><td><?= Yii::t('x', 'Số ngày và phụ cấp điều hành tour theo chặng') ?></td><td>1/7/2018</td></tr>
                        <tr><td><?= Html::a('Phân công điều hành tour theo số tour', '@web/reports/dh-02') ?></td><td>Số lượng, tỉ lệ tour được phân công cho điều hành</td><td>10/9/2018</td></tr>
                        <tr><td><?= Html::a('Tỉ trọng độ dài tour', '@web/reports/dh-03') ?></td><td>Tỉ trọng độ dài tour tính theo ngày khởi hành</td><td>2/10/2018</td></tr>
                        <tr><td><?= Html::a('Số tour chạy theo tháng', '@web/manager/tours-departures') ?></td><td>Số lượng tour khởi hành trong từng tháng qua các năm chia theo người bán hàng</td><td></td></tr>
                        <tr><td><?= Html::a('Tour và khách theo quốc gia đi thăm', '@web/reports/tour-pax-country') ?></td><td>Số tour và số khách đi các nước theo từng năm</td><td></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card tab-pane" id="tab-hr"></div>
        <div class="card tab-pane" id="tab-ot"></div>
    </div>
</div>
