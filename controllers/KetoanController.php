<?

namespace app\controllers;

use Yii;
use yii\web\HttpException;

use common\models\Booking;
use common\models\Dvt;
use common\models\Dv;
use common\models\Dvg;
use common\models\Invoice;
use common\models\Payment;
use common\models\Product;
use common\models\Tour;
use common\models\Venue;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Danh cho Ke toan
class KetoanController extends MyController
{
    // 161227 Duc Hanh nhap file Excel
    public function actionPaymentExcel()
    {
        // 171004 Added Thu Hien
        // if (!in_array(USER_ID, [1, 11, 17])) {
        //     throw new HttpException(403, 'Go away!');
        // }

        $results = false;
        $theTours = false;


        if (!empty($_POST['data'])) {
            $tourCodeList = [];
            $data = $_POST['data'];
            $match = preg_match_all('/"[\w+\s*(\n+)]+"/', $data, $matches);
            foreach ($matches[0] as $str_val) {
                $str = implode('__', explode(PHP_EOL, $str_val));
                $data = str_replace($str_val, $str, $data);
            }
            $lines = explode(PHP_EOL, $data);
            foreach ($lines as $line) {
                $line = str_replace(chr(9), ']|[', $line);
                $cells = explode(']|[', $line);
                if (isset($cells[6]) && $cells[6] != '') {
                    $tourCodeList[] = $cells[1];
                    foreach ($cells as $k => $val) {
                        if ($cells[$k] != '' && strpos($cells[$k], '__') !== false) {
                            $cells[$k] = str_replace(['__', '"'], ['<br/>', ''], $cells[$k]);
                        }
                    }
                    $results[] = $cells;
                }
            }

            // Search for tours
            $theTours = Product::find()
                ->select(['id', 'op_code'])
                ->where(['op_status'=>'op', 'op_code'=>$tourCodeList])
                ->with([
                    'bookings',
                    'bookings.invoices',
                    ])
                ->asArray()
                ->all();
        }

        if (isset($_FILES["import"]) && $_FILES["import"] != '' && $_FILES["import"]["tmp_name"] != '') {
            $results = false;
            $tourCodeList = [];

            $tmp_name = $_FILES["import"]["tmp_name"];
            $spreadsheet = IOFactory::load($tmp_name);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            foreach ($sheetData as $row => $line) {
                foreach ($line as $cell) {
                    $results[$row][] = str_replace('&nbsp;', '', trim(htmlentities($cell)));
                }
                $tourCodeList[] = $results[$row][1];
            }
            // Search for tours
            $theTours = Product::find()
                ->select(['id', 'op_code'])
                ->where(['op_status'=>'op', 'op_code'=>$tourCodeList])
                ->with([
                    'bookings',
                    'bookings.invoices',
                    ])
                ->asArray()
                ->all();
        }
        if ($results) {
            Yii::$app->session->set('results', $results);
        }
        $arr_results = false;
        if (isset($_POST['order'])) {
            $results = Yii::$app->session->get('results');
            $remove_first_row = false;
            if (isset($_POST['check_row_1'])) {
                $remove_first_row = true;
            }
            $order_POST = $_POST['order'];
            foreach ($results as $i => $row) {
                if ($remove_first_row && $i == 1) {
                    continue;
                }
                $arr = [];
                foreach ($row as $k => $v) {
                    $v = str_replace('&nbsp;', '', $v);
                    if ($order_POST[$k] == 'payment_dt') {
                        if (strpos($v,'-') == false && strpos($v, '/') == false) {
                            continue 2;
                        }
                        $tmp_v = str_replace('/', '-', $v);
                        $arr_dt = explode('-', $tmp_v);
                        if (count($arr_dt) != 3) {
                            die('not ok');
                        }
                        $dt = $arr_dt[2].'-'.$arr_dt[1].'-'.$arr_dt[0];
                        $v = date('Y-m-d', strtotime($dt));
                    }
                    $arr[$order_POST[$k]] = $v;
                }
                $arr_results[$i] = $arr;

            }
            Yii::$app->session->set('arr_results', $arr_results);
        }


        //save
        if (isset($_POST['save_btn'])) {
            var_dump(Yii::$app->session->get('arr_results'));die;
        }
        if (isset($_POST['cancel_save'])) {
            if (Yii::$app->session->get('results')) {
                $results = Yii::$app->session->get('results');
            }
            $arr_results = false;
        }
        if (isset($_POST['cancel_next'])) {
            $results = false;
            $arr_results = false;
        }

        /*
        - Hình thức thanh toán
        - Tour code
        - Ref. hoá đơn
        - Số tiền được thanh toán
        - Loại tiền
        - Tỉ giá với VND
        - Ngày hạch toán
        - Note
        Onepay]|[F1702055]|[F170205503]|[3,737,364]|[VND]|[1]|[27/12/2016]|[Note1
        Onepay]|[F1701037]|[F170103702]|[109,863,023]|[VND]|[1]|[27/12/2016]|[Note2
        Onepay]|[F1701090]|[F170109001]|[19,240,277]|[VND]|[1]|[27/12/2016]|[Note3
        */
        return $this->render('ketoan_payment-excel', [
            'theTours'=>$theTours,
            'results'=>$results,
            'arr_results' => $arr_results,
        ]);
    }


