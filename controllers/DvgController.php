<?php

namespace app\controllers;

use common\models\Dv;
use common\models\Dvg;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class DvgController extends MyController
{
    public function actionIndex($dv_id = 0) {
        $query = Dvg::find();

        if ($dv_id != 0) {
            $query->andWhere(['dv_id'=>$dv_id]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theDvgx = $query
            ->with([
                'viaCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'dv'=>function($q) {
                    return $q->select(['id', 'name', 'unit', 'venue_id', 'by_company_id']);
                },
                'dv.venue'=>function($q) {
                    return $q->select(['id', 'name', 'abbr']);
                },
                'dv.byCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->orderBy('dv_id, name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('dvg_index', [
            'pagination'=>$pagination,
            'theDvgx'=>$theDvgx,
            'dv_id'=>$dv_id,
        ]);
    }

    public function actionC($dv_id = 0)
    {
        // Only Ketoan + Lanhdao
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($dv_id == 0) {
            throw new HttpException(403, 'Cannot add: select a dv first');
        }

        $theDv = Dv::find()
            ->where(['id'=>$dv_id])
            ->with(['byCompany', 'venue'])
            ->asArray()
            ->one();
        if (!$theDv) {
            throw new HttpException(404, 'Dv not found.');
        }

        $theDvg = new Dvg;
        $theDvg->scenario = 'dvg/c';

        if ($theDvg->load(Yii::$app->request->post()) && $theDvg->validate()) {
            $theDvg->account_id = ACCOUNT_ID;
            $theDvg->status = 'on';
            $theDvg->created_at = NOW;
            $theDvg->created_by = USER_ID;
            $theDvg->updated_at = NOW;
            $theDvg->updated_by = USER_ID;
            $theDvg->dv_id = $theDv['id'];
            $theDvg->save(false);
            if ($theDv['venue']) {
                return $this->redirect('@web/venues/r/'.$theDv['venue']['id'].'#c');
            }
            if ($theDv['company']) {
                return $this->redirect('@web/companies/r/'.$theDv['byCompany']['id']);
            }
            return $this->redirect('@web/dvg/r/'.$theDvg->id);
        }

        $relatedDvgx = Dvg::find()
            ->where(['dv_id'=>$dv_id])
            ->orderBy('from_dt DESC, name')
            ->limit(1000)
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        return $this->render('dvg_u', [
            'theDvg'=>$theDvg,
            'theDv'=>$theDv,
            'relatedDvgx'=>$relatedDvgx,
            'companyList'=>$companyList,
        ]);
    }

    public function actionR($id = 0)
    {
        return $this->redirect('/dvg/u/'.$id);

        $theDvg = Dvg::find()
            ->where(['id'=>$id])
            ->with(['viaCompany', 'dv'])
            ->one();

        if (!$theDvg) {
            throw new HttpException(404, 'Dvg not found');          
        }

        $relatedDvgx = Dvg::find()
            ->andWhere(['dv_id'=>$theDvg['dv_id']])
            ->orderBy('name')
            ->all();

        return $this->render('dvg_r', [
            'theDvg'=>$theDvg,
            'relatedDvgx'=>$relatedDvgx,
        ]);
    }

    public function actionU($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDvg = Dvg::findOne($id);
        if (!$theDvg) {
            throw new HttpException(404, 'Dvg not found.');
        }

        $theDv = Cp::find()
            ->where(['id'=>$theDvg['dv_id']])
            ->with(['byCompany', 'venue'])
            ->one();
        if (!$theDv) {
            throw new HttpException(404, 'Cp not found.');
        }

        $theCompany = null;

        if ($theDvg['via_company_id'] != 0) {
            $theCompany = Company::find($theDvg['via_company_id']);
        }

        $theDvg->scenario = 'dvg/u';

        $q = Dvg::find();

        if ($theCompany) {
            $q->andWhere(['via_company_id'=>$theCompany['id']]);
        }

        if ($theDvg->load(Yii::$app->request->post()) && $theDvg->validate()) {
            $theDvg->updated_at = NOW;
            $theDvg->updated_by = USER_ID;
            $theDvg->save(false);
            if ($theDv['venue']) {
                return $this->redirect('@web/venues/r/'.$theDv['venue']['id'].'#c');
            }
            if ($theDv['byCompany']) {
                return $this->redirect('@web/companies/r/'.$theDv['byCompany']['id']);
            }
            return $this->redirect('@web/dvg/r/'.$id);
        }

        $relatedDvgx = Dvg::find()
            ->where(['dv_id'=>$theDv['id']])
            ->orderBy('from_dt DESC, name')
            ->limit(1000)
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        return $this->render('dvg_u', [
            'theDvg'=>$theDvg,
            'theDv'=>$theDv,
            'relatedDvgx'=>$relatedDvgx,
            'theCompany'=>$theCompany,
            'companyList'=>$companyList,
        ]);
    }

    public function actionD($id = 0)
    {
        $theDvg = Dvg::find()
            ->where(['id'=>$id])
            ->with(['dv'])
            ->one();

        if (!$theDvg) {
            throw new HttpException(404, 'Cp not found');           
        }

        if (!in_array(USER_ID, [1, 8, 28722, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'yes') {
            $theDvg->delete();
            Yii::$app->session->setFlash('success', 'Đã xoá giá chi phí: '.$theDvg['name']);
            return $this->redirect('@web/dv/r/'.$theDvg['dv']['id']);
        }

        return $this->render('dvg_d', [
            'theDvg'=>$theDvg,
        ]);
    }
}
