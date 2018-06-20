<?

namespace app\controllers\mcp;

use \common\models\Country;
use \common\models\Account;
use \common\models\Issue;
use \common\models\Message;
use \common\models\User;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class IssueController extends \app\controllers\MyController
{
    public function actionIndex($category = '', $status = '', $incharge = '', $name = '', $orderby = 'updated', $sort = 'desc')
    {
        $query = Issue::find();

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theIssues = $query
            ->with([
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'assignedTo'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                }
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('due_date, status')
            ->asArray()
            ->all();

        return $this->render('issue_index', [
            'theIssues'=>$theIssues,
            'pagination'=>$pagination,
        ]);
    }

    public function actionC()
    {
        $theIssue = new Issue;
        $theIssue->scenario = 'issue/c';
        $theIssue->start_date = date('Y-m-d');
        $theIssue->due_date = date('Y-m-d', strtotime('+1 month'));
        $theIssue->assigned_to = 1;
        if ($theIssue->load(Yii::$app->request->post()) && $theIssue->validate()) {
            $theIssue->created_dt = NOW;
            $theIssue->created_by = USER_ID;
            $theIssue->updated_dt = NOW;
            $theIssue->updated_by = USER_ID;
            $theIssue->save(false);
            return $this->redirect('/mcp/issues');
        }

        return $this->render('issue_u', [
            'theIssue'=>$theIssue,
        ]);
    }

    public function actionR($id)
    {
        $theIssue = Issue::find()
            ->where(['id'=>$id])
            ->with([
                'createdBy',
                'updatedBy',
                'assignedTo',
                ])
            ->one();
        if (!$theIssue) {
            throw new HttpException(404);
        }

        $theMessage = new Message;
        $theMessage->scenario = 'message/r';
        if ($theMessage->load(Yii::$app->request->post()) && $theMessage->validate()) {
            // $theMessage->account_id = ACCOUNT_ID;
            $theMessage->co = NOW;
            $theMessage->cb = USER_ID;
            $theMessage->uo = NOW;
            $theMessage->ub = USER_ID;
            $theMessage->rtype = 'issue';
            $theMessage->rid = $theIssue['id'];
            $theMessage->save(false);
            return $this->redirect('/mcp/issues/r/'.$theIssue['id']);
        }

        $theMessages = Message::find()
            ->where(['rtype'=>'issue', 'rid'=>$theIssue['id']])
            ->with([
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name', 'image']);
                }
                ])
            ->orderBy('co')
            ->asArray()
            ->all();

        return $this->render('issue_r', [
            'theIssue'=>$theIssue,
            'theMessage'=>$theMessage,
            'theMessages'=>$theMessages,
        ]);
    }

    public function actionU($id)
    {
        $theIssue = Issue::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theIssue) {
            throw new HttpException(404);
        }

        $theIssue->scenario = 'issue/u';

        $uploadDir = 'issues/'.substr($theIssue['created_dt'], 0, 7).'/'.$theIssue['id'];
        @\yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/'.$uploadDir);

        $ckfSessionName = 'issue'.$theIssue['id'];
        $ckfSessionValue = [
            'ckfResourceName'=>'upload',
            'ckfResourceDirectory'=>$uploadDir,
        ];
        Yii::$app->session->set('ckfRole', 'user');
        Yii::$app->session->set('ckfAuthorized', true);
        Yii::$app->session->set($ckfSessionName, $ckfSessionValue);

        if ($theIssue->load(Yii::$app->request->post()) && $theIssue->validate()) {
            $theIssue->updated_dt = NOW;
            $theIssue->updated_by = USER_ID;
            $theIssue->save(false);
            return $this->redirect('/mcp/issues');
        }
        return $this->render('issue_u', [
            'theIssue'=>$theIssue,
        ]);
    }
}
