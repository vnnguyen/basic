<?

namespace app\controllers\special;

use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\Person;
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

class NguyentvController extends \app\controllers\MyController
{
    // Thong ke khach san, nha dan 2015 - 2016
    // List tat ca ks
    // Ajax lam tung ks
    public function actionIndex($type = 'hotel', $dest = 1, $status = 'restr')
    {
        $query = Venue::find()
            ->select(['id', 'name', 'destination_id', 'stype', 'status', 'search']);
        if ($type == '') {
            $query->andWhere(['stype'=>['home', 'hotel']]);
        } else {
            $query->andWhere(['stype'=>$type]);
        }
        if ($dest != 0 && $type == 'hotel') {
            $query->andWhere(['destination_id'=>$dest]);
        }
        if ($status == 'restr') {
            $query->andWhere(['or', new \yii\db\Expression('LOCATE("str", search)!=0'), new \yii\db\Expression('LOCATE("re", search)!=0')]);
        } elseif ($status == 're') {
            $query->andWhere(new \yii\db\Expression('LOCATE("re", search)!=0'));
        } elseif ($status == 'str') {
            $query->andWhere(new \yii\db\Expression('LOCATE("str", search)!=0'));
        }

        $theVenues = $query
            ->with([
                'destination',
                'destination.country',
                'stats',
                ])
            ->orderBy('destination_id, name')
            ->asArray()
            ->all();
        return $this->render('nguyentv_index', [
            'theVenues'=>$theVenues,
            'type'=>$type,
            'dest'=>$dest,
            'status'=>$status,
            ]);
    }

    // Thong ke khach san, nha dan 2015 - 2016
    // List tat ca ks
    // Ajax lam tung ks
    public function actionStats($yr = 2016)
    {
        $theVenues = Venue::find()
            ->select(['id', 'name', 'destination_id', 'stype', 'status'])
            ->where(['stype'=>['hotel', 'home']])
            ->with([
                'destination',
                'destination.country',
                'stats',
                ])
            ->orderBy('destination_id')
            ->asArray()
            ->all();
        return $this->render('nguyentv_stats', [
            'theVenues'=>$theVenues,
            'yr'=>$yr,
            ]);
    }

    public function actionAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!isset($_POST['id']) || !isset($_POST['yr'])) {
            throw new HttpException(404);
        }

        $ks = (int)$_POST['id'];
        $month = (int)$_POST['yr'];
        if ($month != 2015 && $month != 2016) {
            throw new HttpException(404);
        }

        $sql = 'select tour_id FROM cpt WHERE venue_id=:ks AND LOCATE(:month, dvtour_day)=1 GROUP BY tour_id ORDER BY dvtour_day';
        $tourIdList = Yii::$app->db->createCommand($sql, [':ks'=>$ks, ':month'=>$month])->queryColumn();

        $theTours = Tour::find()
            ->select(['id', 'code', 'name', 'status', 'ct_id'])
            ->where(['id'=>$tourIdList])
            ->with([
                'cpt'=>function($q) use ($ks, $month) {
                    return $q
                    ->select(['dvtour_id', 'dvtour_name', 'dvtour_day', 'tour_id', 'qty', 'unit', 'price', 'unitc'])
                    ->where(['venue_id'=>$ks])
                    ->andWhere('LOCATE(:month, dvtour_day)=1', [':month'=>$month])
                    ->orderBy('dvtour_day');
                },
                'product'=>function($q) {
                    return $q->select(['id', 'day_count', 'day_from']);
                },
                'product.bookings'=>function($q) {
                    return $q->select(['pax', 'product_id']);
                },
                ])
            ->asArray()
            ->all();

        $tourCount = 0;
        $paxCount = 0;
        $roomNightCount = 0;
        $totalPrice = 0;
        foreach ($theTours as $tour) {
            $noPax = 0;
            $noRoomNights = 0;
            $price = 0;

            // Tinh so dem khach san
            $nights = [];
            $rooms = [];
            $names = '';
            foreach ($tour['cpt'] as $cpt) {
                if (
                        strpos($cpt['dvtour_name'], "hách sạn") !== false
                        || strpos($cpt['dvtour_name'], "hà dân") !== false
                        || strpos($cpt['dvtour_name'], "commodation") !== false
                        || strpos($cpt['dvtour_name'], "otel") !== false
                        || strpos($cpt['dvtour_name'], "uest house") !== false
                        || strpos($cpt['dvtour_name'], "uesthouse") !== false
                        || strpos($cpt['dvtour_name'], "stay") !== false
                        ) {

                    if (!isset($nights[$cpt['dvtour_day']])) {
                        $nights[$cpt['dvtour_day']] = $cpt['qty'];
                    } else {
                        $nights[$cpt['dvtour_day']] += $cpt['qty'];
                    }

                    $names .= $cpt['dvtour_name'].': '.$cpt['qty'].' x '.$cpt['unit'].chr(10);
                }
            }

            if (!empty($nights)) {
                foreach ($tour['product']['bookings'] as $booking) {
                    $noPax += $booking['pax'];
                }
                $tourCount ++;
            }

            $calls = [];
            $lastNight = '';

            foreach ($nights as $night=>$room) {
                $noRoomNights += $room;

                if ($lastNight == '') {
                    $calls[] = [
                        'nights'=>1,
                        'from'=>date('j/n', strtotime($night)),
                        'until'=>date('j/n', strtotime($night)),
                    ];
                } else {
                    if (date('Y-m-d', strtotime('-1 day '.$night)) == $lastNight) {
                        $calls[count($calls) - 1]['nights'] ++;
                        $calls[count($calls) - 1]['until'] = date('j/n', strtotime($night));
                    } else {
                        $calls[] = [
                            'nights'=>1,
                            'from'=>date('j/n', strtotime($night)),
                            'until'=>date('j/n', strtotime($night)),
                        ];
                    }
                }
                $lastNight = $night;
            }

            $paxCount += $noPax;
            $roomNightCount += $noRoomNights;
        }

        if ($month == 2015) {
            $sql = 'UPDATE venue_stats SET t2015=:t, p2015=:p, rn2015=:rn WHERE venue_id=:id LIMIT 1';
        } elseif ($month == 2016) {
            $sql = 'UPDATE venue_stats SET t2016=:t, p2016=:p, rn2016=:rn WHERE venue_id=:id LIMIT 1';
        }
        Yii::$app->db->createCommand($sql, [
            ':t'=>$tourCount,
            ':p'=>$paxCount,
            ':rn'=>$roomNightCount,
            ':id'=>$ks,
            ])->execute();

        if ($month == 2015) {
            $return = [
                't2015'=>$tourCount,
                'p2015'=>$paxCount,
                'rn2015'=>$roomNightCount,
                'message'=>'OK',
                't2016'=>'-',
                'p2016'=>'-',
                'rn2016'=>'-',
            ];
        } elseif ($month == 2016) {
            $return = [
                'message'=>'OK',
                't2015'=>'-',
                'p2015'=>'-',
                'rn2015'=>'-',
                't2016'=>$tourCount,
                'p2016'=>$paxCount,
                'rn2016'=>$roomNightCount,
            ];
        }
        return $return;
    }
}