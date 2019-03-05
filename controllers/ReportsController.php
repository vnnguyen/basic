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
use common\models\Country;
use common\models\Destination;
use common\models\Payment;
use common\models\Product;
use common\models\Booking;
use common\models\Inquiry;
use common\models\Invoice;
use common\models\Referral;
use common\models\Kase;
use common\models\Person;
use common\models\User;
use common\models\Tour;

class ReportsController extends MyController
{
    public function actions() {
        return [
            'xcases'=>[
                'class'=>'app\controllers\actions\CasesAction'
            ],
            'xpax-who-did' => [
                'class' => 'app\controllers\actions\PaxWhoDidAction',
            ],
            'xexport' => [
                'class' => 'lajax\translatemanager\controllers\actions\ExportAction',
            ],
            'b2c'=>[
                'class'=>'app\controllers\actions\reports\B2c'
            ],
            'b2c-conversion-rate'=>[
                'class'=>'app\controllers\actions\reports\B2cConversionRate',
            ],
        ];
    }
    /**
     * Bao cao QKKH 1: tour moi khong duoc gt, moi duoc gt, quay lai
     */
    public function actionQhkh01($view = 'tourstart', $year = '', $year2 = '')
    {
        if (!in_array($view, ['casestart', 'tourstart', 'tourend'])) {
            $view = 'tourstart';
        }

        for ($y = date('Y') + 1; $y >= 2007; $y --) {
            $yearList[$y] = $y;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }
        if (!in_array($year2, $yearList)) {
            $year2 = '';
        }

        $query = Product::find()
            ->select(['id', 'day_from', new \yii\db\Expression('IF (day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY)) AS enddate')])
            ->andWhere('op_finish!= "canceled"')
            ->andWhere('op_status="op"')
            ->andWhere('SUBSTRING(op_code, 1, 1)="F"');
        $query2 = Product::find()
            ->select(['id', 'day_from', new \yii\db\Expression('IF (day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY)) AS enddate')])
            ->andWhere('op_finish!= "canceled"')
            ->andWhere('op_status="op"')
            ->andWhere('SUBSTRING(op_code, 1, 1)="F"');

        if ($view == 'tourcasestart') {
            // Tim tat ca cac tour co HS mo nam year
            $k_sql = 'SELECT t.id FROM at_cases k, at_bookings b, at_ct t WHERE b.case_id=k.id AND b.product_id=t.id AND k.is_b2b="no" AND k.deal_status="won" AND b.status="won" AND YEAR(k.created_at)=:year';
            $tourIdYear = Yii::$app->db->createCommand($k_sql, [':year'=>$year])->queryColumn();
            $tourIdYear2 = Yii::$app->db->createCommand($k_sql, [':year'=>$year2])->queryColumn();
            $query->andWhere(['id'=>$tourIdYear]);
            $query2->andWhere(['id'=>$tourIdYear2]);
        } elseif ($view == 'casestart') {
            // All cases regardless of tours
            $cases1 = Kase::find()
                ->select(['id', 'name', 'how_found', 'created_at'])
                ->where(['is_b2b'=>'no'])
                ->andWhere('YEAR(created_at)=:year', [':year'=>$year])
                ->asArray()
                ->all();
            if ($year2 != $year && $year2 != '') {
                $cases2 = Kase::find()
                    ->select(['id', 'name', 'how_found', 'created_at'])
                    ->where(['is_b2b'=>'no'])
                    ->andWhere('YEAR(created_at)=:year', [':year'=>$year2])
                    ->asArray()
                    ->all();
            }
        } elseif ($view == 'tourstart') {
            $query->andWhere('YEAR(day_from)=:year', [':year'=>$year]);
            $query2->andWhere('YEAR(day_from)=:year', [':year'=>$year2]);
        } else {
            // Tour end
            $query->andHaving('YEAR(enddate)=:year', [':year'=>$year]);
            $query2->andHaving('YEAR(enddate)=:year', [':year'=>$year2]);
        }

        for ($m = 0; $m <= 12; $m ++) {
            $result[$year][$m] = [
                'new'=>0,
                'referred'=>0,
                'returning'=>0,
                'total'=>0,
                'ref_how_found'=>[],
            ];
            $result[$year2][$m] = [
                'new'=>0,
                'referred'=>0,
                'returning'=>0,
                'total'=>0,
                'ref_how_found'=>[],
            ];
        }

        if ($view == 'casestart') {
            foreach ($cases1 as $case) {
                $m = (int)substr($case['created_at'], 5, 2);
                $result[$year][$m]['total'] ++;
                if ($case['how_found'] == 'new') {
                    $result[$year][$m]['new'] ++;
                    $result[$year][0]['new'] ++;
                } elseif ($case['how_found'] == 'returning') {
                    $result[$year][$m]['returning'] ++;
                    $result[$year][0]['returning'] ++;
                } else {
                    $result[$year][$m]['referred'] ++;
                    $result[$year][0]['referred'] ++;

                    $howFound = $case['how_found'];
                    $result[$year][0]['ref_how_found'][$howFound] = 1 + ($result[$year][0]['ref_how_found'][$howFound] ?? 0);
                    $result[$year][$m]['ref_how_found'][$howFound] = 1 + ($result[$year][$m]['ref_how_found'][$howFound] ?? 0);
                }
                $result[$year][0]['total'] ++;
            }
            if ($year2 != $year && $year2 != '') {
                foreach ($cases2 as $case) {
                    $m = (int)substr($case['created_at'], 5, 2);
                    $result[$year2][$m]['total'] ++;
                    if ($case['how_found'] == 'new') {
                        $result[$year2][$m]['new'] ++;
                        $result[$year2][0]['new'] ++;
                    } elseif ($case['how_found'] == 'returning') {
                        $result[$year2][$m]['returning'] ++;
                        $result[$year2][0]['returning'] ++;
                    } else {
                        $result[$year2][$m]['referred'] ++;
                        $result[$year2][0]['referred'] ++;

                        $howFound = $case['how_found'];
                        $result[$year2][0]['ref_how_found'][$howFound] = 1 + ($result[$year2][0]['ref_how_found'][$howFound] ?? 0);
                        $result[$year2][$m]['ref_how_found'][$howFound] = 1 + ($result[$year2][$m]['ref_how_found'][$howFound] ?? 0);
                    }
                    $result[$year2][0]['total'] ++;
                }
            }
        } else {

            $theTours = $query
                ->with([
                    'tour'=>function($q) {
                        return $q->select(['id', 'ct_id', 'code', 'name', 'status', 'owner']);
                    },
                    'bookings',
                    'bookings.case'=>function($q) {
                        return $q->select(['id', 'name','how_found']);
                    },
                    'bookings.people'=>function($q){
                        return $q->select(['id', 'name', 'country_code', 'bday', 'bmonth', 'byear', 'gender']);
                    },
                    'bookings.people.bookings'=>function($q){
                        return $q->select(['id','created_at','product_id']);
                    },
                    'bookings.people.bookings.product'=>function($q){
                        return $q->select(['day_from','id']);
                    }
                ])
                ->asArray()
                ->all();

            if ($year2 != $year && $year2 != '') {
                $theTours2 = $query2
                    ->with([
                        'tour'=>function($q) {
                            return $q->select(['id', 'ct_id', 'code', 'name', 'status', 'owner']);
                        },
                        'bookings',
                        'bookings.case'=>function($q) {
                            return $q->select(['id', 'name','how_found']);
                        },
                        'bookings.people'=>function($q){
                            return $q->select(['id', 'name', 'country_code', 'bday', 'bmonth', 'byear', 'gender']);
                        },
                        'bookings.people.bookings'=>function($q){
                            return $q->select(['id','created_at','product_id']);
                        },
                        'bookings.people.bookings.product'=>function($q){
                            return $q->select(['day_from','id']);
                        }
                    ])
                    ->asArray()
                    ->all();
            }

            $cnt = 0;

            foreach ($theTours as $tour) {
                $result[$year][0]['total'] ++;

                $m = $view == 'tourstart' ? (int)substr($tour['day_from'], 5, 2) : (int)substr($tour['enddate'], 5, 2);

                $result[$year][$m]['total'] ++;

                $isReturning = false;

                foreach ($tour['bookings'] as $booking) {
                    foreach ($booking['people'] as $person) {
                        foreach ($person['bookings'] as $pbooking) {
                            if ($pbooking['id'] != $booking['id']) {
                                if (strtotime($tour['day_from']) > strtotime($pbooking['product']['day_from']) ){
                                    $isReturning = true;
                                    if ($year == 2015 && $m == 6) {
                                        echo '<!-- RET: ', $tour['id'], '-->';
                                    }
                                    break;
                                }
                            }
                        }
                    }

                    if ($isReturning) {
                        $result[$year][0]['returning'] ++;
                        $result[$year][$m]['returning'] ++;
                    } else {
                        if (substr($booking['case']['how_found'], 0, 8) == 'referred') {
                            $howFound = $booking['case']['how_found'];
                            $result[$year][0]['referred'] ++;
                            $result[$year][$m]['referred'] ++;

                            $result[$year][0]['ref_how_found'][$howFound] = 1 + ($result[$year][0]['ref_how_found'][$howFound] ?? 0);
                            $result[$year][$m]['ref_how_found'][$howFound] = 1 + ($result[$year][$m]['ref_how_found'][$howFound] ?? 0);
                        } else {
                            $result[$year][0]['new'] ++;
                            $result[$year][$m]['new'] ++;
                        }
                    }
                }

            }

            if ($year2 != $year && $year2 != '') {
                foreach ($theTours2 as $tour) {
                    $result[$year2][0]['total'] ++;

                    $m = $view == 'tourstart' ? (int)substr($tour['day_from'], 5, 2) : (int)substr($tour['enddate'], 5, 2);;
                    $result[$year2][$m]['total'] ++;

                    $isReturning = false;

                    foreach ($tour['bookings'] as $booking) {
                        foreach ($booking['people'] as $person) {
                            foreach ($person['bookings'] as $pbooking) {
                                if ($pbooking['id'] != $booking['id']) {
                                    if (strtotime($tour['day_from']) > strtotime($pbooking['product']['day_from']) ){
                                        $isReturning = true;
                                        // if ($year == 2015 && $m == 6) {
                                        //     echo '<!-- RET: ', $tour['id'], '-->';
                                        // }
                                        break;
                                    }
                                }
                            }
                        }

                        if ($isReturning) {
                            $result[$year2][0]['returning'] ++;
                            $result[$year2][$m]['returning'] ++;
                        } else {
                            if (substr($booking['case']['how_found'], 0, 8) == 'referred') {
                                $howFound = $booking['case']['how_found'];
                                $result[$year2][0]['referred'] ++;
                                $result[$year2][$m]['referred'] ++;
                                $result[$year2][0]['ref_how_found'][$howFound] = 1 + ($result[$year2][0]['ref_how_found'][$howFound] ?? 0);
                                $result[$year2][$m]['ref_how_found'][$howFound] = 1 + ($result[$year2][$m]['ref_how_found'][$howFound] ?? 0);
                            } else {
                                $result[$year2][0]['new'] ++;
                                $result[$year2][$m]['new'] ++;
                            }
                        }
                    }
                }
            }
        } // if casestart

        return $this->render('report_qhkh_01', [
            'view'=>$view,
            'year'=>$year,
            'year2'=>$year2,
            'yearList'=>$yearList,
            'result'=>$result,
        ]);
    }