    public function actionUpdatePayment($payment = 0, $invoice = 0) {
        if (USER_ID != 1) {
            throw new HttpException(403, 'Access denied.');
        }
        $sql = 'UPDATE at_payments SET invoice_id = :invoice WHERE id=:payment LIMIT 1';
        Yii::$app->db->createCommand($sql, [':invoice'=>$invoice, ':payment'=>$payment])->execute();
        echo 'DONE';
    }

    // 160315: Lan muốn xuất danh sách tour vs hoá đơn
    // 160613: Lan muốn xuất theo format mới
    public function actionDoanhThuTour($month = '', $output = '', $usd = 22500, $eur = 24500) {
        if ($month == '') {
            $month = date('Y-m');
        }
        $xrate = [
            'EUR'=>$eur,
            'USD'=>$usd,
        ];
        // Cac tour trong thang
        $sql = 'select op_code, op_name, op_finish, day_from, date_add(day_from, interval day_count-1 day) as day_until, day_count, p.pax, u.name AS seller, IF(company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=company_id LIMIT 1)) AS company from at_bookings b, at_cases k, at_ct p, persons u where k.id=b.case_id AND p.id=b.product_id AND u.id=k.owner_id AND substring(day_from, 1, 7)=:month AND op_status="op" order by day_from, p.id';
        $theTours = Product::find()
            ->select(['id', 'op_code', 'op_name', 'op_finish', 'day_in'=>'day_from', new \yii\db\Expression('date_add(day_from, interval day_count-1 day) as day_out'), 'days'=>'day_count', 'pax'])
            ->with([
                'tour',
                'tourStats'=>function($q) {
                    return $q->select(['tour_id', 'countries']);
                },
                'bookings',
                'bookings.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'bookings.case',
                'bookings.case.company',
                'bookings.case.owner',
                'bookings.invoices'=>function($q) {
                    return $q->orderBy('currency');
                },
                'bookings.payments',
                'bookings.invoices.payments',
                ])
            ->where(['op_status'=>'op'])
            ->andWhere('day_count>=1')
            ->andHaving('substring(day_out, 1, 7)=:month', [':month'=>$month])
            ->orderBy('day_out')
            ->asArray()
            ->all();
        // Check
        if ($output == 'check') {
            if (USER_ID != 1) die('Dang update. Xin doi.');
            foreach ($theTours as $tour) {
                echo '<br><strong>', $tour['op_code'], '</strong>';
                foreach ($tour['bookings'] as $booking) {
                    foreach ($booking['invoices'] as $invoice) {
                        echo '<br> ----- ', $invoice['ref'], ' : ', $invoice['amount'], ' ', $invoice['currency'], ' x ', $invoice['gw_xrate'];
                        foreach ($booking['payments'] as $payment) {
                            echo '<br> ----- ----- ', $payment['ref'], ' : ', $payment['amount'], ' x ', $payment['xrate'];
                            if ($payment['invoice_id'] == 0) {
                                echo ' ----- <span style="color:red">NO-ID</span>';
                            } elseif ($payment['invoice_id'] == $invoice['id']) {
                                echo ' ----- <span style="color:green">ID</span>';
                            } else {
                                echo ' ----- <span style="color:red">ID</span>';
                            }
                            if ($invoice['ref'] == '') {
                                echo ' ----- <span style="color:red">NO-REF</span>';
                            } elseif ($invoice['ref'] != $payment['ref']) {
                                echo ' ----- <span style="color:red">REF</span>';
                            } else {
                                echo ' ----- <span style="color:green">REF</span>';
                                //if ($payment['invoice_id'] == 0 && isset($_GET['update']) && $_GET['update'] == 'yes') {
                                if (isset($_GET['update']) && $_GET['update'] == 'yes') {
                                    $sql = 'UPDATE at_payments SET invoice_id=:i WHERE id=:p LIMIT 1';
                                    Yii::$app->db->createCommand($sql, [':i'=>$invoice['id'], ':p'=>$payment['id']])->execute();
                                }
                            }
                            echo ' (i ', $invoice['id'], ') (p ', $payment['id'], ') (pi ', $payment['invoice_id'], ') <a target="_blank" href="/ketoan/update-payment?payment=', $payment['id'], '&invoice=', $invoice['id'],'">update</a>';
                        }
                    }
                }
            }
            exit;
        }
        // Download
        if ($output == 'download') {
            $filename = 'doanh_thu_tour_ket_thuc_'.$month.'_'.date('Ymd-His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename='.$filename);

            $out = fopen('php://output', 'w');
            fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

            $arr = ['CODE', 'HOA DON', 'LOAI TIEN', 'DA THU', 'VND', 'BAN HANG', 'QUOC GIA', '+', '-', 'NHO THU'];
            fputcsv($out, $arr);

            $output = [];
            foreach ($theTours as $tour) {
                if (!isset($output[$tour['op_code']])) {
                    $output[$tour['op_code']]['countries'] = strtoupper($tour['tourStats']['countries']);
                    $output[$tour['op_code']]['sellers'] = [];
                    $output[$tour['op_code']]['invoice_count'] = 0;
                    $output[$tour['op_code']]['invoices']['+'] = [
                        'nhothu'=>'',
                        'total'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                        'paid'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                    ];
                    $output[$tour['op_code']]['invoices']['-'] = [
                        'nhothu'=>'',
                        'total'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                        'paid'=>[
                            'USD'=>0, 'EUR'=>0, 'VND'=>0, 'LAK'=>0, 'KHR'=>0,
                        ],
                    ];
                }
                foreach ($tour['bookings'] as $booking) {
                    $output[$tour['op_code']]['sellers'][] = $booking['case']['owner']['name'];
                    foreach ($booking['invoices'] as $invoice) {
                        $output[$tour['op_code']]['invoice_count'] ++;
                        if ($invoice['stype'] == 'invoice') {
                            $output[$tour['op_code']]['invoices']['+']['total'][$invoice['currency']] += $invoice['amount'];
                            $output[$tour['op_code']]['invoices']['+']['nhothu'] = $invoice['nho_thu'];
                            foreach ($invoice['payments'] as $payment) {
                                $output[$tour['op_code']]['invoices']['+']['paid']['VND'] += $payment['xrate'] * $payment['amount'];
                            }
                        } else {
                            $output[$tour['op_code']]['invoices']['-']['total'][$invoice['currency']] += $invoice['amount'];
                            $output[$tour['op_code']]['invoices']['+']['nhothu'] = $invoice['nho_thu'];
                            foreach ($invoice['payments'] as $payment) {
                                $output[$tour['op_code']]['invoices']['-']['paid']['VND'] += $payment['xrate'] * $payment['amount'];
                            }
                        }
                    }
                }
            }
//            \fCore::expose($output);
//            exit;
            foreach ($output as $code=>$item) {
                if ($item['invoice_count'] == 0) {
                    fputcsv($out, $arr);
                    $arr = [
                        $code,
                        '',
                        '',
                        '',
                        '',
                        implode(', ', $item['sellers']),
                        $item['countries'],
                        '',
                        '',
                        '',
                    ];
                    fputcsv($out, $arr);
                } else {
                    foreach ($item['invoices'] as $pmkey=>$pm) {
                        foreach ($pm['total'] as $curr=>$num) {
                            if ($num != 0) {
                                $paid = 0;
                                foreach ($pm['paid'] as $pcurr=>$pnum) {
                                    if ($pnum != 0 && $pcurr == 'VND') {
                                        $paid += $pnum;
                                    }
                                }
                                $arr = [
                                    $code,
                                    $num,
                                    $curr,
                                    $paid,
                                    'VND',
                                    implode(', ', $item['sellers']),
                                    $item['countries'],
                                    $pmkey == '+' ? 'invoice' : '',
                                    $pmkey == '-' ? 'refund' : '',
                                    $pm['nhothu'],
                                ];
                                fputcsv($out, $arr);
                            }
                        }
                    }
                }
            }

            fclose($out);
            exit;
        }
        // exit;
        return $this->render('ketoan_doanh-thu-tour', [
            'theTours'=>$theTours,
            'month'=>$month,
            'usd'=>$usd,
            'eur'=>$eur,
        ]);
    }

