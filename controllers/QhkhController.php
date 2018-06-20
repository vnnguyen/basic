<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use common\models\Kase;
use common\models\Person;
use common\models\User;
use common\models\Product;
use common\models\Tour;
use common\models\Booking;
use common\models\User2;

class QhkhController extends MyController
{
    /**
     * Index page for QHKH
     */
    public function actionIndex()
    {
        return $this->render('qhkh_index');
    }

    /**
     * Index page for QHKH
     */
    public function actionQuyTrinhThuMau()
    {
        return $this->render('qhkh_quy-trinh-thu-mau');
    }

    /**
     * Chot tour (tour_stats)
     */
    public function actionChotTour($tour_id = 0, $month = 0, $year = 0, $fg = 'f', $staff = '', $diem = 0, $ketthuc = '', $khaithac = 0)
    {
        // Edit single
        if ($tour_id != 0) {
            $theTour = Product::find()
                ->select(['id', 'op_name', 'op_code', 'op_finish', 'day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(at_ct.day_count=0, at_ct.day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
                ->andWhere(['op_status'=>'op', 'id'=>$tour_id])
                ->with([
                    'tourStats',
                    'tour',
                    'tour.cskh'=>function($q){
                        return $q->select(['id', 'name'=>'nickname']);
                    },
                    ])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found.');
            }
            $theTourOld = Tour::find()
                ->where(['ct_id'=>$theTour['id']])
                ->asArray()
                ->one();
            if (!$theTourOld) {
                throw new HttpException(404, 'Tour not found.');
            }

            $qhkhIdList = [18598, 29296, 1]; // Cao Nhung, Khang Ha
            foreach ($theTour['tour']['cskh'] as $cskh) {
                $qhkhIdList[] = $cskh['id'];
            }
            if (!in_array(USER_ID, $qhkhIdList)) {
                throw new HttpException(403, 'Access denied.');
            }

            $theForm = new \app\models\ChotTourForm;
            $theForm->qhkh_ketthuc = $theTour['tourStats']['qhkh_ketthuc'] ?? '';
            $theForm->qhkh_khaithac = explode('|', $theTour['tourStats']['qhkh_khaithac'] ?? '');
            $theForm->mkt_khaithac = explode('|', $theTour['tourStats']['mkt_khaithac'] ?? '');
            $theForm->qhkh_diem = $theTour['tourStats']['qhkh_diem'] ?? 0;
            $theForm->khach_diem = $theTourOld['pax_ratings'] ?? $theTour['tourStats']['khach_diem'] ?? 0;
            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                if (USER_ID == 18598 || USER_ID == 1) {
                    // Cao Nhung only mkt
                    $sql = 'UPDATE at_tour_stats SET mkt_khaithac=:v4 WHERE tour_id=:id';
                    Yii::$app->db->createCommand($sql, [
                        ':v4'=>!empty($theForm->mkt_khaithac) ? implode('|', $theForm->mkt_khaithac) : '',
                        ':id'=>$theTour['id'],
                        ])->execute();

                } else {
                    $sql = 'UPDATE at_tour_stats SET qhkh_ketthuc=:v1, qhkh_khaithac=:v2, qhkh_diem=:v3 , khach_diem=:v4 WHERE tour_id=:id';
                    Yii::$app->db->createCommand($sql, [
                        ':v1'=>$theForm->qhkh_ketthuc,
                        ':v2'=>!empty($theForm->qhkh_khaithac) ? implode('|', $theForm->qhkh_khaithac) : '',
                        ':v3'=>$theForm->qhkh_diem,
                        ':v4'=>$theForm->khach_diem,
                        ':id'=>$theTour['id'],
                        ])->execute();
                    $sql = 'UPDATE at_tours SET pax_ratings=:v4 WHERE id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':v4'=>$theForm->khach_diem,
                        ':id'=>$theTourOld['id'],
                        ])->execute();

                }
                Yii::$app->session->setFlash('success', 'Đã chốt tour: '.$theTour['op_code']);
                $ymd = explode('-', $theTour['end_date']);
                return $this->redirect('?year='.$ymd[0].'&month='.(int)$ymd[1]);
            }
            return $this->render('qhkh_chot-tour_id', [
                'theTour'=>$theTour,
                'theForm'=>$theForm,
                ]);
        }

        // View all tours in month
        if ($month == 0) {
            $month = date('n');
        }
        if ($year == 0) {
            $year = date('Y');
        }

        $query = Product::find()
            ->select(['at_ct.id', 'op_name', 'op_code', 'op_finish', 'at_ct.day_count', 'start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('IF(at_ct.day_count=0, at_ct.day_from, DATE_ADD(day_from, INTERVAL at_ct.day_count-1 DAY))')])
            ->andWhere(['op_status'=>'op'])
            ->andHaving('YEAR(end_date)=:y AND MONTH(end_date)=:m', [':y'=>$year, ':m'=>$month])
            ->innerJoinWith('tourStats');
        if ($fg == 'f') {
            $query->andWhere('SUBSTRING(op_code, 1, 1)="F"');
        }
        if ($ketthuc != '') {
            $query->andWhere(['qhkh_ketthuc'=>$ketthuc]);
        }
        if ($khaithac != 0) {
            $query->andWhere('LOCATE(:k, qhkh_khaithac)!=0', [':k'=>$khaithac]);
        }
        if ($diem != 0) {
            $query->andWhere(['qhkh_diem'=>$diem]);
        }

        $theTours = $query
            ->with([
                'tour',
                'tour.cskh'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tourStats',
                'bookings',
                'bookings.case',
                'bookings.case.people'=>function($q){
                    return $q->select(['id', 'name', 'email']);
                },
                ])
            ->orderBy('end_date')
            ->asArray()
            ->all();

        if ((int)$staff != 0) {
            $filteredTours = [];
            foreach ($theTours as $tour) {
                foreach ($tour['tour']['cskh'] as $qhkh) {
                    if ($qhkh['id'] == (int)$staff) {
                        $filteredTours[] = $tour;
                        break;
                    }
                }
            }
            $theTours = $filteredTours;
        }

        $sql = 'SELECT user_id FROM at_tour_user WHERE role="cservice" GROUP BY user_id';
        $staffIdList = Yii::$app->db->createCommand($sql)->queryColumn();
        $staffList = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on', 'id'=>$staffIdList])
            ->asArray()
            ->all();
        $staffList = \yii\helpers\ArrayHelper::map($staffList, 'id', 'name');

        return $this->render('qhkh_chot-tour', [
            'theTours'=>$theTours,
            'year'=>$year,
            'month'=>$month,
            'fg'=>$fg,
            'staff'=>$staff,
            'ketthuc'=>$ketthuc,
            'khaithac'=>$khaithac,
            'diem'=>$diem,
            'staffList'=>$staffList,
            ]);
    }

    /**
     * Quy QHKH
     */
    public function actionQuyQhkh($action = '', $month = '', $year = '')
    {
        // View month
        if ($action == 'view-month') {
            if (strlen($month) != 7) {
                $month = date('Y-m');
            }
            $theTours = Product::find()
                // ->select()
                ->with(['bookings'])
                ->where(['op_status'=>'op'])
                ->andWhere('SUBSTRING(day_from,1,7)=:month', [':month'=>$month])
                ->andWhere('SUBSTRING(op_code,1,1)!="G"')
                ->orderBy('day_from')
                ->asArray()
                ->all();
            // foreach ($theTours as $tour) {
            //     foreach ($tour['bookings'] as $booking) {
            //         if (substr($tour['op_code'], 0, 1) == 'G') {
            //     $sql2 = 'UPDATE at_bookings SET quy_qhkh=0, quy_qhkh_updated_dt=0, quy_qhkh_updated_by=0 WHERE id=:id LIMIT 1';
            //     Yii::$app->db->createCommand($sql2, [
            //         ':id'=>$booking['id'],
            //         ])->execute();
            //         }
            //     }
            // }
            return $this->render('qhkh_quy-qhkh_view-month', [
                'theTours'=>$theTours,
                ]);

        }
        // Huan update
        if ($action == 'huan-update') {
            $sql = 'select b.id, t.op_code, t.op_name, b.pax, t.day_count, t.day_from, op_finish from at_bookings b, at_ct t where b.product_id=t.id and year(day_from)=:year and month(day_from)=:month and t.op_status="op"';
            $bookings = Yii::$app->db->createCommand($sql, [':month'=>$month, ':year'=>$year])->queryAll();
            foreach ($bookings as $booking) {
                if ($booking['day_count'] >= 5) {
                    $quy = 10 * $booking['pax'];
                } else {
                    $quy = 5 * $booking['pax'];
                }
                echo '<br>', $booking['id'], ' : ', $booking['op_code'],' (', $booking['pax'], 'p ', $booking['day_count'], 'd) = ', $quy;
                if ($booking['day_count'] < 5) {
                    echo ' -- SMALL --';
                }
                $sql2 = 'UPDATE at_bookings SET quy_qhkh=:q, quy_qhkh_updated_dt=:now, quy_qhkh_updated_by=:me WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql2, [
                    ':q'=>$quy,
                    ':now'=>NOW,
                    ':me'=>USER_ID,
                    ':id'=>$booking['id'],
                    ])->execute();
            }
            exit;
        }

        $thuQuyQhkh = Yii::$app->db->createCommand('select SUM(quy_qhkh) AS tong, SUBSTRING(p.day_from, 1, 7) AS thang from at_bookings b, at_ct p where b.product_id=p.id AND quy_qhkh>0 GROUP BY thang ORDER BY thang DESC')->queryAll();
        $chiQuyQhkh = Yii::$app->db->createCommand('select SUM(quy_qhkh) AS tong, SUBSTRING(quy_qhkh_updated_dt, 1, 10) AS thang from at_bookings where quy_qhkh>0 GROUP BY SUBSTRING(quy_qhkh_updated_dt,1,7) ORDER BY SUBSTRING(quy_qhkh_updated_dt,1,7) DESC')->queryAll();
        return $this->render('qhkh_quy-qhkh', [
            'thuQuyQhkh'=>$thuQuyQhkh,
            'chiQuyQhkh'=>$chiQuyQhkh,
        ]);
    }


}
