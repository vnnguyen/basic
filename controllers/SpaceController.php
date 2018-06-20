<?
namespace app\controllers;

use common\models\Space;
use common\models\User;
use common\models\Comment;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class SpaceController extends MyController
{
    public function actionIndex($cat = 0, $tag = '', $author = 0)
    {
        $query = Space::find()
            ->where(['status'=>'on']);

        if ($cat != 0) {
            $query->andWhere(['cats'=>$cat]);
        }
        if ($tag != '') {
            $query->andWhere(['like', 'tags', $tag]);
        }
        if ($author != 0) {
            $query->andWhere(['author_id'=>$author]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);
        $theSpaces = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('created_dt DESC')
            ->all();
        return $this->render('spaces', [
            'theSpaces'=>$theSpaces,
            'pagination'=>$pagination,
            'cat'=>$cat,
            'tag'=>$tag,
            'author'=>$author,
        ]);
    }

    public function actionC()
    {
        $theSpace = new Space();
        $theSpace->scenario = 'space/c';

        if ($theSpace->load(Yii::$app->request->post()) && $theSpace->validate()) {
            $theSpace->created_dt = NOW;
            $theSpace->created_by = USER_ID;
            $theSpace->updated_dt = NOW;
            $theSpace->updated_by = USER_ID;
            $theSpace->save(false);
            return $this->redirect('@web/spaces/r/'.$theSpace->id);
        }

        return $this->render('space_u', [
            'theSpace'=>$theSpace,
        ]);
    }

    public function actionR($id = 0)
    {
        $theSpace = Space::find()
            ->where(['id'=>$id])
            //->with(['comments', 'author', 'comments.createdBy'])
            ->asArray()
            ->one();

        if (!$theSpace)
            throw new HttpException(404);

        return $this->render('space_r', [
            'theSpace'=>$theSpace,
        ]);
    }

    public function actionU($id = 0)
    {
        $theSpace = Space::findOne($id);
        if (!$theSpace) {
            throw new HttpException(404);
        }

        if (!in_array(USER_ID, [1,2,3,4])) {
            throw new HttpException(403, 'You are not allowed to edit this post');
        }

        $theSpace->scenario = 'space/u';

        if ($theSpace->load(Yii::$app->request->post()) && $theSpace->validate()) {
            $theSpace->updated_dt = NOW;
            $theSpace->updated_by = USER_ID;
            $theSpace->save(false);
            return $this->redirect('@web/spaces/r/'.$theSpace['id']);
        }

        return $this->render('space_u', [
            'theSpace'=>$theSpace,
        ]);
    }
}
