<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;
use common\models\Dv;
use common\models\Cpt;
use common\models\Cp;
use common\models\Cpg;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;

class CpController extends MyController
{

    public function actionIndex()
    {
        return $this->render('cp_index');
    }

    public function actionDoc($page = '00')
    {
        return $this->render('cp_doc', [
            'page'=>$page,
        ]);
    }

    public function _actionIndex($pp = 50, $via = '',$type = 0, $name = '', $venue = '', $tk = '', $status = '', $date = '') {
        $query = Cp::find();
        if ($status != '') {
            $query->andWhere(['status'=>$status]);
        }
        if ($type != 0) {
            $query->andWhere(['stype'=>$type]);
        }
        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }
        if ($tk != '') {
            $query->andWhere(['like', 'tk', $tk]);
        }
        if ($venue != '') {
            // TODO: max 500, increase?
            $venueIdList = Venue::find()
                ->select(['id'])
                ->where(['like', 'name', $venue])
                ->asArray()
                ->limit(500)
                ->column();
            $companyIdList = Company::find()
                ->select(['id'])
                ->where(['like', 'name', $venue])
                ->asArray()
                ->limit(500)
                ->column();
            $query->andWhere(['or', ['venue_id'=>$venueIdList], ['by_company_id'=>$companyIdList]]);
        }
        $viaIdList = [];
        if ($via != '') {
            // Nha phan phoi
            $viaIdList = Company::find()
                ->select(['id'])
                ->where(['like', 'name', $via])
                ->asArray()
                ->limit(500)
                ->column();
        }

