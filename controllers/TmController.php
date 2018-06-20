<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

use common\models\Company;
use common\models\User;
use common\models\Nm;
use common\models\Tm;

class TmController extends MyController
{
    public $allowList = [1, 3, 28722, 17401, 1677];

    // Sample tours
    public function actionIndex($orderby = 'updated', $name = '', $tags = '', $language = 'fr')
    {
        $query = Tm::find()
            ->where(['status'=>'sample']);

        if (strlen($name) > 1) {
            $query->andWhere(['like', 'title', $name]);
        }

        if (strlen($tags) > 1) {
            $tagArray = explode(',', $tags);
            $cnt = 0;
            foreach ($tagArray as $tag) {
                $cnt ++;
                $tagStr = trim($tag);
                if ($tagStr != '') {
                    $query->andWhere('LOCATE(:tag'.$cnt.', tags)!=0', [':tag'.$cnt=>$tagStr]);
                }
            }
        }
        if (strlen($language) > 1) {
            $query->andWhere(['language'=>$language]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $thePrograms = $query
            ->orderBy($orderby == 'updated' ? 'updated_at DESC' : 'title')
            ->with([
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'nickname']);
                }
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('tm', [
            'pagination'=>$pagination,
            'thePrograms'=>$thePrograms,
            'language'=>$language,
            'name'=>$name,
            'tags'=>$tags,
            'orderby'=>$orderby,
        ]);
    }

    public function actionC($id = 0) {
        if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/tm');
        }

        $theProgram = new Tm;
        $theProgram->scenario = 'tm/c';
        $theProgram->language = 'fr';
        if ($theProgram->load(Yii::$app->request->post()) && $theProgram->validate()) {
            $theProgram->owner = 'at';
            $theProgram->created_at = NOW;
            $theProgram->created_by = USER_ID;
            $theProgram->updated_at = NOW;
            $theProgram->updated_by = USER_ID;
            $theProgram->status = 'sample';
            $theProgram->save(false);
            Yii::$app->session->setFlash('success', 'Sample tour has been created');
            return $this->redirect('/tm');
        }
                
        return $this->render('tm_u', [
            'theProgram'=>$theProgram,
        ]);
    }

    public function actionR($id = 0, $action = '', $at = 0, $add = 0)
    {
        $theProgram = Tm::find()
            ->where(['id'=>$id, 'status'=>'sample'])
            ->with([
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                }
            ])
            ->asArray()
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Sample program not found.');
        }

        // Them ngay mau
        if ($action == 'add-day-sample' && $add != 0) {
            // Kiem tra ngay mau co ton tai
            // Them ngay mau vao danh sach ngay cua tour mau
            // Avoid empty string
            $dayIdList = array_filter(explode(',', $theProgram['day_ids']));
            // if (empty($dayIdList)) {
            //     $dayIdList = [$add];
            // } else {
                array_splice($dayIdList, $at, 0, $add);
            // }

            Yii::$app->session->setFlash('warning', implode(',', $dayIdList));

            $sql = 'UPDATE at_ct SET day_ids = :di WHERE id=:id AND status="sample" AND owner="at" LIMIT 1';
            Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProgram['id']])->execute();
            return $this->redirect('/tm/r/'.$theProgram['id']);
        }

        if ($action == 'remove-day-sample' && $at != 0) {
            // Xoa ID ngay mau khoi danh sach ngay cua tour mau
            $dayIdList = array_filter(explode(',', $theProgram['day_ids']));
            unset($dayIdList[$at - 1]);
            $sql = 'UPDATE at_ct SET day_ids = :di WHERE id=:id AND status="sample" AND owner="at" LIMIT 1';
            Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProgram['id']])->execute();
            return $this->redirect('/tm/r/'.$theProgram['id']);
        }

        if ($action == 'sort-day' && isset($_POST['trday'])) {
            $sql = 'UPDATE at_ct SET day_ids = :di WHERE id=:id AND status="sample" AND owner="at" LIMIT 1';
            Yii::$app->db->createCommand($sql, [':di'=>implode(',', $_POST['trday']), ':id'=>$theProgram['id']])->execute();
            echo '1'; return true;
        }

        $theDays = Nm::find()
            ->where(['id'=>explode(',', $theProgram['day_ids'])])
            ->indexBy('id')
            ->asArray()
            ->all();
                
        return $this->render('tm_r', [
            'theDays'=>$theDays,
            'theProgram'=>$theProgram,
        ]);
    }

    public function actionU($id = 0) {

        $theProgram = Tm::find()
            ->where(['status'=>'sample', 'owner'=>'at', 'id'=>$id])
            ->one();
        if (!$theProgram) {
            throw new HttpException(404, 'Sample tour not found.'); 
        }

        if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/tm/r/'.$id);
        }

        $theProgram->scenario = 'tm/u';
        if ($theProgram->load(Yii::$app->request->post()) && $theProgram->validate()) {
            $theProgram->updated_at = NOW;
            $theProgram->updated_by = USER_ID;
            $theProgram->save(false);
            Yii::$app->session->setFlash('success', 'Sample tour has been updated.');
            return $this->redirect('/tm');
        }
                
        return $this->render('tm_u', [
            'theProgram'=>$theProgram,
        ]);
    }

    public function actionD($id = 0) {
        $theProgram = Nm::findOne($id);
        if (!$theProgram) {
            throw new HttpException(404, 'Sample day not found.'); 
        }

        if (!in_array(USER_ID, $this->allowList) || $theProgram->owner == 'si') { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/tm/r/'.$id);
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'delete') {
            $theProgram->delete();
            Yii::$app->session->setFlash('success', 'Sample day has been deleted.');
            return $this->redirect('/tm');
        }
                
        return $this->render('tm_d', [
            'theProgram'=>$theProgram,
        ]);
    }
}
