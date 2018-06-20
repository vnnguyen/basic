<?
namespace app\controllers;

use common\models\Node;
use common\models\Person;
use common\models\Comment;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class NodeController extends MyController
{
    public function actionIndex($cat = 0, $tag = '', $author = 0)
    {
        $query = Node::find()
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
        $theNodes = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('created_dt DESC')
            ->all();
        return $this->render('spaces', [
            'theNodes'=>$theNodes,
            'pagination'=>$pagination,
            'cat'=>$cat,
            'tag'=>$tag,
            'author'=>$author,
        ]);
    }

    public function actionC()
    {
        $theNode = new Node();
        $theNode->scenario = 'space/c';

        if ($theNode->load(Yii::$app->request->post()) && $theNode->validate()) {
            $theNode->created_dt = NOW;
            $theNode->created_by = USER_ID;
            $theNode->updated_dt = NOW;
            $theNode->updated_by = USER_ID;
            $theNode->save(false);
            return $this->redirect('@web/spaces/r/'.$theNode->id);
        }

        return $this->render('space_u', [
            'theNode'=>$theNode,
        ]);
    }

    public function actionR($id = 0, $search = '')
    {
        $theNode = Node::find()
            ->where(['search'=>$search])
            ->asArray()
            ->one();

        if (!$theNode)
            throw new HttpException(404, 'Node not found.');

        return $this->render('node_r', [
            'theNode'=>$theNode,
        ]);
    }

    public function actionU($id = 0)
    {
        $theNode = Node::findOne($id);
        if (!$theNode) {
            throw new HttpException(404);
        }

        if (!in_array(USER_ID, [1,2,3,4])) {
            throw new HttpException(403, 'You are not allowed to edit this post');
        }

        $theNode->scenario = 'space/u';

        if ($theNode->load(Yii::$app->request->post()) && $theNode->validate()) {
            $theNode->updated_dt = NOW;
            $theNode->updated_by = USER_ID;
            $theNode->save(false);
            return $this->redirect('@web/spaces/r/'.$theNode['id']);
        }

        return $this->render('space_u', [
            'theNode'=>$theNode,
        ]);
    }
}
