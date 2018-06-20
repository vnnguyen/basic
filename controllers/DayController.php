<?

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Kase;
use common\models\User;
use common\models\Day;
use common\models\Ngaymau;
use common\models\Message;
use common\models\Product;

class DayController extends MyController
{
    public function actionIndex()
    {
        $query = Day::find();

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theDays = $query
            ->with(['product'])
            ->orderBy('updated_by DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('day_index', [
            'pages'=>$pages,
            'theDays'=>$theDays,
            ]
        );
    }

    // Sample tour days
    public function actionSample($orderby = 'updated', $name = '', $tags = '', $show = 'all', $language = 'fr')
    {
        if (Yii::$app->request->isAjax && isset($_POST['action'], $_POST['day'])) {
            if ($_POST['action'] == 'nouse') {
                $nm = Ngaymau::findOne($_POST['day']);
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

        $query = Ngaymau::find();

        if (SEG2 == 'b2b') {
            $query->andWhere(['owner'=>'si']);
        } else {
            $query->andWhere(['owner'=>'at']);
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

        return $this->render('day_sample', [
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
        $currentDay = Day::find()
            ->where(['id'=>$day_id])
            ->asArray()
            ->one();
        if (!$currentDay) {
            throw new HttpException(404, 'Current day not found');
        }

        $theProduct = Product::find()
            ->where(['id'=>$currentDay['rid'], 'offer_type'=>'combined2016'])
            ->asArray()
            ->one();
        if (!$theProduct) {
            throw new HttpException(404, 'Tour program not found');
        }

        $theDay = new Day;

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
                return $this->redirect('/products/r/'.$theProduct['id']);
            }
        }

        return $this->render('day_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionR($id = 0, $action = '', $option = 0) {
        $theDay = Day::find()
            ->where(['id'=>$id])
            ->with([
                'product',
                'product.bookings',
                'product.bookings.case',
                ])
            ->asArray()
            ->one();

        if (!$theDay) {
            throw new HttpException(404, 'Day not found');
        }

        if ($action == 'delete-option') {
            $newDay = Day::find()
                ->where(['id'=>$option])
                ->one();
            if (!$newDay) {
                throw new HttpException(404, 'Day not found');
            }

            if ($newDay->delete()) {
                return $this->redirect('/products/r/'.$theDay['rid'].'#ngay-'.$theDay['parent_day_id']);
            }
        }

        if ($action == 'add-option') {
            $newDay = new Day;
            $newDay->scenario = 'day/c';
            $newDay->name = 'OPTION';
            $newDay->status = 'option';

            if ($newDay->load(Yii::$app->request->post()) && $newDay->validate()) {
                $title = 'OPTION;REPLACE;';
                if ($_POST['status'] == 'append') {
                    $title = 'OPTION;APPEND;';
                }
                $title .= $_POST['booking_id'].';';
                $newDay->name .= $title.$newDay->name;
                $newDay['created_at'] = NOW;
                $newDay['created_by'] = USER_ID;
                $newDay['updated_at'] = NOW;
                $newDay['updated_by'] = USER_ID;
                $newDay['rid'] = $theDay['rid'];
                $newDay['parent_day_id'] = $theDay['id'];
                $newDay['booking_id'] = 0; // TODO $booking_id;
                $newDay['step'] = 0;
                $newDay['day'] = '0000-00-00';
                if ($newDay->save(false)) {
                    return $this->redirect('/products/r/'.$theDay['rid']);
                }

            }
        }

        if ($action == 'edit-option') {
            $newDay = Day::find()
                ->where(['id'=>$option])
                ->one();
            if (!$newDay) {
                throw new HttpException(404, 'Day not found');
            }

            $newDay->scenario = 'day/u';

            if ($newDay->load(Yii::$app->request->post()) && $newDay->validate()) {
                $title = 'OPTION;REPLACE;';
                if ($_POST['status'] == 'append') {
                    $title = 'OPTION;APPEND;';
                }
                $title .= $_POST['booking_id'].';';
                $newDay->name = $title;

                $newDay['updated_at'] = NOW;
                $newDay['updated_by'] = USER_ID;
                if ($newDay->save(false)) {
                    return $this->redirect('/products/r/'.$theDay['rid']);
                }

            }
        }

        return $this->render('day_r', [
            'theDay'=>$theDay,
            'newDay'=>$newDay ?? null,
            'action'=>$action,
        ]);
    }

    public function actionU($id = 0)
    {
        $theDay = Day::find()
            ->where(['id'=>$id])
            ->with(['product'])
            ->one();
        if (!$theDay) {
            throw new HttpException(404);
        }

        if (!in_array(USER_ID, [1, $theDay['product']['created_by'], $theDay['product']['updated_by']])) {
            throw new HttpException(403, 'Access denied');          
        }

        $theDay->scenario = 'day/u';

        if ($theDay->load(Yii::$app->request->post())) {
            $theDay['updated_at'] = NOW;
            $theDay['updated_by'] = USER_ID;
            $theDay['body'] = str_replace('&nbsp;', ' ', $theDay['body']);
            if ($theDay->save(false)) {
                if ($theDay['rid'] == 0) {
                    return $this->redirect('@web/days/r/'.$theDay->id);
                } else {
                    return $this->redirect('@web/products/r/'.$theDay['rid'].'#ngay-'.$theDay['id']);
                }
            }
        }

        return $this->render('day_u', [
            'theDay'=>$theDay,
        ]);
    }
}
