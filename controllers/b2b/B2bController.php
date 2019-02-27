<?php

namespace app\controllers\b2b;

use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\User;
use common\models\Search;
use common\models\Ct;
use common\models\Client;
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

class B2bController extends \app\controllers\MyController
{

	public function actionIndex()
	{
		return $this->render('b2b_index', [
		]);
	}

    /**
     * Series tour management
     */
    public function actionSeries($client = '', $view = 'tourstart', $year = '', $month = '', $status = '')
    {
        for ($y = 2017; $y <= date('Y') + 2; $y ++) {
            $yearList[$y] = $y;
        }
        for ($m = 1; $m <= 12; $m ++) {
            $monthList[$m] = $m;
        }
        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }
        if (!in_array($month, $monthList) && $month != '') {
            $month = '';
        }

        $query = Tour::find()
            ->where('SUBSTRING(code,1,1)="G"')
            ->andWhere('YEAR(created_dt)=:year', [':year'=>$year]);

        if ((int)$client != 0) {
            // TODO            
        }

        if ($month != '') {
            $query
            ->andWhere('MONTH(created_dt)=:month', [':month'=>$month]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>250,
        ]);

        $theTours = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $clientList = Client::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $statusList = [
            'draft'=>'Draft',
            'open'=>'Open',
            'confirmed'=>'Confirmed',
            'canceled'=>'Canceled',
        ];

        return $this->render('b2b_series', [
            'theTours'=>$theTours,
            'client'=>$client,
            'year'=>$year,
            'month'=>$month,
            'status'=>$status,
            'clientList'=>$clientList,
            'yearList'=>$yearList,
            'monthList'=>$monthList,
            'statusList'=>$statusList,
        ]);
    }

}