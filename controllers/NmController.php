<?php

namespace app\controllers;

use common\models\Company;
use common\models\User;
use common\models\Search;
use common\models\Venue;
use common\models\Nm;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

class NmController extends MyController
{
    // 161116 Mai Phuong
    // 161119 Ngoc Anh
    // 161121 Fleur
    public $allowList = [1, 3, 28722, 17401, 1677, 12952, 26435];

    // Sample tour days
    public function actionIndex($action = '', $to = 0, $at = 0, $orderby = 'updated', $name = '', $tags = '', $show = '', $language = 'fr', $updatedby = 0)
    {
        // Prepare to add day
        if (in_array($action, ['prepare-add-day', 'prepare-add-day-sample']) && $to != 0) {
            Yii::$app->session->set('action', $action);
            Yii::$app->session->set('to', $to);
            Yii::$app->session->set('at', $at);
            return $this->redirect('/nm');
        }

        if (in_array($action, ['cancel-add-day', 'cancel-add-day-sample'])) {
            Yii::$app->session->remove('action');
            Yii::$app->session->remove('to');
            Yii::$app->session->remove('at');
            return $this->redirect('/nm');
        }

        if (Yii::$app->request->isAjax && isset($_POST['action'], $_POST['day'])) {
            if ($_POST['action'] == 'nouse') {
                $nm = Nm::findOne($_POST['day']);
                if (!$nm) {
                    throw new HttpException(404, 'Sample day not found');
                }
                if (strpos($nm->tags, 'nouse') === false) {
                    $nm->tags .= ', nouse';
                    $nm->save(false);
                }
            }
            return true;
        }

        $query = Nm::find();

        if ($show == 'b2b') {
            $query->andWhere(['owner'=>'si']);
        } else {
            $query->andWhere(['owner'=>'at']);
        }

        if ($updatedby != 0) {
            $query->andWhere(['updated_by'=>$updatedby]);
        }

        if (strpos($tags, 'nouse') === false) {
            $query->andWhere('LOCATE("nouse", tags)=0');
        }

        if ($show == '2015') {
            $query->andWhere('LOCATE("2015", tags)!=0');
        }

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
            'route'=>'/'.URI,
        ]);

        $theDays = $query
            ->orderBy($orderby == 'updated' ? 'updated_dt DESC' : 'title')
            ->with([
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'nickname']);
                }
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        if ($show == 'b2b') {
            $updatedByList = Yii::$app->db->createCommand('SELECT u.id, u.nickname AS name FROM persons u, at_ngaymau nm WHERE owner="si" AND nm.updated_by=u.id GROUP BY u.id ORDER BY lname')->queryAll();
        } else {
            $updatedByList = Yii::$app->db->createCommand('SELECT u.id, u.nickname AS name FROM persons u, at_ngaymau nm WHERE owner="at" AND nm.updated_by=u.id GROUP BY u.id ORDER BY lname')->queryAll();
        }

        return $this->render('nm', [
            'pagination'=>$pagination,
            'theDays'=>$theDays,
            'language'=>$language,
            'name'=>$name,
            'tags'=>$tags,
            'show'=>$show,
            'orderby'=>$orderby,
            'updatedby'=>$updatedby,
            'updatedByList'=>$updatedByList,
        ]);
    }

    public function actionC($id = 0) {
        if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/nm');
        }

        $theDay = new Nm;
        $theDay->scenario = 'nm/c';
        $theDay->language = 'fr';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay->owner = 'at';
            $theDay->created_dt = NOW;
            $theDay->created_by = USER_ID;
            $theDay->updated_dt = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->save(false);
            Yii::$app->session->setFlash('success', 'Sample day has been created');
            return $this->redirect('/nm');
        }
                
        return $this->render('nm_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionR($id = 0)
    {
        $theDay = Nm::find()
            ->where(['id'=>$id])
            ->with([
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                }
            ])
            ->asArray()
            ->one();

        $parentId = $theDay['parent_id'];

        $theDays = $parentId == 0 ? [] : Nm::find()
            ->select(['id', 'meals', 'title'])
            ->where(['parent_id'=>$parentId])
            ->orderBy('sorder')
            ->asArray()
            ->all();
                
        return $this->render('nm_r', [
            'theDay'=>$theDay,
            'theDays'=>$theDays,
        ]);
    }

    public function actionU($id = 0) {

        $theDay = Nm::findOne($id);
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.'); 
        }

        if (!in_array(USER_ID, $this->allowList) || $theDay->owner == 'si') { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/nm/r/'.$id);
        }

        $theDay->scenario = 'nm/u';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay->updated_dt = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->save(false);
            Yii::$app->session->setFlash('success', 'Sample day has been updated.');
            return $this->redirect('/nm');
        }
                
        return $this->render('nm_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionD($id = 0) {
        $theDay = Nm::findOne($id);
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.'); 
        }

        if (!in_array(USER_ID, $this->allowList) || $theDay->owner == 'si') { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/nm/r/'.$id);
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'delete') {
            $theDay->delete();
            Yii::$app->session->setFlash('success', 'Sample day has been deleted.');
            return $this->redirect('/nm');
        }
                
        return $this->render('nm_d', [
            'theDay'=>$theDay,
        ]);
    }
}
