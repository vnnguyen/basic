<?php

namespace app\controllers;

use common\models\Dv;
use common\models\Dvc;
use common\models\Dvd;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class DvdController extends MyController
{
    public function actionIndex($venue_id = 0) {
        $query = Dvc::find();

        if ($venue_id !=0) {
            $query->andWhere(['venue_id'=>$venue_id]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theDvdx = $query
            ->with([
                'dvd',
                'venue'=>function($q) {
                    return $q->select(['id', 'name', 'abbr']);
                },
                ])
            ->orderBy('venue_id, name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('dvc_index', [
            'pagination'=>$pagination,
            'theDvdx'=>$theDvdx,
            'venue_id'=>$venue_id,
        ]);
    }

    public function actionC($venue_id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($venue_id == 0) {
            throw new HttpException(403, 'Cannot add dvc: select a venue first');
        }

        $theVenue = Venue::find()
            ->where(['id'=>$venue_id])
            ->with(['dvc'])
            ->asArray()
            ->one();
        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found.');
        }

        $theDvd = new Dvc;
        $theDvd->scenario = 'dvc/c';

        if ($theDvd->load(Yii::$app->request->post()) && $theDvd->validate()) {
            //$theDvd->account_id = ACCOUNT_ID;
            $theDvd->status = 'on';
            $theDvd->created_dt = NOW;
            $theDvd->created_by = USER_ID;
            $theDvd->updated_dt = NOW;
            $theDvd->updated_by = USER_ID;
            $theDvd->venue_id = $theVenue['id'];
            $theDvd->save(false);

            return $this->redirect('@web/venues/r/'.$theVenue['id']);
        }

        return $this->render('dvc_u', [
            'theDvd'=>$theDvd,
            'theVenue'=>$theVenue,
        ]);
    }

    public function actionR($id = 0)
    {

        $theDvd = Dvc::find()
            ->where(['id'=>$id])
            ->with([
                'venue',
                'dvd',
                'cp',
                'cp.dv'=>function($q){
                    return $q->select(['id', 'name'])->where('status!="deleted"');
                },
                ])
            ->asArray()
            ->one();

        if (!$theDvd) {
            throw new HttpException(404, 'Dvc not found');          
        }

        // Post: dvd_add
        if (Yii::$app->request->isPost && isset($_POST['action']) && $_POST['action'] == 'dvd_add') {
            $theDvd = new Dvd;
            $theDvd->updated_dt = NOW;
            $theDvd->updated_by = USER_ID;
            $theDvd->dvc_id = $theDvd['id'];
            $theDvd->stype = 'conds'; //$_POST['stype'];
            $theDvd->code = $_POST['code'] ?? '';
            $theDvd->def = $_POST['def'] ?? '';
            $theDvd->desc = $_POST['desc'] ?? '';
            $theDvd->save(false);
            return $this->redirect('/dvc/r/'.$theDvd['id']);
        }

        $relatedDvcx = Dvc::find()
            ->select(['id', 'name'])
            ->andWhere(['venue_id'=>$theDvd['venue_id']])
            ->orderBy('name')
            ->all();

        return $this->render('dvc_r', [
            'theDvd'=>$theDvd,
            'relatedDvcx'=>$relatedDvcx,
        ]);
    }

    public function actionU($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDvd = Dvd::findOne($id);
        if (!$theDvd) {
            throw new HttpException(404, 'Dvd not found.');
        }

        $theDvc = Dvc::find()
            ->where(['id'=>$theDvd['dvc_id']])
            ->with([
                'cp'=>function($q) use ($theDvd) {
                    return $q->where('id!='.$theDvd['id']);
                },
                'cp.dv',
                ])
            ->asArray()
            ->one();
//            \fCore::expose($theDvc); exit;

        $theDvd->scenario = 'dvd/u';

        if ($theDvd->load(Yii::$app->request->post()) && $theDvd->validate()) {
            $theDvd->updated_dt = NOW;
            $theDvd->updated_by = USER_ID;
            $theDvd->save(false);
            return $this->redirect('@web/dvc/r/'.$theDvd['dvc_id']);
        }

        return $this->render('dvd_u', [
            'theDvd'=>$theDvd,
            'theDvc'=>$theDvc,
        ]);
    }

    public function actionD($id = 0)
    {
        $theDvd = Dvd::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theDvd) {
            throw new HttpException(404, 'Dvd not found');
        }

        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        // if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'yes') {
            $theDvd->delete();
            Yii::$app->session->setFlash('success', 'Đã xoá giai đoạn: '.$theDvd['code']);
            return $this->redirect('@web/dvc/r/'.$theDvd['dvc_id']);
        // }

        // return $this->render('dvg_d', [
        //     'theDvd'=>$theDvd,
        // ]);
    }
}
