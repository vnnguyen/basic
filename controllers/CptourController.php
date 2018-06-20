<?php

namespace app\controllers;

use Yii;
use app\models\CpTour;
use common\models\Venue;
use app\models\Dv;
use app\models\Cp;
use common\models\Product;
use app\models\Dvc;
use app\models\Dvd;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Tour;
use common\models\Ngaymau;
use app\models\Tag;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
class CptourController extends MyController
{
    public function actionCost($id = 0)
    {
        $theTour = Product::find()->where(['id' => $id])->with(['days'])->one();
        if (!$theTour) {
            throw new HttpException(404, "Product not found");
        }
        //get list days
        if ($theTour->day_ids) {
            $dayIdList = explode(',', $theTour->day_ids);
            $start_date = $theTour->day_from;
            $arr_day = [];
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $id) {
                foreach ($theTour->days as $day) {
                    if ($day['id'] == $id) {
                        $cnt ++;
                        $arr_day[date('Y-m-d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt;
                    }
                }
            }
        }
        //submit
        if (Yii::$app->request->isAjax) {
            $tour_id = $_POST['tour_id'];
            $venue_id = $_POST['venue_id'];
            foreach ($_POST['cpt_id'] as $k => $cpt_id) {
                if ($cpt_id == '') {
                    $model = new CpTour;
                    $model->tour_id = $tour_id;
                    $model->venue_id = $venue_id;
                    $model->dv_id = $_POST['dv_id'][$k];
                    $model->qty = $_POST['qty'][$k];
                    $model->plusminus = $_POST['plusminus'][$k];
                    $model->currency = $_POST['currency'][$k];
                    $model->use_day = $_POST['use_day'][$k];
                    $model->num_day = $_POST['num_day'][$k];
                    $model->price = str_replace(',', '', $_POST['price'][$k]);
                    if (!$model->save(false)) {
                        return json_encode(['err' => $model->errors]);
                    }
                } else {
                    $cpt_updated = CpTour::findOne($cpt_id);
                    if ($cpt_updated == null) {
                        return json_encode(['err' => 'cpt not found']);
                    }
                    $cpt_updated->venue_id = $venue_id;
                    $cpt_updated->dv_id = $_POST['dv_id'][$k];
                    $cpt_updated->qty = $_POST['qty'][$k];
                    $cpt_updated->plusminus = $_POST['plusminus'][$k];
                    $cpt_updated->currency = $_POST['currency'][$k];
                    $cpt_updated->use_day = $_POST['use_day'][$k];
                    $cpt_updated->num_day = $_POST['num_day'][$k];
                    $cpt_updated->price = str_replace(',', '', $_POST['price'][$k]);
                    // save and add or update options
                    if (!$cpt_updated->save(false)) {
                        return json_encode(['err' => $cpt_updated->errors]);
                    }
                }
            }
            //return all days and cpts
            $cpts = CpTour::find()
                ->with([
                    'venue',
                    'dv'
                ])
                ->where('tour_id = '.$theTour->id)->asArray()->all();

            $allDays = [];

            foreach ($cpts as $cpt) {
                $allDays[$cpt['use_day']] = '';
            }

            $cnt = 0;
            $dayIdList = explode(',', $theTour['day_ids']);
            foreach ($dayIdList as $di) {
                foreach ($theTour['days'] as $ng) {
                    if ($ng['id'] == $di) {
                        $cnt ++;
                        $ngay = date('Y-m-d', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
                        $allDays[$ngay] = $ng;
                    }
                }
            }
            ksort($allDays);
            return json_encode([
                'cpts' => $cpts,
                'days' => ArrayHelper::toArray($allDays),
                ]);
        }
        $query1 = null;
        $cpts = CpTour::find()
            ->with([
                'venue',
                'dv'
            ])
            ->where('tour_id = '.$theTour->id);
        $cpts_group = clone $cpts;
        $cpts_group = $cpts_group->andWhere('parent_id = 0')->all();
        // var_dump($arr_day);die;
        return $this->render('cp_tour', [
            'theTour' => $theTour,
            'cpts' => $cpts->all(),
            'cpts_group' => $cpts_group,
            'days' => (isset($arr_day))?$arr_day: null,
        ]);
    }
    public function actionRemove_cpt($cpt_id)
    {
        $cpt = CpTour::findOne($cpt_id);
        if ($cpt != null) {
            $arr_removed = [];
            if ($cpt->parent_id == 0) {
                $cpt_ops = CpTour::find()->where('parent_id = '.$cpt->id)->all();
                if ($cpt_ops != null) {
                    foreach ($cpt_ops as $cpt_op) {
                        $cpt_op->delete();
                        $arr_removed[] = $cpt_op->id;
                    }
                }
            }
            if ($cpt->delete()) {
                $arr_removed[] = $cpt->id;
                return json_encode(['success' => $arr_removed]);
            }
        }
        return json_encode(['err' => 'delete fail!']);
    }
    public function actionSearch_ncc($q,$page){
        if (Yii::$app->request->isAjax) {
            $data_ncc = $query = Venue::find()->andWhere(['LIKE', 'name', $q]);
            $resultCount = 200;
            $offset = ($page - 1) * $resultCount;
            $data_ncc = $data_ncc->offset($offset)->limit($resultCount)->asArray()->all();
            $count = count($query->all());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;
            $results = [
              "items" => $data_ncc,
              'total_count' => $count
            ];
            echo json_encode($results);
        }
    }

    public function actionList_dv($id_ncc){
        $query = Dv::find()
            ->with([
                'cp',
            ])
            ->where(['venue_id' => $id_ncc])->andWhere('status != "deleted"');
        $options = clone $query;
        $dv = $query->andWhere(['is_dependent' => 'no'])->asArray()->all();
        $options = $options->andWhere(['is_dependent' => 'yes'])->asArray()->all();
        return json_encode(['dv' => $dv, 'options' => $options]);
    }

    public function actionGet_cpt($cpt_id)
    {
        $cpt = CpTour::find()
            ->with(['venue'])
            ->where(['id' => $cpt_id])
            ->one();
        $venue = $cpt['venue'];
        if ($cpt == null) {
            return json_encode(['err' => 'cpt null']);
        }
        $cpts = CpTour::find()
            ->with([
                'dv',
            ])
            ->where(['venue_id' => $venue['id']])
            ->orderBy('use_day')
            ->all();
        $query_dv = Dv::find()
            ->with([
                'cp',
            ])
            ->where(['venue_id' => $venue['id']])->andWhere('status != "deleted"');
        $dv = $query_dv->andWhere(['is_dependent' => 'no'])->asArray()->all();
        return json_encode([
            'cpts' => ArrayHelper::toArray($cpts),
            'venue' => ArrayHelper::toArray($venue),
            'dvs' => $dv
        ]);
    }

    public function actionList_cp($dv_id = 0, $date_selected = '')
    {
        if (Yii::$app->request->isAjax) {
            $sl = 1;
            $price = 0;
            $data_dv = Dv::find()->where(['id' => $dv_id])->asArray()->one();

            if ($data_dv == null) {
                return json_encode(['err' => 'Ncc does not exist']);
            }
            // if ($data_dv['maxpax'] != '') {
            //     $sl = ceil(intval($arr_info['num_mem']) / intval($data_dv['maxpax']));
            // }
            if ($date_selected == '') {
                return json_encode(['err' => 'date null']);
            }
            $select_dt_arr = explode('/', $date_selected);
            $date_selected = $select_dt_arr[2].'/'.$select_dt_arr[1].'/'.$select_dt_arr[0];
            $dvc = Dvc::find()
            ->where(['venue_id'=>$data_dv['venue_id']])
            ->with([
                'dvd',
                'venue',
                'venue.dv'=>function($q){
                    return $q->where('status!="deleted"')->orderBy('grouping, sorder, name');
                },
                'venue.dv.cp',
                ])
            ->andWhere('DATE(valid_from_dt) <= "'.date('Y/m/d', strtotime($date_selected)).'" AND DATE(valid_until_dt) >= "'.date('Y/m/d', strtotime($date_selected)).'"')

            ->asArray()
            ->one();
            $currency = '';
            $id_ncc = 0;
            if ($dvc != null) {
                $conditions_change = [];
                foreach ($dvc['dvd'] as $dvd) {
                    if ($dvd['stype'] != 'date') { continue;}
                    $arr_dvds = explode(';', $dvd['def']);
                    foreach ($arr_dvds as $dvd_part) {
                        $arr_parts = explode('-', $dvd_part);
                        if (count($arr_parts) != 2) {continue;}
                        $first_arr = explode('/', $arr_parts[0]);
                        $second_arr = explode('/', $arr_parts[1]);
                        if (count($first_arr) != 3 || count($second_arr) != 3) {continue;}
                        $first_arr = $first_arr[2].'/'.$first_arr[1].'/'.$first_arr[0];
                        $second_arr = $second_arr[2].'/'.$second_arr[1].'/'.$second_arr[0];
                        $date_compair = date('Y/m/d', strtotime($date_selected));
                        if ($date_compair >= date('Y/m/d', strtotime($first_arr))
                            && $date_compair <= date('Y/m/d', strtotime($second_arr))) {
                            $dvc['dvd'] = $dvd;
                            foreach ($dvc['venue']['dv'] as $k_dv => $dv) {
                                $valid_cps = [];
                                if ($dv['id'] == $data_dv['id']) {
                                    $id_ncc = $dvc['venue']['id'];
                                    $dvc['venue']['dv'][$k_dv]['name'] = str_replace(
                                        [
                                            '[', ']', '{', '}', '|',
                                        ], [
                                            '', '', '(<span class="text-light text-pink">', '</span>)', '/',
                                            ], $dv['name']);
                                    foreach ($dv['cp'] as $k_cp => $cp) {
                                        if ($cp['period'] == $dvd['code'] && $dvc['id'] == $cp['dvc_id']) {
                                            $valid_cps[] = $cp;
                                        } else {
                                            if (count($dv['cp']) == 1 && $cp['period'] == '') {
                                                $dvc['venue']['dv'][$k_dv]['cp'][$k_cp] = $cp;
                                            }
                                        }
                                    }
                                    if (count($valid_cps) > 0) {
                                        $dvc['venue']['dv'][$k_dv]['cp'] = $valid_cps;
                                    }
                                }
                            }
                        }
                    }
                }

                echo json_encode([
                    'dvc' => $dvc
                ]);
            } else {
                return json_encode(['err' => 'dvc is null']);
            }
        }
    }
}
