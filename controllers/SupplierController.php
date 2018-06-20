<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

use common\models\Supplier;
use common\models\Venue;
use common\models\Dv;
use common\models\Dvt;
use common\models\Dvg;
use common\models\Note;
use common\models\User;
use common\models\Search;
use common\models\Meta;
use common\models\File;
use common\models\Message;


class SupplierController extends MyController
{
    public function actionAjax() {
        $action = Yii::$app->request->post('action');
        $id = Yii::$app->request->post('id');
        if ($action == 'rename-x') {
            $sql = 'UPDATE suppliers SET name=:name WHERE id=:id LIMIT 1';
            $name = 'XXX-'.$id;
            Yii::$app->db->createCommand($sql, [':name'=>$name, ':id'=>$id])->execute();
            echo 'XXX-'.$id;
            exit;
        }
    }

    public function actionIndex($name = '') {
        $query = Supplier::find();

        if (strlen($name) > 2) {
            $query->andWhere(['like', 'name', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $theSuppliers = $query
            ->select(['id', 'name', 'status', 'info'])
            ->with(['metas', 'venues'])
            ->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('supplier_index', [
            'pagination'=>$pagination,
            'theSuppliers'=>$theSuppliers,
            'name'=>$name,
        ]);
    }

    public function actionC($id = 0) {
        $theSupplier = new Supplier;
        $theSupplier->scenario = 'supplier/c';

        if ($theSupplier->load(Yii::$app->request->post()) && $theSupplier->validate()) {

            $theSupplier->created_at = NOW;
            $theSupplier->created_by = MY_ID;
            $theSupplier->updated_at = NOW;
            $theSupplier->updated_by = MY_ID;
            $theSupplier->search = ' '.str_replace('-', '', \fURL::makeFriendly($theSupplier->name, '-'));

            $theSupplier->save(false);
            //return $this->redirect('@web/suppliers/r/'.$theSupplier->id);
            return $this->redirect('@web/suppliers');
        }
                
        return $this->render('supplier_u', [
            'theSupplier'=>$theSupplier,
        ]);
    }

    public function actionR($id = 0) {
        $theSupplier = Supplier::find()
            ->where(['id'=>$id])
            ->with([
                'dv'=>function($query) {
                    $query->select('*');
                    },
                'metas'=>function($query) {
                    $query->andWhere('rtype = "supplier"');
                    },
                ])
            ->asArray()
            ->one();
        if (!$theSupplier) {
            throw new HttpException(404, 'Supplier not found');
        }
                
        return $this->render('supplier_r', [
            'theSupplier'=>$theSupplier,
        ]);
    }

    public function actionU($id = 0) {
        $theSupplier = Supplier::find()
            ->where(['id'=>$id])
            ->with([
                'metas',
                'updatedBy'
                ])
            ->one();

        if (!$theSupplier) {
            throw new HttpException(404, 'Supplier not found');
        }

        $theSupplier->scenario = 'supplier/u';

        $uploadPath = '/upload/suppliers/'.substr($theSupplier['created_dt'], 0, 7).'/'.$theSupplier['id'];
        \yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

        Yii::$app->session->set('ckfinder_authorized', true);
        Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@www').$uploadPath);
        Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
        Yii::$app->session->set('ckfinder_role', 'user');
        Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
        Yii::$app->session->set('ckfinder_resource_name', 'upload');

        if ($theSupplier->load(Yii::$app->request->post()) && $theSupplier->validate()) {
            if ($theSupplier->save(false)) {
                Yii::$app->session->setFlash('success', 'Supplier has been updated: '.$theSupplier['name']);
                return $this->redirect('@web/suppliers/r/'.$theSupplier['id']);
            }
        }
                
        return $this->render('supplier_u', [
            'theSupplier'=>$theSupplier,
        ]);
    }

    // Cac dv cua cong ty nay
    public function actionDv($id = 0)
    {
        $theSupplier = Supplier::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theSupplier) {
            throw new HttpException(404, 'Supplier not found');
        }

        $theVenues = Venue::find()
            ->where(['supplier_id'=>$id])
            ->orderBy('name')
            ->all();

        $theDvx = Dv::find()
            ->where(['supplier_id'=>$id])
            ->orderBy('grouping, name')
            ->all();

        return $this->render('supplier_dv', [
            'theSupplier'=>$theSupplier,
            'theDvx'=>$theDvx,
            'theVenues'=>$theVenues,
        ]);
    }
}
