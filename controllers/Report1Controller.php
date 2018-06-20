<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Venue;
use common\models\Loc;
use common\models\Cp;
use common\models\Cpg;
use common\models\Cpt;
use common\models\Destination;
use common\models\Product;
use common\models\Booking;
use common\models\Inquiry;
use common\models\Invoice;
use common\models\Kase;
use common\models\Person;
use common\models\Tour;

class ReportController extends MyController
{
    public function actions() {
        return [
            'xpax-who-did' => [
                'class' => 'app\controllers\actions\PaxWhoDidAction',
            ],
            'xexport' => [
                'class' => 'lajax\translatemanager\controllers\actions\ExportAction',
            ],
        ];
    }

    // Pax who did
    public function actionPaxWhoDid($visit = '', $notvisit = '', $year = 2016) {
        // Tours which went to Laos
        $sql = 'SELECT * FROM at_tour_stats WHERE LOCATE("la", countries)!=0';
        $toursWhich['went to Laos'] = Yii::$app->db->createCommand($sql)->queryAll();
        // \fCore::expose($toursWhich['went to Laos']); exit;
        $tourIdList = [];
        foreach ($toursWhich['went to Laos'] as $tour) {
            $tourIdList[] = $tour['tour_id'];
        }
        // Tours and pax that went to Laos
        $toursLaos = Product::find()
            ->select(['id'])
            ->with([
                'bookings.pax'=>function($q) {
                    return $q->select(['id']);
                }
                ])
            ->where('op_finish!="canceled"')
            ->andWhere(['id'=>$tourIdList])
            ->asArray()
            ->all();
        $paxIdList = [];
        foreach ($toursLaos as $tour) {
            foreach ($tour['bookings'] as $booking) {
                foreach ($booking['pax'] as $pax) {
                    $paxIdList[] = $pax['id'];
                }
            }
        }

        // Bookings of tours which did not go to Laos
        $tours = Product::find()
            ->select(['id', 'op_code'])
            ->with([
                'bookings.pax'=>function($q) use ($paxIdList) {
                    return $q->select(['id', 'fname', 'lname', 'gender', 'country_code', 'email', 'byear'])
                        ->where('email!=""')
                        ->andWhere(['not', ['id'=>$paxIdList]]);
                }
                ])
            ->where('op_finish!="canceled"')
            ->andWhere(['not', ['id'=>$tourIdList]])
            ->andWhere('YEAR(day_from)=:year', [':year'=>$year])
            ->orderBy('day_from')
            ->asArray()
            ->all();

        return $this->render('report_pax-who-did', [
            'tours'=>$tours,
            'year'=>$year,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('reports_index');
    }

    // 170304
    public function actionTourOperators($year = '')
    {
        $theTours = Product::find()
            ->select(['id', 'day_from'])
            ->where(['op_status'=>'op', 'op_finish'=>''])
            ->andWhere($year != '' ? 'YEAR(day_from)=:year' : '', [':year'=>$year])
            ->orderBy('day_from DESC')
            ->with([
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id']);
                },
                'tour.operators'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                ])
            ->asArray()
            ->all();
        $result = [];
        // \fCore::expose($theTours); exit;
        foreach ($theTours as $tour) {
            $y = substr($tour['day_from'], 0, 4);
            $m = (int)substr($tour['day_from'], 5, 2);
            if (!empty($tour['tour']['operators'])) {
                foreach ($tour['tour']['operators'] as $user) {
                    if (!isset($result[$y][$m][$user['name']])) {
                        $result[$y][$m][$user['name']] = 1;
                    } else {
                        $result[$y][$m][$user['name']] ++;
                    }
                }
            }
        }
        // \fCore::expose($result); exit;
        return $this->render('report_tour-operators', ['result'=>$result]);

    }

    // 160613
    public function actionTourPaxCountry($year = '')
    {
        $tours = Product::find()
            ->select(['id', 'op_name', 'op_code', 'year'=>'YEAR(day_from)'])
            ->where(['op_status'=>'op'])
            ->andWhere('op_finish!="canceled"')
            //->andWhere('YEAR(day_from)=:year', [':year'=>$year])
            ->andWhere('SUBSTRING(op_code,1,1)="F"')
            ->with([
                'days'=>function($q) {
                    return $q->select(['rid', 'name']);
                },
                'bookings'=>function($q) {
                    return $q->select(['pax', 'product_id']);
                },
                ])
            ->orderBy('year')
            ->asArray()
            ->all();
        $result = [];
        foreach ($tours as $tour) {
            $y = $tour['year'];
            $pax = 0;
            foreach ($tour['bookings'] as $booking) {
                $pax += $booking['pax'];
            }
            if (!isset($result[$y])) {
                $result[$y] = [
                    'vn'=>[0, 0], // tour, pax
                    'la'=>[0, 0],
                    'kh'=>[0, 0],
                    'mm'=>[0, 0],
                    'th'=>[0, 0],
                    'id'=>[0, 0],
                    'my'=>[0, 0],
                    'cn'=>[0, 0],
                ];
            }
            $visit = [
                'vn'=>false,
                'la'=>false,
                'kh'=>false,
                'mm'=>false,
                'th'=>false,
                'id'=>false,
                'my'=>false,
                'cn'=>false,
            ];
            foreach ($tour['days'] as $day) {
                $name = strtolower($day['name']);
                $name = str_replace(' ', '', $name);
                if (strpos($name, 'hanoi') !== false || strpos($name, 'saigon') !== false || strpos($name, 'hochiminh') !== false) {
                    $visit['vn'] = true;
                }
                if (strpos($name, 'vientiane') !== false || strpos($name, 'luang') !== false || strpos($name, 'pakse') !== false) {
                    $visit['la'] = true;
                }
                if (strpos($name, 'phnompenh') !== false || strpos($name, 'siemreap') !== false) {
                    $visit['kh'] = true;
                }
                if (strpos($name, 'yangoon') !== false || strpos($name, 'bagan') !== false || strpos($name, 'mandalay') !== false) {
                    $visit['mm'] = true;
                }
                if (strpos($name, 'bangkok') !== false || strpos($name, 'changmai') !== false || strpos($name, 'phuket') !== false) {
                    $visit['th'] = true;
                }
                if (strpos($name, 'yogyakarta') !== false || strpos($name, 'bali') !== false || strpos($name, 'jakarta') !== false) {
                    $visit['id'] = true;
                }
                if (strpos($name, 'kuala') !== false || strpos($name, 'penang') !== false || strpos($name, 'johor') !== false) {
                    $visit['my'] = true;
                }
                if (strpos($name, 'guang') !== false || strpos($name, 'beijing') !== false || strpos($name, 'shanghai') !== false) {
                    $visit['cn'] = true;
                }
            }
            foreach ($visit as $cc=>$tf) {
                if ($tf === true) {
                    $result[$y][$cc][0] ++;
                    $result[$y][$cc][1] += $pax;
                }
            }
        }
        // \fCore::expose($result); exit;
        return $this->render('report_tour-pax-country', ['result'=>$result]);
    }

    public function actionMkt04($month = '', $lang = 'fr', $blank_email = 'no')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        $andLang = '';
        $andEmpty = ' AND u.email!=""';
        if (in_array($lang, ['en', 'fr', 'vi'])) {
            $andLang = ' AND k.language="'.$lang.'"';
        }
        if (in_array($blank_email, ['yes'])) {
            $andEmpty = '';
        }
        $sql = 'SELECT u.id, u.fname, u.lname, u.gender, u.country_code, u.email FROM persons u, at_booking_user bu, at_cases k, at_bookings b WHERE bu.user_id=u.id AND k.id=b.case_id AND bu.booking_id=b.id '.$andLang.$andEmpty.' AND SUBSTRING(u.updated_at,1,7)=:ym GROUP BY u.id ORDER BY u.id LIMIT 1000';
        $results = Yii::$app->db->createCommand($sql, [':ym'=>$month])->queryAll();
        echo '<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title>Danh sách khách mới update - Amica Travel IMS</title><style>table {border-collapse:collapse;} th, td {padding:4px; border:1px solid #ccc;}</style></head><body>';
        echo '<p><strong>DANH SACH KHACH DUOC CAP NHAT THANG ', $month, '</strong></p>';
        echo '<p>Thay đổi các phần in đậm để có kết quả như ý: https://my.amicatravel.com/reports/mkt-04?month=<strong>2016-04|2016-03</strong>&lang=<strong>fr|en</strong>&blank_email=<strong>yes|no</strong></p>';
        echo '<table><thead><tr><th>ID</th><th>HO</th><th>TEN</th><th>GIOI</th><th>QTICH</th><th>EMAIL</th></tr></thead><tbody>';
        foreach ($results as $user) {
            echo '<tr>';
            echo '<td>', $user['id'], '</td>';
            echo '<td>', $user['fname'], '</td>';
            echo '<td>', $user['lname'], '</td>';
            echo '<td>', $user['gender'], '</td>';
            echo '<td>', strtoupper($user['country_code']), '</td>';
            echo '<td>', strtolower($user['email']), '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        exit;
        return $this->render('reports_mkt04', [
            'results'=>$results,
        ]);
    }

    // 160317 Danh sach khach gioi thieu tour con credit
    public function actionMkt03()
    {
        $sql = 'SELECT u.id, u.fname, u.lname, u.email, u.country_code, u.gender, (r.points - r.points_minus) AS credit, (SELECT COUNT(*) FROM at_booking_user bu WHERE bu.user_id=u.id) AS bookings FROM at_referrals r, persons u WHERE u.id=r.user_id AND u.is_member="no" HAVING credit!=0 AND bookings!=0';
        $thePersons = Yii::$app->db->createCommand($sql)->queryAll();

        $results =[];
        foreach ($thePersons as $person) {
            if (!isset($results[$person['id']])) {
                $results[$person['id']] = $person;
            } else {
                $results[$person['id']]['credit'] += $person['credit'];
            }
        }

        $filename = 'mkt_khach_co_credit_'.date('Ymd-His').'.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename='.$filename);

        $out = fopen('php://output', 'w');
        fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

        $arr = ['ID', 'TEN', 'HO', 'EMAIL', 'GIOI', 'QG', 'CREDIT'];
        fputcsv($out, $arr);

        foreach ($results as $id=>$person) {
            $arr = [];
            $arr[] = $person['id'];
            $arr[] = $person['lname'];
            $arr[] = $person['fname'];
            $arr[] = $person['email'];
            $arr[] = $person['gender'];
            $arr[] = strtoupper($person['country_code']);
            $arr[] = $person['credit'];
            fputcsv($out, $arr);
        }
        fclose($out);
        exit;
    }