    /**
     * Bao cao QKKH 2: HS duoc gioi thieu
     */
    public function actionQhkh02($year = '', $year2 = '')
    {
        for ($y = date('Y') + 1; $y >= 2007; $y --) {
            $yearList[$y] = $y;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        if (!in_array($year2, $yearList)) {
            $year2 = '';
        }

        for ($m = 0; $m <= 12; $m ++) {
            $result[$year][$m] = [
                'created'=>0,
                'won'=>0,
                'lost'=>0,
                'pending'=>0,
                'hf'=>[
                    'pending'=>[],
                    'won'=>[],
                    'lost'=>[],
                ],
            ];
            $result[$year2][$m] = [
                'created'=>0,
                'won'=>0,
                'lost'=>0,
                'pending'=>0,
                'hf'=>[
                    'pending'=>[],
                    'won'=>[],
                    'lost'=>[],
                ],
            ];
        }

        // So HS duoc mo
        $sql = 'SELECT id, deal_status, how_found, YEAR(created_at) AS y, MONTH(created_at) AS m FROM at_cases WHERE is_b2b="no" AND YEAR(created_at) IN (:year,:last) AND SUBSTRING(how_found, 1, 8)="referred"';
        $casesCreated = Kase::findBySql($sql, [':year'=>$year, ':last'=>$year2])
            ->asArray()
            ->all();
        // \fCore::expose($casesCreated); exit;
        foreach ($casesCreated as $case) {
            $hf = $case['how_found'];
            $result[$case['y']][$case['m']]['created'] ++;
            $result[$case['y']][0]['created'] ++;

            $result[$case['y']][$case['m']]['hf']['created'][$hf] = ($result[$case['y']][$case['m']]['hf']['created'][$hf] ?? 0) + 1;
            $result[$case['y']][0]['hf']['created'][$hf] = ($result[$case['y']][0]['hf']['created'][$hf] ?? 0) + 1;

            // $result[$case['y']][$case['m']]['hf']['won'][$hf] = ($result[$case['y']][$case['m']]['hf']['won'][$hf] ?? 0) + 1;
            // $result[$case['y']][0]['created']['hf']['won'][$hf] = ($result[$case['y']][0]['hf']['won'][$hf] ?? 0) + 1;

            if ($case['deal_status'] == 'won') {
                $result[$case['y']][$case['m']]['won'] ++;
                $result[$case['y']][0]['won'] ++;

                $result[$case['y']][$case['m']]['hf']['won'][$hf] = ($result[$case['y']][$case['m']]['hf']['won'][$hf] ?? 0) + 1;
                $result[$case['y']][0]['hf']['won'][$hf] = ($result[$case['y']][0]['hf']['won'][$hf] ?? 0) + 1;
            } elseif ($case['deal_status'] == 'lost') {
                $result[$case['y']][$case['m']]['lost'] ++;
                $result[$case['y']][0]['lost'] ++;

                $result[$case['y']][$case['m']]['hf']['lost'][$hf] = ($result[$case['y']][$case['m']]['hf']['lost'][$hf] ?? 0) + 1;
                $result[$case['y']][0]['hf']['lost'][$hf] = ($result[$case['y']][0]['hf']['lost'][$hf] ?? 0) + 1;
            } else {
                $result[$case['y']][$case['m']]['pending'] ++;
                $result[$case['y']][0]['pending'] ++;

                $result[$case['y']][$case['m']]['hf']['pending'][$hf] = ($result[$case['y']][$case['m']]['hf']['pending'][$hf] ?? 0) + 1;
                $result[$case['y']][0]['hf']['pending'][$hf] = ($result[$case['y']][0]['hf']['pending'][$hf] ?? 0) + 1;
            }
        }

        // \fCore::expose($result[$case['y']][0]['hf']); exit;

        return $this->render('report_qhkh_02', [
            'result'=>$result,
            'year'=>$year,
            'year2'=>$year2,
            'yearList'=>$yearList,
        ]);
    }

    /**
     * Bao cao QKKH 3: Qua va thu Club Ami Amica
     */
    public function actionQhkh03($year = '')
    {
        for ($y = date('Y'); $y >= 2012; $y --) {
            $yearList[$y] = $y;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        for ($m = 0; $m <= 12; $m ++) {
            $result[$year][$m] = [
                'sent'=>0,
                'replied'=>0,
                'pct'=>0,
                'avg'=>0,
                'gift'=>[],
            ];
        }

        // Tim cac HSGT co ngay cam on trong nam $year
        $theReferrals = Referral::find()
            ->select(['id', 'ngay_cam_on', 'ngay_chon_qua', 'ngay_gui_qua', 'gift'])
            ->where('YEAR(ngay_cam_on)=:year', [':year'=>$year])
            ->asArray()
            ->all();

        // Tim cac HSGT co ngay cam on trong nam $year
        $refGuiqua = Referral::find()
            ->select(['id', 'ngay_gui_qua', 'gift'])
            ->where('YEAR(ngay_gui_qua)=:year', [':year'=>$year])
            ->asArray()
            ->all();

        // So luong thu da gui va hoi am
        foreach ($theReferrals as $ref) {
            $m = (int)substr($ref['ngay_cam_on'], 5, 2);

            $result[$year][$m]['sent'] ++;
            $result[$year][0]['sent'] ++;
            if ($ref['ngay_chon_qua'] != '0000-00-00') {
                $result[$year][$m]['replied'] ++;
                $result[$year][0]['replied'] ++;
                // Khoang cach tu ngay hoi den ngay tra loi
                $d1 = date_create($ref['ngay_cam_on']);
                $d2 = date_create($ref['ngay_chon_qua']);
                $int = date_diff($d1, $d2, true); // force absolute integer
                $days = $int->format('%d');
                $result[$year][$m]['avg'] += (int)$days;
            }
            $result[$year][$m]['pct'] = 100 * $result[$year][$m]['replied'] / $result[$year][$m]['sent'];
            $result[$year][0]['pct'] = 100 * $result[$year][0]['replied'] / $result[$year][0]['sent'];
        }

        // So luong qua da gui trong thang
        foreach ($refGuiqua as $ref) {
            $m = (int)substr($ref['ngay_gui_qua'], 5, 2);
            if (!isset($result[$year][$m]['gift'][$ref['gift']])) {
                $result[$year][$m]['gift'][$ref['gift']] = 1;
            } else {
                $result[$year][$m]['gift'][$ref['gift']] ++;
            }
            if (!isset($result[$year][0]['gift'][$ref['gift']])) {
                $result[$year][0]['gift'][$ref['gift']] = 1;
            } else {
                $result[$year][0]['gift'][$ref['gift']] ++;
            }
        }

        for ($m = 1; $m <= 12; $m ++) {
            $result[$year][0]['avg'] += $result[$year][$m]['avg'];
            if ($result[$year][$m]['replied'] > 0) {
                $result[$year][$m]['avg'] = $result[$year][$m]['avg'] / $result[$year][$m]['replied'];
            }
        }
        if ($result[$year][0]['replied'] > 0) {
            $result[$year][0]['avg'] = $result[$year][0]['avg'] / $result[$year][0]['replied'];
        }

        if (isset($_GET['xh'])) {
            // \fCore::expose($theReferrals);
            // \fCore::expose($result); exit;
        }

        return $this->render('report_qhkh_03', [
            'result'=>$result,
            'year'=>$year,
            'yearList'=>$yearList,
        ]);
    }

    /**
     * Bao cao QKKH 4: Phan bo QHKH cac tour theo thang
     */
    public function actionQhkh04($year = '')
    {
        $qhkhIdList = [12952, 52998, 47034, 51532, 49949, 53042, 52997, 29123];
        $staffList = User::find()
            ->select(['id', 'name'])
            ->where(['id'=>$qhkhIdList])
            ->asArray()
            ->all();

        for ($y = date('Y') + 2; $y >= 2012; $y --) {
            $yearList[$y] = $y;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        // Find all tours staring this year
        $sql = 'SELECT u.id AS user_id, u.name AS user_name, t.code, MONTH(p.day_from) AS mo, tour_regions FROM at_tours t, at_ct p, at_tour_user tu, users u WHERE tu.user_id=u.id AND tu.tour_id=t.id AND p.id=t.ct_id AND YEAR(p.day_from)=:year AND p.op_status="op" AND p.op_finish!="canceled" AND tu.role="cservice" AND u.status="on"';
        $tours = Yii::$app->db->createCommand($sql, [':year'=>$year])->queryAll();

        $result = [$year=>[]];
        foreach ($tours as $tour) {
            // var_dump($tour);die;
            $month = $tour['mo'];
            $user = $tour['user_id'];
            $pos = strpos($tour['tour_regions'], ':');
            if ($pos !== false) {
                $dest = substr($tour['tour_regions'], $pos + 1, 2);
                // var_dump($tour);die;
                if ($dest == 'vn') {
                    // $dest = Yii::t('x', 'Vietnam');
                } elseif ($dest == 'la') {
                    // $dest = Yii::t('x', 'Laos');
                } elseif ($dest == 'kh') {
                    // $dest = Yii::t('x', 'Cambodia');
                } else {
                    $dest = Yii::t('x', 'Other');
                }
            } else {
                $dest = 'Other';
            }
            if (!isset($result[$year][$month][$dest][$user])) {
                $result[$year][$month][$dest][$user] = 1;
            } else {
                $result[$year][$month][$dest][$user] ++;
            }
        }

        $result = [$year=>[]];

        $tours = Product::find()
            ->select('*')
            ->where(['op_status'=>'op', 'YEAR(day_from)'=>$year, 'owner'=>'at'])
            ->andWhere('op_finish!="canceled"')
            ->with([
                'tourStats',
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id', 'tour_regions']);
                },
                'tour.cskh'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'bookings',
                'bookings.case',
                'bookings.case.stats'

            ])
            ->asArray()
            ->all();

        // \fCore::expose($tours); exit;

        foreach ($tours as $tour) {
            if (count($tour['bookings']) > 1) {
                die('co nhieu booking');
            }
            $month = (int)substr($tour['day_from'], 5, 2);
            $pos = strpos($tour['tour']['tour_regions'], ':');
            if ($pos !== false) {
                $dest = substr($tour['tour']['tour_regions'], $pos + 1, 2);
                if ($dest == 'vn') {
                    // $dest = Yii::t('x', 'Vietnam');
                } elseif ($dest == 'la') {
                    // $dest = Yii::t('x', 'Laos');
                } elseif ($dest == 'kh') {
                    // $dest = Yii::t('x', 'Cambodia');
                } else {
                    $dest = Yii::t('x', 'Other');
                }
            } else {
                $dest = 'Other';
            }
            $point_day = 0;
            $day_count = $tour['tourStats'] != null ? $tour['tourStats']['day_count'] : $tour['day_count'];
            if ($day_count >= 1 && $day_count <= 8) {
                $point_day = 1;
            }
            if ($day_count >= 9 && $day_count <= 12) {
                $point_day = 2;
            }
            if ($day_count >= 13 && $day_count <= 21) {
                $point_day = 3;
            }
            if ($day_count >= 22) {
                $point_day = 4;
            }
            $point_pax = 0;
            $pax_count = $tour['tourStats'] != null ? $tour['tourStats']['pax_count'] : $tour['pax'];
            if ($pax_count >= 1 && $pax_count <= 4) {
                $point_pax = 1;
            }
            if ($pax_count >= 5 && $pax_count <= 7) {
                $point_pax = 2;
            }
            if ($pax_count >= 8 && $pax_count <= 11) {
                $point_pax = 3;
            }
            if ($pax_count >= 12) {
                $point_pax = 4;
            }
            $point_dest = 0;
            $countries = '';
            if ($tour['tourStats'] != null) {
                $countries = $tour['tourStats']['countries'];
            } else if (strpos($tour['tour']['tour_regions'], ':') !== false) {
                $tour['tour']['tour_regions'] = str_replace(',', '|', $tour['tour']['tour_regions']);
                $ar_t_regions = explode('|', $tour['tour']['tour_regions']);
                $resul = [];
                foreach ($ar_t_regions as $region) {
                    if (trim($region) == '') continue;
                    $arr_g = explode(':', trim($region));
                    if (!isset($arr_g[1]) || $arr_g[1] == '') continue;
                    $resul[] = trim(substr($tour['tour']['tour_regions'], 0, 2));
                }
                $countries = implode(',', array_unique($resul));
            }
            // Check countries
            if (strpos($countries, 'kh') !== false || strpos($countries, 'th') !== false ) {
                $point_dest = 2;
            }
            if ( strpos($countries, 'la') !== false || strpos($countries, 'mm') !== false ) {
                $point_dest = 3;
            }
            if ($countries == 'vn') {
                $point_dest = 1;
            }

            // OLD
            // if (
            //     strpos($countries, 'th') !== false
            //     && strpos($countries, 'kh') !== false
            //     ||
            //     (strpos($countries, 'th') !== false)

            // ) {
            //     $point_dest = 2;
            // }
            // if (strpos($countries, 'la') !== false
            //     && strpos($countries, 'mm') !== false)
            // {
            //     $point_dest = 3;
            // }
            $point_age = 0;
            if ($pax_count >= 2 && $tour['bookings'][0]['case']['stats']) {
                if ($tour['bookings'][0]['case']['stats']['group_age_2_11'] + $tour['bookings'][0]['case']['stats']['group_age_2_11'] > 1) {
                    $point_age = 1;
                }
            }
            $point_tour = $point_day + $point_pax + $point_dest + $point_age;
            if (empty($tour['tour']['cskh'])) {
                if (!isset($result[$year][$month][$dest][0])) {
                    $result[$year][$month][$dest][0] = [];
                }
                $result[$year][$month][$dest][0][$tour['id']] = $point_tour;
            } else {
                foreach ($tour['tour']['cskh'] as $cskh) {
                    $user = $cskh['id'];
                    if (!isset($result[$year][$month][$dest][$user])) {
                        $result[$year][$month][$dest][$user] = [];
                    }
                    $result[$year][$month][$dest][$user][$tour['id']] = $point_tour;

                }
            }
        }
        // \fCore::expose($result); exit;

        return $this->render('reports_qhkh-04', [
            'result'=>$result,
            'year'=>$year,
            'yearList'=>$yearList,
            'staffList'=>$staffList,
        ]);
    }

    /**
     * Bao cao QKKH 4: Phan bo QHKH cac tour theo thang
     */
    public function actionQhkh04x($year = '')
    {
        $qhkhIdList = [12952, 52998, 47034, 51532, 49949, 53042, 52997, 29123];
        $staffList = User::find()
            ->select(['id', 'name'])
            ->where(['id'=>$qhkhIdList])
            ->asArray()
            ->all();

        for ($y = date('Y') + 2; $y >= 2012; $y --) {
            $yearList[$y] = $y;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        // Find all tours staring this year
        $sql = 'SELECT u.id AS user_id, u.name AS user_name, t.code, MONTH(p.day_from) AS mo, tour_regions FROM at_tours t, at_ct p, at_tour_user tu, users u WHERE tu.user_id=u.id AND tu.tour_id=t.id AND p.id=t.ct_id AND YEAR(p.day_from)=:year AND p.op_status="op" AND p.op_finish!="canceled" AND tu.role="cservice" AND u.status="on"';
        $tours = Yii::$app->db->createCommand($sql, [':year'=>$year])->queryAll();

        $result = [$year=>[]];
        foreach ($tours as $tour) {
            $month = $tour['mo'];
            $user = $tour['user_id'];
            $pos = strpos($tour['tour_regions'], ':');
            if ($pos !== false) {
                $dest = substr($tour['tour_regions'], $pos + 1, 2);
                if ($dest == 'vn') {
                    // $dest = Yii::t('x', 'Vietnam');
                } elseif ($dest == 'la') {
                    // $dest = Yii::t('x', 'Laos');
                } elseif ($dest == 'kh') {
                    // $dest = Yii::t('x', 'Cambodia');
                } else {
                    $dest = Yii::t('x', 'Other');
                }
            } else {
                $dest = 'Other';
            }
            if (!isset($result[$year][$month][$dest][$user])) {
                $result[$year][$month][$dest][$user] = 1;
            } else {
                $result[$year][$month][$dest][$user] ++;
            }
        }

        $result = [$year=>[]];

        $tours = Product::find()
            ->select('id, day_from')
            ->where(['op_status'=>'op', 'YEAR(day_from)'=>$year, 'owner'=>'at'])
            ->andWhere('op_finish!="canceled"')
            ->with([
                'tour'=>function($q){
                    return $q->select(['id', 'ct_id', 'tour_regions']);
                },
                'tour.cskh'=>function($q){
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()
            ->all();

        // \fCore::expose($tours); exit;

        foreach ($tours as $tour) {
            $month = (int)substr($tour['day_from'], 5, 2);
            $pos = strpos($tour['tour']['tour_regions'], ':');
            if ($pos !== false) {
                $dest = substr($tour['tour']['tour_regions'], $pos + 1, 2);
                if ($dest == 'vn') {
                    // $dest = Yii::t('x', 'Vietnam');
                } elseif ($dest == 'la') {
                    // $dest = Yii::t('x', 'Laos');
                } elseif ($dest == 'kh') {
                    // $dest = Yii::t('x', 'Cambodia');
                } else {
                    $dest = Yii::t('x', 'Other');
                }
            } else {
                $dest = 'Other';
            }

            if (empty($tour['tour']['cskh'])) {
                if (!isset($result[$year][$month][$dest][0])) {
                    $result[$year][$month][$dest][0] = 1;
                } else {
                    $result[$year][$month][$dest][0] ++;
                }
            } else {
                foreach ($tour['tour']['cskh'] as $cskh) {
                    $user = $cskh['id'];
                    if (!isset($result[$year][$month][$dest][$user])) {
                        $result[$year][$month][$dest][$user] = 1;
                    } else {
                        $result[$year][$month][$dest][$user] ++;
                    }
                }
            }
        }

        // \fCore::expose($result); exit;

        return $this->render('reports_qhkh-04', [
            'result'=>$result,
            'year'=>$year,
            'yearList'=>$yearList,
            'staffList'=>$staffList,
        ]);
    }


    /**
     * Doanh so ban hang tung nguoi theo thang trong nam
     *
     * TODO: Doanh so thay doi vi nguoi ban hang cu nghi viec, ket qua co the khac bang reports/bookings
     */
    public function actionB2cOne($year = '', $currency = 'EUR', $xrate_EUR = 1, $xrate_USD = 1.17, $xrate_VND = 26618.5)
    {
        if ($year == '') {
            $year = date('Y');
        }

        $sql = 'SELECT id, op_code, (IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count - 1 DAY))) AS end_date FROM at_ct WHERE op_status="op" AND op_finish!="canceled" HAVING YEAR(end_date)=:y ORDER BY end_date';
        $theTours = Product::findBySql($sql, [':y'=>$year])
            ->with([
                'bookings'=>function($q){
                    return $q->select(['id', 'case_id', 'product_id']);
                },
                'bookings.report'=>function($q){
                    return $q->select(['id', 'booking_id', 'price', 'price_unit', 'cost', 'cost_unit']);
                },
                'bookings.case'=>function($q){
                    return $q->select(['id', 'owner_id', 'is_b2b']);
                },
                'bookings.case.owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->asArray()
            ->all();
        // \fCore::expose($theTours); exit;
        // result[seller][month]
        $result = [];
        foreach ($theTours as $tour) {
            foreach ($tour['bookings'] as $booking) {
                if ($booking['case']['is_b2b'] == 'no') {
                    if (!isset($result[$booking['case']['owner_id']])) {
                        $result[$booking['case']['owner_id']] = [
                            'name'=>$booking['case']['owner']['name'],
                        ];
                        for ($m = 0; $m <= 12; $m ++) {
                            $result[$booking['case']['owner_id']][$m] = [
                                'tours'=>0,
                                'revenue'=>0,
                                'benefit'=>0,
                            ];
                        }
                    }

                    if ($booking['report']['price_unit'] != '') {
                        $ed = (int)substr($tour['end_date'], 5, 2);

                        // \fCore::expose($booking['report']); exit;
                        $revenue = $booking['report']['price'];
                        $cost = $booking['report']['cost'];
                        if ($booking['report']['price_unit'] != $currency) {
                            $revenue = $revenue / ${'xrate_'.$booking['report']['price_unit']};
                        }
                        if ($booking['report']['cost_unit'] != $currency) {
                            $cost = $cost / ${'xrate_'.$booking['report']['cost_unit']};
                        }

                        $result[$booking['case']['owner_id']][$ed]['tours'] ++;
                        $result[$booking['case']['owner_id']][$ed]['revenue'] += $revenue;
                        $result[$booking['case']['owner_id']][$ed]['benefit'] += ($revenue - $cost);

                        $result[$booking['case']['owner_id']][0]['tours'] += $result[$booking['case']['owner_id']][$ed]['tours'];
                        $result[$booking['case']['owner_id']][0]['revenue'] += $revenue;
                        $result[$booking['case']['owner_id']][0]['benefit'] += ($revenue - $cost);

                        // if ($booking['case']['owner_id'] == 4829 && $ed == 10) {
                        //     echo '<br>', $tour['id'], ' - ', $tour['op_code'], ' | ', $booking['report']['price'], $booking['report']['price_unit'], '=', $revenue, $currency, ' ', $cost, '<br>';
                        //     echo ' - - - - - ', $result[$booking['case']['owner_id']][$ed]['tours'], ' ',
                        //         $result[$booking['case']['owner_id']][$ed]['revenue'], ' ',
                        //         $result[$booking['case']['owner_id']][$ed]['benefit'];
                        // }
                    }
                }
            }
        }
        // \fCore::expose($result); exit;
        return $this->render('report_b2c_one', [
            'result'=>$result,
            'year'=>$year,
            'currency'=>$currency,
            'xrate_EUR'=>$xrate_EUR,
            'xrate_USD'=>$xrate_USD,
            'xrate_VND'=>$xrate_VND,
        ]);
    }

    /**
     * Warning for open, pending cases where tour_end_date has passed or less than one week away
     */
    public function actionSalesB2cTourEndDateWarning($year = '', $month = '')
    {
        if ($year == '') {
            $year = date('Y');
        }
        if ($month == '') {
            $month = date('n');
        }
        $result = [];
        $cases = Kase::find()
            ->select(['k.id', 'k.name', 'k.owner_id', 'k.status', 'k.deal_status', 'tour_start_date'])
            ->from('at_cases k')
            ->innerJoinWith('stats')
            ->with([
                'owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->where('is_b2b="no" AND tour_start_date!=0 AND status!="closed" AND deal_status="pending" AND DATE_SUB(tour_start_date, INTERVAL 7 DAY)<NOW()')
            ->orderBy('tour_start_date')
            ->asArray()
            ->all();
        return $this->render('report_sales-b2c_tour-end-date-warning', [
            'result'=>$cases,
            'year'=>$year,
            'month'=>$month,
        ]);
    }

    public function actionCase(
        $prospect = 'all',
        $case_type = 'month_open',
        $found = '', $contacted = '',
        $campaign_id = 'all',
        $device = 'all', $site = 'all',
        $language = 'all')
    {
        $getDestinations = Yii::$app->request->get('destination', '');
        $getNumberDay = Yii::$app->request->get('number_day', '');
        $getOwnerId = Yii::$app->request->get('owner_id', 'all');

        $getNumberPax = Yii::$app->request->get('number_pax', '');
        $getDestSelect = Yii::$app->request->get('destselect', 'all');

        $query = Kase::find()

            ->where('is_b2b = "no"')
            ->andWhere('YEAR(created_at)>=2015')
            ->leftJoin('at_case_stats', 'at_cases.id = at_case_stats.case_id');
        if ($case_type == 'month_end') {
            $query
                ->select(['*',
                'YEAR(DATE_ADD(start_date, INTERVAL
                    CEILING(CASE
                    WHEN day_count_min >= 0 AND day_count_max > 0 THEN (day_count_min + day_count_max)/2
                    WHEN day_count > 0 THEN day_count
                    ELSE day_count_min END) DAY)) AS y',
                'MONTH(DATE_ADD(start_date, INTERVAL
                    CEILING(CASE
                    WHEN day_count_min >= 0 AND day_count_max > 0 THEN (day_count_min + day_count_max)/2
                    WHEN day_count > 0 THEN day_count
                    ELSE day_count_min END) DAY)) AS m',
                ])
                ->andWhere('YEAR(start_date) >= 2016 AND YEAR(start_date) <= 2021');
        }
        if ($case_type == 'month_start') {
            $query->select(['*',
                    'SUBSTRING(start_date, 1, 4) AS y',
                    '
                    CASE
                        WHEN LENGTH(CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(start_date,"-",2),"-",-1),SIGNED INTEGER)) = 1
                            OR LENGTH(CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(start_date,"-",2),"-",-1),SIGNED INTEGER)) = 2
                            THEN CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(start_date,"-",2),"-",-1),SIGNED INTEGER)
                    END AS m'
                    ])
            ->andWhere('SUBSTRING(start_date, 1, 4) in ("2016","2017","2016", "2019", "2020")');
        }
        if (in_array($prospect, [1,2,3,4,5])){
            if ($prospect != 'all') {
                $query->andWhere(['prospect' => $prospect]);
            }
        }
        if ($site != 'all') {
                $query->andWhere(['pa_from_site' => $site]);
            }
        if ($device != 'all') {
            $query->andWhere('request_device=:device',[':device' => $device]);
        }
        if ($getOwnerId != 'all') {
            if (substr($getOwnerId, 0, 5) == 'cofr-') {
                $query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
            } else {
                $query->andWhere(['owner_id'=>(int)$getOwnerId]);
            }
        }
        if (isset($getDestinations) && !empty($getDestinations)) {
            if ($getDestSelect == 'any') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->orWhere(['like', 'req_countries', $des]);
                }
            }
            if ($getDestSelect == 'all') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->andWhere(['like', 'req_countries', $des]);
                }
            }
            if ($getDestSelect == 'only' || $getDestSelect == 'all') {
                foreach ($getDestinations as $des) {
                    $des = trim($des);
                    if ($des == '') continue;
                    $query->andWhere(['like', 'req_countries', $des]);
                }
            }
        }
        if ($language != 'all') {
            $query->andWhere(['language'=>$language]);
        }
        if ($contacted != '') {
            if ($contacted == 'web-direct') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
            } elseif ($contacted == 'link') {
                $query->andWhere(['web_referral'=>'link']);
            } elseif ($contacted == 'social') {
                $query->andWhere(['web_referral'=>'social']);
            } elseif ($contacted == 'web-search') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
            } elseif ($contacted == 'web-search-amica') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
            } elseif ($contacted == 'web-adsense') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
            } elseif ($contacted == 'web-bingad') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
            } elseif ($contacted == 'web-otherad') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
            } elseif ($contacted == 'web-adwords') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
            } elseif ($contacted == 'web-adwords-amica') {
                $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
            } elseif ($contacted == 'web-trip-connexion') {
                $query->andWhere(['web_referral'=>'ad/trip-connexion']);
            } else {
                $query->andWhere(['how_contacted'=>$contacted]);
            }
        }
        if ($getNumberDay != '') {
            $arr_s = explode('-', $getNumberDay);
            if (count($arr_s) == 1) {
                $arr_s[] = $arr_s[0];
            }
            switch (count($arr_s)) {
                case 2:
                    $query->andWhere('
                        CASE
                            WHEN day_count_max > 0
                                THEN day_count_max >= :minDay AND day_count_min <= :maxDay
                            ELSE
                                day_count_min >=:minDay AND day_count_min <=:maxDay
                        END', [':minDay' => $arr_s[0], ':maxDay' => $arr_s[1]]);
                    break;
            }
        }
        if ($campaign_id == 'yes') {
            $query->andWhere('campaign_id!=0');
        } else {
            if ($campaign_id != 'all') $query->andWhere(['campaign_id'=>$campaign_id]);
        }
        if ($getNumberPax != '') {
                $arr_s = explode('-', $getNumberPax);
                if (count($arr_s) == 1) {
                    $arr_s[] = $arr_s[0];
                }
                switch (count($arr_s)) {
                    case 1:
                        $query->andWhere('
                            CASE
                                WHEN pax_count_max > 0
                                    THEN pax_count_max >= :numPax AND pax_count_min <= :numPax
                                ELSE pax_count_min =:numPax
                            END', [':numPax' => $getNumberPax]);
                        break;
                    case 2:
                        $s_pax = explode('-', $getNumberPax);
                        $query->andWhere('
                            CASE
                                WHEN pax_count_max > 0
                                    THEN
                                        pax_count_max >= :minPax AND pax_count_min <= :maxPax
                                ELSE
                                    pax_count_min >=:minPax AND pax_count_min <=:maxPax
                            END', [':minPax' => $arr_s[0], ':maxPax' => $arr_s[1]]);
                        break;
                }
            }
        if ($found != '') {
            $query->andWhere(['how_found'=>$found]);
        }
        $totalCases = [];
        $oldCasesWon = [];
        $caseFail = [];
        $casePending = [];
        if ($case_type == 'month_open') {
            $cases = $query->asArray()->all();
            if (!$cases) {
                throw new HttpException(403,"Not found any case");
            }
            foreach ($cases as $case) {
                $y = intVal(date('Y',strtotime($case['created_at'])));
                $m = intVal(date('m',strtotime($case['created_at'])));
                $totalCases[$y][$m][] = $case['id'];
                if ($case['deal_status'] == 'won') {
                    $oldCasesWon[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'lost') {
                    $caseFail[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'pending'){
                    $casePending[$y][$m][] = $case['id'];
                }
            }
            $y_min = min(array_keys($totalCases));
            $y_max = max(array_keys($totalCases));
            $cntCaseInMonth = [];
            for ($yr = $y_min; $yr <= $y_max; $yr++) {
                for ($mo = 1; $mo <= 12 ; $mo++) {
                    $total_cnt = (isset($totalCases[$yr]) && isset($totalCases[$yr][$mo]))? $totalCases[$yr][$mo]: [];
                    $c_fail = (isset($caseFail[$yr]) && isset($caseFail[$yr][$mo]))? $caseFail[$yr][$mo]: [];
                    $c_pending = (isset($casePending[$yr]) && isset($casePending[$yr][$mo]))? $casePending[$yr][$mo]: [];
                    $old_c_won = (isset($oldCasesWon[$yr]) && isset($oldCasesWon[$yr][$mo]))? $oldCasesWon[$yr][$mo]: [];
                    $cntCaseInMonth[$yr][$mo]['c_total'] = count($total_cnt);
                    $cntCaseInMonth[$yr][$mo]['c_won'] = count($old_c_won);
                    $cntCaseInMonth[$yr][$mo]['c_fail'] = count($c_fail);
                    $cntCaseInMonth[$yr][$mo]['c_pending'] = count($c_pending);
                }
            }
        }
        if ($case_type == 'month_end') {
            $cases = $query->asArray()->all();
            if (!$cases) {
                throw new HttpException(403,"Not found any case");
            }
            $totalCases = [];
            $oldCasesWon = [];
            $caseFail = [];
            $casePending = [];
            foreach ($cases as $case) {
                if ($case['y'] == null) {
                    continue;
                }
                $y = $case['y'];
                $m = $case['m'];
                $totalCases[$y][$m][] = $case['id'];
                if ($case['deal_status'] == 'won') {
                    $oldCasesWon[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'lost') {
                    $caseFail[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'pending'){
                    $casePending[$y][$m][] = $case['id'];
                }
            }
            $query = Booking::find()
                ->select(['at_bookings.id', 'at_bookings.case_id','product_id',
                    'YEAR(DATE_ADD(day_from, INTERVAL at_ct.day_count DAY)) AS y',
                    'MONTH(DATE_ADD(day_from, INTERVAL at_ct.day_count DAY)) AS m',
                ])
                ->innerJoinWith([
                    'product' => function($q){
                        return $q->select(['id', 'day_from', 'day_count']);
                    }
                ])
                ->innerJoinWith([
                    'case' => function($q){
                        return $q->select(['id', 'is_b2b']);
                    }
                ])
                ->innerJoinWith([
                    'case.stats'
                ])
                ->andWhere(['is_b2b' => 'no', 'at_bookings.status' => 'won']);
            if (in_array($prospect, [1,2,3,4,5])){
                if ($prospect != 'all') {
                    $query->andWhere(['prospect' => $prospect]);
                }
            }
            if ($site != 'all') {
                    $query->andWhere(['pa_from_site' => $site]);
                }
            if ($device != 'all') {
                $query->andWhere('request_device=:device',[':device' => $device]);
            }
            if ($getOwnerId != 'all') {
                if (substr($getOwnerId, 0, 5) == 'cofr-') {
                    $query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
                } else {
                    $query->andWhere(['owner_id'=>(int)$getOwnerId]);
                }
            }
            if (isset($getDestinations) && !empty($getDestinations)) {
                if ($getDestSelect == 'any') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->orWhere(['like', 'req_countries', $des]);
                    }
                }
                if ($getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);
                    }
                }
                if ($getDestSelect == 'only' || $getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);
                    }
                }
            }
            if ($language != 'all') $query->andWhere(['language'=>$language]);
            if ($contacted != '') {
                if ($contacted == 'web-direct') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
                } elseif ($contacted == 'link') {
                    $query->andWhere(['web_referral'=>'link']);
                } elseif ($contacted == 'social') {
                    $query->andWhere(['web_referral'=>'social']);
                } elseif ($contacted == 'web-search') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
                } elseif ($contacted == 'web-search-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-adsense') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
                } elseif ($contacted == 'web-bingad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
                } elseif ($contacted == 'web-otherad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
                } elseif ($contacted == 'web-adwords') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
                } elseif ($contacted == 'web-adwords-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-trip-connexion') {
                    $query->andWhere(['web_referral'=>'ad/trip-connexion']);
                } else {
                    $query->andWhere(['how_contacted'=>$contacted]);
                }
            }
            if ($getNumberDay != '') {
                $arr_s = explode('-', $getNumberDay);
                if (count($arr_s) == 1) {
                    $arr_s[] = $arr_s[0];
                }
                switch (count($arr_s)) {
                    case 2:
                        $query->andWhere('
                            CASE
                                WHEN day_count_max > 0
                                    THEN day_count_max >= :minDay AND day_count_min <= :maxDay
                                ELSE
                                    day_count_min >=:minDay AND day_count_min <=:maxDay
                            END', [':minDay' => $arr_s[0], ':maxDay' => $arr_s[1]]);
                        break;
                }
            }
            if ($campaign_id == 'yes') {
                $query->andWhere('campaign_id!=0');
            } else {
                if ($campaign_id != 'all') $query->andWhere(['campaign_id'=>$campaign_id]);
            }
            if ($getNumberPax != '') {
                    $arr_s = explode('-', $getNumberPax);
                    if (count($arr_s) == 1) {
                        $arr_s[] = $arr_s[0];
                    }
                    switch (count($arr_s)) {
                        case 1:
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN pax_count_max >= :numPax AND pax_count_min <= :numPax
                                    ELSE pax_count_min =:numPax
                                END', [':numPax' => $getNumberPax]);
                            break;
                        case 2:
                            $s_pax = explode('-', $getNumberPax);
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN
                                            pax_count_max >= :minPax AND pax_count_min <= :maxPax
                                    ELSE
                                        pax_count_min >=:minPax AND pax_count_min <=:maxPax
                                END', [':minPax' => $arr_s[0], ':maxPax' => $arr_s[1]]);
                            break;
                    }
                }
            if ($found != '') {
                $query->andWhere(['how_found'=>$found]);
            }
            $bookings = $query->asArray()->all();
            $newCasesWon = [];
            $arr_bookings = [];
            if ($bookings) {
                foreach ($bookings as $b) {
                    if (isset($arr_bookings[$b['case_id']])) {
                        $old_b = $arr_bookings[$b['case_id']];
                        $new_date_end = date('Y-m-d',strtotime($b['product']['day_from'].'+'. $b['product']['day_count'].' days'));
                        $old_date_end = date('Y-m-d',strtotime($old_b['product']['day_from'].'+'. $old_b['product']['day_count'].' days'));
                        if (strtotime($new_date_end) > strtotime($old_date_end)) {
                            $arr_bookings[$b['case_id']] = $b;
                        }
                    } else {
                        $arr_bookings[$b['case_id']] = $b;
                    }
                }
                $new_c_ids = [];
                foreach ($arr_bookings as $b) {
                    $newCasesWon[$b['y']][$b['m']][] = $b['case_id'];
                }
            }
            $y_min = min(array_keys($totalCases));
            $y_max = max(array_keys($totalCases));
            $cntCaseInMonth = [];
            for ($yr = $y_min; $yr <= $y_max; $yr++) {
                for ($mo = 1; $mo <= 12 ; $mo++) {
                    $old_c_won = (isset($oldCasesWon[$yr]) && isset($oldCasesWon[$yr][$mo]))? $oldCasesWon[$yr][$mo]: [];
                    $total_cnt = (isset($totalCases[$yr]) && isset($totalCases[$yr][$mo]))? $totalCases[$yr][$mo]: [];
                    $c_won = (isset($newCasesWon[$yr]) && isset($newCasesWon[$yr][$mo]))? $newCasesWon[$yr][$mo]: [];
                    $c_fail = (isset($caseFail[$yr]) && isset($caseFail[$yr][$mo]))? $caseFail[$yr][$mo]: [];
                    $c_pending = (isset($casePending[$yr]) && isset($casePending[$yr][$mo]))? $casePending[$yr][$mo]: [];
                    $not_equal = 0;
                    if (count($old_c_won) != count($c_won)) {
                        $not_equal = count($old_c_won) - count($c_won);
                    }
                    $cntCaseInMonth[$yr][$mo]['c_total'] = count($total_cnt) - $not_equal;
                    $cntCaseInMonth[$yr][$mo]['c_won'] = count($c_won);
                    $cntCaseInMonth[$yr][$mo]['c_fail'] = count($c_fail);
                    $cntCaseInMonth[$yr][$mo]['c_pending'] = count($c_pending);
                }
            }
        }
        if ($case_type == 'month_start') {
            $cases = $query->createCommand()->queryAll();
            if (!$cases) {
                throw new HttpException(403,"Not found any case");
            }
            $totalCases = [];
            $oldCasesWon = [];
            $caseFail = [];
            $casePending = [];
            foreach ($cases as $case) {
                if ($case['y'] == null) {
                    continue;
                }
                $y = $case['y'];
                $m = $case['m'];
                $totalCases[$y][$m][] = $case['id'];
                if ($case['deal_status'] == 'won') {
                    $oldCasesWon[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'lost') {
                    $caseFail[$y][$m][] = $case['id'];
                }
                if ($case['deal_status'] == 'pending'){
                    $casePending[$y][$m][] = $case['id'];
                }
            }
            $query = Booking::find()
                ->select(['at_bookings.id', 'at_bookings.case_id','product_id',
                    'YEAR(day_from) AS y',
                    'MONTH(day_from) AS m',
                ])
                ->innerJoinWith([
                    'product' => function($q){
                        return $q->select(['id', 'day_from', 'day_count']);
                    }
                ])
                ->innerJoinWith([
                    'case' => function($q){
                        return $q->select(['id', 'is_b2b', 'created_at']);
                    }
                ])
                ->innerJoinWith([
                    'case.stats'
                ])
                ->andWhere(['is_b2b' => 'no', 'at_bookings.status' => 'won']);
            if (in_array($prospect, [1,2,3,4,5])){
                if ($prospect != 'all') {
                    $query->andWhere(['prospect' => $prospect]);
                }
            }
            if ($site != 'all') {
                    $query->andWhere(['pa_from_site' => $site]);
                }
            if ($device != 'all') {
                $query->andWhere('request_device=:device',[':device' => $device]);
            }
            if ($getOwnerId != 'all') {
                if (substr($getOwnerId, 0, 5) == 'cofr-') {
                    $query->andWhere(['cofr'=>(int)substr($getOwnerId, 5)]);
                } else {
                    $query->andWhere(['owner_id'=>(int)$getOwnerId]);
                }
            }
            if (isset($getDestinations) && !empty($getDestinations)) {
                if ($getDestSelect == 'any') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->orWhere(['like', 'req_countries', $des]);
                    }
                }
                if ($getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);
                    }
                }
                if ($getDestSelect == 'only' || $getDestSelect == 'all') {
                    foreach ($getDestinations as $des) {
                        $des = trim($des);
                        if ($des == '') continue;
                        $query->andWhere(['like', 'req_countries', $des]);
                    }
                }
            }
            if ($language != 'all') $query->andWhere(['language'=>$language]);
            if ($contacted != '') {
                if ($contacted == 'web-direct') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
                } elseif ($contacted == 'link') {
                    $query->andWhere(['web_referral'=>'link']);
                } elseif ($contacted == 'social') {
                    $query->andWhere(['web_referral'=>'social']);
                } elseif ($contacted == 'web-search') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
                } elseif ($contacted == 'web-search-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-adsense') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
                } elseif ($contacted == 'web-bingad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
                } elseif ($contacted == 'web-otherad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
                } elseif ($contacted == 'web-adwords') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
                } elseif ($contacted == 'web-adwords-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($contacted == 'web-trip-connexion') {
                    $query->andWhere(['web_referral'=>'ad/trip-connexion']);
                } else {
                    $query->andWhere(['how_contacted'=>$contacted]);
                }
            }
            if ($getNumberDay != '') {
                $arr_s = explode('-', $getNumberDay);
                if (count($arr_s) == 1) {
                    $arr_s[] = $arr_s[0];
                }
                switch (count($arr_s)) {
                    case 2:
                        $query->andWhere('
                            CASE
                                WHEN day_count_max > 0
                                    THEN day_count_max >= :minDay AND day_count_min <= :maxDay
                                ELSE
                                    day_count_min >=:minDay AND day_count_min <=:maxDay
                            END', [':minDay' => $arr_s[0], ':maxDay' => $arr_s[1]]);
                        break;
                }
            }
            if ($campaign_id == 'yes') {
                $query->andWhere('campaign_id!=0');
            } else {
                if ($campaign_id != 'all') $query->andWhere(['campaign_id'=>$campaign_id]);
            }
            if ($getNumberPax != '') {
                    $arr_s = explode('-', $getNumberPax);
                    if (count($arr_s) == 1) {
                        $arr_s[] = $arr_s[0];
                    }
                    switch (count($arr_s)) {
                        case 1:
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN pax_count_max >= :numPax AND pax_count_min <= :numPax
                                    ELSE pax_count_min =:numPax
                                END', [':numPax' => $getNumberPax]);
                            break;
                        case 2:
                            $s_pax = explode('-', $getNumberPax);
                            $query->andWhere('
                                CASE
                                    WHEN pax_count_max > 0
                                        THEN
                                            pax_count_max >= :minPax AND pax_count_min <= :maxPax
                                    ELSE
                                        pax_count_min >=:minPax AND pax_count_min <=:maxPax
                                END', [':minPax' => $arr_s[0], ':maxPax' => $arr_s[1]]);
                            break;
                    }
                }
            if ($found != '') {
                $query->andWhere(['how_found'=>$found]);
            }
            $bookings = $query->asArray()->all();
            $newCasesWon = [];
            $arr_bookings = [];
            if ($bookings) {
                foreach ($bookings as $b) {
                    if (isset($arr_bookings[$b['case_id']])) {
                        $old_b = $arr_bookings[$b['case_id']];
                        $new_date_end = date('Y-m-d',strtotime($b['product']['day_from']));
                        $old_date_end = date('Y-m-d',strtotime($old_b['product']['day_from']));
                        if (strtotime($new_date_end) < strtotime($old_date_end)) {
                            $arr_bookings[$b['case_id']] = $b;
                        }
                    } else {
                        $arr_bookings[$b['case_id']] = $b;
                    }
                }
                $new_c_ids = [];
                foreach ($arr_bookings as $b) {
                    $newCasesWon[$b['y']][$b['m']][] = $b['case_id'].' '.$b['case']['created_at'].' => '. $b['product']['day_from'];
                }
            }
            $y_min = min(array_keys($totalCases));
            $y_max = max(array_keys($totalCases));
            $cntCaseInMonth = [];
            for ($yr = $y_min; $yr <= $y_max; $yr++) {
                for ($mo = 1; $mo <= 12 ; $mo++) {
                    $old_c_won = (isset($oldCasesWon[$yr]) && isset($oldCasesWon[$yr][$mo]))? $oldCasesWon[$yr][$mo]: [];
                    $total_cnt = (isset($totalCases[$yr]) && isset($totalCases[$yr][$mo]))? $totalCases[$yr][$mo]: [];
                    $c_won = (isset($newCasesWon[$yr]) && isset($newCasesWon[$yr][$mo]))? $newCasesWon[$yr][$mo]: [];
                    $c_fail = (isset($caseFail[$yr]) && isset($caseFail[$yr][$mo]))? $caseFail[$yr][$mo]: [];
                    $c_pending = (isset($casePending[$yr]) && isset($casePending[$yr][$mo]))? $casePending[$yr][$mo]: [];
                    $not_equal = 0;
                    if (count($old_c_won) != count($c_won)) {
                        $not_equal = count($old_c_won) - count($c_won);
                    }
                    $cntCaseInMonth[$yr][$mo]['c_total'] = count($total_cnt) - $not_equal;
                    $cntCaseInMonth[$yr][$mo]['c_won'] = count($c_won);
                    $cntCaseInMonth[$yr][$mo]['c_fail'] = count($c_fail);
                    $cntCaseInMonth[$yr][$mo]['c_pending'] = count($c_pending);
                }
            }
        }
        $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email FROM at_cases c, users u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
        $campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM campaigns c ORDER BY c.start_dt DESC')->queryAll();
        $tourCountryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        return $this->render('report_case', [
            'case_type' => $case_type,
            'minYear'=>$y_min,
            'maxYear'=>$y_max,
            'result' => $cntCaseInMonth,
            'device' => $device,
            'site' => $site,
            'getDestinations' => $getDestinations,
            'contacted' => $contacted,
            'getNumberDay' => $getNumberDay,
            'ownerList' => $ownerList,
            'getOwnerId' => $getOwnerId,
            'prospect' => $prospect,
            'found' => $found,
            'language' => $language,
            'getNumberPax' => $getNumberPax,
            'campaign_id' => $campaign_id,
            'campaignList' => $campaignList,
            'tourCountryList' => $tourCountryList,
        ]);
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

    /**
     * List all reports
     */
    public function actionIndex()
    {
        $monthInquiryCount = Inquiry::find()
            ->where('SUBSTRING(created_at, 1, 7)="'.date('Y-m').'"')
            ->count();
        $monthCaseCount = Kase::find()
            ->where('SUBSTRING(created_at, 1, 7)="'.date('Y-m').'"')
            ->count();
        $monthNewTourCount = Kase::find()
            ->where('deal_status="won" AND SUBSTRING(deal_status_date, 1, 7)="'.date('Y-m').'"')
            ->count();
        $monthTourCount = Tour::find()
            ->where('SUBSTRING(code, 2, 4)="'.date('ym').'"')
            ->andWhere('status!="deleted"')
            ->count();
        $monthPayments = Payment::find()
            ->select(['xrate', 'amount'])
            ->where('SUBSTRING(payment_dt, 1, 7)="'.date('Y-m').'"')
            ->andWhere('status!="deleted"')
            ->asArray()
            ->all();

        // Month new won
        $wonCasesBySeller = Yii::$app->db
            ->createCommand('select count(*) as total, u.name from at_cases c, users u where u.id=c.owner_id AND deal_status="won" and substring(deal_status_date,1,7)=:ym group by owner_id order by total desc', [':ym'=>date('Y-m')])
            ->queryAll();

        // So HS ban them 12 thang qua
        $last12moWonCases = Yii::$app->db
            ->createCommand('select count(*) as total, SUBSTRING(deal_status_date,1,7) AS ym from at_cases where deal_status="won" group by ym order by ym')
            ->queryAll();

        // Tour khoi hanh 12 thang qua
        $last12moTours = Yii::$app->db
            ->createCommand('select SUBSTRING(ct.day_from,1,7) AS ym, COUNT(*) AS total from at_ct ct, at_tours t where t.ct_id=ct.id AND t.status!="deleted" group by ym order by ym')
            ->queryAll();

        return $this->render('report_index', [
            'monthInquiryCount'=>$monthInquiryCount,
            'monthCaseCount'=>$monthCaseCount,
            'monthNewTourCount'=>$monthNewTourCount,
            'monthTourCount'=>$monthTourCount,
            'wonCasesBySeller'=>$wonCasesBySeller,
            'last12moWonCases'=>$last12moWonCases,
            'last12moTours'=>$last12moTours,
            'monthPayments'=>$monthPayments,
        ]);
    }

    /**
     * So ngay dieu hanh tour va thuong dieu hanh
     */
    public function actionDh01($view = 'startdate', $year = '', $month = '', $code = '', $operator = '')
    {
        $viewList = [
            'startdate'=>Yii::t('x', 'Tour start date'),
            // 'enddate'=>Yii::t('x', 'Tour end date'),
        ];

        for ($y = 2016; $y <= date('Y') + 1; $y ++) {
            $yearList[$y] = $y;
        }
        for ($m = 1; $m <= 12; $m ++) {
            $monthList[$m] = $m;
        }

        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }
        if (!in_array($month, $monthList)) {
            $month = date('n');
        }

        $data = [];
        $result = [];

        // Tat ca tour bat dau trong thang
        $sql = 'SELECT t.id FROM at_ct ct, at_tours t WHERE t.ct_id=ct.id AND ct.op_status="op" AND YEAR(ct.day_from)=:y AND MONTH(ct.day_from)=:m';
        if ($code == 'f' || $code == 'g') {
            $sql .= ' AND SUBSTRING(op_code,1,1)="'.strtoupper($code).'"';
        }
        $tourIdList = Yii::$app->db->createCommand($sql, [':y'=>$year, ':m'=>$month])->queryColumn();

        if (!empty($tourIdList)) {
            $sql = 'SELECT tu.*, u.nickname AS user_name FROM at_tour_user tu, users u WHERE u.id=tu.user_id AND tu.role="operator" AND tu.tour_id IN ('.implode(',', $tourIdList).')';
            $data = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($data as $item) {
                $segs = explode('|', $item['days']);
                foreach ($segs as $seg) {
                    $j = explode(':', $seg);
                    if (!isset($j[1])) {
                        $j[1] = '';
                    }

                    if (!isset($result[$item['user_id']])) {
                        $result[$item['user_id']] = [
                            'name'=>$item['user_name'],
                        ];
                    }
                    if (!isset($result[$item['user_id']][$j[1]])) {
                        $result[$item['user_id']][$j[1]] = 0;
                    }
                    if ($j[0] != '') {
                        $result[$item['user_id']][$j[1]] += $this->count_days($j[0]);
                    }
                }
            }
        }

        // if (isset($_GET['xh'])) {
        //     \fCore::expose($data);
        //     \fCore::expose($result);
        //     exit;
        // }

        return $this->render('report_dh-01', [
            'view'=>$view,
            'year' => $year,
            'month' => $month,
            'code' => $code,
            'operator' => $operator,
            'viewList' => $viewList,
            'yearList' => $yearList,
            'monthList' => $monthList,
            'result' => $result,
        ]);
    }

    /**
     * View tour operator allocation per month in year
     * @since 20180910
     */
    public function actionDh02($view = 'tourstart', $year = '', $inccxl = 'no')
    {
        $viewList = [
            'startdate'=>Yii::t('x', 'Tour start date'),
            'enddate'=>Yii::t('x', 'Tour end date'),
        ];

        if (!array_key_exists($view, $viewList)) {
            $view = 'startdate';
        }

        for ($y = date('Y') + 1; $y >= 2007; $y --) {
            $yearList[$y] = $y;
        }

        if (!array_key_exists($year, $yearList)) {
            $year = date('Y');
        }

        if (!in_array($inccxl, ['yes', 'no'])) {
            $inccxl = 'no';
        }

        $query = Product::find()
            ->select(['id', 'day_from', 'day_until'=>new \yii\db\Expression('IF(day_count=0, 0, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
            ->where(['op_status'=>'op']);

        if ($view == 'startdate') {
            $query->andWhere('YEAR(day_from)=:year', [':year'=>$year]);
        } else {
            $query->andHaving('YEAR(day_until)=:year', [':year'=>$year]);
        }

        if ($inccxl == 'no') {
            $query->andWhere('op_finish!="canceled"');
        }

        $theTours = $query
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
        $operators = [];
        foreach ($theTours as $tour) {
            if ($view == 'startdate') {
                $y = substr($tour['day_from'], 0, 4);
                $m = (int)substr($tour['day_from'], 5, 2);
            } else {
                $y = substr($tour['day_until'], 0, 4);
                $m = (int)substr($tour['day_until'], 5, 2);
            }
            if (!empty($tour['tour']['operators'])) {
                foreach ($tour['tour']['operators'] as $user) {
                    if (!isset($operators[$user['id']])) {
                        $operators[$user['id']] = $user['name'];
                    }
                    if (!isset($result[$y][$m][$user['id']])) {
                        $result[$y][$m][$user['id']] = 1;
                    } else {
                        $result[$y][$m][$user['id']] ++;
                    }
                    if (!isset($result[$y]['all'][$user['id']])) {
                        $result[$y]['all'][$user['id']] = 1;
                    } else {
                        $result[$y]['all'][$user['id']] ++;
                    }
                    if (!isset($result[$y][$m]['all'])) {
                        $result[$y][$m]['all'] = 1;
                    } else {
                        $result[$y][$m]['all'] ++;
                    }
                }
            }
        }

        return $this->render('report_dh-02', [
            'view'=>$view,
            'viewList'=>$viewList,
            'year'=>$year,
            'yearList'=>$yearList,
            'inccxl'=>$inccxl,
            'result'=>$result,
            'operators'=>$operators,
        ]);

    }

    /**
     * Count the number of days from string str, eg: 1-6,8,12-14 (10 days)
     */
    private function count_days($str)
    {
        $count = 0;
        $groups = explode(',', $str);
        foreach ($groups as $group) {
            $days = explode('-', $group);
            if (!isset($days[1])) {
                $count += 1;
            } else {
                $count += ((int)$days[1] - (int)$days[0] + 1);
            }
        }
        return $count;
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
        $sql = 'SELECT u.id, u.fname, u.lname, u.gender, u.country_code, u.email FROM contacts u, at_booking_user bu, at_cases k, at_bookings b WHERE bu.user_id=u.id AND k.id=b.case_id AND bu.booking_id=b.id '.$andLang.$andEmpty.' AND SUBSTRING(u.updated_at,1,7)=:ym GROUP BY u.id ORDER BY u.id LIMIT 1000';
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
        return $this->render('report_mkt-04', [
            'results'=>$results,
        ]);
    }

    /**
     * Đếm số HS theo kênh và loại
     */
    public function actionMkt05($action = 'cost', $view = '', $year = '', $month = '', $date_end = '', $seller = '')
    {
        if (!in_array($action, ['cost', 'view', 'excel', 'update',])) {
            $action == 'view';
            // View list of cases + costs created in 2014-2018 and tour end dates in year-month
            // View costs for cases created in a date range (per Kx-Tx)
            // Export cost details to Excel for Accounting Dept.
            // Update costs for cases created in a date range (per Kx-Tx)
        }

        $channelList = [
            'k1'=>['id'=>'k1', 'name'=>'K1', 'description'=>'Google Adwords'],
            'k2'=>['id'=>'k2', 'name'=>'K2', 'description'=>'Bing Ads'],
            'k3'=>['id'=>'k3', 'name'=>'K3', 'description'=>'Other web search'],
            'k4'=>['id'=>'k4', 'name'=>'K4', 'description'=>'Referral + Ads online + Other web which source could not be determined'],
            'k5'=>['id'=>'k5', 'name'=>'K5', 'description'=>'Direct access'],
            'k6'=>['id'=>'k6', 'name'=>'K6', 'description'=>'Mailing'],
            'k7'=>['id'=>'k7', 'name'=>'K7', 'description'=>'Non-web'],
            'k8'=>['id'=>'k8', 'name'=>'K8', 'description'=>'Other special cases'],
            ''=>['id'=>'k0', 'name'=>'No channel', 'description'=>'No channel data entered'],
        ];

        $typeList = [
            'new'=>['id'=>'new', 'name'=>'New', 'description'=>'New customer'],
            'referred'=>['id'=>'referred', 'name'=>'Referred', 'description'=>'Referred customer'],
            'returning'=>['id'=>'returning', 'name'=>'Returning', 'description'=>'Returning customer'],
        ];
        $noTypeList = [
            ''=>['id'=>'t0', 'name'=>'No type data', 'description'=>'Data not entered'],
        ];


        if ($action == 'cost') {
            if (!in_array($view, ['ok', 'nok', 'all'])) {
                $view = 'ok';
            }
            if ($view == 'ok') {
                $kxCostCond = 'kx_cost IS NOT NULL';
            } elseif ($view == 'nok') {
                $kxCostCond = 'kx_cost IS NULL';
            } else {
                $kxCostCond = '1=1';
            }

            for ($yr = 2014; $yr <= date('Y'); $yr ++) {
                $yearList[$yr] = $yr;
            }

            if ($year == '' && (strlen($date_end) != 4 && strlen($date_end) != 7)) {
                $year = date('Y');
            }

            $dateEndYmList = Yii::$app->db->createCommand('SELECT SUBSTRING(tour_end_date, 1, 7) AS ym FROM at_case_stats WHERE YEAR(tour_end_date)>=2014 GROUP BY ym ORDER BY ym DESC')->queryColumn();
            foreach ($dateEndYmList as $ym) {
                $yr = substr($ym, 0, 4);
                if (!isset($dateEndList[$yr])) {
                    $dateEndList[$yr] = $yr.' - All year';
                }
                $dateEndList[$ym] = $ym;
            }

            // Cac HS tao trong thoi gian
            $query = Kase::find()
                ->innerJoinWith('stats')
                ->select(['id', 'kx', 'tx'=>'SUBSTRING_INDEX(how_found, "/", 1)', 'kx_cost'])
                ->where(['is_b2b'=>'no'])
                ->andWhere($kxCostCond);
            if ($year != '') {
                $query->andWhere(['YEAR(created_at)'=>$year]);
            }
            if (strlen($date_end) == 4 || strlen($date_end) == 7) {
                $query->andWhere('SUBSTRING(tour_end_date,1,'.strlen($date_end).')=:de', [':de'=>$date_end]);
            }
            if ($seller != '') {
                $query->andWhere('owner_id=:se', [':se'=>$seller]);
            }

            $cases = $query
                ->asArray()
                ->all();

            // Danh sach ban hang
            $sql = 'SELECT u.id, IF(u.status="on", "Active", "Retired") AS status, u.nickname AS name FROM users u, at_cases k WHERE YEAR(k.created_at)>=2014 AND k.is_b2b="no" AND k.owner_id=u.id GROUP BY u.id ORDER BY u.lname';
            $allSellerList = Yii::$app->db->createCommand($sql)->queryAll();
            $sellerList = \yii\helpers\ArrayHelper::map($allSellerList, 'id', 'name', 'status');
            // $sellerList = array_merge([

            // ], $sellerList);

            $data = [];
            $data[$year]['allchannels']['alltypes'] = [
                'count'=>0,
                'total'=>0,
                'avg'=>0,
            ];
            foreach ($channelList as $channelId=>$channels) {
                $data[$year][$channelId]['alltypes'] = [
                    'count'=>0,
                    'total'=>0,
                    'avg'=>0,
                ];
                foreach (array_merge($typeList, $noTypeList) as $typeId=>$types) {
                    $data[$year]['allchannels'][$typeId] = [
                        'count'=>0,
                        'total'=>0,
                        'avg'=>0,
                    ];
                    $data[$year][$channelId][$typeId] = [
                        'count'=>0,
                        'total'=>0,
                        'avg'=>0,
                    ];
                }
            }

            // \fCore::expose(count($cases)); //exit;
            foreach ($cases as $case) {
                $data[$year]['allchannels']['alltypes']['count'] ++;
                $data[$year]['allchannels']['alltypes']['total'] += $case['kx_cost'];

                // if (isset($data[$year][$case['kx']][$case['tx']])) {
                    $data[$year][$case['kx']][$case['tx']]['count'] ++;
                    $data[$year][$case['kx']][$case['tx']]['total'] += $case['kx_cost'];

                    $data[$year]['allchannels'][$case['tx']]['count'] ++;
                    $data[$year]['allchannels'][$case['tx']]['total'] += $case['kx_cost'];

                    $data[$year][$case['kx']]['alltypes']['count'] ++;
                    $data[$year][$case['kx']]['alltypes']['total'] += $case['kx_cost'];
                //}
            }

            $vars = [
                'action'=>$action,
                'channelList'=>$channelList,
                'typeList'=>$typeList,
                'view'=>$view,
                'year'=>$year,
                'yearList'=>$yearList,
                'date_end'=>$date_end,
                'dateEndList'=>$dateEndList,
                'seller'=>$seller,
                'sellerList'=>$sellerList,
                'data'=>$data,
            ];
        }

        if ($action == 'excel') {
            if ($year == '') {
                $year = date('Y');
            }
            if ($month == '') {
                $month = date('n');
            }
            // Excel table for accounting dept.
            $cases = Kase::find()
                ->select(['id', 'owner_id', 'kx_cost', 'kx', 'tx'=>'SUBSTRING_INDEX(how_found, "/", 1)'])
                ->innerJoinWith('stats')
                ->where(['is_b2b'=>'no'])
                ->andWhere(['YEAR(tour_end_date)'=>$year, 'MONTH(tour_end_date)'=>$month])
                ->asArray()
                ->all();

            $sellerIdList = \yii\helpers\ArrayHelper::getColumn($cases, 'owner_id');
            $sellerIdList = array_unique($sellerIdList);

            $data = [];
            foreach ($sellerIdList as $sellerId) {
                foreach ($channelList as $channelId=>$channels) {
                    foreach ($typeList as $typeId=>$types) {
                        $data[$sellerId][$channelId][$typeId] = [
                            'num'=>0,
                            'cost'=>0,
                        ];
                    }
                    $data[$sellerId][$channelId]['total'] = 0;
                }
            }
            foreach ($cases as $case) {
                $se = $case['owner_id'];
                $kx = $case['kx'];
                $tx = $case['tx'];
                if (!isset($data[$se][$kx][$tx])) {
                    $data[$se][$kx][$tx] = [
                        'num'=>0,
                        'cost'=>0,
                    ];
                }
                $data[$se][$kx][$tx]['num'] ++;
                $data[$se][$kx][$tx]['cost'] += $case['kx_cost'];

                $data[$se][$kx]['total'] ++;
            }
            $sellerIdList = array_keys($data);
            $sellerList = User::find()
                ->select(['id', 'name'])
                ->where(['id'=>$sellerIdList])
                ->indexBy('id')
                ->asArray()
                ->all();

            // \fCore::expose($data); exit;

            $vars = [
                'channelList'=>$channelList,
                'typeList'=>$typeList,
                'action'=>$action,
                'month'=>$month,
                'year'=>$year,
                'data'=>$data,
                'sellerList'=>$sellerList,
            ];
        }

        if ($action == 'update') {
            $year = date('Y');
            $yearList = [$year];
            $month = date('Y-m');

            if (Yii::$app->request->isPost && isset($_POST['dates'], $_POST['cost'], $_POST['currency']) && $_POST['dates'] != '') {
                if (!in_array(USER_ID, [1, 695])) {
                    throw new HttpException(403, 'Access denied.');
                }

                $dates = explode(' -- ', $_POST['dates']);

                $cases = Kase::find()
                    ->select('id, kx, kx_cost, how_found')
                    ->innerJoinWith('stats')
                    ->where(['is_b2b'=>'no'])
                    ->andWhere('created_at>=:d1 AND created_at<=:d2', [':d1'=>$dates[0], ':d2'=>$dates[1].' 23:59:59'])
                    ->andWhere(isset($_POST['newonly']) && $_POST['newonly'] == 'new' ? 'kx_cost IS NULL' : '1=1')
                    ->orderBy('created_at DESC')
                    ->asArray()
                    ->all();

                foreach (['k0', 'k1', 'k2', 'k3', 'k4', 'k5', 'k6', 'k7', 'k8'] as $kx) {
                    foreach (['new', 'referred', 'returning', 't0'] as $ty) {
                        if (isset($_POST['cost'][$kx][$ty]) && trim($_POST['cost'][$kx][$ty]) != '') {
                            // Only find cases if cost != 0
                            $caseIdList = [];
                            foreach ($cases as $case) {
                                $kOK = false;
                                $tOK = false;
                                if (($kx == 'k0' && $case['kx'] == '') || ($kx == $case['kx'])) {
                                    $kOK = true;
                                }
                                if (($ty == 't0' && $case['how_found'] == '') || substr($case['how_found'], 0, strlen($ty)) == $ty) {
                                    $tOK = true;
                                }
                                if ($tOK && $kOK) {
                                    $caseIdList[] = $case['id'];
                                }
                            }
                            // Update cost for matched cases
                            $cost = (float)trim($_POST['cost'][$kx][$ty]);
                            if (!empty($caseIdList)) {
                                Yii::$app->db->createCommand()
                                    ->update(
                                        'at_case_stats',
                                        ['kx_cost'=>$cost, 'kx_cost_currency'=>$_POST['currency']],
                                        ['case_id'=>$caseIdList]
                                    )
                                    ->execute();
                            }
                            echo '<br><strong style="color:green">[', $kx, ']</strong><strong style="color:red">[', $ty, ']</strong> <strong>', $cost, '</strong><small>', $_POST['currency'], '</small> (', count($caseIdList), ') ', implode(', ', $caseIdList);
                        }
                    }
                }
                echo '<br><br>COST UPDATE DONE. <a style="color:blue" href="?action=view">CLICK HERE TO RETURN TO REPORT</a>.';
                exit;
            }
            //echo '<br><br>COST UPDATE NOT DONE. <a style="color:red" href="?action=update">CLICK HERE TO RETRY</a>.';
            //exit;
            $vars = [
                'action'=>$action,
                'month'=>$month,
                'year'=>$year,
                'yearList'=>$yearList,
                'channelList'=>$channelList,
                'typeList'=>$typeList,
            ];

        }


        return $this->render('report_mkt-05', $vars);
    }

    // 160317 Danh sach khach gioi thieu tour con credit
    public function actionMkt03()
    {
        $sql = 'SELECT u.id, u.fname, u.lname, u.email, u.country_code, u.gender, (r.points - r.points_minus) AS credit, (SELECT COUNT(*) FROM at_booking_user bu WHERE bu.user_id=u.id) AS bookings FROM at_referrals r, contacts u WHERE u.id=r.user_id AND u.is_member="no" HAVING credit!=0 AND bookings!=0';
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
        return $this->render('report_mkt-02', [
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
        return $this->render('report_mkt-01', [
            'results'=>$results,
            'y'=>$y,
        ]);
    }


    public function actionDh03()
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

        return $this->render('report_dh-03', [
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

        $sql = 'select u.email, u.phone, u.gender, u.name, u.country_code, u.byear, count(*) as cnt, user_id from contacts u, at_booking_user bu, at_bookings b where b.id=bu.booking_id AND u.id=bu.user_id AND b.status="won" and u.is_member="no" group by bu.user_id having cnt>=:from ORDER BY u.lname, u.fname desc';
        $thePax = Yii::$app->db->createCommand($sql, [':from'=>$from])->queryAll();

        $paxIdList = [];
        foreach ($thePax as $pax) {
            $paxIdList[] = $pax['user_id'];
        }

        $paxAddrs = [];
        if (!empty($thePax)) {
            $sql = 'SELECT rid, value FROM metas WHERE name="address" AND rtype="user" AND rid IN ('.implode(',', $paxIdList).')';
            $paxAddrs = Yii::$app->db->createCommand($sql)->queryAll();
        }

        $theTours = [];
        if (!empty($paxIdList)) {
            $sql = 'SELECT p.id, p.op_code, b.finish, bu.user_id FROM at_ct p, at_booking_user bu, contacts u, at_bookings b WHERE bu.user_id=u.id AND bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN('.implode(',', $paxIdList).') ORDER BY SUBSTRING(p.op_code,2,4)';
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
        $yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2016=>2016];
        $sql2 = 'SELECT u.id, CONCAT_WS(", ", lname, fname, email) AS name FROM users u, at_bookings b WHERE b.created_by=u.id GROUP BY u.id ORDER BY lname, fname';
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
        // 171004 Added Hien
        // 180517 Added Mong
        // 180725 Added D.M.Ngoc
        if (!in_array(MY_ID, [1,2,3,4,11,17,4065,34717,32206, 34743])) {
            throw new HttpException(403, 'Access denied');
        }
        if (strlen($year) != 4) {
            $year = date('Y');
        }
        $yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018, 2019=>2019];

        $sql2 = 'SELECT u.id, CONCAT_WS(", ", lname, fname, email) AS name FROM users u, at_bookings b WHERE b.created_by=u.id GROUP BY u.id ORDER BY lname, fname';
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
        $sql2 = 'select u.id, CONCAT(u.lname, ", ", u.email) as name from users u, at_cases k where k.owner_id=u.id group by u.id order by lname, fname';
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

    public function actionBookings($viewby = 'ketthuc', $year = '', $month = '', $seller = 0, $currency = 'EUR', $rates = 1.14, $fg = 'f')
    {
        for ($y = 2007; $y <= date('Y') + 1; $y ++) {
            $yearList[$y] = $y;
        }
        for ($m = 1; $m <= 12; $m ++) {
            $monthList[$m] = $m;
        }

        $sql = 'SELECT u.id, CONCAT(u.nickname, " - ", u.email) AS name FROM users u, at_bookings b WHERE u.status="on" AND u.id=b.created_by GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
        $sellerList = Yii::$app->db->createCommand($sql)->queryAll();

        if (!in_array($viewby, ['khoihanh', 'ketthuc', 'bantour'])) {
            $viewby = 'ketthuc';
        }

        $query = Booking::find()
            ->select([
                'at_bookings.created_by', 'at_bookings.updated_by',
                'at_bookings.id', 'at_bookings.pax', 'at_bookings.currency', 'at_bookings.status_dt', 'at_bookings.case_id', 'at_bookings.product_id', 'at_bookings.updated_by', 'at_bookings.note',
                'start_date'=>'at_ct.day_from', 'end_date'=>new \yii\db\Expression('IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->innerJoinWith(['product'])
            ->andWhere([
                // 'at_bookings.status'=>'won',
                'at_ct.op_status'=>'op',
                ]);

        if ((int)$seller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$seller]);
        }
        if ((int)$year == 0) {
            $year = date('Y');
        }
        if ((int)$month == 0) {
            $month = date('n');
        }

        // if (in_array($currency, ['EUR', 'USD', 'VND'])) {
        //     $query->andWhere(['currency'=>$currency]);
        // }

        if ($fg == 'f') {
            $query->andWhere('SUBSTRING(op_code, 1, 1)="F"');
        } elseif ($fg == 'f') {
            $query->andWhere('SUBSTRING(op_code, 1, 1)="G"');
        }

        if ($viewby == 'khoihanh') {
            $query->andHaving('YEAR(start_date)=:y AND MONTH(start_date)=:m', [':y'=>$year, ':m'=>$month]);
        } elseif ($viewby == 'ketthuc') {
            $query->andHaving('YEAR(end_date)=:y AND MONTH(end_date)=:m', [':y'=>$year, ':m'=>$month]);
        } elseif ($viewby == 'bantour') {
            $query->andWhere('YEAR(status_dt)=:y AND MONTH(status_dt)=:m', [':y'=>$year, ':m'=>$month]);
        }

        $theBookings = $query
            ->orderBy($viewby == 'ketthuc' ? 'end_date' : ($viewby == 'khoihanh' ? 'start_date' : 'status_dt'))
            ->with([
                'report',
                'product'=>function($q){
                    return $q->select(['id', 'title', 'op_status', 'op_finish', 'op_code', 'op_name', 'day_count']);
                },
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname', 'image']);
                },
                'case'=>function($query) {
                    return $query->select(['id', 'name', 'owner_id', 'is_b2b']);
                },
                'case.owner'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                }
                ])
            ->asArray()
            ->all();

        return $this->render('reports_bookings', [
            'viewby'=>$viewby,
            'month'=>$month,
            'year'=>$year,
            'seller'=>$seller,
            'currency'=>$currency,
            'fg'=>$fg,
            'rates'=>$rates,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
            'yearList'=>$yearList,
            'monthList'=>$monthList,
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

    /**
     * Report B2C 02: Doanh thu tour
     */
    public function actionB2c02($report = 1)
    {
        // Trong nam 2017, bán được bao nhiêu tour có ngày kết thúc năm 2018, Doanh thu, số khách, số ngày tour của những tour đó là bao nhiêu ?

        if ($report == 1) {
            $date1from = '2017-01-01';
            $date1until = '2017-12-31';
            $date2 = '2018';
        } elseif ($report == 2) {
            $date1from = '2017-01-01';
            $date1until = '2017-07-12';
            $date2 = '2018';
        } else {
            $date1from = '2018-01-01';
            $date1until = '2018-07-12';
            $date2 = '2019';
        }

        $bookings = Booking::find()
            ->innerJoinWith('case')
            ->select(['case_id', 'at_bookings.id', 'pax', 'product_id', 'at_bookings.status_dt', 'at_bookings.status'])
            ->where(['at_bookings.status'=>'won'])
            ->andWhere('at_bookings.status_dt >=:d1 AND at_bookings.status_dt<=:d2', [':d1'=>$date1from, ':d2'=>$date1until])
            ->with([
                'product'=>function($q) use ($date2) {
                    return $q
                        ->select(['id', 'title', 'day_from', 'day_count', 'op_code', 'op_status', 'tour_end'=>new \yii\db\Expression('DATE_ADD(day_from, INTERVAL day_count-1 DAY)')])
                        ->andWhere(['op_status'=>'op'])
                        ->andWhere('op_finish!="canceled"')
                        ->andHaving('YEAR(tour_end)=:d2', [':d2'=>$date2])
                        ;
                },
                // 'people'=>function($q){
                //     return $q->select(['id', 'name']);
                // },
                'invoices'=>function($q){
                    return $q->select(['id', 'amount', 'currency', 'stype', 'booking_id']);
                },
            ])
            ->andWhere(['at_cases.stype'=>'b2c'])
            ->orderBy('status_dt')
            ->asArray()
            ->all();

        return $this->render('report_b2c-02', [
            'bookings'=>$bookings,
            'report'=>$report,
        ]);
    }

}
