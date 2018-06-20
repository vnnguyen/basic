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

class DvcController extends MyController
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

        $theDvcx = $query
            ->with([
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
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
            'theDvcx'=>$theDvcx,
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

        $theDvc = new Dvc;
        $theDvc->scenario = 'dvc/c';
        $model = new \app\models\DvcUploadForm;

        if ($theDvc->load(Yii::$app->request->post()) && $theDvc->validate()) {
            //$theDvc->account_id = ACCOUNT_ID;
            $theDvc->status = 'on';
            $theDvc->created_dt = NOW;
            $theDvc->created_by = USER_ID;
            $theDvc->updated_dt = NOW;
            $theDvc->updated_by = USER_ID;
            $theDvc->venue_id = $theVenue['id'];

            $theDvc->valid_until_dt = substr($theDvc->valid_from_dt, -10);
            $theDvc->valid_from_dt = substr($theDvc->valid_from_dt, 0, 10);

            $theDvc->save(false);

            $model->uploadDir = substr($theDvc['created_dt'], 0, 7).'/'.$theDvc['id'];
            $model->uploadFiles = \yii\web\UploadedFile::getInstances($model, 'uploadFiles');
            if (empty($model->uploadFiles) || $model->upload()) {
                return $this->redirect('@web/dvc/r/'.$theDvc->id);
            }
        }

        return $this->render('dvc_u', [
            'theDvc'=>$theDvc,
            'theVenue'=>$theVenue,
            'model'=>$model,
        ]);
    }

    public function actionR($id = 0, $action = '', $file = '')
    {
        $theDvc = Dvc::find()
            ->where(['id'=>$id])
            ->with([
                'venue',
                'dvd',
                'cp',
                'cp.dv'=>function($q){
                    return $q->select(['id', 'name', 'is_dependent'])->where('status!="deleted"');
                },
                ])
            ->asArray()
            ->one();

        if (!$theDvc) {
            throw new HttpException(404, 'Dvc not found');          
        }

        $uploadDir = Yii::getAlias('@webroot').'/upload/dvc/'.substr($theDvc['created_dt'], 0, 7).'/'.$theDvc['id'];

        // Download file
        if ($action == 'download' && $file != '') {
            return $this->redirect(Yii::getAlias('@web').'/upload/dvc/'.substr($theDvc['created_dt'], 0, 7).'/'.$theDvc['id'].'/'.$file);
        }

        // Delete file
        if ($action == 'delete' && $file != '') {
            @unlink(Yii::getAlias('@webroot').'/upload/dvc/'.substr($theDvc['created_dt'], 0, 7).'/'.$theDvc['id'].'/'.$file);
            return $this->redirect('/dvc/r/'.$theDvc['id']);
        }

        // Post: dvd_add
        if (Yii::$app->request->isPost && isset($_POST['action']) && $_POST['action'] == 'dvd_add') {
            $theDvd = new Dvd;
            $theDvd->updated_dt = NOW;
            $theDvd->updated_by = USER_ID;
            $theDvd->dvc_id = $theDvc['id'];
            $theDvd->stype = $_POST['stype'];
            $theDvd->code = $_POST['code'] ?? '';
            $theDvd->def = $_POST['def'] ?? '';
            $theDvd->desc = $_POST['desc'] ?? '';
            $theDvd->save(false);
            return $this->redirect('/dvc/r/'.$theDvc['id']);
        }

        $relatedDvcx = Dvc::find()
            ->select(['id', 'name'])
            ->andWhere(['venue_id'=>$theDvc['venue_id']])
            ->orderBy('name')
            ->all();

        return $this->render('dvc_r', [
            'theDvc'=>$theDvc,
            'relatedDvcx'=>$relatedDvcx,
        ]);
    }

    public function actionU($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDvc = Dvc::findOne($id);
        if (!$theDvc) {
            throw new HttpException(404, 'Dvc not found.');
        }

        $theDvc->scenario = 'dvc/u';
        if ($theDvc->valid_from_dt != ZERO_DT && $theDvc->valid_until_dt != ZERO_DT) {
            $theDvc->valid_from_dt = substr($theDvc->valid_from_dt, 0, 10) .' - '.substr($theDvc->valid_until_dt, 0, 10);
        } else {
            $theDvc->valid_from_dt = '';
        }

        $model = new \app\models\DvcUploadForm;
        $model->uploadDir = substr($theDvc['created_dt'], 0, 7).'/'.$theDvc['id'];

        if ($theDvc->load(Yii::$app->request->post()) && $theDvc->validate()) {
            $theDvc->valid_until_dt = substr($theDvc->valid_from_dt, -10);
            $theDvc->valid_from_dt = substr($theDvc->valid_from_dt, 0, 10);
            $theDvc->updated_dt = NOW;
            $theDvc->updated_by = USER_ID;
            $model->uploadFiles = \yii\web\UploadedFile::getInstances($model, 'uploadFiles');
            if ($theDvc->save(false) && $model->upload()) {
                return $this->redirect('@web/dvc/r/'.$id);
            }
        }

        $theVenue = Venue::find()
            ->where(['id'=>$theDvc['venue_id']])
            ->with(['dvc'])
            ->asArray()
            ->one();

        return $this->render('dvc_u', [
            'theDvc'=>$theDvc,
            'theVenue'=>$theVenue,
            'model'=>$model,
        ]);
    }

    public function actionD($id = 0)
    {
        $theDvc = Dvc::find()
            ->where(['id'=>$id])
            ->with(['dv'])
            ->one();

        if (!$theDvc) {
            throw new HttpException(404, 'Cp not found');           
        }

        if (!in_array(USER_ID, [1, 8, 28722, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'yes') {
            $theDvc->delete();
            Yii::$app->session->setFlash('success', 'Đã xoá giá chi phí: '.$theDvc['name']);
            return $this->redirect('@web/dv/r/'.$theDvc['dv']['id']);
        }

        return $this->render('dvg_d', [
            'theDvc'=>$theDvc,
        ]);
    }
}
