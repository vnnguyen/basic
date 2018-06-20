<?

namespace app\controllers\special;

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

class CptController extends \app\controllers\MyController
{

    // Theo doi cpt do DH nhap
    public function actionIndex($vat = '', $user = 'all', $tour = '', $dvtour = '', $search = '', $filter = '', $payer = '', $sign = '', $currency = '', $tt = '', $orderby = 'dvtour_day', $limit = 25)
    {
        if (MY_ID > 4 && !in_array(MY_ID, [1,2,3,4,28431,  11,   17,   16,  20787,29739, 30085, 25457])) {
            //throw new HttpException(403, 'Access denied.');
        }

        if (!in_array($limit, [25, 50, 100, 500])) {
            $limit = 25;
        }

        $query = Cpt::find();

        if ($user == 'me') {
            $query->andWhere(['updated_by'=>USER_ID]);
        } elseif ((int)$user != 0) {
            $query->andWhere(['updated_by'=>$user]);
        }

        if ($tt == 'no') {
            $query->andWhere('SUBSTRING(c3,1,2)!="on"');
        } elseif ($tt == 'yes') {
            $query->andWhere('SUBSTRING(c3,1,2)="on"');
        } elseif ($tt == 'c3') {
            $query->andWhere('SUBSTRING(c3,1,2)="on"');
            $query->andWhere('SUBSTRING(c4,1,2)!="on"');
        } elseif ($tt == 'c4') {
            $query->andWhere('SUBSTRING(c3,1,2)="on"');
            $query->andWhere('SUBSTRING(c4,1,2)="on"');
        } elseif ($tt == 'overdue') {
            $query->andWhere('SUBSTRING(c3,1,2)!="on"');
            $query->andWhere('SUBSTRING(c4,1,2)!="on"');
            $query->andWhere('due!=0');
            $query->andWhere('due<=:due', ['due'=>date('Y-m-d')]);
        }

        // Search for tour with code
        $theTour = false;
        $theTours = [];
        $tourIdList = [];
        if (strlen($tour) > 2) {
            // yyyy-mm Thang khoi hanh tour
            if (preg_match("/(\d{4})-(\d{2})/", $tour) || preg_match("/(\d{4})-(\d{2})-(\d{2})/", $tour)) {
                $theTours = Tour::findBySql('SELECT t.id, day_from FROM at_tours t, at_ct p WHERE p.id=t.ct_id AND SUBSTRING(day_from,1,'.strlen($tour).')=:ym', [':ym'=>$tour])
                    ->indexBy('id')
                    ->asArray()
                    ->all();
            } else {
                $theTours = Tour::find()
                    ->select(['id'])
                    ->where(['or', ['like', 'code', $tour], ['id'=>$tour]])
                    ->indexBy('id')
                    ->asArray()
                    ->all();
            }
            if (!empty($theTours)) {
                $tourIdList = array_keys($theTours);
                $query->andWhere(['tour_id'=>$tourIdList]);
                if (count($theTours) == 1) {
                    $theTour = Tour::find()
                        ->where(['id'=>key($theTours)])
                        ->with([
                            'product',
                            'product.days',
                            'product.bookings',
                        ])
                        ->asArray()
                        ->one();
                }
            }
        }

        if (preg_match("/(\d{4})-(\d{2})/", $dvtour) || preg_match("/(\d{4})-(\d{2})-(\d{2})/", $dvtour)) {
            $query->andWhere('SUBSTRING(dvtour_day,1,'.strlen($dvtour).')=:ym', [':ym'=>$dvtour]);
        }

        if (strlen($search) > 2) {
            $supplierOnly = false;
            if (substr($search, 0, 1) == '@') {
                $search = substr($search, 1);
                $supplierOnly = true;
            }
            // Tim venue
            $theVenues = Venue::find()->select(['id'])->where(['like', 'name', $search])->indexBy('id')->asArray()->all();
            $venueIdList = null;
            if (!empty($theVenues)) {
                $venueIdList = array_keys($theVenues);
            }
            $theCompanies = Company::find()->select(['id'])->where(['like', 'name', $search])->indexBy('id')->asArray()->all();
            $companyIdList = null;
            if (!empty($theCompanies)) {
                $companyIdList = array_keys($theCompanies);
            }
            if ($supplierOnly) {
                $query->andFilterWhere(['or', ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
            } else {
                $query->andFilterWhere(['or', ['like', 'dvtour_name', $search], ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
            }
        }

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym FROM cpt GROUP BY ym ORDER BY ym DESC')
            ->queryAll();

        if (in_array($currency, ['eur', 'usd', 'vnd', 'lak', 'khr'])) {
            $query->andWhere(['unitc'=>strtoupper($currency)]);
        }
        if (in_array($sign, ['plus', 'minus'])) {
            $query->andWhere(['plusminus'=>$sign]);
        }
        if ($payer != '' && $payer != 'miennam' && !$theTour) {
            $query->andWhere(['payer'=>$payer]);
        }
        if ($payer == 'miennam' && !$theTour) {
            $query->andWhere(['payer'=>['Amica Saigon', 'Hướng dẫn MN 1', 'Hướng dẫn MN 2', 'Hướng dẫn MN 3']]);
        }
        if ($vat == 'ok') {
            $query->andWhere(['vat_ok'=>'ok']);
        } elseif ($vat == 'nok') {
            $query->andWhere(['vat_ok'=>'']);
        }

        $payerList = Yii::$app->db
            ->createCommand('SELECT payer FROM cpt GROUP BY payer ORDER BY payer')
            ->queryAll();

        // Thay đổi điều kiện tìm kiếm nếu chỉ có 1 tour
        $orderBy = $orderby == 'updated_at' ? 'updated_at DESC' : 'dvtour_day DESC';
        if ($orderby == 'suplier') {
            $query->andWhere('dvtour_day>NOW()');
            $orderby == 'tour_id, venue_id';
        }
        if ($theTour) {
            $limit = 1000;
            $orderBy = 'dvtour_day';
        }

        $countQuery = clone $query;

        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>$limit,
        ]);

        $theCptx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                },
                // 'cp'=>function($query) {
                //     return $query->select(['id', 'name', 'venue_id', 'unit'])
                //         ->with(['venue'=>function($query){
                //             return $query->select(['id', 'name']);
                //             }
                //         ]);
                // },
                'tour'=>function($query) {
                    return $query->select(['id', 'code']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'viaCompany'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'comments'=>function($q){
                    return $q->where(['!=', 'status', 'deleted']);
                },
                'comments.updatedBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                },
                'mtt'=>function($q) {
                    return $q->orderBy('updated_dt');
                },
            ])
            ->orderBy($orderBy)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $sql = $query->createCommand()->getRawSql();

        // Aprroved by
        $approvedByIdList = [];
        foreach ($theCptx as $cpt) {
            if ($cpt['approved_by'] != '') {
                $cpt['approved_by'] = trim($cpt['approved_by'], '[');
                $cpt['approved_by'] = trim($cpt['approved_by'], ']');

                $ids = explode(':][', $cpt['approved_by']);
                foreach ($ids as $id2) {
                    $approvedByIdList[] = (int)$id2;
                }
            }
        }
        $approvedBy = User::find()->select(['id', 'name'])->where(['id'=>$approvedByIdList])->asArray()->all();

        return $this->render('cpt_index', [
            'pagination'=>$pagination,
            'theCptx'=>$theCptx,
            'filter'=>$filter,
            'tour'=>$tour,
            'dvtour'=>$dvtour,
            'search'=>$search,
            'tt'=>$tt,
            'currency'=>$currency,
            'sign'=>$sign,
            'payer'=>$payer,
            'vat'=>$vat,
            'orderby'=>$orderby,
            'limit'=>$limit,
            'payerList'=>$payerList,
            'theTour'=>$theTour,
            'theTours'=>$theTours,
            'sql'=>$sql,
            'approvedBy'=>$approvedBy,
        ]);
    }
}