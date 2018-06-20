<?
namespace app\controllers;

use common\models\Cpt;
use common\models\Company;
use common\models\Ltt;
use common\models\Mtt;
use common\models\Tour;
use common\models\User;
use common\models\Venue;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class LttController extends MyController
{
    public function actionIndex()
    {
        $query = Ltt::find();
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $theLttx = $query
            ->orderBy('payment_dt DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return $this->render('ltt', [
            'pagination'=>$pagination,
            'theLttx'=>$theLttx,
        ]);
    }

    public function actionC()
    {
        $theLtt = new Ltt;
        $theMttx = Mtt::find()
            ->where(['status'=>'draft', 'created_by'=>USER_ID])
            ->with([
                'cpt',
                'cpt.tour'=>function($q) {
                    return $q->select(['id', 'code']);
                },
                'cpt.venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.viaCompany'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->orderBy('created_dt')
            ->asArray()
            ->all();
        if (empty($theMttx)) {
            // return $this->redirect('/cpt');
        }
        $theLtt->payment_dt = date('Y-m-d');
        if ($theLtt->load(Yii::$app->request->post()) && $theLtt->validate()) {

            $theLtt->created_dt = NOW;
            $theLtt->created_by = USER_ID;
            $theLtt->updated_dt = NOW;
            $theLtt->updated_by = USER_ID;
            $theLtt->status = 'draft';
            $theLtt->save(false);

/*            foreach ($theMttx as $mtt) {
                if ($mtt['payment_dt'] == '0000-00-00 00:00:00') {
                    $mtt['payment_dt'] = $theLtt['payment_dt'];
                }
                if ($mtt['tkgn'] == '') {
                    $mtt['tkgn'] = $theLtt['tkgn'];
                }
                if ($mtt['mp'] == '') {
                    $mtt['mp'] = $theLtt['mp'];
                }
                if ($mtt['currency'] == '') {
                    $mtt['currency'] = $theLtt['currency'];
                }
                if ($mtt['xrate'] == 0) {
                    $mtt['xrate'] = $theLtt['xrate'];
                }
                $sql = 'UPDATE at_mtt SET status="on", payment_dt=:dt, currency=:currency, xrate=:xrate, tkgn=:tk, mp=:mp, paid_in_full="yes" WHERE status="draft" AND created_by=:me AND id=:id';
                Yii::$app->db->createCommand($sql, [
                    ':id'=>$mtt['id'],
                    ':tk'=>$mtt['tkgn'],
                    ':mp'=>$mtt['mp'],
                    ':dt'=>$mtt['payment_dt'],
                    ':currency'=>$mtt['currency'],
                    ':xrate'=>$mtt['xrate'],
                    ':me'=>USER_ID,
                    ])->execute();
                if ($mtt['paid_in_full'] == 'yes') {
                    $sql = 'UPDATE cpt SET c3=:c3 WHERE dvtour_id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':c3'=>'on,'.USER_ID.','.NOW,
                        ':id'=>$mtt['cpt_id'],
                    ])->execute();
                } else {
                    $sql = 'UPDATE cpt SET c1=:c1 WHERE dvtour_id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':c1'=>'on,'.USER_ID.','.NOW,
                        ':id'=>$mtt['cpt_id'],
                    ])->execute();
                }
            }
*/
            return $this->redirect('@web/ltt');
        }
        return $this->render('ltt_c', [
            'theLtt'=>$theLtt,
            'theMttx'=>$theMttx,
        ]);
    }


    public function actionR($id = 0, $search = '', $tour = '', $currency = '', $day = '', $limit = 25)
    {
        $theLtt = Ltt::find()
            ->where(['id'=>$id])
            ->with([
                'mtt',
                'mtt.cpt',
                'mtt.cpt.updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'mtt.cpt.tour'=>function($query) {
                    return $query->select(['id', 'code']);
                },
                'mtt.cpt.venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'mtt.cpt.company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'mtt.cpt.mm',
                'mtt.cpt.mm.updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->asArray()
            ->one();
        if (!$theLtt) {
            throw new HttpException(404);
        }

        // Status detail
        $maxStatus = 0;
        $statusDetail = [1=>false,2=>false,3=>false,4=>false,5=>false,];
        $statusDetailArr = explode('|', $theLtt['status_detail']);
        foreach ($statusDetailArr as $item) {
            $statusCode = (int)substr($item, 0, 1);
            if ($maxStatus < $statusCode) {
                $maxStatus = $statusCode;
            }
            if (array_key_exists($statusCode, $statusDetail)) {
                $statusDetail[$statusCode] = true;
            }
        }

        // User access
        $allowUsers = [
            1=>[1, 28431, 11, 17],
            2=>[1, 28431],
            3=>[1, 2, 28431],
            4=>[1, 28431, 11, 17, 16],
            5=>[28431],
        ];

        // Ke toan status
        if (Yii::$app->request->get('action') == 'status') {
            $status = Yii::$app->request->get('status');
            if (array_key_exists($status, $statusDetail) && $statusDetail[$status] === false && $maxStatus < $status) {
                if (!in_array(USER_ID, $allowUsers[$status])) {
                    throw new HttpException(403, 'Access denied.');                 
                }

                $newStatus = $status.'|'.USER_ID.'|'.NOW;
                $sql = 'UPDATE at_ltt SET status=:status, status_detail=:detail WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [
                    ':status'=>$status,
                    ':detail'=>$newStatus.';'.$theLtt['status_detail'],
                    ':id'=>$theLtt['id']
                    ])->execute();
                return $this->redirect(DIR.URI);
            }
        }

        if (!in_array($limit, [25, 50, 100, 500])) {
            $limit = 25;
        }

        return $this->render('ltt_r', [
            'theLtt'=>$theLtt,
        ]);
    }

    public function actionU($id = 0)
    {
        $theLtt = Ltt::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theLtt) {
            throw new HttpException(404);
        }

        $theMttx = Mtt::find()
            ->where(['ltt_id'=>$theLtt['id']])
            ->with([
                'cpt',
                'cpt.tour'=>function($q) {
                    return $q->select(['id', 'code']);
                },
                'cpt.venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.viaCompany'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->orderBy('created_dt')
            ->asArray()
            ->all();


        if ($theLtt->load(Yii::$app->request->post()) && $theLtt->validate()) {
            $theLtt->updated_dt = NOW;
            $theLtt->updated_by = USER_ID;
            $theLtt->save(false);
            return $this->redirect('@web/ketoan/ltt');
        }
        return $this->render('ltt_c', [
            'theLtt'=>$theLtt,
            'theMttx'=>$theMttx,
        ]);
    }

    // Ajax call
    public function actionAjax()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(403);
        }

        $action = Yii::$app->request->post('action');
        if ($action == 'add-cpt-to-ltt') {
            $cpt_id = Yii::$app->request->post('cpt_id', 0);
            $ltt_id = Yii::$app->request->post('ltt_id', 0);
            if ($cpt_id != 0 && $ltt_id != 0) {
                $theCpt = Cpt::find()
                    ->where(['dvtour_id'=>$cpt_id])
                    ->andWhere('SUBSTRING(c8,1,2)!="on"')
                    ->asArray()
                    ->one();
                if (!$theCpt) {
                    throw new HttpException(404);
                }

                $theLtt = Ltt::find(['id'=>$ltt_id])->one();
                if (!$theLtt || $theLtt->status != 0) {
                    //throw new HttpException(403);
                }

                $theMtt = new Mtt;
                $theMtt->created_dt = NOW;
                $theMtt->created_by = USER_ID;
                $theMtt->updated_dt = NOW;
                $theMtt->updated_by = USER_ID;
                $theMtt->ltt_id = $ltt_id;
                $theMtt->cpt_id = $cpt_id;
                $theMtt->amount = $theCpt['qty'] * $theCpt['price'];
                $theMtt->save(false);

                $sql = 'SELECT SUM(amount) FROM at_mtt WHERE ltt_id=:id';
                $amount = Yii::$app->db->createCommand($sql, [':id'=>$ltt_id])->queryScalar();
                $theLtt->amount = $amount;
                $theLtt->save(false);

                Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                return [
                    'mtt_id'=>$theMtt->id,
                    'ltt_amount'=>number_format($theLtt->amount, 2),
                ];
                exit;
            }
        } elseif ($action == 'remove-cpt-from-ltt') {
            $cpt_id = Yii::$app->request->post('cpt_id', 0);
            $ltt_id = Yii::$app->request->post('ltt_id', 0);
            if ($cpt_id != 0 && $ltt_id != 0) {
                $theCpt = Cpt::find()
                    ->where(['dvtour_id'=>$cpt_id])
                    ->asArray()
                    ->one();
                if (!$theCpt) {
                    throw new HttpException(404);
                }

                $theLtt = Ltt::find(['id'=>$ltt_id])->one();
                if (!$theLtt || $theLtt->status != '') {
                    throw new HttpException(403);
                }

                Yii::$app->db->createCommand()->delete('at_mtt', [
                    'ltt_id'=>$ltt_id,
                    'cpt_id'=>$cpt_id,
                    ])->execute();

                $sql = 'SELECT SUM(amount) FROM at_mtt WHERE ltt_id=:id';
                $amount = Yii::$app->db->createCommand($sql, [':id'=>$ltt_id])->queryScalar();
                $theLtt->amount = $amount;
                $theLtt->save(false);

                Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                return [
                    'ltt_amount'=>number_format($theLtt->amount, 2),
                ];
                exit;
            }
        } elseif ($action == 'search-cpt') {
            $tour = Yii::$app->request->post('tour', '');
            $search = Yii::$app->request->post('search', '');
            return $this->searchCpt($tour, $search);
            exit;
        }
        
        throw new HttpException(403);
    }

    private function searchCpt($tour = '', $search = '')
    {
        $query = Cpt::find();

        if (strlen($search) > 2) {
            // Tim venue
            $theVenues = Venue::find()
                ->select(['id'])
                ->where(['like', 'name', $search])
                ->indexBy('id')
                ->asArray()
                ->all();
            $venueIdList = null;
            if (!empty($theVenues)) {
                $venueIdList = array_keys($theVenues);
            }
            $theCompanies = Company::find()
                ->select(['id'])
                ->where(['like', 'name', $search])
                ->indexBy('id')
                ->asArray()
                ->all();
            $companyIdList = null;
            if (!empty($theCompanies)) {
                $companyIdList = array_keys($theCompanies);
            }
            $query->filterWhere(['or', ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
        }

        $theTours = [];
        $tourIdList = [];
        if (strlen($tour) > 2) {
            if (preg_match("/(\d{4})-(\d{2})/", $tour)) {
                $theTours = Tour::findBySql('SELECT t.id, day_from FROM at_tours t, at_ct p WHERE p.id=t.ct_id AND SUBSTRING(day_from,1,7)=:ym', [':ym'=>$tour])
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
            }
        }

        $theCptx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'tour'=>function($query) {
                    return $query->select(['id', 'code']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'mm',
                'mm.updatedBy',
            ])
            ->orderBy('dvtour_day DESC')
            ->limit(100)
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
        $approvedBy = User::find()
            ->select(['id', 'name'])
            ->where(['id'=>$approvedByIdList])
            ->asArray()
            ->all();
        return $this->renderPartial('_search_cpt', [
            'theCptx'=>$theCptx,
            'approvedBy'=>$approvedBy,
            'sql'=>$sql,
        ]);
    }
}
