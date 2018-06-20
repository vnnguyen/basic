<?php

namespace app\controllers\b2b;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Kase;
use common\models\User;
use common\models\Day;
use common\models\Ngaymau;
use common\models\Message;
use common\models\Product;

/*
INSERT INTO at_days (created_at, created_by, updated_at, updated_by, status, rid, parent_day_id, booking_id, step, day, name, body, image, meals, guides, transport, note, tags)
(SELECT created_dt, created_by, updated_dt, updated_by, 'sample', 0, 0, 0, 1, 0, title, body, image, meals, guides, transport, services, tags FROM at_ngaymau)
*/

class DayController extends \app\controllers\MyController
{

    // Sample tour days
    public function actionIndex($orderby = 'updated', $name = '', $tags = '', $show = 'all', $language = 'fr')
    {
        $query = Ngaymau::find()
            ->andWhere(['owner'=>'si']);

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

        return $this->render('day_index', [
            'pagination'=>$pagination,
            'theDays'=>$theDays,
            'language'=>$language,
            'name'=>$name,
            'tags'=>$tags,
            'show'=>$show,
            'orderby'=>$orderby,
        ]);
    }

    // Them ngay la option cua 1 ngay san co
    public function actionC($as = 'option', $day_id = 0, $booking_id = 0)
    {
        $theDay = new Ngaymau;

        $theDay->scenario = 'day/c';

        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay['created_at'] = NOW;
            $theDay['created_by'] = USER_ID;
            $theDay['updated_at'] = NOW;
            $theDay['updated_by'] = USER_ID;
            $theDay['rid'] = $currentDay['rid'];
            $theDay['parent_day_id'] = $day_id;
            $theDay['booking_id'] = $booking_id;
            $theDay['step'] = 0;
            $theDay['day'] = '0000-00-00';
            if ($theDay->save(false)) {
                return $this->redirect('/b2b/days');
            }
        }

        return $this->render('day_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionR($id = 0, $action = '', $option = 0) {
        $theDay = Ngaymau::find()
            ->where(['id'=>$id, 'owner'=>'si'])
            ->asArray()
            ->one();

        if (!$theDay) {
            throw new HttpException(404, 'Day not found');
        }

        return $this->render('day_r', [
            'theDay'=>$theDay,
            'action'=>$action,
        ]);
    }

    public function actionU($id = 0)
    {
        $theDay = Ngaymau::find()
            ->where(['id'=>$id, 'owner'=>'si'])
            ->one();
        if (!$theDay) {
            throw new HttpException(404);
        }

        if (!in_array(USER_ID, [1, $theDay['created_by'], $theDay['updated_by']])) {
            throw new HttpException(403, 'Access denied');   
        }

        $theDay->scenario = 'day/u';

        if ($theDay->load(Yii::$app->request->post())) {
            $theDay['updated_at'] = NOW;
            $theDay['updated_by'] = USER_ID;
            $theDay['body'] = str_replace(['&nbsp;', 'class=', 'style='], [' ', 'c=', 's='], $theDay['body']);
            if ($theDay->save(false)) {
                return $this->redirect('@web/b2b/days');
            }
        }

        return $this->render('day_u', [
            'theDay'=>$theDay,
        ]);
    }
}