    // 151029: Tu Phuong muon lich trinh tour
    public function actionPhuongLichtrinh151029($month = '') {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }
        $tours = Product::find()
            ->select(['id', 'op_code', 'day_from', 'day_ids'])
            ->where(['op_status'=>'op'])
            ->andWhere('SUBSTRING(day_from,1,7)=:month', [':month'=>$month])
            ->with([
                'days'=>function($q) {
                    return $q->select(['rid', 'id', 'name']);
                }
                ])
            ->orderBy('day_from')
            ->asArray()
            ->all();
        echo '<style>table {border-collapse:collapse;} td {border:1px solid #ccc; padding:4px;}</style><table>';
        foreach ($tours as $tour) {
            $cnt = 0;
            $dayIdList = explode(',', $tour['day_ids']);
            foreach ($dayIdList as $id) {
                foreach ($tour['days'] as $day) {
                    if ($day['id'] == $id) {
                        echo '<tr>';                            
                        echo '<td>', $tour['op_code'], '</td>';
                        echo '<td>', date('Y-m-d', strtotime('+'.$cnt.' days', strtotime($tour['day_from']))), '</td>';
                        echo '<td>', $day['name'], '</td>';
                        echo '</tr>';
                    }
                }
                $cnt ++;
            }
        }
        echo '</table>';
    }

    public function actionIndex() {
        return $this->render('ketoan_index', [
        ]);
    }

    public function actionC()
    {
        $theDvt = new Dvt;

        $theDvt->scenario = 'dvt_c';

        return $this->render('dvt_c', [
            'theDvt'=>$theDvt,
        ]);
    }

    public function actionR($id = 0)
    {
        $theDvt = Dvt::findOne($id);

        if (!$theDvt) {
            throw new HttpException(404, 'Not found.');
        }

        return $this->render('dvt_r', [
            'theDvt'=>$theDvt,
        ]);
    }

    public function actionU($id = 0)
    {
        $theDvt = Dvt::findOne($id);

        if (!$theDvt) {
            throw new HttpException(404, 'Not found.');
        }

        $theDvt->scenario = 'dvt_u';

        if ($theDvt->load(Yii::$app->request->post()) && $theDvt->validate()) {
            $theDvt->save();
        }

        return $this->render('dvt_u', [
            'theDvt'=>$theDvt,
        ]);
    }

    public function actionD($id = 0)
    {
        $theDvt = Dvt::findOne($id);

        if (!$theDvt) {
            throw new HttpException(404, 'Not found.');
        }

        return $this->render('dvt_d', [
            'theDvt'=>$theDvt,
        ]);
    }

    public function actionTour($id = 0)
    {
        if (Yii::$app->user->id == 1 && isset($_GET['dvt']) && isset($_GET['dv'])) {
            Yii::$app->db->createCommand('update cpt SET cp_id=:dv WHERE cp_id=0 AND dvtour_id=:dvt LIMIT 1',
                [':dv'=>(int)$_GET['dv'], ':dvt'=>(int)$_GET['dvt']]
                )
                ->execute();
            return $this->redirect(URI);
        }

        $theTour = Tour::find()
            ->where(['id'=>$id])
            ->with([
                'ct',
                'ct.days',
                ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');         
        }

        $query = Dvt::find()
            ->where(['tour_id'=>$id]);

        $theDvtx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'dv'=>function($query) {
                    return $query->select(['id', 'name', 'venue_id', 'unit'])
                        ->with(['venue'=>function($query){
                            return $query->select(['id', 'name']);
                            }
                        ]);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'venue.dv',
                'mm',
                'mm.updatedBy',
            ])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        return $this->render('dvt_tour', [
            'theTour'=>$theTour,
            'theDvtx'=>$theDvtx,
        ]);
    }

    // Danh cho ke toan check hang
    public function actionKetoanTour($id = 0) {
        $theTour = Tour::find()
            ->where(['id'=>$id])
            ->with([
                'ct',
                'ct.days',
                ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');         
        }

        // Ti gia gan nhat voi ngay tour khoi hanh
        $xRateUSD = Yii::$app->db->createCommand('SELECT rate FROM at_xrates WHERE currency1=:usd AND rate_dt<=:tour_start_date ORDER BY rate_dt DESC LIMIT 1', [
                ':usd'=>'USD',
                ':tour_start_date'=>$theTour['ct']['day_from'],
            ])
            ->queryScalar();

        $xRateEUR = Yii::$app->db->createCommand('SELECT rate FROM at_xrates WHERE currency1=:usd AND rate_dt<=:tour_start_date ORDER BY rate_dt DESC LIMIT 1', [
                ':usd'=>'EUR',
                ':tour_start_date'=>$theTour['ct']['day_from'],
            ])
            ->queryScalar();

        $query = Dvt::find()
            ->where(['tour_id'=>$id]);

        $theDvtx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name', 'image']);
                },
                'dv'=>function($query) {
                    return $query->select(['id', 'name', 'venue_id', 'unit'])
                        ->with(['venue'=>function($query){
                            return $query->select(['id', 'name']);
                            }
                        ]);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'venue.dv',
                'mm',
                'mm.updatedBy',
            ])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        return $this->render('dvt_ketoan-tour', [
            'theTour'=>$theTour,
            'theDvtx'=>$theDvtx,
            'xRateUSD'=>$xRateUSD,
            'xRateEUR'=>$xRateEUR,
        ]);
    }

    // TESTING: auto convert posted text to DVT table
    public function actionTest()
    {
        $theVenue = null;
        $theDv = null;

        $postedText = Yii::$app->request->post('text');
        $text = implode("\n", array_map('trim', explode("\n", $postedText)));
        if ($text != '') {
            $lines = explode("\n", $text);
            foreach ($lines as $i=>$line) {
                $line = trim($line);
                $segs = explode(' ', $line);
                $theVenue = Yii::$app->db->createCommand('SELECT v.* FROM venues v, at_search s WHERE s.rtype="venue" AND s.rid=v.id AND s.search LIKE :search LIMIT 1', [':search'=>'%'.$segs[0].'%'])
                    ->queryOne();
                if ($theVenue) {
                    $theDv = Dv::find()
                        ->where(['venue_id'=>$theVenue['id']])
                        ->andWhere(['like', 'search', $segs[1]])
                        ->one();
                }
                break;
            }

        }

        return $this->render('dvt_test', [
            'text'=>$text,
            'theVenue'=>$theVenue,
            'theDv'=>$theDv,
        ]);
    }


}
