<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Company;
use common\models\User;
use common\models\Search;
use common\models\Venue;
use common\models\Ncc2;
use common\models\Message;
use yii\web\HttpException;

class NccController extends MyController
{

    public function actionIndex($search = '', $status = '') {
        $query = Ncc2::find();
        if (strlen($search) > 2) {
            $query->filterWhere(['or', ['like', 'ma', $search], ['like', 'ten', $search]]);
        }
        if (in_array($status, ['ok', 'nok'])) {
            $query->filterWhere(['status'=>$status]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $theNccs = $query
            ->with([
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                }
                ])
            ->orderBy('ten')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return $this->render('ncc_index', [
            'pagination'=>$pagination,
            'theNccs'=>$theNccs,
            'status'=>$status,
            'search'=>$search,
        ]);
    }

    public function actionR($id = 0) {
        $theNcc = Ncc2::find()
            ->where(['id'=>$id])
            ->with([
                'venue',
                ])
            ->asArray()
            ->one();
        if (!$theNcc)
            throw new HttpException(404, 'NCC not found');
                
        return $this->render('ncc_r', [
            'theNcc'=>$theNcc,
        ]);
    }

    public function actionU($id = 0) {
        $theNcc = Ncc2::find()
            ->where(['id'=>$id])
            ->with([
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                }
                ])
            ->one();
        if (!$theNcc)
            throw new HttpException(404, 'NCC not found');

        if ($theNcc->load(Yii::$app->request->post()) && $theNcc->validate()) {
            $theNcc->save(false);
            return $this->redirect('/ncc');
        }
                
        return $this->render('ncc_u', [
            'theNcc'=>$theNcc,
        ]);
    }
}