        if (strlen($date) != 10) {
            $date = date('Y-m-d');
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>$pp,
        ]);

        $theCpx = $query
            ->with([
                'venue'=>function($q) {
                    return $q->select(['id', 'name', 'abbr']);
                },
                'byCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'cpg'=>function($q) use ($date) {
                    return $q->andWhere('from_dt<=:date AND (until_dt="0000-00-00" OR until_dt>=:date)', [':date'=>$date]);
                },
                'cpg.viaCompany'=>function($q) use ($viaIdList) {
                    if (!empty($viaIdList)) {
                        return $q->where(['id'=>$viaIdList]);
                    }
                },
                ])
            ->orderBy('venue_id, by_company_id, name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        if (Yii::$app->request->isPost && in_array(USER_ID, [1, 34718])) {
            if (isset($_POST['cpid']) && is_array($_POST['cpid']) && !empty($_POST['cpid'])) {
                $params = [':now'=>NOW, ':me'=>USER_ID];
                $set = '';
                if (isset($_POST['status']) && in_array($_POST['status'], ['on', 'off', 'draft', 'deleted'])) {
                    $set .= ', status=:status';
                    $params[':status'] = $_POST['status'];
                }
                if (in_array(USER_ID, [1]) && isset($_POST['grouping']) && $_POST['grouping'] != '') {
                    $set .= ', grouping=:grouping';
                    $params[':grouping'] = $_POST['grouping'];
                }
                if (isset($_POST['unit']) && $_POST['unit'] != '') {
                    $set .= ', unit=:unit';
                    $params[':unit'] = $_POST['unit'];
                }
                if (isset($_POST['stype']) && $_POST['stype'] != '') {
                    $set .= ', stype=:stype';
                    $params[':stype'] = $_POST['stype'];
                }
                if (isset($_POST['tk']) && $_POST['tk'] != '') {
                    $set .= ', tk=:tk';
                    $params[':tk'] = $_POST['tk'];
                }
                if ($set != '') {
                    $sql = 'UPDATE cp SET updated_dt=:now, updated_by=:me'.$set.' WHERE id IN ('.implode(', ', $_POST['cpid']).')';
                // \fCore::expose($_POST, $params);
                // exit;
                    Yii::$app->db->createCommand($sql, $params)->execute();
                    //return $this->render();
                }

            }
        }

        return $this->render('cp_index', [
            'pagination'=>$pagination,
            'theCpx'=>$theCpx,
            'type'=>$type,
            'name'=>$name,
            'venue'=>$venue,
            'status'=>$status,
            'tk'=>$tk,
            'pp'=>$pp,
            'date'=>$date,
        ]);
    }

    // Nhap gia kieu moi
    public function actionC($venue_id = 0, $dvc_id = 0, $dv_id = 0)
    {
        // Nhap gia cho dv, khong co contract
        if ($dv_id != 0) {
            $theDv = Dv::find()
                ->where(['id'=>$dv_id])
                ->asArray()
                ->one();
            if (!$theDv) {
                throw new HttpException(404, 'DV not found');
            }

            $theCp = new Cp;
            $theCp->scenario = 'cp/c';
            if ($theCp->load(Yii::$app->request->post()) && $theCp->validate()) {
                $theCp->account_id = 1;
                $theCp->created_dt = NOW;
                $theCp->created_by = USER_ID;
                $theCp->updated_dt = NOW;
                $theCp->updated_by = USER_ID;
                $theCp->status = 'on';
                $theCp->dv_id = $theDv['id'];
                $theCp->save(false);
                return $this->redirect('/dv/r/'.$theDv['id']);
            }

            return $this->render('cp_u', [
                'dv_id'=>$dv_id,
                'dvc_id'=>$dvc_id,
                'theCp'=>$theCp,
                'theDv'=>$theDv,
                ]);
        }

        $theVenue = Venue::find()
            ->where(['id'=>$venue_id])
            ->with([
                'dvc',
                'dv'=>function($q) {
                    return $q
                        ->select(['id', 'venue_id', 'grouping', 'name'=>new \yii\db\Expression('CONCAT(id, " ", name)')])
                        ->where('status!="deleted"')
                        ->orderBy('grouping, sorder, name');
                }
                ])
            ->asArray()
            ->one();
        if (!$theVenue) {
            throw new HttpException(404);
        }

        if (Yii::$app->request->isPost
            && Yii::$app->request->post('action') == 'add-contract'
            && Yii::$app->request->post('contract_name') != ''
            ) {
            $sql = 'INSERT INTO dvc (name, description) VALUES (:n, :d)';
            Yii::$app->db->createCommand($sql, [
                ':n'=>trim(Yii::$app->request->post('contract_name')),
                ':d'=>Yii::$app->request->post('contract_description'),
                ])->execute();
            return $this->redirect('/dv/gia?venue_id='.$venue_id);
        }

        if (Yii::$app->request->isPost
            && Yii::$app->request->post('action') == 'add-prices'
            && Yii::$app->request->post('contract') > 0
            && is_array($_POST['dv'])
            ) {
            // \fCore::expose($_POST);
            // exit;
            foreach ($_POST['dv'] as $i=>$dv) {
                $dv_id = 0;
                foreach ($theVenue['dv'] as $dv) {
                    if ($_POST['dv'][$i] == $dv['name']) {
                        $dv_id = $dv['id'];
                    }
                }
                $sql = 'INSERT INTO cp (account_id, created_dt, created_by, updated_dt, updated_by, dvc_id, dv_id, period, conds, search, price, currency) VALUES (1, :created_dt, :created_by, :updated_dt, :updated_by, :dvc_id, :dv_id, :period, :conds, :search, :price, :currency)';
                Yii::$app->db->createCommand($sql, [
                    ':created_dt'=>NOW,
                    ':created_by'=>USER_ID,
                    ':updated_dt'=>NOW,
                    ':updated_by'=>USER_ID,
                    ':dvc_id'=>$_POST['contract'],
                    ':dv_id'=>$dv_id,
                    ':period'=>$_POST['validity'][$i],
                    ':conds'=>$_POST['conds'][$i],
                    ':search'=>$_POST['price'][$i],
                    ':price'=>$_POST['price'][$i],
                    ':currency'=>$_POST['currency'][$i],
                    ])->execute();
            }

            return $this->redirect('/dvc/r/'.$dvc_id);
        }

        return $this->render('cp_c', [
            'theVenue'=>$theVenue,
            'dvc_id'=>$dvc_id,
        ]);
    }

    public function actionR($id = 0)
    {
        $theCp = Cp::find()
            ->where(['id'=>$id])
            ->with([
                'venue',
                'byCompany',
                'cpg',
                'cpg.viaCompany',
                ])
            ->asArray()
            ->one();

        if (!$theCp) {
            throw new HttpException(404, 'Cp not found');           
        }

        $theVenue = false;
        $theCompany = false;

        if ($theCp['venue_id'] != 0) {
            $theVenue = Venue::findOne($theCp['venue_id']);
        }

        if ($theCp['by_company_id'] != 0) {
            $theCompany = Company::findOne($theCp['by_company_id']);
        }

        $q = Cp::find();

        if ($theCompany) {
            $q->andWhere(['by_company_id'=>$theCompany['id']]);
        } else {
            $q->andWhere(['by_company_id'=>0]);
        }

        if ($theVenue) {
            $q->andWhere(['venue_id'=>$theVenue['id']]);
        } else {
            $q->andWhere(['venue_id'=>0]);
        }

        $relatedCpx = $q
            ->orderBy('stype, name')
            ->asArray()
            ->all();

        return $this->render('cp_r', [
            'theCp'=>$theCp,
            'relatedCpx'=>$relatedCpx,
            'theVenue'=>$theVenue,
            'theCompany'=>$theCompany,
        ]);
    }

    public function actionU($id = 0)
    {
        $theCp = Cp::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theCp) {
            throw new HttpException(404, 'CP not found.');
        }

        $theCp->scenario = 'cp/u';

        $theDv = Dv::find()
            ->where(['id'=>$theCp['dv_id']])
            ->asArray()
            ->one();
        if (!$theDv) {
            throw new HttpException(404, 'DV not found.');
        }

        if ($theCp->load(Yii::$app->request->post()) && $theCp->validate()) {
            if (!in_array(USER_ID, [1, 8, 9198])) {
                throw new HttpException(403, 'Access denied.');
            }

            $theCp->updated_dt = NOW;
            $theCp->updated_by = USER_ID;
            $theCp->save(false);
            return $this->redirect('/dvc/r/'.$theCp['dvc_id']);
        }

        return $this->render('cp_u', [
            'theCp'=>$theCp,
            'theDv'=>$theDv,
            'dv_id'=>0,
        ]);
    }

    public function actionUx($id = 0)
    {
        if ($myId > 4 && !in_array(USER_ID, [1])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theCp = Cp::findOne($id);
        if (!$theCp) {
            throw new HttpException(404, 'Cp not found.');
        }

        $theVenue = null;
        $theCompany = null;

        if ($theCp['venue_id'] != 0) {
            $theVenue = Venue::findOne($theCp['venue_id']);
        }

        if ($theCp['company_id'] != 0) {
            $theCompany = Company::findOne($theCp['company_id']);
        }

        $theCp->scenario = 'cp_u';

        $q = Cp::find()
            ->select(['id', 'stype', 'grouping', 'name', 'total', 'unit', 'info', 'abbr']);

        if ($theCompany) {
            $q->andWhere(['company_id'=>$theCompany['id']]);
        } else {
            $q->andWhere(['company_id'=>0]);
        }

        if ($theVenue) {
            $q->andWhere(['venue_id'=>$theVenue['id']]);
        } else {
            $q->andWhere(['venue_id'=>0]);
        }

        $relatedCpx = $q
            ->orderBy('stype, grouping, name')
            ->limit(100)
            ->all();

        if ($theCp->load(Yii::$app->request->post()) && $theCp->validate()) {
            $theCp->updated_at = NOW;
            $theCp->updated_by = Yii::$app->user->id;
            $theCp->save();
            return $this->redirect('@web/cp/r/'.$theCp->id);
        }

        return $this->render('cp_u', [
            'theCp'=>$theCp,
            'relatedCpx'=>$relatedCpx,
            'theVenue'=>$theVenue,
            'theCompany'=>$theCompany,
        ]);
    }

    public function actionD($id = 0)
    {
        $theCp = Cp::find()
            ->where(['id'=>$id])
            ->with(['venue', 'cpg', 'cpt'])
            ->one();

        if (!$theCp) {
            throw new HttpException(404, 'Cp not found');
        }

        if (!in_array(Yii::$app->user->id, [1, 8, 28722, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        if ($theCp['cpt']) {
            throw new HttpException(403, 'Related bookings found. You need to delete them first.');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
            // Delete related cpg
            Yii::$app->db->createCommand()
                ->delete('cpg', ['dv_id'=>$id])
                ->execute();
            // Delete cp
            $theCp->delete();
            if ($theCp['venue']) {
                return $this->redirect('@web/venues/r/'.$theCp['venue']['id']);
            } else {
                return $this->redirect('@web/cp');
            }
        }

        return $this->render('cp_d', [
            'theCp'=>$theCp
        ]);
    }
}
