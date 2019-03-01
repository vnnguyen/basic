<?php
namespace app\controllers\actions\reports;

use Yii;
use yii\web\Response;

use app\models\Kase;
use common\models\Product;

class B2c extends \yii\base\Action
{
    public function run(
        $view = 'tourend',
        $year = 0, // View data of this year
        $year2 = 0, // Compare to this year
        $currency = 'EUR',
        $sopax = '', $songay = '',
        $doanhthu = '', $loinhuan = '',
        array $diemden = [], $dkdiemden = '',
        $test = '',
        array $kx_source = [], array $tx_source = []
        )
    {
        $indexList = [
            0=>['label'=>'Tổng số tour', 'hint'=>'Số tour kết thúc trong tháng'],
            1=>['label'=>'Tổng số khách'],
            2=>['label'=>'Tổng số ngày'],
            3=>['label'=>'Số khách BQ /tour', 'round'=>1, 'avg'=>[1, 0]],
            4=>['label'=>'Số ngày BQ /tour', 'round'=>1, 'avg'=>[2, 0]],

            5=>['label'=>'Doanh thu', 'sub'=>$currency, 'est'=>true, 'link'=>'', 'hint'=>"Doanh thu dự tính: Lấy tổng tiền các hoá đơn do bán hàng làm khi bán tour; tỉ giá tính tại thời điểm phải thu tiền.\nDoanh thu thực tế: Lấy tổng tiền các lần thanh toán hoá đơn; tỉ giá tính tại thời điểm thu tiền thực tế."],
            6=>['label'=>'Giá vốn', 'sub'=>$currency, 'est'=>true, 'link'=>'', 'hint'=>"Giá vốn dự tính: Lấy giá vốn dự tính do bán hàng nhập khi bán tour; tỉ giá tính tại thời điểm nhập.\nGiá vốn thực tế: Lấy tổng tiền chi phí tour --!thực tế-- do điều hành nhập; tỉ giá tính tại thời điểm phải thanh toán."],
            7=>['label'=>'Lợi nhuận', 'sub'=>$currency, 'est'=>true, 'link'=>''],

            17=>['label'=>'Tỉ lệ lãi', 'sub'=>'%', 'est'=>true, 'round'=>2, 'avg'=>[7, 5], 'pct'=>true, 'hint'=>'100 * (LN / DT)'],
            18=>['label'=>'Tỉ lệ markup', 'sub'=>'%', 'est'=>true, 'round'=>2, 'avg'=>[5, 6], 'pct'=>true, 'minus1'=>true, 'hint'=>'100 * (DT / GV - 1)'],

            8=>['label'=>'Doanh thu BQ /tour', 'sub'=>$currency, 'est'=>true, 'avg'=>[5, 0]],
            9=>['label'=>'Giá vốn BQ /tour', 'sub'=>$currency, 'est'=>true, 'avg'=>[6, 0]],
            10=>['label'=>'Lợi nhuận BQ /tour', 'sub'=>$currency, 'est'=>true, 'avg'=>[7, 0]],
            11=>['label'=>'Doanh thu BQ /khách', 'sub'=>$currency, 'est'=>true, 'avg'=>[5, 1]],
            12=>['label'=>'Giá vốn BQ /khách', 'sub'=>$currency, 'est'=>true, 'avg'=>[6, 1]],
            13=>['label'=>'Lợi nhuận BQ /khách', 'sub'=>$currency, 'est'=>true, 'avg'=>[7, 1]],
            14=>['label'=>'Doanh thu BQ /khách/ngày', 'sub'=>$currency, 'est'=>true, 'round'=>2, 'avg'=>[11, 4], 'hint'=>'11. Doanh thu BQ /khách / 4. Số ngày BQ /tour'],
            15=>['label'=>'Giá vốn BQ /khách/ngày', 'sub'=>$currency, 'est'=>true, 'round'=>2, 'avg'=>[12, 4]],
            16=>['label'=>'Lợi nhuận BQ /khách/ngày', 'sub'=>$currency, 'est'=>true, 'round'=>2, 'avg'=>[13, 4]],
        ];

        $channelList = [
            'k1'=>['id'=>'k1', 'name'=>'K1', 'description'=>'Google Adwords'],
            'k2'=>['id'=>'k2', 'name'=>'K2', 'description'=>'Bing Ads'],
            'k3'=>['id'=>'k3', 'name'=>'K3', 'description'=>'Other web search'],
            'k4'=>['id'=>'k4', 'name'=>'K4', 'description'=>'Referral + Ads online + Other web which source could not be determined'],
            'k5'=>['id'=>'k5', 'name'=>'K5', 'description'=>'Direct access'],
            'k6'=>['id'=>'k6', 'name'=>'K6', 'description'=>'Mailing'],
            'k7'=>['id'=>'k7', 'name'=>'K7', 'description'=>'Non-web'],
            'k8'=>['id'=>'k8', 'name'=>'K8', 'description'=>'Other special cases'],
            'k0'=>['id'=>'k0', 'name'=>'No channel', 'description'=>'No channel data entered'],
        ];

        $typeList = [
            'new'=>['id'=>'new', 'name'=>'New', 'description'=>'New customer'],
            'referred'=>['id'=>'referred', 'name'=>'Referred', 'description'=>'Referred customer'],
            'returning'=>['id'=>'returning', 'name'=>'Returning', 'description'=>'Returning customer'],
            'unknown'=>['id'=>'unknown', 'name'=>'unknown', 'description'=>'Unknown'],
        ];

        if ($year == 0) {
            $year = date('Y');
        }
        if ($year2 == $year) {
            $year2 = 0;
        }

        $arr_xrate = [
            2016 => [
                1 => ['USD'=>22376,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                2 => ['USD'=>22296,'EUR'=>24524,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                3 => ['USD'=>22263,'EUR'=>24683,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                4 => ['USD'=>22258,'EUR'=>25150,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                5 => ['USD'=>22281,'EUR'=>25075,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                6 => ['USD'=>22305,'EUR'=>24944,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                7 => ['USD'=>22263,'EUR'=>24533,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                8 => ['USD'=>22261,'EUR'=>24832,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                9 => ['USD'=>22267,'EUR'=>24869,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                10 => ['USD'=>22276,'EUR'=>24490,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                11 => ['USD'=>22421,'EUR'=>24159,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                12 => ['USD'=>22676,'EUR'=>23834,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
            ],
            2017 => [
                1 => ['USD'=>22563,'EUR'=>23868,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                2 => ['USD'=>22677,'EUR'=>24076,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                3 => ['USD'=>22758,'EUR'=>24226,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                4 => ['USD'=>22673,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                5 => ['USD'=>22675,'EUR'=>24971,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                6 => ['USD'=>22675,'EUR'=>25364,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                7 => ['USD'=>22698,'EUR'=>26078,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                8 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                9 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                10 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                11 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                12 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
            ],
            2018 => [
                1 => ['USD'=>22563,'EUR'=>23868,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                2 => ['USD'=>22677,'EUR'=>24076,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                3 => ['USD'=>22758,'EUR'=>24226,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                4 => ['USD'=>22673,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                5 => ['USD'=>22675,'EUR'=>24971,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                6 => ['USD'=>22675,'EUR'=>25364,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                7 => ['USD'=>22698,'EUR'=>26078,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                8 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                9 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                10 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                11 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                12 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
            ],
            2019 => [
                1 => ['USD'=>22563,'EUR'=>23868,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                2 => ['USD'=>22677,'EUR'=>24076,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                3 => ['USD'=>22758,'EUR'=>24226,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                4 => ['USD'=>22673,'EUR'=>24223,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                5 => ['USD'=>22675,'EUR'=>24971,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                6 => ['USD'=>22675,'EUR'=>25364,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                7 => ['USD'=>22698,'EUR'=>26078,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                8 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                9 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                10 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                11 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
                12 => ['USD'=>22694,'EUR'=>26747,'VND'=>1,'LAK'=>2.73,'THB'=>685.56,'KHR'=>5.63],
            ],
        ];

        // Rates to VND
        $xRate = [
            'USD'=>[
                '2013-01'=>22376, '2013-02'=>22296, '2013-03'=>22263,
                '2013-04'=>22258, '2013-05'=>22281, '2013-06'=>22305,
                '2013-07'=>22263, '2013-08'=>22261, '2013-09'=>22267,
                '2013-10'=>22276, '2013-11'=>22421, '2013-12'=>22676,

                '2014-01'=>22376, '2014-02'=>22296, '2014-03'=>22263,
                '2014-04'=>22258, '2014-05'=>22281, '2014-06'=>22305,
                '2014-07'=>22263, '2014-08'=>22261, '2014-09'=>22267,
                '2014-10'=>22276, '2014-11'=>22421, '2014-12'=>22676,

                '2015-01'=>22376, '2015-02'=>22296, '2015-03'=>22263,
                '2015-04'=>22258, '2015-05'=>22281, '2015-06'=>22305,
                '2015-07'=>22263, '2015-08'=>22261, '2015-09'=>22267,
                '2015-10'=>22276, '2015-11'=>22421, '2015-12'=>22676,

                '2016-01'=>22376, '2016-02'=>22296, '2016-03'=>22263,
                '2016-04'=>22258, '2016-05'=>22281, '2016-06'=>22305,
                '2016-07'=>22263, '2016-08'=>22261, '2016-09'=>22267,
                '2016-10'=>22276, '2016-11'=>22421, '2016-12'=>22676,

                '2017-01'=>22563, '2017-02'=>22677, '2017-03'=>22758,
                '2017-04'=>22673, '2017-05'=>22675, '2017-06'=>22675,
                '2017-07'=>22698, '2017-08'=>22694, '2017-09'=>22694,
                '2017-10'=>22694, '2017-11'=>22694, '2017-12'=>22694,
        // DEMO
                '2018-01'=>22563, '2018-02'=>22677, '2018-03'=>22758,
                '2018-04'=>22673, '2018-05'=>22675, '2018-06'=>22675,
                '2018-07'=>22698, '2018-08'=>22694, '2018-09'=>22694,
                '2018-10'=>22694, '2018-11'=>22694, '2018-12'=>22694,

                '2019-01'=>22563, '2019-02'=>22677, '2019-03'=>22758,
                '2019-04'=>22673, '2019-05'=>22675, '2019-06'=>22675,
                '2019-07'=>22698, '2019-08'=>22694, '2019-09'=>22694,
                '2019-10'=>22694, '2019-11'=>22694, '2019-12'=>22694,

                '0000-00'=>22694,
            ],
            'EUR'=>[
                '2013-01'=>24223, '2013-02'=>24524, '2013-03'=>24683,
                '2013-04'=>25150, '2013-05'=>25075, '2013-06'=>24944,
                '2013-07'=>24533, '2013-08'=>24832, '2013-09'=>24869,
                '2013-10'=>24490, '2013-11'=>24159, '2013-12'=>23834,

                '2014-01'=>24223, '2014-02'=>24524, '2014-03'=>24683,
                '2014-04'=>25150, '2014-05'=>25075, '2014-06'=>24944,
                '2014-07'=>24533, '2014-08'=>24832, '2014-09'=>24869,
                '2014-10'=>24490, '2014-11'=>24159, '2014-12'=>23834,

                '2015-01'=>24223, '2015-02'=>24524, '2015-03'=>24683,
                '2015-04'=>25150, '2015-05'=>25075, '2015-06'=>24944,
                '2015-07'=>24533, '2015-08'=>24832, '2015-09'=>24869,
                '2015-10'=>24490, '2015-11'=>24159, '2015-12'=>23834,

                '2016-01'=>24223, '2016-02'=>24524, '2016-03'=>24683,
                '2016-04'=>25150, '2016-05'=>25075, '2016-06'=>24944,
                '2016-07'=>24533, '2016-08'=>24832, '2016-09'=>24869,
                '2016-10'=>24490, '2016-11'=>24159, '2016-12'=>23834,

                '2017-01'=>23868, '2017-02'=>24076, '2017-03'=>24226,
                '2017-04'=>24223, '2017-05'=>24971, '2017-06'=>25364,
                '2017-07'=>26078, '2017-08'=>26747, '2017-09'=>26747,
                '2017-10'=>26747, '2017-11'=>26747, '2017-12'=>26747,
        // DEMO
                '2018-01'=>23868, '2018-02'=>24076, '2018-03'=>24226,
                '2018-04'=>24223, '2018-05'=>24971, '2018-06'=>25364,
                '2018-07'=>26078, '2018-08'=>26747, '2018-09'=>26747,
                '2018-10'=>26747, '2018-11'=>26747, '2018-12'=>26747,

                '2019-01'=>23868, '2019-02'=>24076, '2019-03'=>24226,
                '2019-04'=>24223, '2019-05'=>24971, '2019-06'=>25364,
                '2019-07'=>26078, '2019-08'=>26747, '2019-09'=>26747,
                '2019-10'=>26747, '2019-11'=>26747, '2019-12'=>26747,

                '0000-00'=>26747,

            ],

        ];

        // $result[$yyyy][$mm][$index]
        $result = [];
        $detail = [];
        // 'Số tour', kx, tx, 'Số khách', 'Số ngày',
        // 'Số khách BQ /tour', 'Số ngày BQ /tour',
        // 'Doanh thu', 'Giá vốn', 'Lợi nhuận',
        // 'Doanh thu BQ /tour', 'Giá vốn BQ /tour', 'Lợi nhuận BQ /tour',
        // 'Doanh thu BQ /khách', 'Giá vốn BQ /khách', 'Lợi nhuận BQ /khách',
        // 'Doanh thu BQ /khách/ngày', 'Giá vốn BQ /khách/ngày', 'Lợi nhuận BQ /khách/ngày',

        $query = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
            ->where(['and', ['op_status'=>'op'], 'op_finish!="canceled"'])
            ->andWhere('SUBSTRING(op_code,1,1)="F"')
            ->with([
                'bookings'=>function($q){
                    return $q->select(['id', 'product_id', 'case_id', 'pax', 'created_at']);
                },
                'bookings.case',
                'bookings.case.stats',
                'bookings.invoices'=>function($q){
                    return $q->select(['id', 'booking_id', 'amount', 'currency', 'due_dt', 'stype']);
                },
                'bookings.invoices.payments'=>function($q){
                    return $q->select(['invoice_id', 'amount', 'currency', 'xrate', 'payment_dt']);
                },
                'bookings.report'=>function($q){
                    return $q->select(['booking_id', 'price', 'price_unit', 'cost', 'cost_unit']);
                },
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id']);
                },
                'tour.cpt'=>function($q){
                    return $q->select(['tour_id', 'qty', 'price', 'plusminus', 'unitc', 'dvtour_day', 'due']);
                },
                ])
            ;
        if ($view == 'tourstart') {
            $query->andHaving('YEAR(start_date)=:year', [':year'=>$year]);
        } else {
            $query->andHaving('YEAR(end_date)=:year', [':year'=>$year]);
        }
        if ($view == 'tourstart') {
            $query->andHaving('YEAR(start_date)=:year', [':year'=>$year]);
        }

        $theTours = $query
            ->asArray()
            ->all();

        for ($m = 0; $m <= 12; $m ++) {
            for ($i = 0; $i <= 20; $i ++) {
                // Con so thuc te
                $result[$year][$m][$i]['actual'] = 0;
                // Con so du tinh, neu co
                $result[$year][$m][$i]['estimated'] = 0;
                // Con so so sanh, neu co
                $result[$year][$m][$i]['comp'] = 0;
            }
            // Con so tim kiem
            $result[$year][$m]['tk'] = 0;
            // Ti le % con so tim kiem so voi thuc te
            $result[$year][$m]['pc'] = 0;
            // Doanh thu nguyen te
            $hoadonNguyente[$year][$m] = [];
            $thuNguyente[$year][$m] = [];
            foreach ($channelList as $k => $channel) {
                foreach ($typeList as $type => $tl) {
                    $result[$year][$m][$k][$type] = 0;
                }
            }
        }

        $xrate = [
            'EUR'=>1,
            'LAK'=>0.0001,
            'KHR'=>0.00021,
            'THB'=>0.026,
            'USD'=>0.85,
            'VND'=>0.000037,
        ];

        // Cac tham so tim kiem
        $sopaxMin = 0;
        $sopaxMax = 0;
        if ($sopax != '') {
            $sopaxArr = explode('-', $sopax);
            $sopaxMin = (int)trim($sopaxArr[0]);
            if (count($sopaxArr) == 2) {
                $sopaxMax = (int)trim($sopaxArr[1] ?? '0');
            }
            else {
                $sopaxMax = $sopaxMin;
            }
        }

        $songayMin = 0;
        $songayMax = 0;
        if ($songay != '') {
            $songayArr = explode('-', $songay);
            $songayMin = (int)trim($songayArr[0]);
            $songayMax = (int)trim($songayArr[1] ?? '0');
        }
        foreach ($theTours as $tour) {
            if (count($tour['bookings']) > 1) {
                var_dump($tour['bookings']);die;
            }
            $kx = $tour['bookings'][0]['case']['stats']['kx'];
            $tx = $tour['bookings'][0]['case']['how_found'];
            if ($kx == '') {
                $kx = 'k0';
            }
            if ($tx == '') {
                $tx = 'unknown';
            }
            // Thong so cua tour nay, neu thoa cac dieu kien tim kiem thi moi cho vao ket qua cuoi cung
            foreach ($indexList as $i=>$index) {
                $tourStat[$i] = [
                    'actual'=>0,
                    'estimated'=>0
                ];
            }

            if ($view == 'tourstart') {
                $month = (int)substr($tour['start_date'], 5, 2);
            } else {
                $month = (int)substr($tour['end_date'], 5, 2);
            }

            // test source

            foreach ($typeList as $type => $tp) {
                if (strpos($tx, $type) !== false) {
                    $tx = $type;
                }
            }
            if ((isset($kx_source) && !empty($kx_source) && !in_array($kx, $kx_source))
                || (isset($tx_source) && !empty($tx_source) && !in_array($tx, $tx_source))
            ) {
                continue;
            }
            if (!isset($result[$year][$month][$kx][$tx])) {
                var_dump($month);
                var_dump($kx);
                var_dump($ty);
                var_dump($result[$year][$month][$kx][$ty]);
                die;
            }

            $result[$year][$month][$kx][$tx] ++;

            // So tour
            $tourStat[0]['actual'] = 1;
            // So ngay
            $tourStat[2]['actual'] = $tour['day_count'];

            foreach ($tour['bookings'] as $booking) {
                // So khach
                $tourStat[1]['actual'] += $booking['pax'];

                // Doanh thu - thuc te
                foreach ($booking['invoices'] as $invoice) {
                    if (!isset($hoadonNguyente[$year][$month][$invoice['currency']])) {
                        $hoadonNguyente[$year][$month][$invoice['currency']] = 0;
                    }
                    $hoadonNguyente[$year][$month][$invoice['currency']] += $invoice['stype'] == 'credit' ? -$invoice['amount'] : $invoice['amount'];
                    // echo '<br>HDON THANG ', $month, ' += ', number_format($invoice['amount']), ' ', $invoice['currency'];

                    $cu = $invoice['currency'];
                    $mo = substr($invoice['due_dt'], 0, 7);
                    if ($cu == $currency) {
                        // Cung loai tien xem ket qua
                        $am = $invoice['amount'];
                    } else {
                        if ($currency == 'VND') {
                            $am = $xRate[$cu][$mo] * $invoice['amount'];
                        } else {
                            $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $invoice['amount'];
                        }
                    }

                    if ($invoice['stype'] == 'credit') {
                        $am = -$am;
                    }

                    $tourStat[5]['estimated'] += $am;

                    if ($month == 12 && USER_ID == 1) {
                        // echo '<br>', $mo, ': ', $invoice['amount'], ' ', $invoice['currency'], ' (x', $xRate[$cu][$mo] ?? 1, ') = ', number_format($am);
                        // echo ' ==> ', number_format($result[$year][$month][5]['estimated']);
                    }

                    foreach ($invoice['payments'] as $payment) {
                        if (!isset($thuNguyente[$year][$month][$payment['currency']])) {
                            $thuNguyente[$year][$month][$payment['currency']] = 0;
                        }
                        $thuNguyente[$year][$month][$payment['currency']] += $invoice['stype'] == 'credit' ? -$payment['amount'] : $payment['amount'];
                        // if ($month == 9) {
                        //     echo '<br>--------------- THU THANG ', $month, ' += ', number_format($payment['amount']), ' ', $payment['currency'];
                        // }

                        // TODO: use payment's exchange rate

                        $cu = $payment['currency'];
                        $mo = substr($payment['payment_dt'], 0, 7);
                        if ($cu == $currency) {
                            // Cung loai tien xem ket qua
                            $am = $payment['amount'];
                        } else {
                            if ($currency == 'VND') {
                                $am = ($payment['xrate'] > 1 ? $payment['xrate'] : $xRate[$cu][$mo]) * $payment['amount'];
                            } else {
                                $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $payment['amount'];
                            }
                        }

                        if ($invoice['stype'] == 'credit') {
                            $am = -$am;
                        }

                        $tourStat[5]['actual'] += $am;
                    }
                }

                // Gia von - du tinh
                if ($booking['report']) {
                    $cu = $booking['report']['cost_unit'];
                    $mo = substr($booking['created_at'], 0, 7);
                    if ($cu == $currency) {
                        // Cung loai tien xem ket qua
                        $am = $booking['report']['cost'];
                    } else {
                        if ($currency == 'VND') {
                            if (!isset($xRate[$cu][$mo])) {
                                echo $cu, '/', $mo;
                                exit;
                            }
                            $am = $xRate[$cu][$mo] * $booking['report']['cost'];
                        } else {
                            $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $booking['report']['cost'];
                        }
                    }

                    $tourStat[6]['estimated'] += $am;
                }
            }

            if (!empty($tour['tour']['cpt'])) {

                foreach ($tour['tour']['cpt'] as $cpt) {
                    $cu = $cpt['unitc'];
                    $mo = substr($cpt['due'] == '0000-00-00' ? $cpt['dvtour_day'] : $cpt['due'], 0, 7);

                    if ($cu == $currency) {
                        // Cung loai tien xem ket qua
                        $am = $cpt['qty'] * $cpt['price'];
                    } else {
                        if ($currency == 'VND') {
                            $am = ($xRate[$cu][$mo] ?? $xrate[$cu]) * $cpt['qty'] * $cpt['price'];
                        } else {
                            if (isset($xRate[$currency][$mo])) {
                                $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $cpt['qty'] * $cpt['price'];
                            } else {
                                // Mot so loai tien khong co ti gia ke toan
                                $am = $xrate[$cu] / $xrate[$currency] * $cpt['qty'] * $cpt['price'];
                            }
                        }
                    }

                    if ($cpt['plusminus'] == 'minus') {
                        $am = -$am;
                    }

                    $tourStat[6]['actual'] += $am;
                }
            }

            // Loi nhuan
            $tourStat[7]['actual'] = $tourStat[5]['actual'] - $tourStat[6]['actual'];
            $tourStat[7]['estimated'] = $tourStat[5]['estimated'] - $tourStat[6]['estimated'];

            // Kiem tra dieu kien tim kiem
            $songayOk = false;
            $sopaxOk = true;
            $sourceOk = true;
            $diemdenOk = true;

            if ($sopax != '' && ($tourStat[1]['actual'] < $sopaxMin || $tourStat[1]['actual'] > $sopaxMax)) {
                $sopaxOk = false;
            }
            if ($songay == '' || (($songayMin != 0 || $songayMax !=0) && $songayMin <= $tour['day_count'] && $tour['day_count'] <= $songayMax)) {
                $songayOk = true;
            }


            if ((isset($kx_source) && !empty($kx_source) && !in_array($kx, $kx_source))
                || (isset($tx_source) && !empty($tx_source) && !in_array($tx, $tx_source))
            ) {
                $sourceOk = false;
            }
            if (isset($diemden) && is_array($diemden) && !empty($diemden)) {

                $tour_countries = $tour['bookings'][0]['case']['stats']['req_countries'];
                if ($dkdiemden == 'all' || $dkdiemden == 'only') {

                    foreach ($diemden as $dest) {
                        if (strpos($tour_countries, $dest) === false) {
                            $diemdenOk = false;
                        }
                    }
                    if ($dkdiemden == 'only') {
                        if (strlen($tour_countries) != 2 * count($diemden) + count($diemden) - 1) {
                            $diemdenOk = false;
                        }
                    }
                } elseif ($dkdiemden == 'any') {
                    $orConditions = '(';
                    foreach ($diemden as $dest) {
                        if (strpos($tour_countries, $dest) !== false) {
                            $diemdenOk = true;
                            break;
                        } else {
                            $diemdenOk = false;
                        }
                    }
                } else {
                    // Exact
                    asort($diemden);
                    $destList = implode('|', $diemden);
                   if ($tour_countries != $destList) {
                    $diemdenOk = false;
                   }
                }
            }

            $filterOk = $sopaxOk && $songayOk && $sourceOk;

            if ($filterOk) {
                // Tour nay thoa dieu kien tim kiem, cho vao ket qua chung
                foreach ($indexList as $i=>$index) {
                    $result[$year][$month][$i]['actual'] += $tourStat[$i]['actual'] ?? 0;
                    $result[$year][$month][$i]['estimated'] += $tourStat[$i]['estimated'] ?? 0;
                }
                // $result[$year][$month]['tk'] ++;
                // $result[$year][$month]['pc'] = $result[$year][$month][0] == 0 ? 0 : 100 * ($result[$year][$month]['tk'] / $result[$year][$month][0]);

                if (!isset($detail[$month])) {
                    $detail[$month] = [];
                }
                $detail[$month][] = [
                    $tour['id'],
                    $tour['op_code'],
                    $tour['op_name'],
                    $tourStat[5]['actual'],
                    $tourStat[6]['actual'],
                    $tourStat[2]['actual'],
                    $tourStat[1]['actual'],
                    $kx,
                    $tx,
                ];


            }
        }
        for ($m = 1; $m <= 12; $m ++) {
            // Tinh bang cong thuc tu dong cho cac index
            foreach ($indexList as $i=>$index) {
                if (isset($index['avg']) && is_array($index['avg'])) {
                    // Average
                    $result[$year][$m][$i]['actual'] = $result[$year][$m][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['actual'] / $result[$year][$m][$index['avg'][1]]['actual'];
                    if (isset($indexList[$i]['est'])) {
                        if (isset($indexList[$index['avg'][1]]['est'])) {
                            $result[$year][$m][$i]['estimated'] = $result[$year][$m][$index['avg'][1]]['estimated'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['estimated'] / $result[$year][$m][$index['avg'][1]]['estimated'];
                        } else {
                            $result[$year][$m][$i]['estimated'] = $result[$year][$m][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['estimated'] / $result[$year][$m][$index['avg'][1]]['actual'];
                        }
                    }
                    // For markup
                    if (isset($index['minus1'])) {
                        $result[$year][$m][$i]['actual'] -= 1;
                        $result[$year][$m][$i]['estimated'] -= 1;
                    }
                    // For percentage
                    if (isset($index['pct'])) {
                        $result[$year][$m][$i]['actual'] *= 100;
                        $result[$year][$m][$i]['estimated'] *= 100;
                    }
                }
            }
        }

        // Year total
        foreach ($indexList as $i=>$index) {
            if (isset($index['avg']) && is_array($index['avg'])) {
                // Average
                $result[$year][0][$i]['actual'] = $result[$year][0][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['actual'] / $result[$year][0][$index['avg'][1]]['actual'];
                if (isset($indexList[$i]['est'])) {
                    if (isset($indexList[$index['avg'][1]]['est'])) {
                        $result[$year][0][$i]['estimated'] = $result[$year][0][$index['avg'][1]]['estimated'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['estimated'] / $result[$year][0][$index['avg'][1]]['estimated'];
                    } else {
                        $result[$year][0][$i]['estimated'] = $result[$year][0][$index['avg'][1]]['actual'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['estimated'] / $result[$year][0][$index['avg'][1]]['actual'];
                    }
                }
                // For markup
                if (isset($index['minus1'])) {
                    $result[$year][0][$i]['actual'] -= 1;
                    $result[$year][0][$i]['estimated'] -= 1;
                }
                // For percentage
                if (isset($index['pct'])) {
                    $result[$year][0][$i]['actual'] *= 100;
                    $result[$year][0][$i]['estimated'] *= 100;
                }
            } else {
                // Total
                for ($m = 1; $m <= 12; $m ++) {
                    $result[$year][0][$i]['actual'] += $result[$year][$m][$i]['actual'];
                    $result[$year][0][$i]['estimated'] += $result[$year][$m][$i]['estimated'];
                }
            }

        }


        // Binh quan
        // So pax
        // $result[$year][0][3]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][1]['actual'] / $result[$year][0][0]['actual'];
        // So ngay
        // $result[$year][0][4]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
        // Ti le lai
        // $result[$year][0][17]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
        // Ti le markup
        // $result[$year][0][18]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];

        // Doanh thu BQ/tour
        // $result[$year][0][8]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][5]['actual'] / $result[$year][0][0]['actual'];
        // Chi phi BQ/pax
        // $result[$year][0][9]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][6]['actual'] / $result[$year][0][0]['actual'];
        // Loi nhuan BQ/pax
        // $result[$year][0][10]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][7]['actual'] / $result[$year][0][0]['actual'];

        // Doanh thu BQ/pax
        // $result[$year][0][11]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
        // Chi phi BQ/pax
        // $result[$year][0][12]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
        // Loi nhuan BQ/pax
        // $result[$year][0][13]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];

        // Ti le markup
        // $result[$year][0][14]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
        // Ti le markup
        // $result[$year][0][15]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];
        // Ti le markup
        // $result[$year][0][16]['actual'] = $result[$year][0][0]['actual'] == 0 ? 0 : $result[$year][0][2]['actual'] / $result[$year][0][0]['actual'];

        if ($year2 != 0 && $year2 != $year) {
            $query2 = Product::find()
                ->select(['id', 'op_code', 'op_name', 'day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
                ->where(['and', ['op_status'=>'op'], 'op_finish!="canceled"'])
                ->andWhere('SUBSTRING(op_code,1,1)="F"')
                ->with([
                    'bookings'=>function($q){
                        return $q->select(['id', 'product_id', 'pax', 'created_at']);
                    },
                    'bookings.invoices'=>function($q){
                        return $q->select(['id', 'booking_id', 'amount', 'currency', 'due_dt', 'stype']);
                    },
                    'bookings.invoices.payments'=>function($q){
                        return $q->select(['invoice_id', 'amount', 'currency', 'xrate', 'payment_dt']);
                    },
                    'bookings.report'=>function($q){
                        return $q->select(['booking_id', 'price', 'price_unit', 'cost', 'cost_unit']);
                    },
                    'tour'=>function($q){
                        return $q->select(['id', 'ct_id']);
                    },
                    'tour.cpt'=>function($q){
                        return $q->select(['tour_id', 'qty', 'price', 'plusminus', 'unitc', 'dvtour_day', 'due']);
                    },
                    ])
                ;
            if ($view == 'tourstart') {
                $query2->andHaving('YEAR(start_date)=:year', [':year'=>$year2]);
            } else {
                $query2->andHaving('YEAR(end_date)=:year', [':year'=>$year2]);
            }

            $theTours2 = $query2
                ->asArray()
                ->all();

        foreach ($theTours2 as $tour) {
            // Thong so cua tour nay, neu thoa cac dieu kien tim kiem thi moi cho vao ket qua cuoi cung
            foreach ($indexList as $i=>$index) {
                $tourStat[$i] = [
                    'comp'=>0,
                ];
            }

            if ($view == 'tourstart') {
                $month = (int)substr($tour['start_date'], 5, 2);
            } else {
                $month = (int)substr($tour['end_date'], 5, 2);
            }

            // So tour
            $tourStat[0]['comp'] = 1;
            // So ngay
            $tourStat[2]['comp'] = $tour['day_count'];

            foreach ($tour['bookings'] as $booking) {
                // So khach
                $tourStat[1]['comp'] += $booking['pax'];

                // Doanh thu - thuc te
                foreach ($booking['invoices'] as $invoice) {
                    // if (!isset($hoadonNguyente[$year][$month][$invoice['currency']])) {
                    //     $hoadonNguyente[$year][$month][$invoice['currency']] = 0;
                    // }
                    // $hoadonNguyente[$year][$month][$invoice['currency']] += $invoice['stype'] == 'credit' ? -$invoice['amount'] : $invoice['amount'];

                    $cu = $invoice['currency'];
                    $mo = substr($invoice['due_dt'], 0, 7);
                    if ($cu == $currency) {
                        // Cung loai tien xem ket qua
                        $am = $invoice['amount'];
                    } else {
                        if ($currency == 'VND') {
                            $am = $xRate[$cu][$mo] * $invoice['amount'];
                        } else {
                            $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $invoice['amount'];
                        }
                    }

                    if ($invoice['stype'] == 'credit') {
                        $am = -$am;
                    }

                    // $tourStat[5]['estimated'] += $am;

                    foreach ($invoice['payments'] as $payment) {
                        // if (!isset($thuNguyente[$year][$month][$payment['currency']])) {
                        //     $thuNguyente[$year][$month][$payment['currency']] = 0;
                        // }
                        // $thuNguyente[$year][$month][$payment['currency']] += $invoice['stype'] == 'credit' ? -$payment['amount'] : $payment['amount'];

                        $cu = $payment['currency'];
                        $mo = substr($payment['payment_dt'], 0, 7);
                        if ($cu == $currency) {
                            // Cung loai tien xem ket qua
                            $am = $payment['amount'];
                        } else {
                            if ($currency == 'VND') {
                                $am = ($payment['xrate'] > 1 ? $payment['xrate'] : $xRate[$cu][$mo]) * $payment['amount'];
                            } else {
                                $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $payment['amount'];
                            }
                        }

                        if ($invoice['stype'] == 'credit') {
                            $am = -$am;
                        }

                        $tourStat[5]['comp'] += $am;
                    }
                }

                // Gia von - du tinh
                if ($booking['report']) {
                    $cu = $booking['report']['cost_unit'];
                    $mo = substr($booking['created_at'], 0, 7);
                    if ($cu == $currency) {
                        // Cung loai tien xem ket qua
                        $am = $booking['report']['cost'];
                    } else {
                        if ($currency == 'VND') {
                            if (!isset($xRate[$cu][$mo])) {
                                echo $cu, '/', $mo;
                                exit;
                            }
                            $am = $xRate[$cu][$mo] * $booking['report']['cost'];
                        } else {
                            $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $booking['report']['cost'];
                        }
                    }

                    // $tourStat[6]['estimated'] += $am;
                }
            }

            if (!empty($tour['tour']['cpt'])) {

                foreach ($tour['tour']['cpt'] as $cpt) {
                    $cu = $cpt['unitc'];
                    $mo = substr($cpt['due'] == '0000-00-00' ? $cpt['dvtour_day'] : $cpt['due'], 0, 7);

                    if ($cu == $currency) {
                        // Cung loai tien xem ket qua
                        $am = $cpt['qty'] * $cpt['price'];
                    } else {
                        if ($currency == 'VND') {
                            $am = ($xRate[$cu][$mo] ?? $xrate[$cu]) * $cpt['qty'] * $cpt['price'];
                        } else {
                            if (isset($xRate[$currency][$mo])) {
                                $am = ($xRate[$cu][$mo] ?? 1) / $xRate[$currency][$mo] * $cpt['qty'] * $cpt['price'];
                            } else {
                                // Mot so loai tien khong co ti gia ke toan
                                $am = $xate[$cu] / $xrate[$currency] * $cpt['qty'] * $cpt['price'];
                            }
                        }
                    }

                    if ($cpt['plusminus'] == 'minus') {
                        $am = -$am;
                    }

                    $tourStat[6]['comp'] += $am;
                }
            }

            // Loi nhuan
            $tourStat[7]['comp'] = $tourStat[5]['comp'] - $tourStat[6]['comp'];
            // $tourStat[7]['estimated'] = $tourStat[5]['estimated'] - $tourStat[6]['estimated'];

            // Kiem tra dieu kien tim kiem
            $songayOk = false;
            $sopaxOk = true;

            if ($sopax != '' && ($tourStat[1]['comp'] < $sopaxMin || $tourStat[1]['comp'] > $sopaxMax)) {
                $sopaxOk = false;
            }
            if ($songay == '' || (($songayMin != 0 || $songayMax !=0) && $songayMin <= $tour['day_count'] && $tour['day_count'] <= $songayMax)) {
                $songayOk = true;
            }

            $filterOk = $sopaxOk && $songayOk;

            if ($filterOk) {
                // Tour nay thoa dieu kien tim kiem, cho vao ket qua chung
                foreach ($indexList as $i=>$index) {
                    $result[$year][$month][$i]['comp'] += $tourStat[$i]['comp'] ?? 0;
                    // $result[$year][$month][$i]['estimated'] += $tourStat[$i]['estimated'] ?? 0;
                }
                // $result[$year][$month]['tk'] ++;
                // $result[$year][$month]['pc'] = $result[$year][$month][0] == 0 ? 0 : 100 * ($result[$year][$month]['tk'] / $result[$year][$month][0]);

                // if (!isset($detail[$month])) {
                //     $detail[$month] = [];
                // }
                // $detail[$month][] = [
                //     $tour['id'],
                //     $tour['op_code'],
                //     $tour['op_name'],
                //     $tourStat[5]['actual'],
                //     $tourStat[6]['actual'],
                //     $tourStat[2]['actual'],
                //     $tourStat[1]['actual'],
                // ];


            }
        }

        for ($m = 1; $m <= 12; $m ++) {
            // Tinh bang cong thuc tu dong cho cac index
            foreach ($indexList as $i=>$index) {
                if (isset($index['avg']) && is_array($index['avg'])) {
                    // Average
                    $result[$year][$m][$i]['comp'] = $result[$year][$m][$index['avg'][1]]['comp'] == 0 ? 0 : $result[$year][$m][$index['avg'][0]]['comp'] / $result[$year][$m][$index['avg'][1]]['comp'];

                    // For markup
                    if (isset($index['minus1'])) {
                        $result[$year][$m][$i]['comp'] -= 1;
                    }
                    // For percentage
                    if (isset($index['pct'])) {
                        $result[$year][$m][$i]['comp'] *= 100;
                    }
                }
            }
        }

        // Year total
        foreach ($indexList as $i=>$index) {
            if (isset($index['avg']) && is_array($index['avg'])) {
                // Average
                $result[$year][0][$i]['comp'] = $result[$year][0][$index['avg'][1]]['comp'] == 0 ? 0 : $result[$year][0][$index['avg'][0]]['comp'] / $result[$year][0][$index['avg'][1]]['comp'];
                // For markup
                if (isset($index['minus1'])) {
                    $result[$year][0][$i]['comp'] -= 1;
                }
                // For percentage
                if (isset($index['pct'])) {
                    $result[$year][0][$i]['comp'] *= 100;
                }
            } else {
                // Total
                for ($m = 1; $m <= 12; $m ++) {
                    $result[$year][0][$i]['comp'] += $result[$year][$m][$i]['comp'];
                }
            }
        }

        } // if year2
        if (isset($_GET['export-data'])) {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->mergeCells('A1:A2');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'Source');
            $columnIndex = 2;
            for ($m = 1; $m <= 13; $m ++) {
                $spreadsheet->getActiveSheet()->mergeCells($this->stringFromColumnIndex($columnIndex).'1:' . $this->stringFromColumnIndex($columnIndex + 2) . '1');
                $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex).'1', ($m <= 12) ? $m : 'total');
                $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex).'2', 'New');
                $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex + 1).'2', 'Returning');
                $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex + 2).'2', 'Referred');
                $columnIndex += 3;
            }
            $k = 3;
            foreach ($channelList as $kx => $channel) {
                $arr_row = [];
                $arr_row[] = $kx;
                $total_year['new'] = 0;
                $total_year['returning'] = 0;
                $total_year['referred'] = 0;
                $spreadsheet->getActiveSheet()->setCellValue('A'.$k, $kx);
                for ($m = 1; $m <= 12; $m ++) {
                    $total_year['new'] += $result[$year][$m][$kx]['new'];
                    $total_year['returning'] += $result[$year][$m][$kx]['returning'];
                    $total_year['referred'] += $result[$year][$m][$kx]['returning'];
                    $arr_row[] = $result[$year][$m][$kx]['new'];
                    $arr_row[] = $result[$year][$m][$kx]['returning'];
                    $arr_row[] = $result[$year][$m][$kx]['referred'];
                }

                $arr_row[] = $total_year['new'];
                $arr_row[] = $total_year['returning'];
                $arr_row[] = $total_year['referred'];
                $spreadsheet->getActiveSheet()->fromArray($arr_row, null, 'A'.$k);
                $k++;
            }
            // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            // $writer->save("05featuredemo.xlsx");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename='. rand(1, 100) . 'report.Xlsx');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }

        return $this->controller->render('report_b2c', [
            'tourCount'=>count($theTours),
            'indexList'=>$indexList,
            'xrateTable'=>$arr_xrate,
            'result'=>$result,
            'detail'=>$detail,
            'view'=>$view,
            'year'=>$year,
            'year2'=>$year2,
            'currency'=>$currency,
            'xrate'=>$xrate,
            'sopax'=>$sopax,
            'songay'=>$songay,
            'doanhthu'=>$doanhthu,
            'loinhuan'=>$loinhuan,
            'diemden'=>$diemden,
            'dkdiemden'=>$dkdiemden,
            'hoadonNguyente'=>$hoadonNguyente,
            'thuNguyente'=>$thuNguyente,

            'channelList' => $channelList,
            'typeList' =>$typeList,
            'kx_source' => $kx_source,
            'tx_source' => $tx_source
        ]);
    }
}
?>