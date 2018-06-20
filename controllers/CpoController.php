<?php

namespace app\controllers;

use common\models\Cpt;
use common\models\Dvo;
use common\models\Cpo;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class CpoController extends MyController
{
    public function actionIndex($dvo_id = 0) {
        $query = Cpo::find();

        if ($dvo_id != 0) {
            $query->andWhere(['dvo_id'=>$dvo_id]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theCpox = $query
            ->with([
                'viaCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'dvo'=>function($q) {
                    return $q->select(['id', 'name', 'unit', 'venue_id', 'by_company_id']);
                },
                'dvo.venue'=>function($q) {
                    return $q->select(['id', 'name', 'abbr']);
                },
                'dvo.byCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->orderBy('dvo_id, name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('cpo_index', [
            'pagination'=>$pagination,
            'theCpox'=>$theCpox,
            'dvo_id'=>$dvo_id,
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

        $theDvo = Dvo::find()
            ->where(['id'=>$cp_id])
            ->with(['byCompany', 'venue'])
            ->one();
        if (!$theDvo) {
            throw new HttpException(404, 'Dv not found.');
        }

        $relatedCpox = Cpo::find()
            ->where(['dvo_id'=>$cp_id])
            ->orderBy('from_dt DESC, name')
            ->limit(1000)
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        $theCpo = new Cpo;
        $theCpo->scenario = 'cpo/c';

        if ($theCpo->load(Yii::$app->request->post()) && $theCpo->validate()) {
            $theCpo->status = 'on';
            $theCpo->created_at = NOW;
            $theCpo->created_by = USER_ID;
            $theCpo->updated_at = NOW;
            $theCpo->updated_by = USER_ID;
            $theCpo->dvo_id = $theDvo['id'];
            $theCpo->save(false);
            if ($theDvo['venue']) {
                return $this->redirect('@web/venues/r/'.$theDvo['venue']['id'].'#c');
            }
            if ($theDvo['company']) {
                return $this->redirect('@web/companies/r/'.$theDvo['byCompany']['id']);
            }
            return $this->redirect('@web/cpo/r/'.$theCpo->id);
        }

        return $this->render('cpo_u', [
            'theCpo'=>$theCpo,
            'theDvo'=>$theDvo,
            'relatedCpox'=>$relatedCpox,
            'companyList'=>$companyList,
        ]);
    }

    public function actionR($id = 0)
    {
        return $this->redirect('/cpo/u/'.$id);

        $theCpo = Cpg::find()
            ->where(['id'=>$id])
            ->with(['viaCompany', 'cp'])
            ->one();

        if (!$theCpo) {
            throw new HttpException(404, 'Cpg not found');          
        }

        $relatedCpgx = Cpg::find()
            ->andWhere(['cp_id'=>$theCpo['cp_id']])
            ->orderBy('name')
            ->all();

        return $this->render('cpg_r', [
            'theCpo'=>$theCpo,
            'relatedCpgx'=>$relatedCpgx,
        ]);
    }

    public function actionU($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theCpo = Cpo::findOne($id);
        if (!$theCpo) {
            throw new HttpException(404, 'Cpo not found.');
        }

        $theDvo = Dvo::find()
            ->where(['id'=>$theCpo['dvo_id']])
            ->with(['byCompany', 'venue'])
            ->one();
        if (!$theDvo) {
            throw new HttpException(404, 'DV not found.');
        }

        $theCompany = null;

        if ($theCpo['via_company_id'] != 0) {
            $theCompany = Company::find($theCpo['via_company_id']);
        }

        $theCpo->scenario = 'cpo/u';

        $q = Cpo::find();

        if ($theCompany) {
            $q->andWhere(['via_company_id'=>$theCompany['id']]);
        }

        if ($theCpo->load(Yii::$app->request->post()) && $theCpo->validate()) {
            $theCpo->updated_at = NOW;
            $theCpo->updated_by = USER_ID;
            $theCpo->save(false);
            if ($theDvo['venue']) {
                return $this->redirect('@web/venues/r/'.$theDvo['venue']['id'].'#c');
            }
            if ($theDvo['byCompany']) {
                return $this->redirect('@web/companies/r/'.$theDvo['byCompany']['id']);
            }
            return $this->redirect('@web/cpo/r/'.$id);
        }

        $relatedCpox = Cpo::find()
            ->where(['dvo_id'=>$theDvo['id']])
            ->orderBy('from_dt DESC, name')
            ->limit(1000)
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        return $this->render('cpo_u', [
            'theCpo'=>$theCpo,
            'theDvo'=>$theDvo,
            'relatedCpox'=>$relatedCpox,
            'theCompany'=>$theCompany,
            'companyList'=>$companyList,
        ]);
    }

    public function actionD($id = 0)
    {
        $theCpo = Cpo::find()
            ->where(['id'=>$id])
            ->with(['dvo'])
            ->one();

        if (!$theCpo) {
            throw new HttpException(404, 'Cpo not found');
        }

        if (!in_array(USER_ID, [1, 8, 28722, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'yes') {
            $theCpo->delete();
            Yii::$app->session->setFlash('success', 'Đã xoá giá chi phí: '.$theCpo['name']);
            return $this->redirect('@web/dvo/r/'.$theCpo['dvo']['id']);
        }

        return $this->render('cpo_d', [
            'theCpo'=>$theCpo,
        ]);
    }
}
