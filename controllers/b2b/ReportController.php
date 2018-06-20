<?php

namespace app\controllers\b2b;

use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\User;
use common\models\Search;
use common\models\Ct;
use common\models\Cpt;
use common\models\Day;
use common\models\Kase;
use common\models\Inquiry;
use common\models\Message;
use common\models\ProfileTA;
use common\models\Sysnote;
use common\models\Tour;
use common\models\Product;
use common\models\Booking;
use common\models\Task;
use common\models\SampleTourDay;
use common\models\SampleTourProgram;
use Mailgun\Mailgun;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\GridView;
use yii\data\Pagination;
use yii\web\HttpException;

class ReportController extends \app\controllers\MyController
{
    public function actionIndex()
    {
        return $this->render('report_index', [
        ]);
    }
    public function actionReports()
    {

        $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
        $getBantour = Yii::$app->request->get('bantour', 0);
        $getSeller = Yii::$app->request->get('seller', 0);
        $getCurrency = Yii::$app->request->get('currency', 0);
        $getB2b = Yii::$app->request->get('b2b', 0);

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

        return $this->render('bookings_reports', [
            'getKhoihanh'=>$getKhoihanh,
            'getBantour'=>$getBantour,
            'getSeller'=>$getSeller,
            'getCurrency'=>$getCurrency,
            'getB2b'=>$getB2b,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
            'listKhoiHanh'=>$listKhoiHanh,
            'listBanTour'=>$listBanTour,
        ]);
    }
    public function actionTour()
    {
        $getTypeDate = Yii::$app->request->get('type_date', 'm');
        $getDate = Yii::$app->request->get('date', date('Y-m'));
        $getDestination = Yii::$app->request->get('destination', '');
        $getSeller = Yii::$app->request->get('seller', 0);
        $getStatus = Yii::$app->request->get('sale_status', '');
        if ($getDestination != '') {
            $getDestination = array_diff($getDestination, ['']);
        }
        $sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM persons u, at_bookings b, at_cases c WHERE u.id=b.created_by AND b.case_id = c.id AND c.is_b2b = "yes" AND c.stype = "b2b-series" GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
        $sellerList = Yii::$app->db->createCommand($sql)->queryAll();

        $query = Booking::find()
            ->joinWith([
            'product'
            ])
            ->joinWith([
                'case' => function($q){
                    $q->andWhere(['is_b2b' => 'yes']);
                }
            ]);

        if ((int)$getSeller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$getSeller]);
        }
        if ($getTypeDate != 'm') {
            $query->andWhere('SUBSTRING(at_bookings.status_dt,1,4)=:y', [':y'=>$getDate]);
        } else {
            $query->andWhere('SUBSTRING(at_bookings.status_dt,1,7)=:ym', [':ym'=>$getDate]);
        }
        if ($getStatus != '') {//var_dump($getStatus);die();
            $query->andWhere(['at_ct.op_status' => $getStatus]);
        } else {
            $query->andWhere('at_ct.op_status = "op"');
        }
        if ($getDestination != '') {
            $query->joinWith([
                'case.stats'
            ]);
            foreach ($getDestination as $des) {
                if ($des != '') {
                    $query->andWhere(['at_case_stats.pa_destinations' => $des]);
                }
            }
        }

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
                },
                'case.stats'=>function($query) {
                    return $query->select(['case_id', 'req_countries']);
                }
                ])
            ->asArray()
            ->all();

        // var_dump($theBookings);die();
        return $this->render('tour_report', [
            'getTypeDate'=>$getTypeDate,
            'getDate'=>$getDate,
            'getDestination'=>$getDestination,
            'getStatus'=>$getStatus,
            'getSeller' => $getSeller,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
        ]);
    }
    public function actionTour_series()
    {
        $getTypeDate = Yii::$app->request->get('type_date', 'm');
        $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
        $getBantour = Yii::$app->request->get('bantour', 0);
        $getSeller = Yii::$app->request->get('seller', 0);
        $getCurrency = Yii::$app->request->get('currency', 0);

        $sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM persons u, at_bookings b, at_cases c WHERE u.id=b.created_by AND b.case_id = c.id AND c.is_b2b = "yes" AND c.stype = "b2b-series" GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
        $sellerList = Yii::$app->db->createCommand($sql)->queryAll();

        $query = Booking::find()
            ->andWhere(['at_bookings.status'=>'won'])
            ->joinWith([
                'case'=>function($q) {
                    $q->andWhere('is_b2b = "yes" AND stype = "b2b-series"');
                }
            ]);

        if ((int)$getSeller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$getSeller]);
        }
        if ($getBantour == 0) {
            $getBantour = date('Y-m');
        }
        if ($getTypeDate != 'm' && $getBantour != 0) {
            $query->andWhere('SUBSTRING(at_bookings.status_dt,1,4)=:y', [':y'=>$getBantour]);
        } else {
            $query->andWhere('SUBSTRING(at_bookings.status_dt,1,7)=:ym', [':ym'=>$getBantour]);
        }

        if (in_array($getCurrency, ['EUR', 'USD', 'VND'])) {
            $query->andWhere(['currency'=>$getCurrency]);
        }

        $query->joinWith([
            'product'
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

        return $this->render('tour_series_reports', [
            'getTypeDate' => $getTypeDate,
            'getBantour'=>$getBantour,
            'getSeller'=>$getSeller,
            'getCurrency'=>$getCurrency,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
        ]);
    }
    public function actionTour_request()
    {
        $getTypeDate = Yii::$app->request->get('type_date', 'm');
        $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
        $getBantour = Yii::$app->request->get('bantour', 0);
        $getSeller = Yii::$app->request->get('seller', 0);
        $getCurrency = Yii::$app->request->get('currency', 0);

        $sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM persons u, at_bookings b, at_cases c WHERE u.id=b.created_by AND b.case_id = c.id AND c.is_b2b = "yes" AND c.stype = "b2b-series" GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
        $sellerList = Yii::$app->db->createCommand($sql)->queryAll();

        $query = Booking::find()
            ->andWhere(['at_bookings.status'=>'won'])
            ->joinWith([
                'case'=>function($q) {
                    $q->andWhere('is_b2b = "yes" AND stype = "b2b"');
                }
            ]);

        if ((int)$getSeller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$getSeller]);
        }
        if ($getBantour == 0) {
            $getBantour = date('Y-m');
        }
        // var_dump($getStatus);die();
        if ($getTypeDate != 'm') {
            $query->andWhere('SUBSTRING(at_bookings.status_dt,1,4)=:y', [':y'=>$getBantour]);
        } else {
            $query->andWhere('SUBSTRING(at_bookings.status_dt,1,7)=:ym', [':ym'=>$getBantour]);
        }

        if (in_array($getCurrency, ['EUR', 'USD', 'VND'])) {
            $query->andWhere(['currency'=>$getCurrency]);
        }

        $query->joinWith([
            'product'
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

        return $this->render('tour_request_reports', [
            'getTypeDate' => $getTypeDate,
            'getBantour'=>$getBantour,
            'getSeller'=>$getSeller,
            'getCurrency'=>$getCurrency,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
        ]);
    }
    public function actionTour_start()
    {
        $getTypeDate = Yii::$app->request->get('type_date', 'm');
        $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
        $getSeller = Yii::$app->request->get('seller', 0);
        $getCurrency = Yii::$app->request->get('currency', 0);
        $getStype = Yii::$app->request->get('stype_tour', '');

        $sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM persons u, at_bookings b , at_cases c WHERE u.id=b.created_by AND b.case_id = c.id AND c.is_b2b = "yes" AND c.stype = "b2b-series" GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
        $sellerList = Yii::$app->db->createCommand($sql)->queryAll();

        $query = Booking::find()
            ->andWhere(['at_bookings.status'=>'won'])
            ->joinWith([
                'case'=>function($q) {
                    $q->andWhere('is_b2b = "yes"');
                }
            ]);

        
        if ((int)$getSeller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$getSeller]);
        }
        if (in_array($getCurrency, ['EUR', 'USD', 'VND'])) {
            $query->andWhere(['currency'=>$getCurrency]);
        }
        if ($getTypeDate == '' || $getTypeDate == 'm' ) {
            $query->joinWith([
                'product'=>function($q) {
                    $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
                    if ((int)$getKhoihanh != 0) {
                        $q->andWhere('SUBSTRING(day_from,1,7)=:ym', [':ym'=>$getKhoihanh]);
                    } else {
                        $q->andWhere('SUBSTRING(day_from,1,7)=:ym', [':ym'=>date('Y-m')]);
                    }
                }
            ]);
        } else {
            $query->joinWith([
                'product'=>function($q) {
                    $getKhoihanh = Yii::$app->request->get('khoihanh', 0);
                    if ((int)$getKhoihanh != 0) {
                        $q->andWhere('SUBSTRING(day_from,1,4)=:ym', [':y'=>$getKhoihanh]);
                    } else {
                        $q->andWhere('SUBSTRING(day_from,1,4)=:ym', [':y'=>date('Y')]);
                    }
                }
            ]);
        }
        if ($getStype != '') {
            $query->andWhere('stype =:stype', [':stype' => $getStype]);
        }
        if ($getKhoihanh == 0) {
            $getKhoihanh = date('Y-m');
        }
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

        return $this->render('tour_start_reports', [
            'getTypeDate' => $getTypeDate,
            'getKhoihanh'=>$getKhoihanh,
            'getSeller'=>$getSeller,
            'getCurrency'=>$getCurrency,
            'getStypeTour' => $getStype,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
        ]);
    }
    public function actionExport_data($data = [])
    {
        $data_source = [];
        foreach (unserialize($data) as $k => $v) {
            $arr_tmp = [];
            $arr_tmp['#'] = strtoupper(str_replace('_', ' ', $k));
            foreach ($v as $key => $value) {
                $arr_tmp[$key] = $value;
            }
            $data_source[] = $arr_tmp;
        }
        \moonland\phpexcel\Excel::widget([
            'models' => $data_source,
            'mode' => 'export',
            'columns' => ['#', 'series','request','total'],
            'headers' => ['#'=> '#', 'series' => 'Series','request' => 'Request', 'total' => 'Total'], 
        ]);
        return 1;
    }

}