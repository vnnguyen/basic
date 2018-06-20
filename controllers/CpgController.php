<?php

namespace app\controllers;

use common\models\Cpt;
use common\models\Cp;
use common\models\Cpg;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class CpgController extends MyController
{
    public function actionIndex($cp_id = 0) {
        $query = Cpg::find();

        if ($cp_id != 0) {
            $query->andWhere(['cp_id'=>$cp_id]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theCpgx = $query
            ->with([
                'viaCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'cp'=>function($q) {
                    return $q->select(['id', 'name', 'unit', 'venue_id', 'by_company_id']);
                },
                'cp.venue'=>function($q) {
                    return $q->select(['id', 'name', 'abbr']);
                },
                'cp.byCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->orderBy('cp_id, name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('cpg_index', [
            'pagination'=>$pagination,
            'theCpgx'=>$theCpgx,
            'cp_id'=>$cp_id,
        ]);
    }

    public function actionC($cp_id = 0)
    {
        // Only Ketoan + Lanhdao
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($cp_id == 0) {
            throw new HttpException(403, 'Cannot add: select a cp first');
        }

        $theCp = Cp::find()
            ->where(['id'=>$cp_id])
            ->with(['byCompany', 'venue'])
            ->one();
        if (!$theCp) {
            throw new HttpException(404, 'Cp not found.');
        }

        $relatedCpgx = Cpg::find()
            ->where(['cp_id'=>$cp_id])
            ->orderBy('from_dt DESC, name')
            ->limit(1000)
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        $theCpg = new Cpg;
        $theCpg->scenario = 'cpg/c';

        if ($theCpg->load(Yii::$app->request->post()) && $theCpg->validate()) {
            $theCpg->status = 'on';
            $theCpg->created_at = NOW;
            $theCpg->created_by = USER_ID;
            $theCpg->updated_at = NOW;
            $theCpg->updated_by = USER_ID;
            $theCpg->cp_id = $theCp['id'];
            $theCpg->save(false);
            if ($theCp['venue']) {
                return $this->redirect('@web/venues/r/'.$theCp['venue']['id'].'#c');
            }
            if ($theCp['company']) {
                return $this->redirect('@web/companies/r/'.$theCp['byCompany']['id']);
            }
            return $this->redirect('@web/cpg/r/'.$theCpg->id);
        }

        return $this->render('cpg_u', [
            'theCpg'=>$theCpg,
            'theCp'=>$theCp,
            'relatedCpgx'=>$relatedCpgx,
            'companyList'=>$companyList,
        ]);
    }

    public function actionR($id = 0)
    {
        return $this->redirect('/cpg/u/'.$id);

        $theCpg = Cpg::find()
            ->where(['id'=>$id])
            ->with(['viaCompany', 'cp'])
            ->one();

        if (!$theCpg) {
            throw new HttpException(404, 'Cpg not found');          
        }

        $relatedCpgx = Cpg::find()
            ->andWhere(['cp_id'=>$theCpg['cp_id']])
            ->orderBy('name')
            ->all();

        return $this->render('cpg_r', [
            'theCpg'=>$theCpg,
            'relatedCpgx'=>$relatedCpgx,
        ]);
    }

    public function actionU($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theCpg = Cpg::findOne($id);
        if (!$theCpg) {
            throw new HttpException(404, 'Cpg not found.');
        }

        $theCp = Cp::find()
            ->where(['id'=>$theCpg['cp_id']])
            ->with(['byCompany', 'venue'])
            ->one();
        if (!$theCp) {
            throw new HttpException(404, 'Cp not found.');
        }

        $theCompany = null;

        if ($theCpg['via_company_id'] != 0) {
            $theCompany = Company::find($theCpg['via_company_id']);
        }

        $theCpg->scenario = 'cpg/u';

        $q = Cpg::find();

        if ($theCompany) {
            $q->andWhere(['via_company_id'=>$theCompany['id']]);
        }

        if ($theCpg->load(Yii::$app->request->post()) && $theCpg->validate()) {
            $theCpg->updated_at = NOW;
            $theCpg->updated_by = USER_ID;
            $theCpg->save(false);
            if ($theCp['venue']) {
                return $this->redirect('@web/venues/r/'.$theCp['venue']['id'].'#c');
            }
            if ($theCp['byCompany']) {
                return $this->redirect('@web/companies/r/'.$theCp['byCompany']['id']);
            }
            return $this->redirect('@web/cpg/r/'.$id);
        }

        $relatedCpgx = Cpg::find()
            ->where(['cp_id'=>$theCp['id']])
            ->orderBy('from_dt DESC, name')
            ->limit(1000)
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        return $this->render('cpg_u', [
            'theCpg'=>$theCpg,
            'theCp'=>$theCp,
            'relatedCpgx'=>$relatedCpgx,
            'theCompany'=>$theCompany,
            'companyList'=>$companyList,
        ]);
    }

    public function actionD($id = 0)
    {
        $theCpg = Cpg::find()
            ->where(['id'=>$id])
            ->with(['cp'])
            ->one();

        if (!$theCpg) {
            throw new HttpException(404, 'Cp not found');           
        }

        if (!in_array(USER_ID, [1, 8, 28722, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'yes') {
            $theCpg->delete();
            Yii::$app->session->setFlash('success', 'Đã xoá giá chi phí: '.$theCpg['name']);
            return $this->redirect('@web/cp/r/'.$theCpg['cp']['id']);
        }

        return $this->render('cpg_d', [
            'theCpg'=>$theCpg,
        ]);
    }
}