    // 160220 Danh sach khach lien he khong mua tour
    public function actionMkt02($y = 2016, $l ='fr')
    {
        if (!in_array($y, [2016, 2015, 2014, 2013])) {
            $y = 2016;
        }

        if (!in_array($l, ['fr', 'en'])) {
            $l = 'fr';
        }

        $query = Kase::find()
            ->select(['id'])
            ->where(['deal_status'=>'lost', 'is_b2b'=>'no', 'language'=>$l])
            ->andWhere('YEAR(created_at)=:y', [':y'=>$y]);

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>500,
        ]);

        $lostDeals = $query
            ->with([
                'people'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'gender', 'email', 'phone', 'country_code']);
                },
                'people.bookings'=>function($q) {
                    return $q->select(['id']);
                },
                'inquiries'/*=>function($q) {
                    return $q->orderBy('id DESC')->limit(1);
                }*/
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $thePeople = [];
        foreach ($lostDeals as $deal) {
            if (empty($deal['people'])) {
                // echo '<hr>EMPTY: '.$deal['id'];
                foreach ($deal['inquiries'] as $inquiry) {
                    try {
                        $data = @unserialize($inquiry['data']);
                        $age = $data['agesOfTravelers12'] ?? 0;
                        $country = $data['country'] ?? '';
                        $phone = $data['phone'] ?? '';
                    } catch(Exception $e) {
                        $age = 0;
                        $phone = '';
                        $country = '';
                    }
                    $thePeople[$inquiry['email']] = [
                        'id'=>0,
                        'fname'=>$inquiry['name'],
                        'lname'=>'',
                        'gender'=>'',
                        'age'=>$age,
                        'email'=>$inquiry['email'],
                        'phone'=>$phone,
                        'country_code'=>$country,
                    ];
                }
            } else {
                $age = 0;
                foreach ($deal['inquiries'] as $inquiry) {
                    try {
                        $data = @unserialize($inquiry['data']);
                        $age = $data['agesOfTravelers12'] ?? 0;
                    } catch(Exception $e) {
                        $age = 0;
                    }
                }
                foreach ($deal['people'] as $person) {
                    // Not returning
                    if (count($person['bookings']) == 0) {
                        $thePeople[$person['email']] = [
                            'id'=>$person['id'],
                            'fname'=>$person['fname'],
                            'lname'=>$person['lname'],
                            'gender'=>$person['gender'],
                            'age'=>$age,
                            'email'=>$person['email'],
                            'phone'=>$person['phone'],
                            'country_code'=>$person['country_code'],
                        ];                      
                    }
                }
            }
        }

        if (isset($_GET['x'])) {
            echo count($lostDeals), '-', count($thePeople);
            \fCore::expose($lostDeals);
            exit;
        }
        return $this->render('reports_mkt-02', [
            'thePeople'=>$thePeople,
            'pagination'=>$pagination,
            'y'=>$y,
            'l'=>$l,
        ]);
    }

    // 160108 So luong yeu cau theo tour tren web theo nam
    public function actionMkt01($y = 2015)
    {
        if (!in_array((int)$y, [2014, 2015, 2016, 2017])) {
            $y = 2015;
        }
        $inquiries = Inquiry::find()
            ->select('data')
            ->where('YEAR(created_at)=:y', [':y'=>$y])
            //->andWhere()
            ->asArray()
            ->all();
        foreach ($inquiries as $inquiry) {
            $data = @unserialize($inquiry['data']);
            if (is_array($data)) {
                if (isset($data['tourName']) && $data['tourName'] != '') {
                    $url = str_replace(['http://', 'https://'], ['', ''], $data['tourUrl']);
                    if (strpos($url, '?') !== false) {
                        $url = strchr($data['tourUrl'], '?', true);
                    }
                    if (isset($results[$url])) {
                        $results[$url]['cnt'] ++; 
                    } else {
                        $results[$url] = [
                            'cnt'=>1,
                            'name'=>$data['tourName'],
                            'url'=>$data['tourUrl'],
                        ];
                    }
                }
            }
        }
        return $this->render('reports_mkt-01', [
            'results'=>$results,
            'y'=>$y,
        ]);
    }


    public function actionTourLength()
    {
        // Năm đi tour
        $sql = 'SELECT MAX(YEAR(day_from)) FROM at_ct WHERE op_status="op"';
        $maxYear = Yii::$app->db->createCommand($sql)->queryScalar();

        // Các phân nhóm
        $defaultGrouping = '1-7,8-14,15-';
        $getGrouping = Yii::$app->request->get('grouping', $defaultGrouping);
        $groups = explode(',', $getGrouping);
        $theGroups = [];
        foreach ($groups as $group) {
            $item = explode('-', $group);
            if (isset($item[0])) {
                $from = (int)$item[0];
            } else {
                $from = 0;
            }
            if (isset($item[1])) {
                $to = (int)$item[1] == 0 ? 9999 : (int)$item[1];
            } else {
                $to = 9999;
            }
            $theGroups[] = [$group, $from, $to];
        }


        $theProducts = Product::find()
            ->select(['id', 'day_count', 'day_from'])
            ->where(['op_status'=>'op'])
            ->asArray()
            ->all();
        return $this->render('reports_tour-length', [
            'minYear'=>2007,
            'maxYear'=>$maxYear,
            'theProducts'=>$theProducts,
            'theGroups'=>$theGroups,
            'getGrouping'=>$getGrouping,
        ]);
    }

    // Customers with more than $from tours
    public function actionPaxTours($from = 2)
    {
        // Khong list 1 tour vi qua nhieu
        if ($from < 2) {
            $from = 2;
        }

        $sql = 'select u.email, u.phone, u.gender, u.name, u.country_code, u.byear, count(*) as cnt, user_id from persons u, at_booking_user bu, at_bookings b where b.id=bu.booking_id AND u.id=bu.user_id AND b.status="won" and u.is_member="no" group by bu.user_id having cnt>=:from ORDER BY u.lname, u.fname desc';
        $thePax = Yii::$app->db->createCommand($sql, [':from'=>$from])->queryAll();

        $paxIdList = [];
        foreach ($thePax as $pax) {
            $paxIdList[] = $pax['user_id'];
        }

        $paxAddrs = [];
        if (!empty($thePax)) {
            $sql = 'SELECT rid, v FROM at_meta WHERE k="address" AND rtype="user" AND rid IN ('.implode(',', $paxIdList).')';
            $paxAddrs = Yii::$app->db->createCommand($sql)->queryAll();
        }

        $theTours = [];
        if (!empty($paxIdList)) {
            $sql = 'SELECT p.id, p.op_code, b.finish, bu.user_id FROM at_ct p, at_booking_user bu, persons u, at_bookings b WHERE bu.user_id=u.id AND bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN('.implode(',', $paxIdList).') ORDER BY SUBSTRING(p.op_code,2,4)';
            $theTours = Yii::$app->db->createCommand($sql)->queryAll();
        }

        return $this->render('reports_pax-tours', [
            'theTours'=>$theTours,
            'thePax'=>$thePax,
            'paxAddrs'=>$paxAddrs,
            'from'=>$from,
        ]);
    }

    public function actionKqkdtour($year = '', $seller = 0, $code = '', $orderby = 'date', $eur = 24100, $usd = 21450, $vnd = 1)
    {
        // 160706 Rem Lan, add Hanh
        if (!in_array(MY_ID, [1,2,3,4,17,34717])) {
            throw new HttpException(403, 'Access denied');
        }

        if (strlen($year) != 4) {
            $year = date('Y');
        }
        $yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018];
        $sql2 = 'SELECT u.id, CONCAT_WS(", ", lname, fname, email) AS name FROM persons u, at_bookings b WHERE b.created_by=u.id GROUP BY u.id ORDER BY lname, fname';
        $sellerList = Yii::$app->db->createCommand($sql2)->queryAll();
        
        $query = Booking::find();

        if (in_array(strtolower($code), ['f', 'g'])) {
            $query->andWhere('LOWER(SUBSTRING(at_ct.op_code, 1,1))=:code', [':code'=>strtolower($code)]);
        }

        if ($seller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$seller]);
        }

        $theBookings = $query
            ->select(['at_bookings.id', 'at_bookings.created_by', 'product_id', 'finish'])
            ->with([
                'invoices'=>function($q) {
                    return $q->select(['id', 'booking_id', 'amount', 'stype', 'currency', 'payment_status'])->where(['status'=>'active']);
                },
                'payments'=>function($q) {
                    return $q->select(['id', 'booking_id', 'amount', 'currency', 'xrate']);
                },
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->joinWith([
                'product'=>function($q) use ($year) {
                    //return $q->select(['id', 'op_name', 'op_code', 'day_from', 'day_until'=>'DATE_ADD(day_from, INTERVAL day_count-1 DAY)']);//->having('YEAR(day_until)=:year', [':year'=>$year]);
                    return $q->select(['id', 'op_name', 'op_code', 'day_until'])->where('YEAR(day_until)=:year', [':year'=>$year]);
                }
            ])
            ->andWhere(['at_bookings.status'=>'won'])
            //->andWhere('YEAR(at_ct.day_from)=:year', [':year'=>$year])
            //->having('YEAR(at_ct.day_until)=:year', [':year'=>$year])
            ->orderBy($orderby == 'date' ? 'at_ct.day_until' : 'at_ct.op_code')
            ->asArray()
            ->limit(5000)
            ->all();

        //\fCore::expose($theBookings);
        //exit;

        $result = [];
        for ($mm = 0; $mm <= 12; $mm ++) {
            $result['due'][$mm] = 0;
            $result['paid'][$mm] = 0;
            $result['bal'][$mm] = 0;
        }

        foreach ($theBookings as $booking) {
            //$mm = (int)substr($booking['product']['day_from'], 5, 2);
            $mm = (int)substr($booking['product']['day_until'], 5, 2);

            $result['due']['bkg'.$booking['id']] = 0;
            $result['paid']['bkg'.$booking['id']] = 0;
            $result['bal']['bkg'.$booking['id']] = 0;

            foreach ($booking['payments'] as $payment) {
                $amountVnd = $payment['amount'] * $payment['xrate'];
                $result['paid'][$mm] += $amountVnd;
                $result['paid'][0] += $amountVnd;
                $result['paid']['bkg'.$booking['id']] += $amountVnd;
            }

            foreach ($booking['invoices'] as $invoice) {
                if ($invoice['stype'] == 'invoice') {
                    $amount = $invoice['amount'];
                } elseif ($invoice['stype'] == 'credit') {
                    $amount = -$invoice['amount'];
                }

                if ($invoice['currency'] == 'USD') {
                    $amountVnd = $usd * $amount;
                } elseif ($invoice['currency'] == 'EUR') {
                    $amountVnd = $eur * $amount;
                } else {
                    $amountVnd = $amount;
                }

                if ($invoice['payment_status'] == 'unpaid') {
                    $result['bal'][$mm] += $amountVnd;
                    $result['bal'][0] += $amountVnd;
                    $result['bal']['bkg'.$booking['id']] += $amountVnd;
                }

                $result['due'][$mm] = $result['paid'][$mm] + $result['bal'][$mm];
                $result['due'][0] = $result['paid'][0] + $result['bal'][0];
                $result['due']['bkg'.$booking['id']] = $result['paid']['bkg'.$booking['id']] + $result['bal']['bkg'.$booking['id']];
            }
        }

        return $this->render('reports_kqkdtour', [
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
            'yearList'=>$yearList,
            'seller'=>$seller,
            'orderby'=>$orderby,
            'year'=>$year,
            'eur'=>$eur,
            'usd'=>$usd,
            'vnd'=>$vnd,
            'code'=>$code,
            'result'=>$result,
        ]);
    }

    public function actionLichtttour($year = '', $seller = 0, $method = '', $nhothu = '', $eur = 24062, $usd = 21335)
    {
        // 160706 Rem Lan, add Hanh
        if (!in_array(MY_ID, [1,2,3,4,17,4065])) {
            throw new HttpException(403, 'Access denied');
        }
        if (strlen($year) != 4) {
            $year = date('Y');
        }
        $yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018];

        $sql2 = 'SELECT u.id, CONCAT_WS(", ", lname, fname, email) AS name FROM persons u, at_bookings b WHERE b.created_by=u.id GROUP BY u.id ORDER BY lname, fname';
        $sellerList = Yii::$app->db->createCommand($sql2)->queryAll();

        $query = Invoice::find()
            ->andWhere(['status'=>'active'])
            ->andWhere(['payment_status'=>'unpaid'])
            ->andWhere('YEAR(due_dt)=:year', [':year'=>$year]);

        if ($method != '') {
            $query->andWhere(['method'=>$method]);
        }

        if ($nhothu == 'yes') {
            $query->andWhere('nho_thu!=""');
        } elseif ($nhothu == 'no') {
            $query->andWhere('nho_thu=""');
        } elseif ($nhothu != '') {
            $query->andWhere(['nho_thu'=>str_replace('+', ' ', $nhothu)]);
        }

        $theInvoices = $query
            ->with([
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'booking',
                'booking.product',
            ])
            ->orderBy('due_dt')
            ->asArray()
            ->limit(5000)
            ->indexBy('id')
            ->all();

        // Ngày bắt đầu tính
        if (date('D') == 'Mon') {
            $startDate = date('Y-m-d'); 
        } else {
            $startDate = date('Y-m-d', strtotime('last Monday')); 
        }

        $result = [];
        $total = [];

        foreach ($theInvoices as $invoice) {
            if (strtotime($invoice['due_dt']) < strtotime($startDate)) {
                $result['overdue'][] = $invoice['id'];
                if (!isset($total['overdue'][$invoice['currency']])) {
                    $total['overdue'][$invoice['currency']] = 0;
                }
                if ($invoice['stype'] == 'credit') {
                    $invoice['amount'] = -$invoice['amount'];
                }
                $total['overdue'][$invoice['currency']] += $invoice['amount'];
            }
        }

        while (substr($startDate, 0, 4) == $year) {
            $endDate = date('Y-m-d', strtotime('+6 days', strtotime($startDate)));
            foreach ($theInvoices as $id => $invoice) {
                $dueDate = substr($invoice['due_dt'], 0, 10);
                if (strtotime($dueDate) >= strtotime($startDate) && strtotime($dueDate) <= strtotime($endDate)) {
                    $result[$startDate][] = $id;
                    if (!isset($total[$startDate][$invoice['currency']])) {
                        $total[$startDate][$invoice['currency']] = 0;
                    }
                    if ($invoice['stype'] == 'credit') {
                        $invoice['amount'] = -$invoice['amount'];
                    }
                    $total[$startDate][$invoice['currency']] += $invoice['amount'];
                }
            }
            $startDate = date('Y-m-d', strtotime('+7 days', strtotime($startDate)));
        }

        $sql = 'SELECT method FROM invoices GROUP BY method ORDER BY method';
        $methodList = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'SELECT nho_thu FROM invoices GROUP BY nho_thu ORDER BY nho_thu';
        $nhothuList = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('reports_lichtttour', [
            'theInvoices'=>$theInvoices,
            'sellerList'=>$sellerList,
            'yearList'=>$yearList,
            'methodList'=>$methodList,
            'nhothuList'=>$nhothuList,
            'seller'=>$seller,
            'year'=>$year,
            'method'=>$method,
            'nhothu'=>$nhothu,
            'result'=>$result,
            'total'=>$total,
            'eur'=>$eur,
            'usd'=>$usd,
        ]);
    }

    // Lost cases
    public function actionLostCases($month = '', $for = 'b2c', $seller = 0, $reason = '', $search = '')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }
        $query = Kase::find()
            ->andWhere(['status'=>'closed', 'deal_status'=>['lost', 'pending']])
            ->andWhere('SUBSTRING(closed,1,7)=:month', [':month'=>$month]);
        if ($seller != 0) {
            $query->andWhere(['owner_id'=>$seller]); 
        }
        if ($reason != '') {
            $query->andWhere(['why_closed'=>$reason]); 
        }
        if ($for == 'b2b') {
            $query->andWhere(['is_b2b'=>'yes']); 
        } elseif ($for == 'b2c') {
            $query->andWhere(['is_b2b'=>'no']); 
        }
        if (strlen($search) >= 2) {
            $query->andWhere(['like', 'closed_note', $search]); 
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theCases = $query
            ->select(['id', 'name', 'status', 'ref', 'is_priority', 'deal_status', 'opened', 'closed', 'owner_id', 'created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'why_closed', 'closed_note'])
            ->orderBy('closed DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with([
                'stats',
                'owner'=>function($query) {
                    return $query->select(['id', 'name', 'image']);
                },
                'referrer'=>function($query) {
                    return $query->select(['id', 'name', 'is_client']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->asArray()
            ->all();

        $sql = 'select substring(closed,1,7) as ym from at_cases group by ym having ym!="0000-00" order by ym desc';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();
        $sql2 = 'select u.id, CONCAT(u.lname, ", ", u.email) as name from persons u, at_cases k where k.owner_id=u.id group by u.id order by lname, fname';
        $sellerList = Yii::$app->db->createCommand($sql2)->queryAll();

        return $this->render('reports_lost-cases', [
            'theCases'=>$theCases,
            'pagination'=>$pagination,
            'month'=>$month,
            'seller'=>$seller,
            'reason'=>$reason,
            'search'=>$search,
            'monthList'=>$monthList,
            'sellerList'=>$sellerList,
            'for'=>$for,
        ]);
    }

    public function actionBookings()
    {
        $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
        $getBantour = Yii::$app->request->get('bantour', 0);
        $getSeller = Yii::$app->request->get('seller', 0);
        $getCurrency = Yii::$app->request->get('currency', 0);
        $getB2b = Yii::$app->request->get('b2b', 'b2c');
        $rates = Yii::$app->request->get('rates', 1.14);

        $sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM persons u, at_bookings b WHERE u.id=b.created_by GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
        $sellerList = Yii::$app->db->createCommand($sql)->queryAll();
        $sql = 'SELECT SUBSTRING(p.day_from,1,7) AS ym FROM at_ct p, at_bookings b WHERE p.id=b.product_id AND b.status="won" GROUP BY ym ORDER BY ym DESC';
        $listKhoiHanh = Yii::$app->db->createCommand($sql)->queryAll();
        $sql = 'SELECT SUBSTRING(b.status_dt,1,7) AS ym FROM at_bookings b WHERE b.status="won" GROUP BY ym ORDER BY ym DESC';
        $listBanTour = Yii::$app->db->createCommand($sql)->queryAll();

        $query = Booking::find()
            ->andWhere(['at_bookings.status'=>'won']);

        if ((int)$getSeller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$getSeller]);
        }
        if ($getKhoihanh == 0 && $getBantour == 0) {
            $getBantour = date('Y-m');
        }
        if ($getBantour != 0) {
            $query->andWhere('SUBSTRING(at_bookings.status_dt,1,7)=:ym', [':ym'=>$getBantour]);
        }

        if (in_array($getCurrency, ['EUR', 'USD', 'VND'])) {
            $query->andWhere(['currency'=>$getCurrency]);
        }

        $query->joinWith([
            'product'=>function($q) {
                $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
                if ($getKhoihanh != 0) {
                    $q->andWhere('SUBSTRING(day_from,1,7)=:ym', [':ym'=>$getKhoihanh]);
                }
            }
        ]);

        $theBookings = $query
            ->orderBy('at_ct.day_from')
            ->with([
                'report',
                'product',
                'product.tour',
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name', 'image']);
                },
                'case'=>function($query) {
                    return $query->select(['id', 'name', 'owner_id', 'is_b2b']);
                },
                'case.owner'=>function($query) {
                    return $query->select(['id', 'name']);
                }
                ])
            ->asArray()
            ->all();

        return $this->render('reports_bookings', [
            'getKhoihanh'=>$getKhoihanh,
            'getBantour'=>$getBantour,
            'getSeller'=>$getSeller,
            'getCurrency'=>$getCurrency,
            'getB2b'=>$getB2b,
            'rates'=>$rates,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
            'listKhoiHanh'=>$listKhoiHanh,
            'listBanTour'=>$listBanTour,
        ]);
    }

    // Danh sach khach hang o khach san
    public function actionCustomersHotel($id = 455, $year = 2017, $month = 1)
    {
        // Khach san
        $theVenue = Venue::findOne($id);

        if (!$theVenue) {
            throw new HttpException(404, 'Hotel not found');
        }

        // Cac tour dung khach san tinh theo ngay su dung
        if ($month == 0) {
            $sql = 'select t.ct_id, t.id, t.code from at_tours t, cpt cp where cp.tour_id=t.id and t.status!="deleted" and cp.dvtour_day<:now and cp.venue_id=:id and year(dvtour_day)=:year group by tour_id order by dvtour_day limit 1000';
            $theTours = Tour::findBySql($sql, [
                //':now'=>date('Y-m-d'),
                ':now'=>'2019-01-01',
                ':id'=>$id,
                ':year'=>$year,
            ])
            ->with([
                'product'=>function($q) {
                    return $q->select(['id', 'title', 'day_from']);
                },
                'product.bookings'=>function($q) {
                    return $q->select('*');
                },
                'product.bookings.pax'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name', 'byear', 'country_code', 'gender', 'email', 'phone'])->orderBy('lname, fname');
                },
                //'product.bookings.pax.metas'=>function($q) {
                //  return $q->select(['v'])->where(['k'=>'address']);
                //},
            ])
            ->asArray()
            ->all();
        } else {
            $sql = 'select t.ct_id, t.id, t.code from at_tours t, cpt cp where cp.tour_id=t.id and t.status!="deleted" and cp.dvtour_day<:now and cp.venue_id=:id and year(dvtour_day)=:year and month(dvtour_day)=:month group by tour_id order by dvtour_day limit 1000';
            $theTours = Tour::findBySql($sql, [
                //':now'=>date('Y-m-d'),
                ':now'=>'2019-01-01',
                ':id'=>$id,
                ':year'=>$year,
                ':month'=>$month,
            ])
            ->with([
                'product'=>function($q) {
                    return $q->select(['id', 'title', 'day_from']);
                },
                'product.bookings'=>function($q) {
                    return $q->select('*');
                },
                'product.bookings.pax'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name', 'byear', 'country_code', 'gender', 'email', 'phone'])->orderBy('lname, fname');
                },
                //'product.bookings.pax.metas'=>function($q) {
                //  return $q->select(['v'])->where(['k'=>'address']);
                //},
            ])
            ->asArray()
            ->all();
        }
        $allCountries = \common\models\Country::find()->select(['code', 'name_en'])->indexBy('code')->asArray()->all();

        return $this->render('reports_pax-hotel', [
            'theVenue'=>$theVenue,
            'theTours'=>$theTours,
            'allCountries'=>$allCountries,
            'id'=>$id,
            'year'=>$year,
            'month'=>$month,
        ]);
    }

    // Count of customers
    public function actionCustomersTours()
    {
        $sql = 'SELECT p.id AS tour_id, b.pax, p.day_from FROM at_bookings b, at_ct p WHERE p.id=b.product_id AND b.status="won" AND b.finish!="canceled" ORDER BY p.day_from';
        $theBookings = Yii::$app->db->createCommand($sql)->queryAll();

        $result = [];
        $resultTours = [];
        $countedTourIdList = [];

        foreach ($theBookings as $booking) {
            $year = (int)substr($booking['day_from'], 0, 4);
            $month = (int)substr($booking['day_from'], 5, 2);
            // Count tours
            if (!isset($resultTours[$year][$month])) {
                $resultTours[$year][$month] = 0;
            }
            if (!isset($resultTours[$year][0])) {
                $resultTours[$year][0] = 0;
            }
            if (!in_array($booking['tour_id'], $countedTourIdList)) {
                $resultTours[$year][$month] ++;
                $resultTours[$year][0] ++;
                $countedTourIdList[] = $booking['tour_id'];
            }

            // Count pax
            if (!isset($result[$year][$month])) {
                $result[$year][$month] = 0;
            }
            $result[$year][$month] += $booking['pax'];
            if (!isset($result[$year][0])) {
                $result[$year][0] = 0;
            }
            $result[$year][0] += $booking['pax'];
        }

        ksort($result);
        ksort($resultTours);

        return $this->render('reports_customers-tours', [
            'result'=>$result,
            'resultTours'=>$resultTours,
        ]);
    }

    public function actionCasesProspectByInquirySource($month = '', $prospect = 'all', $site = 'fr', $source = 'all')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        } 
        $whereMonth = ' AND SUBSTRING(i.created_at,1,7)=:month ';

        $whereSource = ' AND k.id != :source';
        if ($source != 'all') {
            $whereSource = ' AND k.how_found=:source ';
        }

        $whereProspect = ' AND k.id!=:prospect ';
        if ($prospect != 'all') {
            $whereProspect = ' AND s.prospect=:prospect ';
        }

        $whereSite = '';
        if ($site == 'val') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="val"';
        } elseif ($site == 'vac') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="vac"';
        } elseif ($site == 'vpc') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="vpc"';
        } elseif ($site == 'ami') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="ami"';
        } elseif ($site == 'fr') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,2)="fr"';
        } elseif ($site == 'en') {
            $whereSite = ' AND SUBSTRING(i.form_name,1,3)="en"';
        }
        $whereMonth = ' AND SUBSTRING(i.created_at,1,7)=:month ';

        $sql = 'SELECT COUNT(*) FROM at_inquiries i, at_cases k, at_case_stats s WHERE k.how_contacted="web" AND i.case_id=k.id AND s.case_id=k.id'.$whereMonth.$whereSite.$whereProspect.$whereSource.' GROUP BY k.id';
        $count = Yii::$app->db->createCommand($sql, [
            ':month'=>$month,
            ':prospect'=>$prospect,
            ':source'=>$source,
        ])->queryScalar();

        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'=>500,
        ]);

        $sql = 'SELECT k.id, k.name, i.form_name, i.created_at, k.how_found, s.prospect FROM at_inquiries i, at_cases k, at_case_stats s WHERE k.how_contacted="web" AND i.case_id=k.id AND s.case_id=k.id'.$whereMonth.$whereSite.$whereProspect.$whereSource.' GROUP BY k.id ORDER BY i.created_at LIMIT '.$pagination->offset.', '.$pagination->limit;
        $theCases = Yii::$app->db->createCommand($sql, [
            ':month'=>$month,
            ':prospect'=>$prospect,
            ':source'=>$source,
        ])->queryAll();

        $sql = 'SELECT how_found FROM at_cases GROUP BY how_found ORDER BY how_found';
        $sources = Yii::$app->db->createCommand($sql)->queryAll();
        $sourceList = ['all'=>'All sources'];
        foreach ($sources as $xsource) {
            $sourceList[$xsource['how_found']] = $xsource['how_found'];
        }

        $sql = 'SELECT SUBSTRING(i.created_at,1,7) AS ym FROM at_inquiries i GROUP BY ym ORDER BY ym DESC';
        $months = Yii::$app->db->createCommand($sql)->queryAll();
        $monthList = [];
        foreach ($months as $xmonth) {
            $monthList[$xmonth['ym']] = $xmonth['ym'];
        }

        return $this->render('reports_cases-prospect-by-inquiry-source', [
            'theCases'=>$theCases,
            'pagination'=>$pagination,
            'prospect'=>$prospect,
            'month'=>$month,
            'site'=>$site,
            'source'=>$source,
            'sourceList'=>$sourceList,
            'monthList'=>$monthList,
        ]);
    }
}
