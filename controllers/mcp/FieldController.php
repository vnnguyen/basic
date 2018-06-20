<?

namespace app\controllers\mcp;

use \common\models\Country;
use \common\models\Account;
use \common\models\Field;
use \common\models\Message;
use \common\models\User;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class FieldController extends \app\controllers\MyController
{
    public function actionIndex($action = 'list', $id = 0, $cat = '', $status = '', $name = '', $orderby = 'updated', $sort = 'desc')
    {
        if ($action = 'edit' && $id != 0) {
            $theField = Field::findOne($id);
            $theField->scenario = 'field/c';
        } else {
            $theField = new Field;
            $theField->scenario = 'field/c';
        }
        if ($theField->load(Yii::$app->request->post()) && $theField->validate()) {
            $theField->created_dt = NOW;
            $theField->created_by = USER_ID;
            $theField->updated_dt = NOW;
            $theField->updated_by = USER_ID;
            $theField->save(false);

            Yii::$app->session->setFlash('success', 'Field created: '.$theField['name']);
            return $this->redirect('/mcp/fields');
        }

        $query = Field::find();

        if ($cat != '') {
            $query->andWhere(['cat'=>$cat]);
        }

        if ($status != '') {
            $query->andWhere(['status'=>$status]);
        }

        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>$cat != '' ? 100 : 25,
        ]);

        $orderText = 'updated_dt';
        if ($orderby == 'name') {
            $orderby = 'name';
        } elseif ($orderby == 'type') {
            $orderby = 'stype';
        }

        if ($sort == 'desc') {
            $sortText = ' DESC';
        } else {
            $sortText = '';
        }

        $theFields = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('cat, name')
            ->asArray()
            ->all();

        return $this->render('field_index', [
            'theField'=>$theField,
            'theFields'=>$theFields,
            'pagination'=>$pagination,
            'cat'=>$cat,
            'status'=>$status,
            'name'=>$name,
            'action'=>$action,
        ]);
    }
}
