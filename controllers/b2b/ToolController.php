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
use common\models\TourStats;
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
use yii\web\Response;

class ToolController extends \app\controllers\MyController
{
    public function actionSpecial()
    {
        $action = Yii::$app->request->post('action', '');
        $id = Yii::$app->request->post('id', '');
        $theTour = Product::find()
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->with([
                'tourStats',
                'bookings',
                ])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404);
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$theTour['id']])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourPaxCount = 0;
        foreach ($theTour['bookings'] as $booking) {
            $theTourPaxCount += (int)$booking['pax'];
        }

        $theStats = TourStats::find()
            ->where(['tour_id'=>$id])
            ->one();

        if (!$theStats) {
            $theStats = new TourStats;
            $theStats['tour_id'] = $theTour['id'];
            $theStats['tour_old_id'] = $theTourOld['id'];
            $theStats['start_date'] = $theTour['day_from'];
            $theStats['end_date'] = date('Y-m-d', strtotime('+ '.($theTour['day_count'] - 1).' days', strtotime($theTour['day_from'])));
            $theStats['day_count'] = $theTour['day_count'];
            $theStats['pax_count'] = $theTourPaxCount;
            $theStats['tour_code'] = $theTour['op_code'];
            $theStats['tour_name'] = $theTour['op_name'];
            $theStats['countries'] = [];
        }

        $response = [];
    	if ($action == 'mark-private-series') {
            if ($theStats['b2b_type'] == 'private') {
                $theStats->b2b_type = 'series';
                $response['text'] = '[S]';
                $response['class'] = 'text-pink';
            } else {
                $theStats->b2b_type = 'private';
                $response['text'] = '[P]';
                $response['class'] = 'text-violet';
            }
            $theStats->save(false);
    	}

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

}