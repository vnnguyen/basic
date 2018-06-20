<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;
use common\models\Cpt;
use common\models\Dvo;
use common\models\Cpo;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;

class DvoController extends MyController
{
    public function actionDoc($page = '00')
    {
        return $this->render('dvo_doc', [
            'page'=>$page,
        ]);
    }

    public function actionIndex($pp = 50, $via = '',$type = 0, $name = '', $venue = '', $tk = '', $status = '', $date = '') {
        $query = Dvo::find();
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

        $theDvox = $query
            ->with([
                'venue'=>function($q) {
                    return $q->select(['id', 'name', 'abbr']);
                },
                'byCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'cpo'=>function($q) use ($date) {
                    return $q->andWhere('from_dt<=:date AND (until_dt="0000-00-00" OR until_dt>=:date)', [':date'=>$date]);
                },
                'cpo.viaCompany'=>function($q) use ($viaIdList) {
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

        return $this->render('dvo_index', [
            'pagination'=>$pagination,
            'theDvox'=>$theDvox,
            'type'=>$type,
            'name'=>$name,
            'venue'=>$venue,
            'status'=>$status,
            'tk'=>$tk,
            'pp'=>$pp,
            'date'=>$date,
        ]);
    }

    public function actionC($venue = 0, $company = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theVenue = null;
        $theCompany = null;

        if ($venue != 0) {
            $theVenue = Venue::findOne($venue);
        }

        if ($company != 0) {
            $theCompany = Company::findOne($company);
        }

        $theDvo = new Dvo;
        $theDvo->scenario = 'cp/c';

        if ($theDvo->load(Yii::$app->request->post()) && $theDvo->validate()) {
            $theDvo->created_dt = NOW;
            $theDvo->created_by = USER_ID;
            $theDvo->updated_dt = NOW;
            $theDvo->updated_by = USER_ID;
            $theDvo->by_company_id = $company;
            $theDvo->venue_id = $venue;
            $theDvo->save(false);
            return $this->redirect('/dvo/r/'.$theDvo->id);
        }

        $q = Dvo::find();

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

        return $this->render('dvo_u', [
            'theDvo'=>$theDvo,
            'relatedCpx'=>$relatedCpx,
            'theVenue'=>$theVenue,
            'theCompany'=>$theCompany,
        ]);
    }

    public function actionR($id = 0)
    {
        $theDvo = Dvo::find()
            ->where(['id'=>$id])
            ->with([
                'venue',
                'byCompany',
                'cpo',
                'cpo.viaCompany',
                ])
            ->asArray()
            ->one();

        if (!$theDvo) {
            throw new HttpException(404, 'Cp not found');           
        }

        $theVenue = false;
        $theCompany = false;

        if ($theDvo['venue_id'] != 0) {
            $theVenue = Venue::findOne($theDvo['venue_id']);
        }

        if ($theDvo['by_company_id'] != 0) {
            $theCompany = Company::findOne($theDvo['by_company_id']);
        }

        $q = Dvo::find();

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

        return $this->render('dvo_r', [
            'theDvo'=>$theDvo,
            'relatedCpx'=>$relatedCpx,
            'theVenue'=>$theVenue,
            'theCompany'=>$theCompany,
        ]);
    }

    public function actionU($id = 0)
    {
        $theDvo = Dvo::findOne($id);
        if (!$theDvo) {
            throw new HttpException(404, 'CP not found.');
        }

        $theDvo->scenario = 'cp/u';

        if ($theDvo->load(Yii::$app->request->post()) && $theDvo->validate()) {
            if (!in_array(USER_ID, [1, 8, 9198])) {
                throw new HttpException(403, 'Access denied.');
            }

            $theDvo->updated_dt = NOW;
            $theDvo->updated_by = USER_ID;
            $theDvo->save(false);
            return $this->redirect('/cp');
        }


        $theVenue = false;
        $theCompany = false;

        if ($theDvo['venue_id'] != 0) {
            $theVenue = Venue::findOne($theDvo['venue_id']);
        }

        if ($theDvo['by_company_id'] != 0) {
            $theCompany = Company::findOne($theDvo['by_company_id']);
        }

        $q = Dvo::find();

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

        return $this->render('dvo_u', [
            'theDvo'=>$theDvo,
            'relatedCpx'=>$relatedCpx,
            'theVenue'=>$theVenue,
            'theCompany'=>$theCompany,
        ]);
    }

    public function actionUx($id = 0)
    {
        if ($myId > 4 && !in_array(USER_ID, [1])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDvo = Dvo::findOne($id);
        if (!$theDvo) {
            throw new HttpException(404, 'Cp not found.');
        }

        $theVenue = null;
        $theCompany = null;

        if ($theDvo['venue_id'] != 0) {
            $theVenue = Venue::findOne($theDvo['venue_id']);
        }

        if ($theDvo['company_id'] != 0) {
            $theCompany = Company::findOne($theDvo['company_id']);
        }

        $theDvo->scenario = 'cp_u';

        $q = Dvo::find()
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

        if ($theDvo->load(Yii::$app->request->post()) && $theDvo->validate()) {
            $theDvo->updated_at = NOW;
            $theDvo->updated_by = Yii::$app->user->id;
            $theDvo->save();
            return $this->redirect('@web/cp/r/'.$theDvo->id);
        }

        return $this->render('dvo_u', [
            'theDvo'=>$theDvo,
            'relatedCpx'=>$relatedCpx,
            'theVenue'=>$theVenue,
            'theCompany'=>$theCompany,
        ]);
    }

    public function actionD($id = 0)
    {
        $theDvo = Dvo::find()
            ->where(['id'=>$id])
            ->with(['venue', 'cpo'])
            ->one();

        if (!$theDvo) {
            throw new HttpException(404, 'DV not found');
        }

        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
            // Delete related cpo
            Yii::$app->db->createCommand()
                ->delete('cpo', ['dvo_id'=>$id])
                ->execute();
            // Delete cp
            $theDvo->delete();
            if ($theDvo['venue']) {
                return $this->redirect('@web/venues/r/'.$theDvo['venue']['id']);
            } else {
                return $this->redirect('@web/dv');
            }
        }

        return $this->render('dvo_d', [
            'theDvo'=>$theDvo
        ]);
    }
}
