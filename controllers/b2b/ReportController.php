<?php

namespace app\controllers\b2b;

use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\Person;
use common\models\Search;
use common\models\Client;
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
use common\models\User2;
use common\models\SampleTourDay;
use common\models\SampleTourProgram;
use Mailgun\Mailgun;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class ReportController extends \app\controllers\MyController
{
    public function actionIndex($seller = 0, $client = 0, $currency = 'USD', $year = '', $month = '', $viewtour = 'sale_date')
    {
        if ((int)$year == 0) {
            $year = date('Y');
        }

        $sellerIdList = Yii::$app->db->createCommand('SELECT owner_id FROM at_cases WHERE is_b2b="yes" GROUP BY owner_id')->queryColumn();
        $sql = 'SELECT u.id, CONCAT(u.lname, " ", u.email) AS name FROM users u, at_bookings b WHERE u.id=b.created_by AND u.id IN ('.implode(',', $sellerIdList).') GROUP BY b.created_by ORDER BY u.status, u.lname, u.fname';
        $sellerList = User2::findBySql($sql)
            ->asArray()
            ->all();

        $query = Booking::find()
            ->andWhere(['at_bookings.status'=>'won']);

        if ((int)$seller != 0) {
            $query->andWhere(['at_bookings.created_by'=>$seller]);
        }
        if ($viewtour == 'sale_date') {
            // Sale date
            $query->andWhere('YEAR(at_bookings.status_dt)=:yr', [':yr'=>$year]);
            if ((int)$month != 0) {
                $query->andWhere('MONTH(at_bookings.status_dt)=:mo', [':mo'=>$month]);
            }
            $query->joinWith([
                'product'
            ]);
        } elseif ($viewtour == 'start_date') {
            // Start date
            $query->joinWith([
                'product'=>function($q) use ($year, $month) {
                    $q->andWhere('YEAR(day_from)=:yr', [':yr'=>$year]);
                    if ((int)$month != 0) {
                        $q->andWhere('MONTH(day_from)=:mo', [':mo'=>$month]);
                    }
                    return $q;
                }
                ])
                ->orderBy('at_ct.day_from');
        } else {
            // End date
            $query
                ->select(['at_ct.*', 'at_bookings.*', new \yii\db\Expression('IF (day_count=0, start_date, DATE_ADD(day_from, INTERVAL day_count-1 DAY)) AS end_date')])
                ->innerJoinWith('product')
                ->orderBy('end_date')
                ->andHaving('YEAR(end_date)=:yr', [':yr'=>$year]);
            if ((int)$month != 0) {
                $query->andHaving('MONTH(end_date)=:mo', [':mo'=>$month]);
            }

        }

        if (in_array($currency, ['EUR', 'USD', 'VND'])) {
            $query->andWhere(['currency'=>$currency]);
        }

        $theBookings = $query
            ->with([
                'report',
                'product',
                'product.tour',
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name', 'image']);
                },
                'case'=>function($query) {
                    return $query->select(['id', 'name', 'owner_id', 'company_id', 'is_b2b', 'stype']);
                },
                'case.company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'case.owner'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                }
            ])
            ->asArray()
            ->all();

        // Cases created this month
        $monthCases = Kase::find()
            ->where(['is_b2b'=>'yes', 'stype'=>'b2b'])
            ->andWhere((int)$seller != 0 ? 'owner_id=:s' : '1!=:s', [':s'=>$seller])
            ->andWhere('YEAR(created_at)=:ym', [':ym'=>$year, ':mo'=>$month])
            ->andWhere((int)$month != 0 ? 'MONTH(created_at)=:mo' : '1!=:mo', [':mo'=>$month])
            ->with([
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'owner'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                },
                'stats'
            ])
            ->orderBy('company_id, created_at DESC')
            ->asArray()
            ->all();

        $clientList = Client::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('report_index', [
            'seller'=>$seller,
            'currency'=>$currency,
            'theBookings'=>$theBookings,
            'sellerList'=>$sellerList,
            'monthCases'=>$monthCases,
            'year'=>$year,
            'month'=>$month,
            'viewtour'=>$viewtour,
            'client'=>$client,
            'clientList'=>$clientList,
        ]);
    }

    public function actionSeller($seller = '', $year = '', $month = '')
    {
        if ((int)$year == 0) {
            $year = date('Y');
        }
        if ((int)$month == 0) {
            $month = date('n');
        }
        return $this->render('report_seller', [
            'seller'=>$seller,
            'year'=>$year,
            'month'=>$month,
        ]);
    }

}