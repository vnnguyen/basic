<?php

namespace app\controllers\b2b;

use common\models\Company;
use common\models\Person;
use common\models\Search;
use common\models\Venue;
use common\models\Nm;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

class DayController extends \app\controllers\MyController
{
    public $allowList = [1, 3, 26052, 29013, 40399]; // Hieu Jonathan Alain Nghĩa

    // Sample tour days
    public function actionIndex($orderby = 'updated', $name = '', $tags = '', $show = 'all', $language = 'fr')
    {
        $query = Nm::find()->andWhere(['owner'=>'si']);

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

        return $this->render('nm', [
            'pagination'=>$pagination,
            'theDays'=>$theDays,
            'language'=>$language,
            'name'=>$name,
            'tags'=>$tags,
            'show'=>$show,
            'orderby'=>$orderby,
        ]);
    }

    public function actionC($id = 0) {
        if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/b2b/days');
        }

        $theDay = new Nm;
        $theDay->scenario = 'nm/c';
        $theDay->language = 'fr';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay->owner = 'si';
            $theDay->created_dt = NOW;
            $theDay->created_by = USER_ID;
            $theDay->updated_dt = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->save(false);
            Yii::$app->session->setFlash('success', 'Sample day has been created');
            return $this->redirect('/b2b/days');
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
                
        return $this->render('nm_r', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionU($id = 0) {
        if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/b2b/days/r/'.$id);
        }

        $theDay = Nm::findOne($id);
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.'); 
        }
        $theDay->scenario = 'nm/u';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay->updated_dt = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->save(false);
            Yii::$app->session->setFlash('success', 'Sample day has been updated.');
            return $this->redirect('/b2b/days');
        }
                
        return $this->render('nm_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionD($id = 0) {
        if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/b2b/days/r/'.$id);
        }

        $theDay = Nm::findOne($id);
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.'); 
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'delete') {
            $theDay->delete();
            Yii::$app->session->setFlash('success', 'Sample day has been deleted.');
            return $this->redirect('/b2b/days');
        }
                
        return $this->render('nm_d', [
            'theDay'=>$theDay,
        ]);
    }
}
