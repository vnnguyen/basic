<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

use common\models\Vendor;
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

class CompaniesController extends MyController
{
    public function actionIndex($name = '') {
        $query = Vendor::find();

        if (strlen($name) > 2) {
            $query->andWhere(['like', 'name', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $theVendors = $query
            ->select(['id', 'name', 'status', 'info'])
            ->with([
                'metas',
            ])
            ->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('vendors_index', [
            'pagination'=>$pagination,
            'theVendors'=>$theVendors,
            'name'=>$name,
        ]);
    }

    public function actionC($id = 0) {
        $theVendor = new Vendor;
        $theVendor->scenario = 'vendors/c';

        if ($theVendor->load(Yii::$app->request->post()) && $theVendor->validate()) {

            $theVendor->created_at = NOW;
            $theVendor->created_by = MY_ID;
            $theVendor->updated_at = NOW;
            $theVendor->updated_by = MY_ID;
            $theVendor->search = ' '.str_replace('-', '', \fURL::makeFriendly($theVendor->name, '-'));

            $theVendor->save(false);
            //return $this->redirect('@web/vendors/r/'.$theVendor->id);
            return $this->redirect('@web/vendors');
        }
                
        return $this->render('vendors_u', [
            'theVendor'=>$theVendor,
        ]);
    }

    public function actionR($id = 0) {
        $theVendor = Vendor::find()
            ->where(['id'=>$id])
            ->with([
                'dv'=>function($query) {
                    $query->select('*');
                    },
                'metas'=>function($query) {
                    $query->andWhere('rtype = "vendor"');
                    },
                ])
            ->asArray()
            ->one();
        if (!$theVendor) {
            throw new HttpException(404, 'Vendor not found');
        }
                
        return $this->render('vendors_r', [
            'theVendor'=>$theVendor,
        ]);
    }

    public function actionU($id = 0) {
        $theVendor = Vendor::find()
            ->where(['id'=>$id])
            ->with([
                'metas',
                'updatedBy'
                ])
            ->one();

        if (!$theVendor) {
            throw new HttpException(404, 'Vendor not found');
        }

        $theVendor->scenario = 'vendors/u';

        $uploadPath = '/upload/vendors/'.substr($theVendor['created_dt'], 0, 7).'/'.$theVendor['id'];
        \yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

        Yii::$app->session->set('ckfinder_authorized', true);
        Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@www').$uploadPath);
        Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
        Yii::$app->session->set('ckfinder_role', 'user');
        Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
        Yii::$app->session->set('ckfinder_resource_name', 'upload');

        if ($theVendor->load(Yii::$app->request->post()) && $theVendor->validate()) {
            if ($theVendor->save(false)) {
                Yii::$app->session->setFlash('success', 'Vendor has been updated: '.$theVendor['name']);
                return $this->redirect('@web/vendors/r/'.$theVendor['id']);
            }
        }
                
        return $this->render('vendors_u', [
            'theVendor'=>$theVendor,
        ]);
    }

    // Cac dv cua cong ty nay
    public function actionDv($id = 0)
    {
        $theVendor = Vendor::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theVendor) {
            throw new HttpException(404, 'Vendor not found');
        }

        $theVenues = Venue::find()
            ->where(['vendor_id'=>$id])
            ->orderBy('name')
            ->all();

        $theDvx = Dv::find()
            ->where(['vendor_id'=>$id])
            ->orderBy('grouping, name')
            ->all();

        return $this->render('vendors_dv', [
            'theVendor'=>$theVendor,
            'theDvx'=>$theDvx,
            'theVenues'=>$theVenues,
        ]);
    }
}
